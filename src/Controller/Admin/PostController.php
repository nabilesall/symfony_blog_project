<?php

// Path: src\Controller\Admin\PostController.php
namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{
    /**
     * @Route("/admin/post", name="admin_post")
     */
    public function index()
    {
        return $this->render('admin/post/index.html.twig', [
            'controller_name' => 'PostController',
        ]);
    }
}

?>