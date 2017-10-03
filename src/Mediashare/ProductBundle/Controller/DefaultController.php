<?php

namespace Mediashare\ProductBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $product = $this->getDoctrine()->getRepository('MediashareProductBundle:Product')->findAll();
        return $this->render('MediashareProductBundle:Default:index.html.twig', array('product' => $product));
    }

    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $detail = $em->getRepository('MediashareProductBundle:Product')->find($id);
        return $this->render('MediashareProductBundle:Default:show.html.twig', array('detail' => $detail));
    }

    public function categoriesAction($id)
    {

        $catParent = $this->getDoctrine()->getRepository('MediashareProductBundle:Category')->find($id);
        if(!$catParent){
            throw $this->createNotFoundException('Unable to find Category entity.');
        }
        $categories = $this->getDoctrine()->getRepository('MediashareProductBundle:Category')->findBy(array('parent' => $id, 'online'=> true), array('position'=> 'DESC'));

        return $this->render('MediashareProductBundle:Default:categories.html.twig', array('categories' => $categories, 'parent'=> $catParent));
    }

    public function showCategoryAction($id)
    {

        $catParent = $this->getDoctrine()->getRepository('MediashareProductBundle:Category')->find($id);
        if(!$catParent){
            throw $this->createNotFoundException('Unable to find Category entity.');
        }
        $products = $this->getDoctrine()->getRepository('MediashareProductBundle:Product')->findBy(array('category'=> $id, 'online'=> true), array('position'=> 'DESC'));

        return $this->render('MediashareProductBundle:Default:category_show.html.twig', array('products' => $products, 'parent'=> $catParent));
    }
}

