<?php

// src/Controller/ProgramController.php

namespace App\Controller;

use App\Entity\Season;
use App\Entity\Comment;
use App\Entity\Episode;
use App\Entity\User;
use App\Entity\Program;

use App\Service\Slugify;

use App\Form\CommentType;

use App\Form\ProgramType;
use App\Form\SearchProgramType;
use Symfony\Component\Mime\Email;
use App\Repository\SeasonRepository;
use App\Repository\CommentRepository;
use App\Repository\EpisodeRepository;
use App\Repository\ProgramRepository;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\Security\Core\Exception\AccessDeniedException;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * @Route("/program", name="program_")
 */

class ProgramController extends AbstractController

{
    /**
     * Show all rows from Program’s entity
     *
     * @Route("/", name="index")
     * @return Response A response instance
     */
    public function index(Request $request, ProgramRepository $programRepository): Response
    {
        $form = $this->createForm(SearchProgramType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $search = $form->getData()['search'];
            $programs = $programRepository->findAllWithProgramsAndActors($search);
        } else {
            $programs = $programRepository->findAll();
        }

        return $this->render(
            'program/index.html.twig',
            [
            'programs' => $programs,
            'form' => $form->createView(),
            ]
        );
    }


    /**
     
     * @Route("/list/{page<\d+>}", name="list")
     
     */
    public function list(ProgramRepository $programRepository): Response
    {
        $programs = $programRepository->findAll();

        return $this->render('program/list.html.twig', ['programs' => $programs,]);
    }

    /**
     * @Route("/new", name="new")
     */
    public function new(Request $request, EntityManagerInterface $entityManager, Slugify $slugify, MailerInterface $mailer): Response
    {
        // new program is create
        $program = new Program();
        // with the program's form
        $form = $this->createForm(ProgramType::class, $program);
        // Get data from HTTP request
        $form->handleRequest($request);
        // Was the form submitted ?
        if ($form->isSubmitted() && $form->isValid()) {
            // Add user actuel    
            $program->setOwner($this->getUser());
            // add Slug in BDD
            $slug = $slugify->generate($program->getTitle());
            $program->setSlug($slug);

            $entityManager->persist($program);
            $entityManager->flush();

            $this->addFlash('success', 'Une nouvelle série a été crée');

            //send an email when you add a new program
            // $email = (new Email())
            //     ->from($this->getParameter('mailer_from'))
            //     ->to('dev.marionpatinet@gmail.com')
            //     ->subject('Une nouvelle série vient d\'être publiée !')
            //     ->html($this->renderView('Program/newProgramEmail.html.twig', ['program' => $program]));

            // $mailer->send($email);

            //redirect to program's list
            return $this->redirectToRoute('program_index');
        }
        return $this->render('program/new.html.twig', [
            "program" => $program,
            "form" => $form->createView(),
        ]);
    }

    /**
     * Getting a program by slug 
     * @Route("/{slug}", methods={"GET"}, name="show")
     * @ParamConverter("program", class="App\Entity\Program", options={"mapping": {"slug": "slug"}})
     * @return Response
     */

    public function show(Program $program, SeasonRepository $seasonRepository): Response
    {

        $seasons = $seasonRepository->findByProgram($program);

        if (!$program) {
            throw $this->createNotFoundException(
                'No program with id : ' . $program->getId() . ' found in program\'s table.'
            );
        }

        return $this->render('program/show.html.twig', [
            'program' => $program,
            'seasons' => $seasons,
        ]);
    }


    /**
     * @Route("/{slug}/seasons/{season}", name="season_show")
     * @ParamConverter("program", class="App\Entity\Program", options={"mapping": {"slug": "slug"}})
     */
    public function showSeason(Program $program, Season $season): Response
    {

        return $this->render('program/season_show.html.twig', [
            'program' => $program,
            'season' => $season,
        ]);
    }

    #[Route('/{program_slug}/season/{season}/episode/{episode_slug}', name: 'episode_show', methods: ['GET', 'POST'])]
    #[ParamConverter('program', options: ['mapping' => ['program_slug' => 'slug']])]
    #[ParamConverter('episode', options: ['mapping' => ['episode_slug' => 'slug']])]
    public function showEpisode(Program $program, Season $season, Episode $episode, Request $request, EntityManagerInterface $entityManager, CommentRepository $commentRepository): Response
    {
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($this->getUser() == null) {
                return $this->redirectToRoute('login');

                throw new AccessDeniedException('Vous devez être connecté pour laisser un commentaire.');
            }
            $comment->setAuthor($this->getUser());
            $comment->setEpisode($episode);
            $entityManager->persist($comment);
            $entityManager->flush();

            return $this->redirectToRoute('program_episode_show', [
                'program_slug' => $program->getSlug(),
                'season' => $season->getId(),
                'episode_slug' => $episode->getSlug(),
            ]);
        }

        return $this->render('program/episode_show.html.twig', [
            'program' => $program,
            'season' => $season,
            'episode' => $episode,
            'comments' => $commentRepository->findByEpisode($episode, ['id' => 'asc']),
            'form' => $form->createView()
        ]);
    }
    /**
     * @Route("/{slug}/edit", name="edit", methods={"GET","POST"})
     * @return Response
     */
    public function edit(Request $request, Program $program, Slugify $slugify): Response
    {
        $form = $this->createForm(ProgramType::class, $program);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Check wether the logged in user is the owner of the program
            if (!($this->getUser() == $program->getOwner())) {

                // If not the owner, throws a 403 Access Denied exception
                throw new AccessDeniedException('Seul son auteur peut modifier cette série.');

        }
            $program->setSlug($slugify->generate($program->getTitle()));
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'Un nouveau programme a bien été modifié');

            return $this->redirectToRoute('program_index');
        }

        return $this->render('program/edit.html.twig', [
            'program' => $program,
            'form' => $form->createView(),
        ]);
    }

    #[Route('delete/{slug}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, Program $program, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$program->getId(), $request->request->get('_token'))) {
 
            $entityManager->remove($program);
            $entityManager->flush();

            $this->addFlash('danger', "La série a été supprimée");
        }

        return $this->redirectToRoute('program_index', [], Response::HTTP_SEE_OTHER);
    }

}
