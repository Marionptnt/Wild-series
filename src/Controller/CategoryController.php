<?php

// src/Controller/CategoryController.php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Category;

use App\Repository\CategoryRepository;
use App\Repository\ProgramRepository;

use App\Form\CategoryType;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/category", name="category_")
 */

class CategoryController extends AbstractController
{
    /**
     * Filter all rows from Program by category
     *
     * @Route("/", name="index")
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

     * The controller for the category add form

     * Display the form or deal with it

     *

     * @Route("/new", name="new")

     */

    public function new(Request $request): Response

    {

        // Create a new Category Object

        $category = new Category();

        // Create the associated Form

        $form = $this->createForm(CategoryType::class, $category);

        // Get data from HTTP request

        $form->handleRequest($request);

        // Was the form submitted ?

        if ($form->isSubmitted()) {

            // Deal with the submitted data
            // Get the Entity Manager

            $entityManager = $this->getDoctrine()->getManager();

            // Persist Category Object

            $entityManager->persist($category);

            // Flush the persisted object

            $entityManager->flush();

            // Finally redirect to categories list

            return $this->redirectToRoute('category_index');
        }
        return $this->render('category/new.html.twig', [
            "form" => $form->createView(),
        ]);
    }

    /**
     * @Route("/{name}", requirements={"id"="\d+"}, name="show")
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
