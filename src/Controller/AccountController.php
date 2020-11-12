<?php

namespace App\Controller;

use App\Entity\PasswordUpdate;
use App\Form\AccountForm\UserEmailType;
use App\Form\AccountForm\UserIdentityType;
use App\Form\AccountForm\UserPasswordType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Controller des fonctionnalités liées au compte
 * 
 * @Route("/bcp/profil")
 */
class AccountController extends AbstractController
{
    /**
     * Affiche la vue qui permet d'éditer les coordonnées de l'utilisateur
     * 
     * @Route("/informations", name="account_informations", methods={"GET","POST"})
     */
    public function editInformations(Request $request): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $user = $this->getUser();
        $form = $this->createForm(UserIdentityType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'Votre profil a été mis à jour');
            return $this->redirectToRoute('index');
        }

        return $this->render('bcp/account/edit_informations.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Affiche la vue qui permet d'éditer le mot de passe de l'utilisateur
     * 
     * @Route("/motdepasse", name="account_password", methods={"GET","POST"})
     */
    public function editPassword(Request $request, UserPasswordEncoderInterface $encoder): Response
    {
        $passwordupdate = new PasswordUpdate();
        $this->denyAccessUnlessGranted('ROLE_USER');
        $user = $this->getUser();
        $form = $this->createForm(UserPasswordType::class, $passwordupdate);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            if (!password_verify($passwordupdate->getOldPassword(), $user->getPassword())) {
                $this->addFlash('danger', 'Le mot de passe actuel est incorrect');
            } else {
                $hash = $encoder->encodePassword($user, $passwordupdate->getNewPassword());
                $user->setPassword($hash);
                $manager->persist($user);
                $manager->flush();
                $this->addFlash('success', 'Votre mot de passe a été mis à jour');
                return $this->redirectToRoute('index');
            }
        }

        return $this->render('bcp/account/edit_password.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }
}
