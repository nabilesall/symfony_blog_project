<?php 

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;

class DefaultController extends AbstractController
{
    
    /**
     * Cette méthode permet d'afficher la page d'accueil
     * Elle affiche les 6 derniers articles publiés
     * Elle affiche les catégories
     * 
     * @Route("/", name="home")
     */
    public function index(Request $request, ManagerRegistry $doctrine): Response
    {

        //On récupère les catégories
        $categoryRepository = $doctrine->getRepository(\App\Entity\Category::class);
        $category = $categoryRepository->findAll();

        for($i=0; $i < count($category); $i++){
            $category[$i] = array(
                "id" => $category[$i]->getId(),
                "name" => $category[$i]->getName(),
            );
        }

        //On récupère les posts
        $postRepository = $doctrine->getRepository(\App\Entity\Post::class);
        //$post = $postRepository->findAll();      
        $post = $postRepository->findBy(array(), array('publishedAt' => 'DESC'), 6);

        //on transforme les objets en tableau pour pouvoir les afficher dans twig avec un for
        for($i=0; $i < count($post); $i++){
            $post[$i] = array(
                "id" => $post[$i]->getId(),
                "content" => \App\Utils\Text::excerpt($post[$i]->getContent()),
                "publishedAt" => $post[$i]->getPublishedAt(),
                "title" => $post[$i]->getTitle(),
            );
        }

        return $this -> render('home.html.twig',[
            'userName' => $request->getSession()->get('userName'),
            'userStatus' => $request->getSession()->get('userStatus'),
            'categories' => $category,
            'articles' => $post,
        ]);
    }

}

?>