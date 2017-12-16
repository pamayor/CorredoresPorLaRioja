<?php

namespace App\CorredoresRiojaBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\CorredoresRiojaBundle\Form\CorredorType;
use Doctrine\ORM\EntityManager;
use App\CorredoresRiojaDomain\Model\Corredor;
use App\CorredoresRiojaDomain\Model\Carrera;
use App\CorredoresRiojaDomain\Model\Organizacion;
use App\CorredoresRiojaDomain\Model\Participante;
use Twig_Environment;
use Symfony\Component\Security\Core\Encoder\EncoderFactory;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\RedirectResponse;
use BeSimple\I18nRoutingBundle\Routing\Router;
use Symfony\Component\Translation\TranslatorInterface;

class CorredoresController {

    private $twig;
    private $entityManager;
    private $encoderFactory;
    private $tokenStorage;
    private $formFactory;
    private $router;
    private $translator;
    private $mailer;

    function __construct(Twig_Environment $twig, EntityManager $entityManager, EncoderFactory $encoderFactory, TokenStorage $tokenStorage, FormFactory $formFactory, Router $router, TranslatorInterface $translator, \Swift_Mailer $mailer) {
        $this->twig = $twig;
        $this->entityManager = $entityManager;
        $this->encoderFactory = $encoderFactory;
        $this->tokenStorage = $tokenStorage;
        $this->formFactory = $formFactory;
        $this->router = $router;
        $this->translator = $translator;
        $this->mailer = $mailer;
    }

    public function indexAction() {
        $carreras = $this->entityManager->getRepository(Carrera::class)->buscarDisputadasONo(FALSE);
        return new Response($this->twig->render("AppCorredoresRiojaBundle:Corredores:portada.html.twig", array('carreras' => $carreras, 'active' => 'index')));
    }

    public function getCarrerasAction() {
        $carrerasNoDisputadas = $this->entityManager->getRepository(Carrera::class)->buscarDisputadasONo(FALSE);
        $carrerasSiDisputadas = $this->entityManager->getRepository(Carrera::class)->buscarDisputadasONo(TRUE);
        return new Response($this->twig->render("AppCorredoresRiojaBundle:Corredores:carreras.html.twig", array('carrerasNoDisputadas' => $carrerasNoDisputadas, 'carrerasDisputadas' => $carrerasSiDisputadas, 'active' => 'carreras')));
    }

    public function getDetalleCarreraPorSlugAction($slug) {
        $detalleCarrera = $this->entityManager->getRepository(Carrera::class)->buscarPorSlug($slug);
        $participantes = $this->entityManager->getRepository(Participante::class)->buscarParticipantesDeCarrera($detalleCarrera->getId());
        return new Response($this->twig->render("AppCorredoresRiojaBundle:Corredores:carrera.html.twig", array('carrera' => $detalleCarrera, 'participantes' => $participantes)));
    }

    public function getDetalleOrganizacionPorSlugAction($slug) {
        $organizacion = $this->entityManager->getRepository(Organizacion::class)->buscarPorSlug($slug);
        $carreras = $this->entityManager->getRepository(Carrera::class)->buscarPorOrganizacionDisputadasONo($organizacion->getId(), FALSE);
        return new Response($this->twig->render("AppCorredoresRiojaBundle:Corredores:organizacion.html.twig", array('organizacion' => $organizacion, 'carreras' => $carreras)));
    }

    public function registroAction(Request $peticion) {
        $form = $this->formFactory->create(CorredorType::class);
        $form->handleRequest($peticion);
        if ($form->isValid()) {
            // RecogentityManageros el corredor que se ha registrado
            $corredor = $form->getData();
            // Codificamos la contraseña del corredor
            $encoderFactory = $this->encoderFactory->getEncoder($corredor);
            $password = $encoderFactory->encodePassword($corredor->getPassword(), $corredor->getSalt());
            $corredor->saveEncodedPassword($password);
            // Lo almacenamos en nuestro repositorio de corredores
            $this->entityManager->getRepository(Corredor::class)->guardar($corredor);
            // Creamos un mensaje flash para mostrar al usuario que se ha registrado correctamente
            $session = $peticion->getSession();
            $session->getFlashBag()->add('info', $this->translator->trans('enhorabuena') . $corredor->getNombre() . $this->translator->trans('registro_ok'));
            //Enviamos mensaje de confirmacion
            $message = \Swift_Message::newInstance()
                    ->setSubject('Registro correcto')
                    ->setFrom('corredoreslarioja@noreply.com')
                    ->setTo($corredor->getEmail())
                    ->setBody($corredor->getNombre() . ' has realizado el registro correctamente');
            $this->mailer->send($message);
            // Reedirigimos al usuario a la portada
            return new RedirectResponse($this->router->generate('corredores_homepage'));
        }
        return new Response($this->twig->render("AppCorredoresRiojaBundle:Corredores:registro.html.twig", array('formulario' => $form->createView())));
    }

