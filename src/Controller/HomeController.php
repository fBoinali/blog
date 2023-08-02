<?php

namespace App\Controller;

use App\Entity\Articles;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/home')]
class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home' , methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $articles= $entityManager->getRepository(Articles::class)->findBy([], ['date'=> 'desc'], 3);

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'articles' => $articles,

        ]);
    }
}
