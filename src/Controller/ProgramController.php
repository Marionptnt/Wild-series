<?php

// src/Controller/ProgramController.php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Program;
use App\Entity\Season;
use App\Entity\Episode;

use App\Repository\ProgramRepository;
use App\Repository\SeasonRepository;
use App\Repository\EpisodeRepository;

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
    public function index(): Response
    {
        $programs = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findAll();



        return $this->render('program/index.html.twig', [

            'programs' => $programs,

        ]);
    }

    /* écriture requirements plus condensé--> */

    /**
     
     * @Route("/list/{page<\d+>}", name="list")
     
     */
    public function list(ProgramRepository $programRepository): Response
    {
        $programs = $programRepository->findAll();

        return $this->render('program/list.html.twig', ['programs' => $programs,]);
    }

    /*
    public function new(): Response
{
    // traitement d'un formulaire par exemple
 
    // redirection vers la page 'program_show',
    // correspondant à l'url /program/4
    return $this->redirectToRoute('program_show', ['id' => 4]);
}*/

    /**

     * Getting a program by id

     *

     * @Route("/{id<^[0-9]+$>}", requirements={"id"="\d+"}, name="show")

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
     * @Route("/{program}/seasons/{season}", name="season_show")
     */
    public function showSeason(Program $program, Season $season): Response
    {


        if (!$program) {
            throw $this->createNotFoundException(
                'No program with id : ' . $program . ' found in program\'s table.'
            );
        }
        if (!$season) {
            throw $this->createNotFoundException(
                'No season with id : ' . $season . ' found in season\'s table.'
            );
        }

        return $this->render('program/season_show.html.twig', [
            'program' => $program,
            'season' => $season,
        ]);
    }

    /**
     * @Route("/{programId}/seasons/{seasonId}/episode/{episodeId}", name="episode_show")
     * @ParamConverter("program", class="App\Entity\Program", options={"mapping": {"programId": "id"}})
     * @ParamConverter("season", class="App\Entity\Season", options={"mapping": {"seasonId": "id"}})
     * @ParamConverter("episode", class="App\Entity\Episode", options={"mapping": {"episodeId": "id"}})
     */
    public function showEpisode(Program $program, Season $season, Episode $episode): Response
    {

        return $this->render('templates/program/episode_show.html.twig', [
            'program' => $program,
            'season' => $season,
            'espisode' => $episode
        ]);
    }
}
