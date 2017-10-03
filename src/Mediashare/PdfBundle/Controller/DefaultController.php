<?php

namespace Mediashare\PdfBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('MediasharePdfBundle:Default:index.html.twig', array('name' => $name));
    }
}
