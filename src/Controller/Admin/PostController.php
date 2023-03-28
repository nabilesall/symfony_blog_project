<?php

namespace App\Controller\Admin;

use App\Utils\Text;
use App\Entity\Post;
use App\Entity\Comment;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;

class PostController extends AbstractController
{
    /**
     * @Route("admin/post", name="admin.post.index")
     */
    public function index(Request $request, ManagerRegistry $doctrine): Response
    {

        //on vérifie que l'utilisateur est connecté
        if($request->getSession()->get('userName')!= null){
            //on récupère les posts
            $postRepository = $doctrine->getRepository(Post::class);
            $post = $postRepository->findAll();        

            //on transforme les objets en tableau pour pouvoir les afficher dans twig avec un for
            for($i=0; $i < count($post); $i++){
                $post[$i] = array(
                    "id" => $post[$i]->getId(),
                    "content" => Text::excerpt($post[$i]->getContent()),
                    "publishedAt" => $post[$i]->getPublishedAt(),
                    "title" => $post[$i]->getTitle(),
                );
            }

            //var_dump($post);

            //on affiche la page
            return $this->render('admin/post/index.html.twig', [
                'posts' => $post,
                'userName' => $request->getSession()->get('userName'),
            ]);
        }else{
            //si l'utilisateur n'est pas connecté, on le redirige vers la page de connexion
            return $this->redirectToRoute('connection');
        }
    }


    /**
     * @Route("admin/post/create", name="admin.post.create")
     */
    public function create(Request $request, ManagerRegistry $doctrine): Response
    {
        $post = new Post();
        $form = $this->createForm(\App\Form\PostType::class, $post);

        $form->handleRequest($request);

        //si le formulaire est soumis et valide on enregistre le post
        //et on redirige vers la page de l'article
        if($form->isSubmitted() && $form->isValid()) {
            $post = $form->getData();

            $postRepository = $doctrine->getRepository(Post::class);
            $postRepository->save($post,true);

            return $this->redirectToRoute('admin.post.show', [
                'id' => $post->getId()
            ]);
        }

        return $this->render('admin/post/create.html.twig', [
            'controller_name' => 'PostController',
            'form' => $form->createView(),
            'userName' => $request->getSession()->get('userName'),
        ]);
    }

    /**
     * @Route("admin/post/{id}", name="admin.post.show")
     */
    public function show(Request $request, ManagerRegistry $doctrine, $id): Response
    {
        $postRepository = $doctrine->getRepository(Post::class);
        $post = $postRepository->find($id);

        $commentRepository = $doctrine->getRepository(Comment::class);
        $comments = $commentRepository->findBy([
            'post' => $id
        ]);

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

        if($post == null){
            return $this->redirectToRoute('admin.post.index');
        }else{

            //formulaire de commentaire
            $comment = new Comment();
            $form = $this->createForm(\App\Form\CommentType::class, $comment);
            $form->handleRequest($request);

            //si le formulaire est soumis et valide on enregistre le commentaire
            if($form->isSubmitted() && $form->isValid()) {

                $comment = $form->getData();
                $comment->setPost($post->getId());
                $comment->setUserName($request->getSession()->get('userName'));

                $commentRepository = $doctrine->getRepository(Comment::class);
                $commentRepository->save($comment,true);

                return $this->redirectToRoute('admin.post.show', [
                    'id' => $post->getId()
                ]);
            }

            $postInArray = array(
                "id" => $post->getId(),
                "title" => $post->getTitle(),
                "content" => $post->getContent(),
                "publishedAt" => $post->getPublishedAt()
            );
            
            return $this->render('admin/post/show.html.twig', [
                'post' => $postInArray,
                'comments' => $comments,
                'form' => $form->createView(),
                'userName' => $request->getSession()->get('userName'),
            ]);
        } 
    }


    /**
     * @Route("admin/post/{id}/edit", name="admin.post.edit")
     */
    public function edit(Request $request, ManagerRegistry $doctrine, $id): Response
    {//fonctionne
        $entityManager = $doctrine->getManager();
        $postRepository= $entityManager ->getRepository(\App\Entity\Post::class);
        $post = $postRepository->find($id);

        $form = $this->createForm(\App\Form\PostType::class, $post);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $post = $form->getData();

            $post ->setTitle($post->getTitle());
            $post ->setContent($post->getContent());
            $postRepository = $doctrine->getRepository(Post::class);

            $entityManager->flush();

            return $this->redirectToRoute('admin.post.show', [
                'id' => $post->getId()
            ]);
        }
        

        if($post == null){
            return $this->redirectToRoute('admin.post.index');
        }

        return $this->render('admin/post/edit.html.twig', [
            'post' => $post,
            'form' => $form->createView(),
            'userName' => $request->getSession()->get('userName'),
        ]);
    }


    /**
     * @Route("admin/post/{id}/remove", name="admin.post.remove")
     */

    public function remove(Request $request, ManagerRegistry $doctrine, $id): Response
    {
        $postRepository= $doctrine ->getRepository(\App\Entity\Post::class);

        $post = $postRepository->findOneBy(['id' => $id]);

        if($post == null){
            return $this->redirectToRoute('admin.post.index');
        }

        $entityManager->remove($post,true);

        return $this->redirectToRoute('admin.post.index');
    }
}