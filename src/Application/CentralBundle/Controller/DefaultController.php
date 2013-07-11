<?php

namespace Application\CentralBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('ApplicationCentralBundle:Default:index.html.twig', array('name' => $name));
    }
}
