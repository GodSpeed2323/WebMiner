<?php

namespace Mediashare\AppBundle\Controller;

use Doctrine\Common\Persistence\Mapping\MappingException;
use Mediashare\AppBundle\Entity\Contact;
use Mediashare\AppBundle\Entity\Server;
use Mediashare\AppBundle\Entity\Config;
use Mediashare\UserBundle\Entity\User;
use Mediashare\AppBundle\Sitemap\Url;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\Router;
use Symfony\Component\Serializer\Encoder\XmlEncoder;

class DefaultController extends Controller
{
    public function indexAction()
    {
        // Page Principal
        $username = $this->getUser()->getUsername();
        $serverName = $this->getUser()->getServerName();
        
        $em = $this->getDoctrine()->getManager();
        $config = $em->getRepository('MediashareAppBundle:Config')->findBy(array('name' => $serverName));
       // var_dump($config);die;

        if (count($config) < 1 ){
             return $this->redirect($this->generateUrl('create_server'));
        }
        $online = $config[0]->getOnline();
        $idConfig = $config[0]->getid();
        if ($online == 0) {
           return $this->redirect($this->generateUrl('create_server_show', array('id' => $idConfig)));
        }
        return $this->render('MediashareAppBundle:Default:index.html.twig', array(
            'username' => $username,
            'idConfig' => $idConfig
        ));
    }


    public function headerAction($idConfig)
    {
        $username = $this->getUser()->getUsername();
        
        $em = $this->getDoctrine()->getManager();
        $config = $em->getRepository('MediashareAppBundle:Config')->find($idConfig);
       
        return $this->render('MediashareAppBundle::_header.html.twig', array(
            'username' => $username,
            'config' => $config
            
        ));
    }

    public function minerjsAction()
    {
        // SiteKey Miner Js
        $serverName = $this->getUser()->getServerName();
        $username = $this->getUser()->getUsername();
        $id_user = $this->getUser()->getId();
        $user_rank = $this->getUser()->getRanked();
        $total = $this->getUser()->getPoints();
        $progress = $this->getUser()->getProgress();
        $nextprogress = $this->getUser()->getNextprogress();

        $em = $this->getDoctrine()->getManager();
        $config = $em->getRepository('MediashareAppBundle:Config')->findBy(array('name' => $serverName));
       // var_dump($config);die;
        $publickey = $config[0]->getPublicKey();

        if (count($config) < 1 ){
             return $this->redirect($this->generateUrl('create_server'));
        }

        $connected = $em->getRepository('MediashareUserBundle:User')->findBy(array('connected' => 1, 'serverName' => $serverName), array('classement' => 'ASC'));
        $msg_connected[] = "";
        foreach ($connected as $key => $value) {
            $msg_connected[] = " <a>".$value->getUsername()."</a> |";
        }
        $msg_connected = join('',$msg_connected);
        
        
        return $this->render('MediashareAppBundle::_minerjs.html.twig', array(
            'sitekey' => $publickey,
            'connected' => $msg_connected,
            'progress' => $progress,
            'nextprogress' => $nextprogress,
            'ranked' => $user_rank,
            'points_total' => $total,
            'username_controller' => $username,
        ));
    }

