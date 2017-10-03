<?php

namespace Mediashare\AdminBundle\Controller;

use Mediashare\AdminBundle\Entity\File;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('MediashareAdminBundle:Default:index.html.twig');
    }

    public function messageAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('MediashareAppBundle:Contact')->findBy(array(), array('createDate' => 'ASC'), 3);
        return $this->render('MediashareAdminBundle::_message.html.twig', array('entities' => $entities));
    }

    public function uploadAction(Request $request)
    {
        $form = $this->createFormBuilder()->getForm();
        $form->handleRequest($request);

        $files = $request->files->all();
        if ($request->getMethod() == "POST") {
            $result = array();
            foreach ($files as $file) {
                $name = sha1(uniqid(mt_rand(), true)) . '.' . $file->guessExtension();
                $file->move(__DIR__ . '/../../../../web/upload/tmp', $name);
                $result[$request->getScheme() . '://' . $request->getHttpHost() . $request->getBasePath() . '/upload/tmp/' . $name] = __DIR__ . '/../../../../web/upload/tmp/' . $name;
            }
            return new JsonResponse($result);
        }

        return $this->render('MediashareAdminBundle:Default:dropzone.html.twig', array(
            'form' => $form->createView()
        ));
    }
}
