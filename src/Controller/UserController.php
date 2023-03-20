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

            var_dump( $userForConnection);

            //$entityManager = $doctrine->getManager();
            $userForConnectionRepo = $doctrine->getRepository(User::class);
            $userForConnectionn = $userForConnectionRepo->findWithUserName($userForConnection->getUserName());
            var_dump($userForConnectionn);

            /*return $this->redirectToRoute('connection', [
                'id' => "ookk"
            ]);*/

            /*return $this->redirectToRoute('post_show', [
                'id' => $post->getId()
            ]);*/
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

            var_dump($userForInscription);
            /*$entityManager = $doctrine->getManager();

            $entityManager->persist($userForInscription);
            $entityManager->flush();*/

            return $this->redirectToRoute('connection', [
                'id' => "ookk"
            ]);

            /*return $this->redirectToRoute('post_show', [
                'id' => $post->getId()
            ]);*/
        }

        return $this->render('inscription.html.twig', [
            'form' => $form->createView(),
            'controller_name' => 'InscriptionController',
        ]);
    }
}

?>