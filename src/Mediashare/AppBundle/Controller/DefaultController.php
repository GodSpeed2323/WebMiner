<?php

namespace Mediashare\AppBundle\Controller;

use Doctrine\Common\Persistence\Mapping\MappingException;
use Mediashare\AppBundle\Entity\Contact;
use Mediashare\AppBundle\Entity\Server;
use Mediashare\AppBundle\Form\ContactType;
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
        
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('MediashareUserBundle:User')->findBy(array(), array('classement' => 'ASC'));
  

        return $this->render('MediashareAppBundle:Default:index.html.twig', array(
            'username' => $username,
            'entity' => $entity,
        ));
    }

    public function updateAction()
    {   
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('MediashareUserBundle:User')->findBy(array(), array('classement' => 'ASC'));

        return $this->render('MediashareAppBundle::_login.html.twig', array(
            'entity' => $entity,
        ));
    }
    public function connectedAction()
    {   
        // Miner Connected
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('MediashareUserBundle:User')->findBy(array('connected' => 1), array('classement' => 'ASC'));

        return $this->render('MediashareAppBundle::_connected.html.twig', array(
            'entity' => $entity,
        ));
    }
    public function minerjsAction()
    {   
        // SiteKey Miner Js
        $publickey = $this->getUser()->getPublickey();
        if (!$publickey) {
            $publickey = 'qiitf8zlomSEfJkQUJhUogqz95AUoLvE';
        }
        $privatekey = $this->getUser()->getPrivatekey();
        if (!$privatekey) {
            $privatekey = 'UEYZDnaSATmGtwne5vYjBfJBa9loLHTv';
        }

        return $this->render('MediashareAppBundle::_minerjs.html.twig', array(
            'sitekey' => $publickey,
        ));
    }

    public function rankedAction($total, $value)
    {
        
    }
    public function topminersAction()
    {   

        $privatekey = $this->getUser()->getPrivatekey();
        $today = date("mdHi"); 

        if (!$privatekey) {
            $privatekey = 'UEYZDnaSATmGtwne5vYjBfJBa9loLHTv';
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
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('MediashareUserBundle:User')->findBy(array('username' => $username));
            foreach ($entity as $key => $value) {
                if ($value->getPoints() < $total) {
                    if ($value->getTimer() < $today+5) {
                        $this->online($today);
                    }
                    $value->setUpdated(true);
                    $value->setTimer($today);
                    $value->setPoints($total);
                    $value->setClassement($loop);
                }else{
                    if ($value->getTimer() < $today-5) {
                        $value->setConnected(false);
                    }
                    $value->setUpdated(false);
                    $value->setClassement($loop);
                }
                        
                        $user_rank = $value->getRanked();

                        if ($total > 100000 && $user_rank < 1) {
                            $value->setRanked(1);
                            $value->setRankedupdated(true);
                        }
                        if ($total > 1000000 && $user_rank < 2) {
                            $value->setRanked(2);
                            $value->setRankedupdated(true);
                        }
                        if ($total > 10000000 && $user_rank < 3) {
                            $value->setRanked(3);
                            $value->setRankedupdated(true);
                        }
                        if ($total > 50000000 && $user_rank < 4) {
                            $value->setRanked(4);
                            $value->setRankedupdated(true);
                        }
                        if ($total > 100000000 && $user_rank < 5) {
                            $value->setRanked(5);
                            $value->setRankedupdated(true);
                        }
                    $em->persist($value);
                    $em->flush();
            }  


        }
        echo json_encode($response);
        curl_close($curl);

       
        
        return $this->render('MediashareAppBundle::zero.html.twig');

    }
    public function online($today)
    {
        $IdUser = $this->getUser()->getId();
        $em1 = $this->getDoctrine()->getManager();
            $login = $em1->getRepository('MediashareUserBundle:User')->find($IdUser);
            $login->setConnected(true);
            $login->setTimer($today);
        $em1->persist($login);
        $em1->flush();   

    }
    public function sitestatesAction()
    {
        $privatekey = $this->getUser()->getPrivatekey();
        if (!$privatekey) {
            $privatekey = 'UEYZDnaSATmGtwne5vYjBfJBa9loLHTv';
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

        $em = $this->getDoctrine()->getManager();
            $Server = $em->getRepository('MediashareAppBundle:Server')->find(1);
            $Server->setPointsSeconde($points_seconde);
            $Server->setPointsTotal($points_total);
            $Server->setXmrTotal($xmr_total);
        $em->persist($Server);
        $em->flush(); 

        $this->connectedAction();
        return $this->render('MediashareAppBundle::zero.html.twig');
    }
    public function loginuserAction($username)
    {
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

    public function legalAction()
    {
        return $this->render('MediashareAppBundle:Default:legal.html.twig');
    }

    public function footerAction()
    {
        $date = new \DateTime();
        return $this->render('MediashareAppBundle::_footer.html.twig', array('date' => $date));
    }

    public function menuAction()
    {
        $em = $this->getDoctrine()->getManager();
        $menus = $em->getRepository('MediashareAdminBundle:Menu')->findBy(
            array('parent' => null),
            array('position' => 'ASC')
        );
        return $this->render('MediashareAppBundle::_menu.html.twig', array(
            'menus' => $menus
        ));
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

        $form->add('submit', 'submit', array('label' => 'Envoyer'));
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

                    return $this->redirect($this->generateUrl('mediashare_app_thanks'));
                }
            }

        }
        return $this->render('MediashareAppBundle:Default:contact.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
            'captchaKey' => $captchaKey
        ));

    }

    public
    function thanksAction()
    {
        return $this->render('MediashareAppBundle:Default:thanks.html.twig');
    }

    public
    function cookiesAction()
    {
        return $this->render('MediashareAppBundle:Default:cookies.html.twig');
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
