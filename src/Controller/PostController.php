<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;

class PostController extends AbstractController
{
    #[Route('/post', name: 'app_post')]
    public function index(ManagerRegistry $doctrine): Response
    {

        $post = new \App\Entity\Post();
        $post->setTitle('Hello World');
        $post->setContent('This is a test post');

        $entityManager = $doctrine->getManager();
        $entityManager->persist($post);
        $entityManager->flush();

        return $this->render('post/index.html.twig', [
            'controller_name' => $post->getTitle(),
        ]);
    }
}
