<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LogAdminController extends AbstractController
{
    /**
     * @Route("/admin/log", name="log_admin")
     */
    public function login(AuthenticationUtils $outils)
    {
        $erreur = $outils->getLastAuthenticationError();
        $identifiant = $outils->getLastUsername();
        
        return $this->render('admin/log.html.twig', [
            'erreur' => $erreur !== null,
            'identifiant' => $identifiant,
        ]);
        return $this->redirectToRoute('admin_categ');
    }

    /**
     * permet la deconnexion
     *
     * @Route("/admin/logout", name="admin_logout")
     * @return void
     */
    public function logout(){

    }
}
