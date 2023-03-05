<?php
// src/Controller/HelloController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HelloController extends AbstractController
{
    /**
     * @Route("/hello")
     */
    public function hello($name): Response
    {
        return new Response("Hello World! Je suis dans le hello controller. Hello " . $name . "!");
    }
}

?>