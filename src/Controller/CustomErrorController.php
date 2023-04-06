<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CustomErrorController extends AbstractController
{
    /**
     * Cette méthode permet d'afficher la page d'erreur
     * 
     * @Route("/error", name="error")
     */
    public function show(): Response
    {
        return $this->render('errors/error.html.twig');
    }
}

?>