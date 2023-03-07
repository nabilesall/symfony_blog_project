<?php

// Path: src\Controller\User\PostController.php
namespace App\Controller\User;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{
    /**
     * @Route("/user/post", name="user_post")
     */
    public function index()
    {
        return $this->render('user/post/index.html.twig', [
            'controller_name' => 'PostController',
        ]);
    }
}

?>