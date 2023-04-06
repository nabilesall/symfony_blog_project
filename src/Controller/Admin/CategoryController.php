<?php


namespace App\Controller\Admin;

use App\Utils\Text;
use App\Entity\Category;
use App\Form\CategoryType;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;

class CategoryController extends AbstractController
{
    /**
     * Cette méthode permet d'afficher la liste des catégories
     * Elle est le point d'entrée de l'URL /admin/category
     * 
     * @Route("/admin/category", name="admin.category.index")
     */
    public function index(Request $request, ManagerRegistry $doctrine): Response
    {
        //on vérifie que l'admin est connecté
        if($request->getSession()->get('userName')!= null && $request->getSession()->get('userStatus') == 0)
        {
            $categoryRepository = $doctrine->getRepository(\App\Entity\Category::class);
            $category = $categoryRepository->findAll();

            //on transforme les objets en tableau associatif
            //pour pouvoir les utiliser dans la vue plus facilement
            for($i=0; $i < count($category); $i++){
                $category[$i] = array(
                    "id" => $category[$i]->getId(),
                    "name" => $category[$i]->getName(),
                );
            }
            
            return $this->render('admin/category/index.html.twig', [
                'categories' => $category,
                'userName' => $request->getSession()->get('userName'),
                'userStatus' => $request->getSession()->get('userStatus'),
            ]);
        }
        else{
            //si l'utilisateur n'est pas admin, on le redirige vers la d'erreur
            return $this->redirectToRoute('error');
        }
    }


    /**
     * Cette méthode permet d'afficher le formulaire de création d'une catégorie
     * Elle permet aussi de traiter le formulaire pour créer la catégorie
     * 
     * @Route("/admin/category/create", name="admin.category.create")
     */
    public function create(Request $request, ManagerRegistry $doctrine): Response
    {
        //on vérifie que l'admin est connecté
        if($request->getSession()->get('userName')!= null && $request->getSession()->get('userStatus') == 0)
        {
            $category = new Category();
            $form = $this->createForm(\App\Form\CategoryType::class, $category);

            $form->handleRequest($request);

            if($form->isSubmitted() && $form->isValid()) {
                $category = $form->getData();

                $categoryRepository = $doctrine->getRepository(Category::class);
                $categoryRepository->save($category,true);

                return $this->redirectToRoute('admin.category.show', [
                    'id' => $category->getId()
                ]);
            }

            return $this->render('admin/category/create.html.twig', [
                'controller_name' => 'CategoryController',
                'form' => $form->createView(),
                'userName' => $request->getSession()->get('userName'),
                'userStatus' => $request->getSession()->get('userStatus'),
            ]);
        }
        else{
            //si l'utilisateur n'est pas admin, on le redirige vers la d'erreur
            return $this->redirectToRoute('error');
        }
    }


    /**
     * Cette méthode permet d'afficher le détail d'une catégorie
     * 
     * @Route("/admin/category/{id}", name="admin.category.show")
     */
    public function show(Request $request, ManagerRegistry $doctrine, $id): Response
    {
        //on vérifie que l'admin est connecté
        if($request->getSession()->get('userName')!= null && $request->getSession()->get('userStatus') == 0){
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
                    "content" => Text::excerpt($post->getContent()),
                    "publishedAt" => $post->getPublishedAt(),
                );
                $postsInArray[] = $postInArray;
            }

            return $this->render('admin/category/show.html.twig', [
                'category' => $categoryInArray,
                'posts' => $postsInArray,
                'userName' => $request->getSession()->get('userName'),
                'userStatus' => $request->getSession()->get('userStatus'),
            ]);
        }
        else{
            //si l'utilisateur n'est pas admin, on le redirige vers la d'erreur
            return $this->redirectToRoute('error');
        }
    }


    /**
     * Cette méthode permet d'afficher le formulaire d'édition d'une catégorie
     * Elle permet aussi de traiter le formulaire pour modifier la catégorie
     * 
     * @Route("/admin/category/{id}/edit", name="admin.category.edit")
     */
    public function edit(Request $request, ManagerRegistry $doctrine, $id): Response
    {
        //on vérifie que l'admin est connecté
        if($request->getSession()->get('userName')!= null && $request->getSession()->get('userStatus') == 0){
            $categoryRepository = $doctrine->getRepository(\App\Entity\Category::class);
            $category = $categoryRepository->find($id);

            $form = $this->createForm(CategoryType::class, $category);

            $form->handleRequest($request);

            if($form->isSubmitted() && $form->isValid()) {
                $category = $form->getData();

                $categoryRepository = $doctrine->getRepository(Category::class);
                $categoryRepository->save($category,true);

                return $this->redirectToRoute('admin.category.show', [
                    'id' => $category->getId(),
                ]);
            }

            return $this->render('admin/category/edit.html.twig', [
                'form' => $form->createView(),
                'userName' => $request->getSession()->get('userName'),
                'userStatus' => $request->getSession()->get('userStatus'),
            ]);
        }else{
            //si l'utilisateur n'est pas admin, on le redirige vers la d'erreur
            return $this->redirectToRoute('error');
        }
    }


    /**
     * Cette méthode permet de supprimer une catégorie
     * 
     * @Route("/admin/category/{id}/remove", name="admin.category.remove")
     */
    public function remove(Request $request, ManagerRegistry $doctrine, $id): Response
    {
        //on vérifie que l'admin est connecté
        if($request->getSession()->get('userName')!= null && $request->getSession()->get('userStatus') == 0){
            $categoryRepository = $doctrine->getRepository(\App\Entity\Category::class);
            $category = $categoryRepository->findOneBy(['id' => $id]);

            $categoryRepository->remove($category,true);

            return $this->redirectToRoute('admin.category.index');
        }else{
            //si l'utilisateur n'est pas admin, on le redirige vers la d'erreur
            return $this->redirectToRoute('error');
        }
    }
}

?>