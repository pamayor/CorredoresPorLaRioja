<?php

namespace App\CorredoresRiojaBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManager;
use App\CorredoresRiojaDomain\Model\Carrera;
use App\CorredoresRiojaDomain\Model\Organizacion;
use Twig_Environment;
use Symfony\Component\Security\Core\Encoder\EncoderFactory;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\RedirectResponse;
use BeSimple\I18nRoutingBundle\Routing\Router;
use App\CorredoresRiojaBundle\Form\OrganizadorType;

class OrganizacionController {

    private $twig;
    private $entityManager;
    private $encoderFactory;
    private $tokenStorage;
    private $formFactory;
    private $router;

    function __construct(Twig_Environment $twig, EntityManager $entityManager, EncoderFactory $encoderFactory, TokenStorage $tokenStorage, FormFactory $formFactory, Router $router) {
        $this->twig = $twig;
        $this->entityManager = $entityManager;
        $this->encoderFactory = $encoderFactory;
        $this->tokenStorage = $tokenStorage;
        $this->formFactory = $formFactory;
        $this->router = $router;
    }

    public function indexAction() {
        $user = $this->tokenStorage->getToken()->getUser();
        $organizacion = $this->entityManager->getRepository(Organizacion::class)->buscarPorEmail($user->getUsername());
        $carrerasNoDisputadas = $this->entityManager->getRepository(Carrera::class)->buscarPorOrganizacionDisputadasONo($organizacion->getId(), FALSE);
        $carrerasSiDisputadas = $this->entityManager->getRepository(Carrera::class)->buscarPorOrganizacionDisputadasONo($organizacion->getId(), TRUE);
        return new Response($this->twig->render("AppCorredoresRiojaBundle:Organizadores:portada.html.twig", array('carrerasNoDisputadas' => $carrerasNoDisputadas, 'carrerasDisputadas' => $carrerasSiDisputadas, 'active' => 'index')));
    }

    public function registroAction(Request $peticion) {
        $form = $this->formFactory->create(OrganizadorType::class);
        $form->handleRequest($peticion);
        if ($form->isValid()) {
            $organizador = $form->getData();
            $encoderFactory = $this->encoderFactory->getEncoder($organizador);
            $password = $encoderFactory->encodePassword($organizador->getPassword(), $organizador->getSalt());
            $organizador->saveEncodedPassword($password);
            $this->entityManager->getRepository(Organizacion::class)->guardar($organizador);
            $session = $peticion->getSession();
            $session->getFlashBag()->add('info', '¡Enhorabuena, ' . $organizador->getNombre() . ' te has registrado en CorredoresPorLaRioja!');
            // Reedirigimos al usuario a la portada
            return new RedirectResponse($this->router->generate('organizadores_homepage'));
        }
        return new Response($this->twig->render("AppCorredoresRiojaBundle:Organizadores:registro.html.twig", array('formulario' => $form->createView())));
    }

    public function perfilAction(Request $peticion) {
        $user = $this->tokenStorage->getToken()->getUser();
        $organizacionInicial = $this->entityManager->getRepository(Organizacion::class)->buscarPorEmail($user->getUsername());
        $form = $this->formFactory->create(OrganizadorType::class, $organizacionInicial, array(
            'is_profile' => true
        ));
        $form->handleRequest($peticion);
        if ($form->isValid()) {
            $organizador = $form->getData();
            if ($organizador->getPassword()) {
                $encoderFactory = $this->encoderFactory->getEncoder($organizador);
                $password = $encoderFactory->encodePassword($organizador->getPassword(), $organizador->getSalt());
                $organizador->saveEncodedPassword($password);
            } else {
                $organizador->saveEncodedPassword($organizacionInicial->getPassword());
            }
            $this->entityManager->getRepository(Organizacion::class)->actualizar($organizador);
            $session = $peticion->getSession();
            $session->getFlashBag()->add('info', '¡Enhorabuena, ' . $organizador->getNombre() . ' has actualizado correctamente tus datos!');
            return new RedirectResponse($this->router->generate('organizadores_homepage'));
        }
        return new Response($this->twig->render("AppCorredoresRiojaBundle:Organizadores:perfil.html.twig", array('formulario' => $form->createView(), 'active' => 'perfil')));
    }

}
