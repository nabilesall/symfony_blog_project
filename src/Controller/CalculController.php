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
        return $this -> render('calcul/somme.html.twig', [
            'a' => $a,
            'b' => $b,
            'somme' => $result
        ]);
    }

    /**
     * @Route("/square/{a}", methods={"GET"})
     */
    public function square($a): Response
    {
        $result = $a * $a;
        return $this -> render('calcul/squared.html.twig', [
            'a' => $a,
            'square' => $result
        ]);
    }
}

?>