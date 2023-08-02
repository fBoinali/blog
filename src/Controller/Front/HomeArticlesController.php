<?php

namespace App\Controller\Front;

use App\Entity\Categories;
use Carbon\Carbon;
use App\Entity\Articles;
use App\Form\ArticlesType;
use App\Service\FileUploaderService;
use App\Repository\ArticlesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/home/homeArticles')]
class HomeArticlesController extends AbstractController
{
    #[Route('/{idCategorie}', name: 'app_homeArticles_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager, $idCategorie): Response
    {
        //dd($idCategorie);
        // Regroupe les articles par catégorie.
        $listArticlesCateg = $entityManager->getRepository(Articles::class)->findBy(['fk_categories' => $idCategorie]);

       // dd($listArticlesCateg);
        return $this->render('home/homeArticlesIndex.html.twig', [
            'controller_name' => 'HomeArticlesController',
            'articles' => $listArticlesCateg,
        ]);
    }
}
