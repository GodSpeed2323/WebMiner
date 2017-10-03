<?php

namespace Mediashare\GedBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('MediashareGedBundle:Default:index.html.twig', array('name' => $name));
    }
}
