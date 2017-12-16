<?php

namespace App\CorredoresRiojaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('AppCorredoresRiojaBundle:Default:index.html.twig');
    }
}
