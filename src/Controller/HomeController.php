<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    public function navbarTop(CategoryRepository $categoryRepository): Response
    {
    return $this->render('navbartop.html.twig', [
      'categories' => $categoryRepository->findBy([], ['id' => 'DESC'])
    ]);
    }

}
