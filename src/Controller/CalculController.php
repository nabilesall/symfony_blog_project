<?php
// src/Controller/CalculController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CalculController extends AbstractController
{
    /**
     * @Route("/calcul/{a}/{b}", methods={"GET"})
     */
    public function calcul($a, $b): Response
    {
        $result = $a + $b;
        return new Response("Je suis dans le calcul controller. Le résultat de " . $a . " + " . $b . " est " . $result . "!");
    }

    /**
     * @Route("/square/{a}", methods={"GET"})
     */
    public function square($a): Response
    {
        $result = $a * $a;
        return new Response("Je suis dans le calcul controller. " . $a ."² est " . $result . "!");
    }
}


?>