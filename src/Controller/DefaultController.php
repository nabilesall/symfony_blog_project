<?php 

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    #[Route('/hello/{name}', methods: ['GET'])]
    public function index(string $name): Response
    {
        return new Response("Hello $name!");
    }
}

?>