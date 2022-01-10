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
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($category);
            $entityManager->flush();

            $this->addFlash('success', 'Une nouvelle catégorie a été créée');

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
                'Aucune catégorie trouvée pour ' . $name
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
