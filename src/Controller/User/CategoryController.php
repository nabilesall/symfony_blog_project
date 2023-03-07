<?php

// Path: src\Controller\User\CategoryController.php
namespace App\Controller\User;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    /**
     * @Route("/user/category", name="user_category")
     */
    public function index()
    {
        return $this->render('user/category/index.html.twig', [
            'controller_name' => 'CategoryController',
        ]);
    }
}

?>