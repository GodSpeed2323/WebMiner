<?php

namespace Mediashare\AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Mediashare\AppBundle\Entity\Contact;
use Mediashare\AppBundle\Form\ContactType;

/**
 * Contact controller.
 *
 */
class ContactController extends Controller
{

    /**
     * Lists all Contact entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('MediashareAppBundle:Contact')->findBy(
            array(),
            array('createDate' => 'DESC')
        );

        return $this->render('MediashareAppBundle:Contact:index.html.twig', array(
            'entities' => $entities,
        ));
    }

    /**
     * Finds and displays a Contact entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MediashareAppBundle:Contact')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Contact entity.');
        }

        return $this->render('MediashareAppBundle:Contact:show.html.twig', array(
            'entity'      => $entity
        ));
    }
}
