<?php

// src/Controller/CategoryController.php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Program;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use App\Repository\ProgramRepository;


class CategoryController extends AbstractController
{
    /**
     * Filter all rows from Program by category
     *
     * @Route("/category/", name="category_index")
     * @return Response A response instance
     */
    public function index(): Response
    {
        $categories = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findAll();



        return $this->render('category/index.html.twig', [

            'categories' => $categories,

        ]);
    }
    /**
     * @Route("/category/{name}", requirements={"id"="\d+"}, name="category_show")
     */
    public function show(string $name, CategoryRepository $categoryRepository, ProgramRepository $programRepository): Response
    {

        $category = $categoryRepository->findOneBy(['name' => $name]);

        if (!$category) {
            throw $this->createNotFoundException(
                'No category with name : ' . $name . ' found in category\'s table.'
            );
        } else {
            $programs = $programRepository->findByCategory($category, ['id' => 'desc'], '3');
        }

        return $this->render('category/show.html.twig', [
            'category' => $category,
            'programs' => $programs,
        ]);
    }
}
