<?php

namespace App\Controller\Front;

use App\Entity\Categories;
use App\Form\CategoriesType;
use App\Repository\CategoriesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/home/homeCategories')]
class HomeCategoriesController extends AbstractController
{
    #[Route('/', name: 'app_homeCategories_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $categories= $entityManager->getRepository(Categories::class)->findAll();

        return $this->render('home/homeCategoriesIndex.html.twig', [
            'controller_name' => 'HomeCategoriesController',
            'categories' => $categories
        ]);
    }


}
