<?php

namespace Mediashare\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Mediashare\AppBundle\Entity\Top;

class TopController extends Controller
{
	public function updateAction()
	{
		$this->updateServerStat();
		$this->updateTopMiners();
		$this->updateProfile();
        $this->updateConnected();

		return $this->render('MediashareAppBundle::zero.html.twig');
	}

    public function updateProfile()
    { 
        $id = $this->getUser()->getId();

        $points_total = 0;

        $em = $this->getDoctrine()->getManager();
        $top = $em->getRepository('MediashareAppBundle:Top')->findBy(array('iduser' => $id));
        foreach ($top as $key => $value) {
            $classement = $value->getRanked();
            $points_total = $points_total+$value->getPoints();
            if ($classement < $value->getRanked()) {
                $classement = $classement;
            } 
        }

        $user = $em->getRepository('MediashareUserBundle:User')->find($id);
        $user->setPoints($points_total);
        $user->setClassement($classement);

        $em->persist($user);
        $em->flush();

    }

    public function updateConnected()
    { 
        $em = $this->getDoctrine()->getManager();
        $config = $em->getRepository('MediashareAppBundle:Config')->findAll();
        foreach ($config as $key => $value) {
            $connected = $em->getRepository('MediashareAppBundle:Top')->findBy(array('idconfig' => $value->getId(),'connected' => true ));
            $nbOnline = 0;
            foreach ($connected as $key => $value2) {
                $user = $em->getRepository('MediashareUserBundle:User')->find($value2->getIduser());
                $user->setConnected(true);
                $em->persist($user);
                $em->flush();
                $nbOnline++;
            }
            $value->setNbOnline($nbOnline);
            $em->persist($user);
            $em->flush();
        }
    }

