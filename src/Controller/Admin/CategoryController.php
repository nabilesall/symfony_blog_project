<?php

// Path: src\Controller\Admin\CategoryController.php
namespace App\Controller\Admin;

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
     * @Route("/admin/category", name="admin.category.index")
     */
    public function index(Request $request, ManagerRegistry $doctrine): Response
    {//fonctionne
        if($request->getSession()->get('userName')!= null){

            $categoryRepository = $doctrine->getRepository(\App\Entity\Category::class);
            $category = $categoryRepository->findAll();

            var_dump($category);
            return $this->render('admin/category.html.twig', [
                'userName' => $request->getSession()->get('userName'),
            ]);
        }
        else{
            return $this->redirectToRoute('connection');
        }
    }


    /**
     * @Route("/admin/category/create", name="admin.category.create")
     */
    public function create(Request $request, ManagerRegistry $doctrine): Response
    {//fonctionne
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
        ]);
    }


    /**
     * @Route("/admin/category/{id}", name="admin.category.show")
     */
    public function show(Request $request, ManagerRegistry $doctrine, $id): Response
    {//fonctionne
        if($request->getSession()->get('userName')!= null){
            $categoryRepository = $doctrine->getRepository(\App\Entity\Category::class);
            $category = $categoryRepository->find($id);

            var_dump($category);
            return $this->render('admin/category/show.html.twig', [
                'category' => $category->getName(),
                'userName' => $request->getSession()->get('userName'),
            ]);
        }
        else{
            return $this->redirectToRoute('connection');
        }
    }


    /**
     * @Route("/admin/category/{id}/edit", name="admin.category.edit")
     */
    public function edit(Request $request, ManagerRegistry $doctrine, $id): Response
    {//fonctionne
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
        ]);
    }


    /**
     * @Route("/admin/category/{id}/remove", name="admin.category.remove")
     */
    public function remove(Request $request, ManagerRegistry $doctrine, $id): Response
    {//fonctionne
        $categoryRepository = $doctrine->getRepository(\App\Entity\Category::class);
        $category = $categoryRepository->findOneBy(['id' => $id]);

        $categoryRepository->remove($category,true);

        return $this->redirectToRoute('admin.category.index');
    }
}


?>