<?php

// src/Controller/ProgramController.php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Program;

use App\Repository\ProgramRepository;



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

     * @Route("/show/{id<^[0-9]+$>}", name="show")

     * @return Response

     */

    public function show(int $id): Response

    {

        $program = $this->getDoctrine()

            ->getRepository(Program::class)

            ->findOneBy(['id' => $id]);


        if (!$program) {

            throw $this->createNotFoundException(

                'No program with id : ' . $id . ' found in program\'s table.'

            );
        }

        return $this->render('program/show.html.twig', [

            'program' => $program,

        ]);
    }
}
