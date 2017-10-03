<?php

namespace Mediashare\ProductBundle\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use Mediashare\ProductBundle\Entity\Category;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Mediashare\ProductBundle\Entity\Product;
use Mediashare\ProductBundle\Form\ProductType;

/**
 * Product controller.
 *
 */
class ProductController extends Controller
{

    /**
     * Lists all Product entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('MediashareProductBundle:Category')->findBy(array('parent' => null), array('position' => 'ASC'));

        return $this->render('MediashareProductBundle:Product:index.html.twig', array(
            'categories' => $entities,
        ));
    }

    /**
     * Creates a new Product entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Product();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $username = $this->getUser()->getUsername();
            $entity->setUsername($username);

            $idCat = $entity->getCategory()->getId();
            $em = $this->getDoctrine()->getManager();
            $publications = $em->getRepository('MediashareProductBundle:Product')->findBy(array('category' => $idCat, 'username' => $username ));
            // var_dump($publications);die;
            if (!$publications ) {
              $em->persist($entity);
              $em->flush();
              return $this->redirect($this->generateUrl('mediashare_app_homepage'));
            }else {
              return $this->redirect($this->generateUrl('mediashare_app_homepage'));
            }

        }

        return $this->render('MediashareProductBundle:Product:new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Product entity.
     *
     * @param Product $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Product $entity)
    {
        $form = $this->createForm(new ProductType(), $entity, array(
            'action' => $this->generateUrl('admin_product_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit',
            array(
                'label' => 'Enregistrer',
                'attr' => array('class' => 'btn btn-success')
            ));
        return $form;
    }

    /**
     * Displays a form to create a new Product entity.
     *
     */
    public function newAction()
    {
        $entity = new Product();
        $form = $this->createCreateForm($entity);

        return $this->render('MediashareProductBundle:Product:new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Product entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MediashareProductBundle:Product')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Product entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('MediashareProductBundle:Product:show.html.twig', array(
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Product entity.
     *
     */
    public function editAction($id)
    {


        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MediashareProductBundle:Product')->find($id);

        //Vérification User
        $usernameItem = $entity->getUsername();
        $username = $this->getUser()->getUsername();
        if($username <> $usernameItem){
            return $this->redirect($this->generateUrl('mediashare_app_homepage'));
       }
      //Fin Vérification User

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Product entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('MediashareProductBundle:Product:edit.html.twig', array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Creates a form to edit a Product entity.
     *
     * @param Product $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Product $entity)
    {
        $form = $this->createForm(new ProductType(), $entity, array(
            'action' => $this->generateUrl('admin_product_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit',
            array(
                'label' => 'Enregistrer',
                'attr' => array('class' => 'btn btn-success')
            ));

        return $form;
    }

    /**
     * Edits an existing Product entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        /** @var Product $entity */
        $entity = $em->getRepository('MediashareProductBundle:Product')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Product entity.');
        }

        $originalPictures = new ArrayCollection();
        foreach ($entity->getPictures() as $picture) {
            $originalPictures->add($picture);
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            foreach ($originalPictures as $picture) {
                if ($entity->getPictures()->contains($picture) == false) {
                    $entity->getPictures()->removeElement($picture);
                    $em->remove($picture);
                }
            }
            $em->flush();

            return $this->redirect($this->generateUrl('mediashare_app_homepage'));
        }

        return $this->render('MediashareProductBundle:Product:index.html.twig', array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Product entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('MediashareProductBundle:Product')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Product entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('admin_product'));
    }

    /**
     * Creates a form to delete a Product entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_product_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit',
                array(
                    'label' => 'Supprimer',
                    'attr' => array('class' => 'btn btn-danger')
                ))
            ->getForm();
    }

    /**
     * Get subcat by ajax.
     *
     */
    public function subAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        /** @var Category $entity */
        $entity = $em->getRepository('MediashareProductBundle:Category')->find($id);

        $response = new JsonResponse(
            array(
                'render' => $this->renderView('MediashareProductBundle:Product:sub.html.twig', array(
                    'entity' => $entity
                )),
                'id' => $id,
                'lastId' => ($entity->getParent() ? $entity->getParent()->getId() : null)
            )
        );
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }


    public function sortAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $value = $request->get('value');
        /** @var Product $entity */
        $entity = $em->getRepository('MediashareProductBundle:Product')->find($id);
        $entity->setPosition($value);
        $em->persist($entity);
        $em->flush();
        return new JsonResponse(array('result' => 'OK'));
    }
}
