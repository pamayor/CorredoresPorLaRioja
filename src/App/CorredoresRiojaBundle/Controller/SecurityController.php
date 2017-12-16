<?php

namespace App\CorredoresRiojaBundle\Controller;

use Twig_Environment;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController {

    private $twig;
    private $authenticationUtils;

    function __construct(Twig_Environment $twig, AuthenticationUtils $authenticationUtils) {
        $this->twig = $twig;
        $this->authenticationUtils = $authenticationUtils;
    }

    public function loginAction() {
        $error = $this->authenticationUtils->getLastAuthenticationError();
        $lastUsername = $this->authenticationUtils->getLastUsername();
        return new Response($this->twig->render('AppCorredoresRiojaBundle:Security:login.html.twig', array(
                    'last_username' => $lastUsername,
                    'error' => $error,
                    'active' => 'login')));
    }

    public function loginOrganizacionAction() {
        $error = $this->authenticationUtils->getLastAuthenticationError();
        $lastUsername = $this->authenticationUtils->getLastUsername();
        return new Response($this->twig->render('AppCorredoresRiojaBundle:Security:loginorganizacion.html.twig', array(
                    'last_username' => $lastUsername,
                    'error' => $error,
                    'active' => 'login')));
    }

}
