<?php

namespace Mediashare\NewsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $listnews = $this->getDoctrine()->getRepository('MediashareNewsBundle:News')->findAll();
        return $this->render('MediashareNewsBundle:Default:index.html.twig', array('listnews' => $listnews));
    }

    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $detailnews = $em->getRepository('MediashareNewsBundle:News')->find($id);
        return $this->render('MediashareNewsBundle:Default:show.html.twig', array('detailnews' => $detailnews));
    }
}
