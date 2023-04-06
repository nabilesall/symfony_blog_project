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
use Symfony\Component\Form\FormError;

class UserController extends AbstractController
{
    /**
     * Cette methode permet de se connecter
     * 
     * @Route("/connection", name="connection")
     */
    public function connection(Request $request, ManagerRegistry $doctrine): Response
    {
        $userForConnection = new User();
        $form = $this->createForm(ConnectionType::class, $userForConnection);

        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()) {
            $userForConnection = $form->getData();
            
            $userRepository = $doctrine->getRepository(User::class);
            $user = $userRepository->findOneBy([
                'UserName' => $userForConnection->getUserName()
            ]);

            if ($user && password_verify($userForConnection->getUserPassword(), $user->getUserPassword())) {
                $request->getSession()->set('userName', $user->getUserName());
                $request->getSession()->set('userStatus', $user->getUserStatus());
                return $this->redirectToRoute('home');                
            }else{
                $form ->addError(new FormError('Nom d\'utilisateur ou mot de passe incorrect'));
                return $this->render('userContrroler/connection.html.twig', [
                    'form' => $form->createView()
                ]);
            }
        }

        return $this->render('userController/connection.html.twig', [
            'form' => $form->createView()
        ]);
    }


    /**
     * Cette methode permet de s'inscrire
     * 
     * @Route("/inscription", name="inscription")
     */
    public function inscription(Request $request, ManagerRegistry $doctrine): Response
    {

        $userForInscription = new User();
        $form = $this->createForm(InscriptionType::class, $userForInscription);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userForInscription = $form->getData();

            $exitUserName = $doctrine->getRepository(User::class)->findOneBy([
                'UserName' => $userForInscription->getUserName()
            ]);

            if($exitUserName) {
                $form ->addError(new FormError('Ce nom d\'utilisateur est déjà utilisé'));
                return $this->render('userController/inscription.html.twig', [
                    'form' => $form->createView()
                ]);
            }
            else{
                // Hasher le mot de passe
                $plainPassword = $doctrine->getRepository(User::class)->getUserPassword();
                $hashedPassword = password_hash($plainPassword, PASSWORD_DEFAULT, ['cost' => 12]);
                $userForInscription->setUserPassword($hashedPassword);

                $userRepository -> save($userForInscription,true);

                return $this->redirectToRoute('connection');
            }
            
        }

        return $this->render('userController/inscription.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    /**
     * Cette methode permet de se deconnecter
     * @Route("/logout", name="logout")
     */
    public function logout(Request $request): Response
    {
        $request->getSession()->set('userName', null);
        $request->getSession()->set('userStatus', null);
        return $this->redirectToRoute('connection');
    }


    /**
     * Cette methode permet de voir le profil
     * @Route("/profil/{name}", name="profil")
     */
    public function profil(Request $request, ManagerRegistry $doctrine, $name): Response
    {
        $userName = $request->getSession()->get('userName');
        $userStatus = $request->getSession()->get('userStatus');

        if($userName  == $name && isset($userStatus) ) {
            $user = $doctrine->getRepository(User::class)->findOneBy([
                'UserName' => $name
            ]);

            return $this->render('userController/profil.html.twig', [
                'userName' => $user->getUserName(),
                'user' => $user
            ]);
        }else{
            return $this->redirectToRoute('connection');
        }
    }

    /**
     * Cette methode permet de modifier le profil
     * @Route("/profil/{name}/edit", name="profil.edit")
     */
    public function profilEdit(Request $request, ManagerRegistry $doctrine, $name): Response
    {
        $userName = $request->getSession()->get('userName');
        $userStatus = $request->getSession()->get('userStatus');

        if($userName  == $name && isset($userStatus) ) {
            $user = $doctrine->getRepository(User::class)->findOneBy([
                'UserName' => $name
            ]);

            $form = $this->createForm(InscriptionType::class, $user);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $user = $form->getData();

                $exitUserName = $doctrine->getRepository(User::class)->findOneBy([
                    'UserName' => $user->getUserName()
                ]);

                if($exitUserName) {
                    $form ->addError(new FormError('Ce nom d\'utilisateur est déjà utilisé'));
                    return $this->render('userController/inscription.html.twig', [
                        'form' => $form->createView()
                    ]);
                }
                else{
                    // Hasher le mot de passe
                    $plainPassword = $doctrine->getRepository(User::class)->getUserPassword();
                    $hashedPassword = password_hash($plainPassword, PASSWORD_DEFAULT, ['cost' => 12]);
                    $user->setUserPassword($hashedPassword);

                    $userRepository -> save($user,true);

                    return $this->redirectToRoute('profil', ['name' => $user->getUserName()]);
                }
                
            }

            return $this->render('userController/profilEdit.html.twig', [
                'userName' => $user->getUserName(),
                'form' => $form->createView()
            ]);
        }else{
            return $this->redirectToRoute('connection');
        }
    }
}

?>