<?php

namespace App\Controller\Front;

use App\Entity\Articles;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/home')]
class HomeArticlesController extends AbstractController
{
    #[Route('/articles{idCategorie}', name: 'app_homeArticles_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager, $idCategorie): Response
    {

        $listArticlesCateg = $entityManager->getRepository(Articles::class)->findBy(['fk_categories' => $idCategorie],['date'=> 'desc']);

        return $this->render('home/home_articlesIndex.html.twig', [
            'controller_name' => 'HomeArticlesController',
            'articles' => $listArticlesCateg,
        ]);
    }
}