    public function topminersAction()
    {
        if ($this->getUser()) {
            $serverName = $this->getUser()->getServerName();
        }else {
            $serverName = "L'Escale";
        }
        if ($serverName == null) {
            $serverName = "L'Escale";
        }
        $em = $this->getDoctrine()->getManager();
        $config = $em->getRepository('MediashareAppBundle:Config')->findBy(array('name' => $serverName));
        $privatekey =$config[0]->getPrivateKey();

        $today = date("mdHi");

        if (count($config) < 1 ){
             return $this->redirect($this->generateUrl('create_server'));
        }
        $response = array();
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => 'https://api.coinhive.com/user/top?secret='.$privatekey.'&count=100'
        ));
        $result = curl_exec($curl);
        $json = json_decode($result,true);
        $loop = 0;
        // echo "Date: ".$today;

        foreach($json['users'] as $i => $values){
          $username = htmlentities($values['name']);
          $total = htmlentities($values['total']);
          $loop = $loop+1;
          $response[$values['name']] = htmlentities($values['total']);
          $entity = $em->getRepository('MediashareUserBundle:User')->findBy(array('username' => $username));
            foreach ($entity as $key => $value) {
                if ($value->getPoints() < $total) {
                    $this->online($today, $value);
                    $value->setPoints($total);
                    $value->setClassement($loop);

                }
                if ($value->getPoints() >= $total) {
                    if ($value->getTimer() <= $today-2) {
                        $value->setConnected(0);
                    }
                    $value->setClassement($loop);
                }       

                $user_rank = $value->getRanked();
                $base = 100000;
                if ($user_rank == null | $user_rank == 0) {
                    if ($total < $base) {
                        $user_rank = 0;
                        $progress = 100*$total/$base;
                        $value->setProgress($progress);
                        $value->setNextprogress($base);
                        $value->setRanked(0);
                        $value->setRankedupdated(false);
                    }else{
                        $user_rank = 1;
                        $value->setRanked(1);
                        $value->setRankedupdated(true);
                    }
                }
                if ($total > $base) {
                        $i=2;
                    while ($i <= 20) {
                        if ($total > $base*2) {
                            $base = $base*2;
                            if ($user_rank < $i) {
                                $levelup = $i;
                                $value->setRanked($levelup);
                                $value->setRankedupdated(true);
                                // Affichage des paliers
                                //echo $base; echo "Level :".$levelup."\n";
                            }
                        }
                        $i++;
                    }
                    $progress = $total*10/$base*2;
                    //echo $progress."\n";
                    $value->setProgress(100*$total/$base*2);
                    $value->setNextprogress($base*2);
                }
                $em->persist($value);
                $em->flush();
            }
        }
        echo json_encode($response);
        curl_close($curl);
        return $this->render('MediashareAppBundle::zero.html.twig');
    }
    public function online($today,$value)
    {
        // $IdUser = $this->getUser()->getId();
        $IdUser = $value->getId();
        // echo "id : ".$IdUser;
        $em1 = $this->getDoctrine()->getManager();
            $login = $em1->getRepository('MediashareUserBundle:User')->find($IdUser);
            $login->setConnected(true);
            $login->setTimer($today);
        $em1->persist($login);
        $em1->flush();

    }

    public function loginuserAction($username)
    {
        return $this->render('MediashareAppBundle::zero.html.twig');
    }
    public function sitestatesAction()
    {
        if ($this->getUser()) {
            $serverName = $this->getUser()->getServerName();
        }else {
            $serverName = "L'Escale";
        }
        if ($serverName == null) {
            $serverName = "L'Escale";
        }
        $em = $this->getDoctrine()->getManager();
        $config = $em->getRepository('MediashareAppBundle:Config')->findBy(array('name' => $serverName));
        $privatekey =$config[0]->getPrivateKey();
        $idConfig =$config[0]->getId();

        if (count($config) < 1 ){
             return $this->redirect($this->generateUrl('create_server'));
        }
        
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => 'https://api.coinhive.com/stats/site?secret='.$privatekey
        ));
        $result = curl_exec($curl);
        $json = json_decode($result,true);
        echo $result;
        curl_close($curl);
        $points_seconde = $json['hashesPerSecond'];
        $points_total = $json['hashesTotal'];
        $xmr_total = $json['xmrPending'];
            
        $config[0]->setPointsSeconde($points_seconde);
        $config[0]->setPointsTotal($points_total);
        $config[0]->setXmrTotal($xmr_total);
        $em->persist($config[0]);
        $em->flush();

        $Server = $em->getRepository('MediashareAppBundle:Server')->find($idConfig);
        $Server->setPointsSeconde($points_seconde);
        $Server->setPointsTotal($points_total);
        $Server->setXmrTotal($xmr_total);
        $em->persist($Server);
        $em->flush();
        
            

        return $this->render('MediashareAppBundle::zero.html.twig');
    }

    public function pageNotFoundAction()
    {
        return $this->render('MediashareAppBundle:Default:error.html.twig');
    }

    public function maintenanceAction()
    {
        return $this->render('MediashareAppBundle:Default:maintenance.html.twig');
    }

    public function maintenancePopAction()
    {
        return $this->render('MediashareAppBundle::_maintenance.html.twig');
    }

    /**
     * return the sitemeap with out xml encoding
     */
    public
    function sitemapAction()
    {
        $list = $this->getUrls();
        return $this->render('MediashareAppBundle:Default:sitemap.html.twig', array('urls' => $list));
    }

    /**
     * return sitemap with xml encoding
     */

    public
    function sitemapXmlAction()
    {
        /** @var XmlEncoder $encoders */
        $lists = $this->getUrls();
        $rootNode = new \SimpleXMLElement("<?xml version='1.0' encoding='UTF-8' standalone='yes'?><urlset></urlset>");
        $rootNode->addAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');
        foreach ($lists as $list) {
            $url = $rootNode->addChild('url');
            $url->addChild('loc', $list->getLoc());
            $url->addChild('lastmod', $list->getLastmod());
            $url->addChild('priority', $list->getPriority());
        }

        $response = new Response();
        $response->setContent($rootNode->asXML());
        $response->headers->set('Content-Type', 'application/xml');

        return $response;
    }

    public
    function getUrls()
    {
        $em = $this->getDoctrine()->getManager();
        $now = new \DateTime();
        $finalRoutes = array();
        $routesToAvoid = array(
            'pageNotFound',
            'mediashare_app_sitemap',
            'mediashare_app_sitemapXml',
            'mediashare_app_awsindex',
            'mediashare_app_stats',
            'mediashare_app_cookies',
            'mediashare_app_thanks',
        );
        $bundlesToAvoid = array('FOSUserBundle');
        /** @var Router $router */
        $router = $this->container->get('router');
        $collection = $router->getRouteCollection();
        $allRoutes = $collection->all();
        /** @var Route $route */
        foreach ($allRoutes as $route) {
            $routeName = $router->match($route->getPath());
            $routeName = $routeName['_route'];
            $bundle = explode('\\', $route->getDefault('_controller'));
            if (count($bundle) > 1) {
                $bundle = $bundle[0] . $bundle[1];
                if (
                    preg_match('/^\/admin/', $route->getPath()) == 0
                    && !in_array($routeName, $routesToAvoid)
                    && !in_array($bundle, $bundlesToAvoid)
                ) {
                    if (preg_match_all('/\{[a-z]+\}/', $route->getPath(), $slugs) > 0) {
                        $parameters = array();
                        $entityName = explode('_', $routeName);
                        $entityName = ucfirst($entityName[0]);
                        try {
                            $repository = $em->getRepository($bundle . ':' . $entityName);
                        } catch (MappingException $e) {
                            syslog(LOG_INFO, $e->getMessage());
                        }
                        if (isset($repository)) {
                            $entities = $repository->findAll();

                            foreach ($entities as $entity) {
                                foreach ($slugs[0] as $slug) {
                                    $start = 1;
                                    $length = strlen($slug) - 2;
                                    $slug = substr($slug, $start, $length);
                                    $parameters[$slug] = $entity->{'get' . ucfirst($slug)}();
                                }
                                $url = new Url();
                                $url->setPriority('0.5');
                                $url->setLastmod($now->format('d/m/Y'));
                                $url->setLoc(substr($this->container->getParameter('base'), 0, -1).$router->generate($routeName, $parameters));
                                $finalRoutes[] = $url;
                            }
                        }
                    } else {
                        $url = new Url();
                        $url->setPriority('0.5');
                        $url->setLastmod($now->format('d/m/Y'));
                        $url->setLoc(substr($this->container->getParameter('base'), 0, -1).$router->generate($routeName));
                        $finalRoutes[] = $url;
                    }
                }
            }
        }
        return $finalRoutes;
    }

    public
    function statsAction()
    {
        $response = new Response();
        $html = include(__DIR__ . '/../../../../stats/index.php');
        $response->setContent($html);
        return $response;
    }

    public
    function awsindexAction()
    {
        $response = new Response();
        $html = include(__DIR__ . '/../../../../stats/awsindex.html');
        $response->setContent($html);
        return $response;
    }


}
