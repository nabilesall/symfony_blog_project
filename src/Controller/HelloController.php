<?php
// src/Controller/HelloController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HelloController extends AbstractController
{
    #[Route('/hello/{name}', methods: ['GET'])]
    public function hello($name)
    {
        return $this -> render('hello/name.html.twig', [
            'name' => $name
        ]);
    }
}

?>