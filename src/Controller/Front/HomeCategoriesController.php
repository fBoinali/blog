<?php

namespace App\Controller\Front;

use App\Entity\Categories;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/home')]
class HomeCategoriesController extends AbstractController
{
    #[Route('/categories', name: 'app_homeCategories_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $categories= $entityManager->getRepository(Categories::class)->findAll();

        return $this->render('home/home_categoriesIndex.html.twig', [
            'controller_name' => 'HomeCategoriesController',
            'categories' => $categories
        ]);
    }
}
