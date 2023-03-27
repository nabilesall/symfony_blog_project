<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\InscriptionType;
use App\Form\ConnectionType;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;

class UserController extends AbstractController
{
    /**
     * @Route("/connection", name="connection")
     */
    public function connection(Request $request, ManagerRegistry $doctrine): Response
    {

        $userForConnection = new User();
        $form = $this->createForm(ConnectionType::class, $userForConnection);

        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()) {
            $userForConnection = $form->getData();

            //var_dump( $userForConnection);

            $userRepository = $doctrine->getRepository(User::class);
            $user = $userRepository->findOneBy([
                'UserName' => $userForConnection->getUserName(),
                'UserPassword' => $userForConnection->getUserPassword()
            ]);

            if ($user) {
                $request->getSession()->set('userName', $user->getUserName());
                return $this->redirectToRoute('home');
                /*return $this->render('home.html.twig', [
                    'controller_name' => 'PostController',
                    'userName' => $user->getUserName()
                ]);*/
            }
        }

        return $this->render('connection.html.twig', [
            'form' => $form->createView(),
            'controller_name' => 'ConnectionController',
        ]);
    }


    /**
     * @Route("/inscription", name="inscription")
     */
    public function inscription(Request $request, ManagerRegistry $doctrine): Response
    {

        $userForInscription = new User();
        $form = $this->createForm(InscriptionType::class, $userForInscription);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userForInscription = $form->getData();

            $userRepository = $doctrine->getRepository(User::class);
            $userRepository -> save($userForInscription,true);

            return $this->redirectToRoute('connection');

        }

        return $this->render('inscription.html.twig', [
            'form' => $form->createView(),
            'controller_name' => 'InscriptionController',
        ]);
    }


    /**
     * @Route("/logout", name="logout")
     */
    public function logout(Request $request): Response
    {
        $request->getSession()->set('userName', null);
        return $this->render('home.html.twig', [
        ]);
    }
}

?>