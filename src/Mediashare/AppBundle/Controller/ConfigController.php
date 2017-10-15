<?php

namespace Mediashare\AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Mediashare\AppBundle\Entity\Config;
use Mediashare\AppBundle\Form\ConfigType;

/**
 * Config controller.
 *
 */
class ConfigController extends Controller
{

    /**
     * Lists all Config entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('MediashareAppBundle:Config')->findBy(array(),array('pointsTotal' => 'DESC'));

        return $this->render('MediashareAppBundle:Config:index.html.twig', array(
            'entities' => $entities,
            'error' => $error = null
        ));
    }

    /**
     * Lists all Config entities.
     *
     */
    public function errorAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('MediashareAppBundle:Config')->findBy(array(),array('pointsTotal' => 'DESC'));
        $error = "Select a Server !";
        return $this->render('MediashareAppBundle:Config:index.html.twig', array(
            'entities' => $entities,
            'error' => $error,
        ));
    }
    /**
     * Connexion all Config entities.
     *
     */
    public function connexionAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $idUser = $this->getUser()->getId();
        $user = $em->getRepository('MediashareUserBundle:User')->find($idUser);
        $config = $em->getRepository('MediashareAppBundle:Config')->find($id);
        $user->setConfig($id);
        $user->setServername($config->getName());

        $em->persist($user);
        $em->flush();

        return $this->redirect($this->generateUrl('mediashare_app_homepage'));
    }
    /**
     * Creates a new Config entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Config();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);
        $error = false;
        if ($form->isValid()) {
            $token = $form->getData()->getPrivatekey();
            $response = array();
            $curl = curl_init();
            // Test Token
            curl_setopt_array($curl, array(
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_URL => 'https://api.coinhive.com/user/top?secret='.$token.'&count=100'
            ));
            $result = curl_exec($curl);
            $json = json_decode($result,true);
            if ($json['success'] == true ) {
                $em = $this->getDoctrine()->getManager();
                
                $idadmin = $this->getUser()->getId();
                $admin = $this->getUser()->getUsername();
                $entity->setidAdmin($idadmin);
                $entity->setAdmin($admin);
                $entity->setOnline(true);

                $em->persist($entity);
                $em->flush();

                // return $this->redirect($this->generateUrl('create_server_show', array('id' => $entity->getId())));
                return $this->redirect($this->generateUrl('create_server'));
            }else{
                $error = "Your CoinHive Api Keys is not valid !";
            }
        }

        return $this->render('MediashareAppBundle:Config:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'error' => $error,
        ));
    }

    /**
     * Creates a form to create a Config entity.
     *
     * @param Config $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Config $entity)
    {
        $form = $this->createForm(new ConfigType(), $entity, array(
            'action' => $this->generateUrl('create_server_create'),
            'method' => 'POST',
        ));

         $form->add('submit', 'submit',
        array(
            'label' => 'Ajouter',
            'attr' => array('class' => 'btn btn-success')
        ));

        return $form;
    }

    /**
     * Displays a form to create a new Config entity.
     *
     */
    public function newAction()
    {
        $entity = new Config();
        $form   = $this->createCreateForm($entity);

        return $this->render('MediashareAppBundle:Config:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'error' => $error = null
        ));
    }

    /**
     * Finds and displays a Config entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MediashareAppBundle:Config')->find($id);
        $serverName = $entity->getName();
        $users = $em->getRepository('MediashareAppBundle:Top')->findBy(array('idconfig' => $id), array('classement' => 'ASC'));

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Config entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        if ($entity->getOnline() == 1) {
            $onlineForm = $this->createOfflineForm($id);
        }else{
            $onlineForm = $this->createOnlineForm($id);
        }

        return $this->render('MediashareAppBundle:Config:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
            'online_form' => $onlineForm->createView(),
            'users' => $users,
        ));
    }

    /**
     * Displays a form to edit an existing Config entity.
     *
     */
    public function editAction($id)
    {   
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('MediashareAppBundle:Config')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Config entity.');
        }
        $idadmin = $entity->getIdadmin();
        if ($this->getUser()->hasRole('ROLE_ADMIN') || $this->getUser()->getId() == $idadmin) {
            
            $editForm = $this->createEditForm($entity);
            $deleteForm = $this->createDeleteForm($id);
            if ($entity->getOnline() == 1) {
                $onlineForm = $this->createOfflineForm($id);
            }else{
                $onlineForm = $this->createOnlineForm($id);
            }

            return $this->render('MediashareAppBundle:Config:edit.html.twig', array(
                'entity'      => $entity,
                'edit_form'   => $editForm->createView(),
                'online_form'   => $onlineForm->createView(),
                'delete_form' => $deleteForm->createView(),
            ));
        } else {
            return $this->indexAction();
        }
    }

    /**
    * Creates a form to edit a Config entity.
    *
    * @param Config $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Config $entity)
    {
        $form = $this->createForm(new ConfigType(), $entity, array(
            'action' => $this->generateUrl('create_server_update', array('id' => $entity->getId())),
            'method' => 'PUT',
            'edit' => true
        ));

         $form->add('submit', 'submit',
            array(
                'label' => 'Enregistrer',
                'attr' => array('class' => 'btn btn-success')
            ));

        return $form;
    }
    /**
     * Edits an existing Config entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MediashareAppBundle:Config')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Config entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('create_server_show', array('id' => $id)));
        }

        return $this->render('MediashareAppBundle:Config:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a Config entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);
        
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('MediashareAppBundle:Config')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Config entity.');
        }

        $idadmin = $entity->getIdadmin();
        if ($this->getUser()->hasRole('ROLE_SUPER_ADMIN') || $this->getUser()->getId() == $idadmin) {
            if ($form->isValid()) {
                $em->remove($entity);
                $em->flush();
            }
        }

        return $this->redirect($this->generateUrl('create_server'));
    }
    
    /**
     * Online a Config entity.
     *
     */
    public function onlineAction(Request $request, $id)
    {
        $form = $this->createOnlineForm($id);
        $form->handleRequest($request);
        
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('MediashareAppBundle:Config')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Config entity.');
        }

        $idadmin = $entity->getIdadmin();
        if ($this->getUser()->hasRole('ROLE_SUPER_ADMIN') || $this->getUser()->getId() == $idadmin) {
            if ($form->isValid()) {
                $entity->setOnline(1);
                $em->persist($entity);
                $em->flush();
            }
        }

        return $this->redirect($this->generateUrl('create_server_show', array('id' => $entity->getId())));
    } 
    /**
     * Offline a Config entity.
     *
     */
    public function offlineAction(Request $request, $id)
    {
        $form = $this->createOnlineForm($id);
        $form->handleRequest($request);
        
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('MediashareAppBundle:Config')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Config entity.');
        }

        $idadmin = $entity->getIdadmin();
        if ($this->getUser()->hasRole('ROLE_SUPER_ADMIN') || $this->getUser()->getId() == $idadmin) {
            if ($form->isValid()) {
                $entity->setOnline(0);
                $em->persist($entity);
                $em->flush();
            }
        }

        return $this->redirect($this->generateUrl('create_server_show', array('id' => $entity->getId())));
    }
    
    /**
     * Creates a form to delete a Config entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('create_server_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit',
                array(
                    'label' => 'Supprimer',
                    'attr' => array('class' => 'btn btn-danger')
                ))
            ->getForm();
        ;
    }
    /**
     * Creates a form to delete a Config entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createOnlineForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('create_server_online', array('id' => $id)))
            ->setMethod('PUT')
            ->add('submit', 'submit',
                array(
                    'label' => ' Go Online',
                    'attr' => array('class' => 'btn btn-success fa fa-power-off')
                ))
            ->getForm();
        ;
    }
    /**
     * Creates a form to delete a Config entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createOfflineForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('create_server_offline', array('id' => $id)))
            ->setMethod('PUT')
            ->add('submit', 'submit',
                array(
                    'label' => ' Go Offline',
                    'attr' => array('class' => 'btn btn-wanted fa fa-power-off')
                ))
            ->getForm();
        ;
    }

}
