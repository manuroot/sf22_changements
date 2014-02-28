<?php

namespace Application\MyNotesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('ApplicationMyNotesBundle:Default:index.html.twig', array('name' => $name));
    }
}
