<?php

// Path: src\Controller\Admin\CommentController.php
namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class CommentController extends AbstractController
{
    /**
     * @Route("/admin/comment", name="admin_comment")
     */
    public function index()
    {
        return $this->render('admin/comment.html.twig');
    }
}

?>