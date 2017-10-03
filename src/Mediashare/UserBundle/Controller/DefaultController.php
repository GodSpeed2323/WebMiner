<?php

namespace Mediashare\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $folders = $this->getUser()->getFolders();
        return $this->render('MediashareUserBundle:Default:index.html.twig', array(
            'folders' => $folders
        ));
    }
}
