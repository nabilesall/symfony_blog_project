<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class CustomErrorController extends AbstractController
{
    /**
     * Cette méthode permet d'afficher la page d'erreur
     * 
     * @Route("/error", name="error")
     */
    public function show(Request $request): Response
    {
        return $this->render('errors/error.html.twig',[
            'userName' => $request->getSession()->get('userName'),
            'userStatus' => $request->getSession()->get('userStatus'),
        ]
    );
    }
}

?>