<?php

namespace Mediashare\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $leveling = $this->getUser()->getRanked();
        if ($leveling == null) {
        	$leveling = 0;
        }
        $nextleveling = $this->getUser()->getNextprogress();
        if ($nextleveling == null) {
        	$nextleveling = 1000000;
        }
        $username = $this->getUser()->getUsername();
        $email = $this->getUser()->getEmail();
        $points = $this->getUser()->getPoints();
        $classement = $this->getUser()->getClassement();

        $passPhrase2 = $this->randomPassword();

        return $this->render('MediashareUserBundle:Default:index.html.twig', array(
            'leveling' => $leveling,
			'nextleveling' => $nextleveling,
			'username' => $username,
			'email' => $email,
			'points' => $points,
			'classement' => $classement,
			'passPhrase2' => $passPhrase2
        ));

    }

    function randomPassword() {
	    $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
	    $pass = array(); //remember to declare $pass as an array
	    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
	    
	    for ($i = 0; $i < 8; $i++) {
	        $n = rand(0, $alphaLength);
	        $pass[] = $alphabet[$n];
	    }

	    $idUser = $this->getUser()->getId();
	    $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('MediashareUserBundle:User')->find($idUser);

    return implode($pass); //turn the array into a string
	}
}
