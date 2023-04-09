<?php

namespace App\Controller\User;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @Route("/user", name="user.")
 */
class CategoryController extends AbstractController
{
    /**
     * Cette méthode permet d'afficher une catégorie
     * 
     * @Route("/category/{id}", name="category.show")
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

        //on transforme les objets en tableau associatif
        //pour pouvoir les utiliser dans la vue plus facilement
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