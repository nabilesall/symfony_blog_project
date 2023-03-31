<?php 

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends AbstractController
{
    #[Route('/', methods: ['GET'])]
    /**
     * @Route("/", name="home")
     */
    public function index(Request $request): Response
    {
        return $this -> render('home.html.twig',[
            'userName' => $request->getSession()->get('userName'),
            'userStatus' => $request->getSession()->get('userStatus'),
        ]);
    }

}

?>