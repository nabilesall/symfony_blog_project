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
     * Cette méthode permet d'afficher la liste des commentaires
     * Elle est le point d'entrée de l'URL /admin/comment
     * 
     * @Route("/admin/comment", name="admin.comment.index")
     */
    public function index(ManagerRegistry $doctrine, Request $request)
    {
        //on vérifie que l'utilisateur est connecté
        if($request->getSession()->get('userName')!= null){
            $commentRepository = $doctrine->getRepository(Comment::class);
            $comments = $commentRepository->findAll();

            //on transforme les objets en tableau pour 
            //pouvoir les afficher dans twig avec un for
            for ($i=0; $i < count($comments); $i++) { 
                $comments[$i] = array(
                    "id" => $comments[$i]->getId(),
                    "content" => $comments[$i]->getContent(),
                    "publishedAt" => $comments[$i]->getCreatedAt(),
                    "userName" => $comments[$i]->getUserName(),
                    "postId" => $comments[$i]->getPost(),
                );
            }
            
            return $this->render('admin/comment/index.html.twig', [
                'comments' => $comments,
                'userName' => $request->getSession()->get('userName'),
                'userStatus' => $request->getSession()->get('userStatus'),
            ]);
        }else{
            //si l'utilisateur n'est pas connecté, on le redirige vers la page de connexion
            return $this->redirectToRoute('connection');
        }
    }


    /**
     * Cette méthode permet de supprimer un commentaire
     * 
     * @Route("/admin/comment/{id}/remove", name="admin.comment.remove")
     */
    public function remove(ManagerRegistry $doctrine, Request $request, $id)
    {
        $commentRepository = $doctrine->getRepository(Comment::class);
        $comment = $commentRepository->findOneBy(['id' => $id]);

        if($comment == null){
            return $this->redirectToRoute('admin.comment.index');
        }

        $commentRepository->remove($comment,true);

        return $this->redirectToRoute('admin.comment.index');
    }
}

?>