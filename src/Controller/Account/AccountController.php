<?php

namespace App\Controller\Account;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/account')]
class AccountController extends AbstractController
{

    #[Route('/', name: 'app_account')]
    public function index(): Response
    {
        $user = $this->getUser();
        $commentaires = $this->getUser()->getCommentaires();

        return $this->render('account/accueil.html.twig', [
            'controller_name' => 'AccountController',
            'user'=> $user,
            'commentaires'=> $commentaires,
        ]);
    }



}
