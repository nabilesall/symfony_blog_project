<?php

// Path: src\Controller\User\CommentController.php
namespace App\Controller\User;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class CommentController extends AbstractController
{
    /**
     * @Route("/user/comment", name="user_comment")
     */
    public function index()
    {
        return $this->render('user/comment/index.html.twig', [
            'controller_name' => 'CommentController',
        ]);
    }
}

?>