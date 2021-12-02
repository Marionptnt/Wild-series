<?php

// src/Controller/ProgramController.php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Routing\Annotation\Route;



class ProgramController extends AbstractController

{
    /**

     * @Route("/program/", name="program_index")

     */

    public function index(): Response

    {

        return $this->render('program/index.html.twig', [

            'website' => 'Wild Séries',

        ]);
    }

    /* écriture requirements plus condensé--> */

    /**
     
     * @Route("/program/list/{page<\d+>}", name="program_list")
     
     */
    public function list(int $page = 1): Response
    {
        return $this->render('program/list.html.twig', [
            'page' => $page,
        ]);
    }

    /*
    public function new(): Response
{
    // traitement d'un formulaire par exemple
 
    // redirection vers la page 'program_show',
    // correspondant à l'url /program/4
    return $this->redirectToRoute('program_show', ['id' => 4]);*/
}


    /**

     * @Route("/program/{id}",methods={"GET"}, requirements={"id"="\d+"}, name="program_show")

     */
    public function show(int $id): Response
    {
        return $this->render('program/show.html.twig', [
            'id' => $id,
        ]);
    }
}
