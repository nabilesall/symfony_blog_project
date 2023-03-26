<?php

namespace App\Controller\Admin;

use App\Entity\Post;
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
    {//fonctionne

        $postRepository = $doctrine->getRepository(Post::class);//->getRepository(\App\Entity\Post::class);
        $post = $postRepository->findAll();

        var_dump($post);
        return $this->render('admin/post/index.html.twig', [
            'controller_name' => 'PostController'
        ]);
    }


    /**
     * @Route("admin/post/create", name="admin.post.create")
     */
    public function create(Request $request, ManagerRegistry $doctrine): Response
    {//fonctionne
        $post = new Post();
        $form = $this->createForm(\App\Form\PostType::class, $post);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $post = $form->getData();

            $postRepository = $doctrine->getRepository(Post::class);
            $postRepository->save($post,true);

            return $this->redirectToRoute('admin.post.index', [
                'id' => $post->getId()
            ]);
        }

        return $this->render('admin/post/create.html.twig', [
            'controller_name' => 'PostController',
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("admin/post/{id}", name="admin.post.show")
     */
    public function show(ManagerRegistry $doctrine, $id): Response
    {//fonctionne
        $postRepository = $doctrine->getRepository(\App\Entity\Post::class);
        $post = $postRepository->find($id);

        if (!$post) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }

        return $this->render('admin/post/show.html.twig', [
            'post' => $post->getTitle(),
        ]);
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

            return $this->redirectToRoute('admin.post.index', [
                'id' => $post->getId()
            ]);
        }
        

        if (!$post) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }

        return $this->render('admin/post/edit.html.twig', [
            'post' => $post,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("admin/post/{id}/remove", name="admin.post.remove")
     */

    public function remove($id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $postRepository= $entityManager ->getRepository(\App\Entity\Post::class);

        $post = $postRepository->find($id);

        if (!$post) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }

        $entityManager->remove($post);
        $entityManager->flush();

        return $this->redirectToRoute('admin.post.index');
    }

}