    public function online($today,$top)
    {
        // User Mine on server configurate
        $IdUser = $top->getId();
        $em1 = $this->getDoctrine()->getManager();
            $login = $em1->getRepository('MediashareAppBundle:Top')->find($IdUser);
            $login->setConnected(true);
            $login->setTimer($today);
        $em1->persist($login);
        $em1->flush();

    }
	public function updateServerStat()
    {
        
        // Get Config User
        $em = $this->getDoctrine()->getManager();
        $config = $em->getRepository('MediashareAppBundle:Config')->findAll();

       	foreach ($config as $key => $value) {
       		$privatekey = $value->getPrivateKey();
       		$idServer = $value->getId();
       		 // Get Server Info
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_URL => 'https://api.coinhive.com/stats/site?secret='.$privatekey
            ));
            $result = curl_exec($curl);
            $json = json_decode($result,true);
            echo $result;
            curl_close($curl);
            $error = $json['success'];
            if ($error == true) {
                $points_seconde = $json['hashesPerSecond'];
                $points_total = $json['hashesTotal'];
                $xmr_total = $json['xmrPending'];
                
                // Set Info 
                $Server = $em->getRepository('MediashareAppBundle:Config')->find($idServer);
                $Server->setPointsSeconde($points_seconde);
                $Server->setPointsTotal($points_total);
                $Server->setXmrTotal($xmr_total);

                $em->persist($Server);
                $em->flush();
            }
       	}
       
    }
	

	public function updateTopMiners()
	{
		

        // Get Config User
        $em = $this->getDoctrine()->getManager();
        $config = $em->getRepository('MediashareAppBundle:Config')->findAll();
        foreach ($config as $key => $value) {
			$privatekey = $value->getPrivateKey();
            $id_Config = $value->getId();
            $configName = $value->getName();
        	
        $today = date("mdHi");
        $loop = 0;
        $response = array();
        $curl = curl_init();

        // Get Info Top Miners (100)
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => 'https://api.coinhive.com/user/top?secret='.$privatekey.'&count=100'
        ));
        $result = curl_exec($curl);
        $json = json_decode($result,true);
        if ($json['success'] == true ) {
            // Loop 100 Miners
            foreach($json['users'] as $i => $values){
              $username_json = htmlentities($values['name']);
              $total_json = htmlentities($values['total']);
              $loop++;
              $response[$values['name']] = htmlentities($values['total']);

              // Get a User for Update Info & Leveling if user exist
              $user = $em->getRepository('MediashareUserBundle:User')->findOneBy(array('username' => $username_json));
              if ($user) {
              // Get Server Configurate for user
              $iduser_json = $user->getId();
              $top = $em->getRepository('MediashareAppBundle:Top')->findOneBy(array('iduser' => $iduser_json, 'idconfig' => $id_Config ));
              // Create a Top if not exist
              if (!$top) {
                $top = new Top();
    			$em = $this->getDoctrine()->getManager();
    	        $top->setUsername($username_json);
    	        $top->setIdconfig($id_Config);
    	        $top->setIduser($iduser_json);
                $top->setPoints($total_json);
                $top->setServername($configName);
    	        $progress = 100*$total_json/100000;

    	        $top->setRanked(0);
    	        $top->setClassement(100);
    	        $top->setConnected(false);
    	        $top->setRankedupdated(false);
    	        $top->setTimer($today);
    	        $top->setNextprogress(100000);
    	        $top->setProgress($progress);
    	        $top->setTicketpass('NotSecure');
    	        
    	        $em->persist($top);
    	        $em->flush();
              }else{
    	        $top->setUsername($username_json);
                $top->setServername($configName);
    	        // Test if Points update
    	        if ($top->getPoints() < $total_json) {
    	            $this->online($today, $top);
    	            $top->setPoints($total_json);
    	            $top->setClassement($loop);

    	        }
    	        // Offline Miner AFK
    	        if ($top->getPoints() >= $total_json) {
    	            if ($top->getTimer() <= $today-2) {
    	                $top->setConnected(0);
    	            }
    	            $top->setClassement($loop);
    	        }       

    	        // Test Level Rank
    	        $user_rank = $top->getRanked();
    	        $base = 100000;
    	        // if level < 1
    	        if ($user_rank == null | $user_rank == 0) {
    	            if ($total_json < $base) {
    	                $user_rank = 0;
    	                $progress = 100*$total_json/$base;
    	                $top->setProgress($progress);
    	                $top->setNextprogress($base);
    	                $top->setRanked(0);
    	                $top->setRankedupdated(false);
    	            }else{
    	                $user_rank = 1;
    	                $top->setRanked(1);
    	                $top->setRankedupdated(true);
    	            }
    	        }
    	            if ($total_json > $base) {
    	                    $i=2;
    	                while ($i <= 20) {
    	                    if ($total_json > $base*2) {
    	                        $base = $base*2;
    	                        if ($user_rank < $i) {
    	                            $levelup = $i;
    	                            $top->setRanked($levelup);
    	                            $top->setRankedupdated(true);
    	                            // Affichage des paliers
    	                            //echo $base; echo "Level :".$levelup."\n";
    	                        }
    	                    }
    	                    $i++;
    	                }
    	                $progress = $total_json*10/$base*2;
    	                //echo $progress."\n";
    	                $top->setProgress(100*$total_json/$base*2);
    	                $top->setNextprogress($base*2);
    	            }
    	            $em->persist($top);
    	            $em->flush();
    	        }
    	   }
    	}
    }
    echo json_encode($response);
    curl_close($curl);	
        }
	}

 	public function createTop($iduser_json, $idconfig_json, $total_json, $em, $today, $username_json)
    {
        $top = new Top();

        $top->setUsername($username_json);
        $top->setIdconfig($idconfig_json);
        $top->setIduser($iduser_json);
        $top->setPoints($total_json);
        $progress = 100*$total_json/100000;

        $top->setRanked(0);
        $top->setClassement(100);
        $top->setConnected(false);
        $top->setRankedupdated(false);
        $top->setTimer($today);
        $top->setNextprogress(100000);
        $top->setProgress($progress);
        $top->setTicketpass('NotSecure');
        
        $em->persist($top);
        $em->flush();

    }
}
