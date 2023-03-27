<?php

// Path: src\Controller\Admin\CommentController.php
namespace App\Controller\Admin;

use App\Entity\Comment;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;


class CommentController extends AbstractController
{
    /**
     * @Route("/admin/comment", name="admin.comment.index")
     */
    public function index(ManagerRegistry $doctrine, Request $request)
    {
        $commentRepository = $doctrine->getRepository(Comment::class);
        $comments = $commentRepository->findAll();

        //on transforme les objets en tableau pour pouvoir les afficher dans twig avec un for
        for ($i=0; $i < count($comments); $i++) { 
            $comments[$i] = array(
                "id" => $comments[$i]->getId(),
                "content" => $comments[$i]->getContent(),
                "publishedAt" => $comments[$i]->getCreatedAt(),
                "userName" => $comments[$i]->getUserName()
            );
        }

        var_dump($comments);
        return $this->render('admin/comment/index.html.twig', [
            'comments' => $comments,
            'userName' => $request->getSession()->get('userName'),
        ]);
    }
}

?>