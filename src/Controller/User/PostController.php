<?php

// Path: src\Controller\User\PostController.php
namespace App\Controller\User;

use App\Entity\Post;
use App\Entity\Category;
use App\Entity\Comment;

use App\Utils\Text;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Form\FormError;

class PostController extends AbstractController
{
    /**
     * Cette méthode permet d'afficher la liste des articles
     * pour un utilisateur connecté ou non connecté
     * 
     * @Route("user/post", name="user.post.index")
     */
    public function index(Request $request, ManagerRegistry $doctrine): Response
    {
        $postRepository = $doctrine->getRepository(Post::class);
        $post = $postRepository->findAll();  

        //on transforme les objets en tableau associatif
        //pour pouvoir les utiliser dans la vue plus facilement
        for($i=0; $i < count($post); $i++){
            $post[$i] = array(
                "id" => $post[$i]->getId(),
                "content" => Text::excerpt($post[$i]->getContent()),
                "publishedAt" => $post[$i]->getPublishedAt(),
                "title" => $post[$i]->getTitle(),
            );
        }

        return $this->render('user/post/index.html.twig', [
            'posts' => $post,
            'userName' => $request->getSession()->get('userName'),
        ]);
    }


    /**
     * Cette méthode permet d'afficher un article
     * 
     * @Route("user/post/{id}", name="user.post.show")
     */
    public function show(Request $request, ManagerRegistry $doctrine, $id): Response
    {
        $postRepository = $doctrine->getRepository(Post::class);
        $post = $postRepository->find($id);

        //on récupère les catégories
        $categoryNames = [];
        foreach($post->getCategories() as $category) {
            $categoryNames[] = $category->getName();
        }

        //on récupère les commentaires
        $commentRepository = $doctrine->getRepository(Comment::class);
        $comments = $commentRepository->findBy(['post' => $id]);

        //on transforme les objets en tableau pour pouvoir les afficher dans twig avec un for
        for ($i=0; $i < count($comments); $i++) { 
            $comments[$i] = array(
                "id" => $comments[$i]->getId(),
                "content" => $comments[$i]->getContent(),
                "publishedAt" => $comments[$i]->getCreatedAt(),
                "userName" => $comments[$i]->getUserName()
            );
        }

        if($post == null){
            return $this->redirectToRoute('user.post.index');
        }else{// Si l'article existe on affiche le formulaire de commentaire
            $comment = new Comment();
            $form = $this->createForm(\App\Form\CommentType::class, $comment);
            $form->handleRequest($request);

            //si le formulaire est soumis et valide on enregistre le commentaire
            if($form->isSubmitted() && $form->isValid()) {

                $comment = $form->getData();
                if($comment->getContent() == null){
                    $form->addError(new FormError("Le commentaire ne peut pas être vide"));
                    return $this->render('user/post/show.html.twig', [
                        'post' => $post,
                        'tags' => $categoryNames,
                        'comments' => $comments,
                        'form' => $form->createView(),
                        'userName' => $request->getSession()->get('userName'),
                        'userStatus' => $request->getSession()->get('userStatus'),
                    ]);
                }

                //on enregistre le commentaire
                $comment->setPost($post->getId());
                if($request->getSession()->get('userName') == null){
                    $comment->setUserName("Anonyme");
                }else{
                    $comment->setUserName($request->getSession()->get('userName'));
                }

                $comment->setCreatedAt(new \DateTime());

                $commentRepository -> save($comment,true);

                return $this->redirectToRoute('user.post.show', ['id' => $post->getId()]);
            }

            $postInArray = array(
                "id" => $post->getId(),
                "content" => $post->getContent(),
                "publishedAt" => $post->getPublishedAt(),
                "title" => $post->getTitle(),
            );

            return $this->render('user/post/show.html.twig', [
                'post' => $postInArray,
                'tags' => $categoryNames,
                'comments' => $comments,
                'form' => $form->createView(),
                'userName' => $request->getSession()->get('userName'),
                'userStatus' => $request->getSession()->get('userStatus'),
            ]);
        }
    }

}

?>