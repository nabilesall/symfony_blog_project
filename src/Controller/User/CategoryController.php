<?php

namespace App\Controller\User;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;

class CategoryController extends AbstractController
{
    /**
<<<<<<< HEAD
     * Cette méthode permet d'afficher une catégorie
     * 
=======
>>>>>>> 78fb58bf05c8f336cebfc90d0f81c3659a00c641
     * @Route("/user/category/{id}", name="user.category.show")
     */
    public function show(Request $request, ManagerRegistry $doctrine, $id): Response
    {
        $categoryRepository = $doctrine->getRepository(\App\Entity\Category::class);
        $category = $categoryRepository->find($id);

        $categoryInArray = array(
            "id" => $category->getId(),
            "name" => $category->getName(),
        );

        $posts = $category->getPosts();

        $postsInArray = array();

<<<<<<< HEAD
        //on transforme les objets en tableau associatif
        //pour pouvoir les utiliser dans la vue plus facilement
=======
>>>>>>> 78fb58bf05c8f336cebfc90d0f81c3659a00c641
        foreach($posts as $post){
            $postInArray = array(
                "id" => $post->getId(),
                "title" => $post->getTitle(),
                "content" => \App\Utils\Text::excerpt($post->getContent()),
                "publishedAt" => $post->getPublishedAt(),
            );
            $postsInArray[] = $postInArray;
        }

        return $this->render('user/category/show.html.twig', [
            'category' => $categoryInArray,
            'posts' => $postsInArray,
            'userName' => $request->getSession()->get('userName'),
        ]);
    }
}

?>