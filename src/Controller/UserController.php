<?php

namespace App\Controller;

use App\Form\AccountType;
use App\Entity\PasswordUpdate;
use App\Form\PasswordUpdateType;
use Symfony\Component\Form\FormError;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{
    /**
     * @Route("/", name="user_log")
     */
    public function login(AuthenticationUtils $outils)
    {
        $erreur = $outils->getLastAuthenticationError();
        $identifiant = $outils->getLastUsername();

        return $this->render('user/log.html.twig', [
            'erreur' => $erreur !== null,
            'identifiant' => $identifiant
        ]);
        return $this->redirectToRoute('homepage');
    }

    /**
     * permet la deconnexion
     *
     * @Route("/user/logout", name="user_logout")
     * @return void
     */
    public function logout()
    {
    }

    /**
     * modif profil
     * @Route("/user/info", name="user_info")
     */
    public function profile(Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder){
        
        $user = $this->getUser();

        $form_info = $this->createForm(AccountType::class, $user);

        $form_info->handleRequest($request);

        if($form_info->isSubmitted() && $form_info->isValid()){
            $manager->persist($user);
            $manager->flush();

            $this->addFlash(
                'success',
                "Modification du profil enregistré"
            );
        }

        $passwordUpdate = new PasswordUpdate();

        $form = $this->createForm(PasswordUpdateType::class, $passwordUpdate);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            //verifié le oldpassword
            if(!password_verify($passwordUpdate->getOldPassword(), $user->getHash())){
                //gérer l'erreur
                $form->get('oldPassword')->addError(new FormError("Le mot de passe ne correspond pas a votre mot de passe actuel"));
            } else {
                $newPassword = $passwordUpdate->getNewPassword();
                $hash = $encoder->encodePassword($user, $newPassword);

                $user->setHash($hash);

                $manager->persist($user);
                $manager->flush($user);

                $this->addFlash(
                    'success',
                    "Votre mot de passe a bien été modifié !"
                );
            }
        }

        return $this->render('user/modifInfo.html.twig', [
            'form_info' => $form_info->createView(),
            'form' => $form->createView(),
        ]);
    }
}