    public function getMisCarrerasAction() {
        $user = $this->tokenStorage->getToken()->getUser();
        $corredor = $this->entityManager->getRepository(Corredor::class)->buscarPorDNI($user->getUsername());
        $participacionesSiDisputadas = $this->entityManager->getRepository(Participante::class)->buscarCarrerasDisputadasONoDeCorredor($user->getUsername(), TRUE);
        $participacionesNoDisputadas = $this->entityManager->getRepository(Participante::class)->buscarCarrerasDisputadasONoDeCorredor($user->getUsername(), FALSE);
        return new Response($this->twig->render("AppCorredoresRiojaBundle:Corredores:miscarreras.html.twig", array('participacionesNoDisputadas' => $participacionesNoDisputadas, 'participacionesSiDisputadas' => $participacionesSiDisputadas, 'nameUser' => $corredor->getNombre(), 'active' => 'miscarreras')));
    }

    public function inscribirAction($slug, Request $peticion) {
        $user = $this->tokenStorage->getToken()->getUser();
        $corredor = $this->entityManager->getRepository(Corredor::class)->buscarPorDNI($user->getUsername());
        $carrera = $this->entityManager->getRepository(Carrera::class)->buscarPorSlug($slug);
        $participante = $this->entityManager->getRepository(Participante::class)->obtenerCorredorEnCarrera($corredor->getDni(), $carrera->getId());
        $session = $peticion->getSession();
        if (count($participante) == 0) {
            $this->entityManager->getRepository(Participante::class)->inscribirCorredorEnCarrera($corredor, $carrera);
            $session->getFlashBag()->add('info', $this->translator->trans('enhorabuena_carrera') . $carrera->getNombre());
        } else {
            $session->getFlashBag()->add('info', $this->translator->trans('mal_carrera') . $carrera->getNombre());
        }
        return new RedirectResponse($this->router->generate('corredores_mis_carreras'));
    }

    public function desapuntarAction($id) {
        $this->entityManager->getRepository(Participante::class)->eliminar($id);
        return new RedirectResponse($this->router->generate('corredores_mis_carreras'));
    }

    public function perfilAction(Request $peticion) {
        $user = $this->tokenStorage->getToken()->getUser();
        $corredorInicial = $this->entityManager->getRepository(Corredor::class)->buscarPorDNI($user->getUsername());
        $form = $this->formFactory->create(CorredorType::class, $corredorInicial, array(
            'is_profile' => true
        ));
        $form->handleRequest($peticion);
        if ($form->isValid()) {
            $corredor = $form->getData();
            if ($corredor->getPassword()) {
                $encoderFactory = $this->encoderFactory->getEncoder($corredor);
                $password = $encoderFactory->encodePassword($corredor->getPassword(), $corredor->getSalt());
                $corredor->saveEncodedPassword($password);
            } else {
                $corredor->saveEncodedPassword($corredorInicial->getPassword());
            }
            $this->entityManager->getRepository(Corredor::class)->actualizar($corredor);
            $session = $peticion->getSession();
            $session->getFlashBag()->add('info', $this->translator->trans('enhorabuena') . $corredor->getNombre() . $this->translator->trans('actualiza_ok'));
            return new RedirectResponse($this->router->generate('corredores_homepage'));
        }
        return new Response($this->twig->render("AppCorredoresRiojaBundle:Corredores:perfil.html.twig", array('formulario' => $form->createView(), 'active' => 'perfil')));
    }

}
