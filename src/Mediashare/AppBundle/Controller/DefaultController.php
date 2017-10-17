<?php

namespace Mediashare\AppBundle\Controller;

use Doctrine\Common\Persistence\Mapping\MappingException;
use Mediashare\AppBundle\Entity\Top;
use Mediashare\AppBundle\Entity\Contact;
use Mediashare\AppBundle\Form\ContactType;
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
        $idConfig = $this->getUser()->getConfig();
        if (!$idConfig) {
           return $this->redirect($this->generateUrl('create_server_error'));
        }
  
        return $this->render('MediashareAppBundle:Default:index.html.twig', array(
            'username' => $username,
            'idConfig' => $idConfig
        ));
    }

    public function infoAction()
    {
        // Page Principal
        $username = $this->getUser()->getUsername();
        $idConfig = $this->getUser()->getConfig();
        if (!$idConfig) {
           return $this->redirect($this->generateUrl('create_server'));
        }
        $em = $this->getDoctrine()->getManager();
        $config = $em->getRepository('MediashareAppBundle:Config')->find($idConfig);
       
        return $this->render('MediashareAppBundle::_info.html.twig', array(
            'username' => $username,
            'config' => $config
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
        // Get Data User for current Server Config
        $idUser = $this->getUser()->getId();
        $idConfig = $this->getUser()->getConfig();
        $username = $this->getUser()->getUsername();
        // Get Config Server
        $em = $this->getDoctrine()->getManager();
        $config = $em->getRepository('MediashareAppBundle:Config')->find($idConfig);
        $publickey = $config->getPublicKey();
        $serverName = $config->getName();

        $today = date("mdHi");
        // Get Stat User for current Server
        $top = $em->getRepository('MediashareAppBundle:Top')->findOneBy(array('idconfig' => $idConfig, 'iduser' => $idUser));
        if (!$top) {
            $user_rank = 0;
            $total = 0;
            $progress = 0;
            $nextprogress = 100000;
        }else{
            $user_rank = $top->getRanked();
            $total = $top->getPoints();
            $progress = $top->getProgress();
            $nextprogress = $top->getNextprogress();
        }
        // Get Miner Online
        $msg_connected[] = "";
        $connected = $em->getRepository('MediashareAppBundle:Top')->findBy(array('connected' => 1, 'idconfig' => $idConfig), array('classement' => 'ASC'));
        foreach ($connected as $key => $value) {
            $user_connected = $em->getRepository('MediashareUserBundle:User')->find($value->getIduser());
            $msg_connected[] = " <a>".$user_connected->getUsername()."</a> |";
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

    public function createTop($iduser_json, $idconfig_json, $serverName, $total_json, $em, $today, $username_json)
    {
        $top = new Top();

        $top->setUsername($username_json);
        $top->setIdconfig($idconfig_json);
        $top->setIduser($iduser_json);
        $top->setPoints($total_json);
        $top->setServername($serverName);
        $progress = 100*$total_json/100000;

        $top->setRanked(0);
        $top->setClassement(100);
        $top->setConnected(true);
        $top->setRankedupdated(true);
        $top->setTimer($today);
        $top->setNextprogress(100000);
        $top->setProgress($progress);
        $top->setTicketpass('NotSecure');
        
        
        $em->persist($top);
        $em->flush();

    }
    public function topminersAction()
    {
        // Update Mining Progress for Current User to Server Configurate
        $error = 0;
        // Get Data User
        $username = $this->getUser()->getUsername();
        $idUser = $this->getUser()->getId();
        $idConfig = $this->getUser()->getConfig();
        if (!$idConfig) {
            return $this->redirect($this->generateUrl('create_server'));
        }

        // Get Config User
        $em = $this->getDoctrine()->getManager();
        $config = $em->getRepository('MediashareAppBundle:Config')->find($idConfig);
        $publickey = $config->getPublicKey();
        $privatekey = $config->getPrivateKey();
        $serverName = $config->getName();

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
          $idconfig_json = $user->getConfig();
          if (!$idconfig_json) {
                $idconfig_json = 1;
            }
          $top = $em->getRepository('MediashareAppBundle:Top')->findOneBy(array('iduser' => $iduser_json, 'idconfig' => $idConfig));
          // Create a Top if not exist
          if (!$top) {
            $this->createTop($iduser_json, $idconfig_json, $serverName, $total_json, $em, $today, $username_json);
          } else {

            $top->setUsername($username_json);
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
    echo json_encode($response);
    curl_close($curl);

        return $this->render('MediashareAppBundle::zero.html.twig');
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

    public function loginuserAction($username)
    {
        return $this->render('MediashareAppBundle::zero.html.twig');
    }

    public function sitestatesAction()
    {
        // Update Mining Progress for Current User
        $error = 0;
        // Get Data User
        $username = $this->getUser()->getUsername();
        $idUser = $this->getUser()->getId();
        $idConfig = $this->getUser()->getConfig();

        // Get Config User
        $em = $this->getDoctrine()->getManager();
        $config = $em->getRepository('MediashareAppBundle:Config')->find($idConfig);
        $publickey = $config->getPublicKey();
        $privatekey = $config->getPrivateKey();

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
        $points_seconde = $json['hashesPerSecond'];
        $points_total = $json['hashesTotal'];
        $xmr_total = $json['xmrPending'];
        
        // Set Info 
        $Server = $em->getRepository('MediashareAppBundle:Config')->find($idConfig);
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
     * Page Contact
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function contactAction(Request $request)
    {
        $captchaKey = $this->container->getParameter('captchaKey');
        $entity = new Contact();
        $form = $this->createForm(new ContactType(), $entity, array(
            'method' => 'POST',
        ));
        $idConfig = $this->getUser()->getConfig();

        $form->add('submit', 'submit',
        array(
            'label' => 'Ajouter',
            'attr' => array('class' => 'btn btn-success')
        ));
        $form->handleRequest($request);

        if ($form->isValid()) {
            if (isset($_POST['g-recaptcha-response'])) {
                $captcha = $_POST['g-recaptcha-response'];
            }
            if ($captcha) {
                $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".$captchaKey."&response=" . $captcha . "&remoteip=" . $_SERVER['REMOTE_ADDR']);
                if ($response == false) {
                    return $this->redirectToRoute('mediashare_app_contact');
                } else {
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($entity);
                    $em->flush();

                    $message = \Swift_Message::newInstance()
                        ->setSubject('Contact site')
                        ->setFrom($entity->getEmail())
                        ->setTo($this->container->getParameter('mail_to'))
                        ->setBody($this->renderView('MediashareAppBundle:Mail:contact.html.twig', array(
                            'entity' => $entity
                        )))
                        ->setContentType('text/html');
                    $this->get('mailer')->send($message);

                    return $this->redirect($this->generateUrl('mediashare_app_homepage'));
                }
            }
        }
        return $this->render('MediashareAppBundle:Default:contact.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
            'captchaKey' => $captchaKey,
            'idConfig' => $idConfig
        ));

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
