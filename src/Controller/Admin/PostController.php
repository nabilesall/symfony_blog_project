<?php

namespace App\Controller\Admin;

use App\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;

class PostController extends AbstractController
{
    /**
     * @Route("admin/post", name="admin.post.index")
     */
    public function index(ManagerRegistry $doctrine): Response
    {

        $postRepository = $doctrine->getRepository(Post::class);//->getRepository(\App\Entity\Post::class);
        $post = $postRepository->findAll();

        //var_dump($post);        

        return $this->render('admin/post/index.html.twig', [
            'controller_name' => 'PostController',
        ]);
    }


    /**
     * @Route("admin/post/{id}", name="adminpost.show")
     */
    public function show(ManagerRegistry $doctrine, $id): Response
    {
        $postRepository = $doctrine->getRepository(\App\Entity\Post::class);
        $post = $postRepository->find($id);

        if (!$post) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }

        return $this->render('admin/post/show.html.twig', [
            'post' => $post,
        ]);
    }


    /**
     * @Route("admin/post/{id}/edit", name="admin.post.edit")
     */
    public function edit(ManagerRegistry $doctrine, $id): Response
    {
        $entityManager = $doctrine->getManager();
        $postRepository= $entityManager ->getRepository(\App\Entity\Post::class);
        //$post = $postRepository->find($id);

        $post = new Post();
        $form = $this->createForm(\App\Form\PostType::class, $post);

        

        if (!$post) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }

        return $this->render('admin/post/edit.html.twig', [
            'form' => $form->createView(),
            'post' => $post,
        ]);
    }


    /**
     * @Route("admin./post/{id}/update", name="admin.post.update")
     */
    /*public function update($id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $postRepository= $entityManager ->getRepository(\App\Entity\Post::class);

        $post = $postRepository->find($id);

        if (!$post) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }

        $post->setTitle('Hi World');
        $post->setContent('Mon contenu a été modifié');

        $entityManager->flush();

        return $this->render('admin/post/show.html.twig', [
            'post' => $post,
        ]);
    }*/


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
