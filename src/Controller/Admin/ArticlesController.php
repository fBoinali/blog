<?php

namespace App\Controller\Admin;

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

#[Route('/admin/articles')]
class ArticlesController extends AbstractController
{
    #[Route('/list/{idCategorie?}', name: 'app_articles_index', methods: ['GET'])]
    public function index(ArticlesRepository $articlesRepository, $idCategorie ): Response
    {
        // Regroupe les articles par catégorie.
        if ($idCategorie) {
            $listArticlesCateg = $articlesRepository->findBy(['fk_categories'=> $idCategorie]);
        }
        else {
            $listArticlesCateg = $articlesRepository->findAll();
        }
        //dd($categorie);
        return $this->render('admin/articles/index.html.twig', [
            'articles' => $listArticlesCateg,
        ]);
    }

    #[Route('/new', name: 'app_articles_new', methods: ['GET', 'POST'])]
    public function new(
        Request                $request,
        EntityManagerInterface $entityManager,
        FileUploaderService    $fileUploaderService,
                               $publicUploadDir
    ): Response
    {

        $article = new Articles();
        $form = $this->createForm(ArticlesType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $article->setDate(Carbon::now());
            $this->doUpload($form, $article, $fileUploaderService, $publicUploadDir);
            $entityManager->persist($article);
            $entityManager->flush();

            return $this->redirectToRoute('app_articles_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/articles/new.html.twig', [
            'article' => $article,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_articles_show', methods: ['GET'])]
    public function show(Articles $article): Response
    {
        return $this->render('admin/articles/show.html.twig', [
            'article' => $article,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_articles_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Articles $article, EntityManagerInterface $entityManager, FileUploaderService $fileUploaderService, $publicUploadDir): Response
    {
        $form = $this->createForm(ArticlesType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->doUpload($form, $article, $fileUploaderService, $publicUploadDir);
            $entityManager->flush();
            return $this->redirectToRoute('app_articles_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/articles/edit.html.twig', [
            'article' => $article,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_articles_delete', methods: ['POST'])]
    public function delete(Request $request, Articles $article, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $article->getId(), $request->request->get('_token'))) {
            $entityManager->remove($article);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_articles_index', [], Response::HTTP_SEE_OTHER);
    }

    private function doUpload($form, $article, $fileUploaderService, $publicUploadDir)
    {
        $file = $form['logo']->getData();
        if ($file !== null) {
            $file_name = $fileUploaderService->upload($file);
            $file_path = $publicUploadDir . '/' . $file_name;
            $article->setLogo($file_path);
        }
    }
}
