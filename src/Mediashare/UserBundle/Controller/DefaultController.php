<?php

namespace Mediashare\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $idUser = $this->getUser()->getId();
        $idConfig = $this->getUser()->getConfig();
        $leveling = $this->getUser()->getRanked();
        $nextleveling = $this->getUser()->getNextprogress();
        $username = $this->getUser()->getUsername();
        $email = $this->getUser()->getEmail();
        $points = $this->getUser()->getPoints();
        $classement = $this->getUser()->getClassement();
        $serverName = $this->getUser()->getServerName();

        $passPhrase2 = $this->randomPassword($idUser);

        $em = $this->getDoctrine()->getManager();
        $servers = $em->getRepository('MediashareAppBundle:Top')->findBy(array('iduser' => $idUser), array('points' => 'DESC'));


        return $this->render('MediashareUserBundle:Default:index.html.twig', array(
            'idUser' => $idUser,
            'leveling' => $leveling,
			'nextleveling' => $nextleveling,
			'username' => $username,
			'email' => $email,
			'points' => $points,
			'classement' => $classement,
			'passPhrase2' => $passPhrase2,
            'serverName' => $serverName,
            'entities' => $servers
        ));

    }

    function randomPassword($idUser) {
	    $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
	    $pass = array(); //remember to declare $pass as an array
	    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
	    
	    for ($i = 0; $i < 8; $i++) {
	        $n = rand(0, $alphaLength);
	        $pass[] = $alphabet[$n];
	    }

	    
	    $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('MediashareUserBundle:User')->find($idUser);
        $entity->setTicketpass(implode($pass));
        $em->persist($entity);
        $em->flush();


    return implode($pass); //turn the array into a string
	}
}
