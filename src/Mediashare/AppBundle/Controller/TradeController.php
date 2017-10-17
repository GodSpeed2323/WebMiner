<?php

namespace Mediashare\AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Mediashare\AppBundle\Entity\Trade;
use Mediashare\AppBundle\Form\TradeType;

/**
 * Trade controller.
 *
 */
class TradeController extends Controller
{

    /**
     * Lists all Trade entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('MediashareAppBundle:Trade')->findAll();

        return $this->render('MediashareAppBundle:Trade:index.html.twig', array(
            'entities' => $entities,
        ));
    }
    /**
     * Creates a new Trade entity.
     *
     */
    public function createAction(Request $request, $iduser, $idconfig)
    {
        $entity = new Trade();
        $form = $this->createCreateForm($entity,$iduser, $idconfig);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $user = $em->getRepository('MediashareUserBundle:User')->find($iduser);
            $config = $em->getRepository('MediashareAppBundle:Config')->find($idconfig);
            if ($user->getTicketpass() == $form->getData()->getTicketpass() & $this->getUser()->getId() == $config->getIdadmin()) {
               
                $entity->setIduser($iduser);
                $entity->setIdconfig($idconfig);
                $entity->setUsername($user->getUsername());
                $entity->setServername($config->getName());

          

                $em->persist($entity);
                $em->flush();

                $top = $em->getRepository('MediashareAppBundle:Top')->findOneBy(array('iduser' => $iduser, 'idconfig' => $idconfig));
                $points = $top->getPoints();
                $points = $points-$form->getData()->getAmout();
                $top->setPoints($points);
                
                $em->persist($top);
                $em->flush();

                return $this->redirect($this->generateUrl('trade_show', array('id' => $entity->getId())));
            }
        }

        return $this->render('MediashareAppBundle:Trade:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Trade entity.
     *
     * @param Trade $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Trade $entity, $iduser, $idconfig)
    {
        $form = $this->createForm(new TradeType(), $entity, array(
            'action' => $this->generateUrl('trade_create', array('iduser' => $iduser, 'idconfig' => $idconfig)),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Trade entity.
     *
     */
    public function newAction($iduser, $idconfig)
    {
        $entity = new Trade();
        $form   = $this->createCreateForm($entity, $iduser, $idconfig);

        return $this->render('MediashareAppBundle:Trade:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Trade entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MediashareAppBundle:Trade')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Trade entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('MediashareAppBundle:Trade:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Trade entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MediashareAppBundle:Trade')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Trade entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('MediashareAppBundle:Trade:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Trade entity.
    *
    * @param Trade $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Trade $entity)
    {
        $form = $this->createForm(new TradeType(), $entity, array(
            'action' => $this->generateUrl('trade_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Trade entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MediashareAppBundle:Trade')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Trade entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('trade_edit', array('id' => $id)));
        }

        return $this->render('MediashareAppBundle:Trade:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a Trade entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('MediashareAppBundle:Trade')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Trade entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('trade'));
    }

    /**
     * Creates a form to delete a Trade entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('trade_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
