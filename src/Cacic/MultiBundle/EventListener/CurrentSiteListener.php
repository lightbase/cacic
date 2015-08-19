<?php
/**
 * Created by PhpStorm.
 * User: eduardo
 * Date: 08/01/15
 * Time: 17:35
 */

namespace Cacic\MultiBundle\EventListener;

use Cacic\MultiBundle\Connection\ConnectionWrapper;
use Cacic\MultiBundle\Site\SiteManager;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class CurrentSiteListener
 * Copied from http://knpuniversity.com/screencast/question-answer-day/symfony2-dynamic-subdomains
 *
 * @package Cacic\MultiBundle\EventListener
 */
class CurrentSiteListener {
    private $siteManager;
    private $container;
    //private $em;
    //private $baseHost;

    public function __construct(SiteManager $siteManager, ContainerInterface $container)
    {
        $this->siteManager = $siteManager;
        $this->container = $container;
        //$this->em = $em;
        //$this->baseHost = $baseHost;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();
        $container = $this->container;

        $em = $container->get('doctrine.orm.entity_manager');
        $baseHost = $container->getParameter('base_host');
        $hostMethod = $container->getParameter('host_method');


        if ($hostMethod == 'subdomain') {
            $currentHost = $request->getHttpHost();
            $subdomain = str_replace('.'.$baseHost, '', $currentHost);

            $site = $em
                ->getRepository('CacicMultiBundle:Sites')
                ->findOneBy(
                    array(
                        'subdomain' => $subdomain
                    )
                );

        } else {
            $domain = $request->getBasePath();

            // Load the database for this website
            $dbname = trim($domain, "/");

            $site = $em
                ->getRepository('CacicMultiBundle:Sites')
                ->findOneBy(
                    array(
                        'subdir' => $dbname
                    )
                );
        }

        $this->siteManager->setCurrentSite($site->getUsername());

        // Debug
        $logger = $container->get('logger');
        $logger->debug("MULTI-SITE DEBUG: detected domain $dbname");

        if (empty($dbname)) {
            // Se for nulo, pega o valor que está no parâmetro
            $dbname = $container->getParameter('database_name');
            $dbuser = $container->getParameter('database_user');
            $dbpass = $container->getParameter('database_password');
            $dbhost = $container->getParameter('database_host');
        } else {
            $dbname = $site->getUsername();
            $dbuser = $site->getDbUser();
            $dbpass = $site->getDbPassword();
            $dbhost = $site->getDbHost();
        }

        $container->get('doctrine.dbal.dynamic_connection')->forceSwitch($dbname, $dbuser, $dbpass, $dbhost);
    }

} 