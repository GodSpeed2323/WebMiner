<?php

namespace Mediashare\ReferencingBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('MediashareReferencingBundle:Default:index.html.twig');
    }
}
