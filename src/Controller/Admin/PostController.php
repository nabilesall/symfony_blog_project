<?php

namespace App\Controller\Admin;

use App\Utils\Text;
use App\Entity\Post;
use App\Entity\Comment;
use App\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormError;

class PostController extends AbstractController
{
    /**
     * @Route("admin/post", name="admin.post.index")
     */
    public function index(Request $request, ManagerRegistry $doctrine): Response
    {

        //on vérifie que l'admin est connecté
        if($request->getSession()->get('userName')!= null && $request->getSession()->get('userStatus') == 0){
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

            //on affiche la page
            return $this->render('admin/post/index.html.twig', [
                'posts' => $post,
                'userName' => $request->getSession()->get('userName'),
                'userStatus' => $request->getSession()->get('userStatus'),
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
        //on vérifie que l'admin est connecté
        if($request->getSession()->get('userName')!= null && $request->getSession()->get('userStatus') == 0){
            $post = new Post();
            $form = $this->createForm(\App\Form\PostType::class, $post);

            $form->handleRequest($request);

            //si le formulaire est soumis et valide on enregistre le post
            //et on redirige vers la page de l'article
            if($form->isSubmitted() && $form->isValid()) {
                $post = $form->getData();

                foreach ($post->getCategories() as $category) {
                    $post->addCategory($category);
                }

                $post->setPublishedAt(new \DateTime());

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
                'userStatus' => $request->getSession()->get('userStatus'),
            ]);
        }else{
            //si l'utilisateur n'est pas admin, on le redirige vers la page de connexion
            return $this->redirectToRoute('connection');
        }
    }

    /**
     * @Route("admin/post/{id}", name="admin.post.show")
     */
    public function show(Request $request, ManagerRegistry $doctrine, $id): Response
    {
        //on vérifie que l'admin est connecté
        if($request->getSession()->get('userName')!= null && $request->getSession()->get('userStatus') == 0){
            $postRepository = $doctrine->getRepository(Post::class);
            $post = $postRepository->find($id);

            //on récupère les catégories
            $categoryNames = [];
            foreach($post->getCategories() as $category) {
                $categoryNames[] = $category->getName();
            }

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
                    if($comment->getContent() == null){//erreur
                        $form->addError(new FormError('Le commentaire ne peut pas être vide'));                                     
                        return $this->render('admin/post/show.html.twig', [
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
                    $comment->setUserName($request->getSession()->get('userName'));
                    $comment->setCreatedAt(new \DateTime());

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
                    'tags' => $categoryNames,
                    'comments' => $comments,
                    'form' => $form->createView(),
                    'userName' => $request->getSession()->get('userName'),
                    'userStatus' => $request->getSession()->get('userStatus'),
                ]);
            }
        }else{
            //si l'utilisateur n'est pas admin , on le redirige vers la page de connexion
            return $this->redirectToRoute('connection');
        }
    }


    /**
     * @Route("admin/post/{id}/edit", name="admin.post.edit")
     */
    public function edit(Request $request, ManagerRegistry $doctrine, $id): Response
    {//fonctionne
        //on vérifie que l'admin est connecté
        if($request->getSession()->get('userName')!= null && $request->getSession()->get('userStatus') == 0){
            $entityManager = $doctrine->getManager();
            $postRepository= $entityManager ->getRepository(\App\Entity\Post::class);
            $post = $postRepository->find($id);

            $form = $this->createForm(\App\Form\PostType::class, $post);
            $form->handleRequest($request);

            if($form->isSubmitted() && $form->isValid()) {
                $post = $form->getData();

                // Mettre à jour les catégories
                $categories = $form->get('categories')->getData();
                foreach ($post->getCategories() as $category) {
                    if (!$categories->contains($category)) {
                        $post->removeCategory($category);
                    }
                }
                foreach ($categories as $category) {
                    $post->addCategory($category);
                }

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
                'userStatus' => $request->getSession()->get('userStatus'),
            ]);
        }else{
            //si l'utilisateur n'est pas admin, on le redirige vers la page de connexion
            return $this->redirectToRoute('connection');
        }
    }


    /**
     * @Route("admin/post/{id}/remove", name="admin.post.remove")
     */

    public function remove(Request $request, ManagerRegistry $doctrine, $id): Response
    {
        //on vérifie que l'admin est connecté
        if($request->getSession()->get('userName')!= null && $request->getSession()->get('userStatus') == 0){
            $postRepository= $doctrine ->getRepository(\App\Entity\Post::class);

            $post = $postRepository->findOneBy(['id' => $id]);

            if($post == null){
                return $this->redirectToRoute('admin.post.index');
            }

            $entityManager->remove($post,true);

            return $this->redirectToRoute('admin.post.index');
        }
    }
}