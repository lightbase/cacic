<?php
/**
 * Created by PhpStorm.
 * User: eduardo
 * Date: 08/01/15
 * Time: 17:35
 */

namespace Cacic\MultiBundle\EventListener;

use Cacic\MultiBundle\Site\SiteManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Event\ConsoleCommandEvent;
use Symfony\Component\Console\Input\ArgvInput;

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
        $logger = $container->get('logger');

        $em = $container->get('doctrine.orm.entity_manager');
        $baseHost = $container->getParameter('base_host');
        $hostMethod = $container->getParameter('host_method');

        // Primeiro pega o repositório padrão
        $dbname = $container->getParameter('database_name');
        $dbuser = $container->getParameter('database_user');
        $dbpass = $container->getParameter('database_password');
        $dbhost = $container->getParameter('database_host');

        $container->get('doctrine.dbal.dynamic_connection')->forceSwitch($dbname, $dbuser, $dbpass, $dbhost);

        // Agora verifica o método de login
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

        // Debug
        $logger->debug("MULTI-SITE DEBUG: detected domain $dbname");

        if (empty($site)) {
            // Se for nulo, pega o valor que está no parâmetro
            $dbname = $container->getParameter('database_name');
            $dbuser = $container->getParameter('database_user');
            $dbpass = $container->getParameter('database_password');
            $dbhost = $container->getParameter('database_host');
        } else {
            // Ajusta o site para o nome do usuário encontrado
            $this->siteManager->setCurrentSite($site->getUsername());

            $dbname = $site->getUsername();
            $dbuser = $site->getDbUser();
            $dbpass = $site->getDbPassword();
            $dbhost = $site->getDbHost();
        }

        $container->get('doctrine.dbal.dynamic_connection')->forceSwitch($dbname, $dbuser, $dbpass, $dbhost);
    }

    public function onConsoleCommand(ConsoleCommandEvent $event)
    {
        $container = $this->container;
        $logger = $container->get('logger');
        $inputDefinition = $event->getCommand()->getApplication()->getDefinition();

        // add the option to the application's input definition
        $inputDefinition->addOption(
            new InputOption('site', null, InputOption::VALUE_OPTIONAL, 'Nome do site a considerar no processo', null)
        );

        // merge the application's input definition
        $event->getCommand()->mergeApplicationDefinition();

        $input = new ArgvInput();

        // we use the input definition of the command
        $input->bind($event->getCommand()->getDefinition());

        $site = $input->getOption('site');

        // Agora altera o banco para o nome solicitado
        $em = $container->get('doctrine.orm.entity_manager');

        // Primeiro pega o repositório padrão
        $dbname = $container->getParameter('database_name');
        $dbuser = $container->getParameter('database_user');
        $dbpass = $container->getParameter('database_password');
        $dbhost = $container->getParameter('database_host');

        $container->get('doctrine.dbal.dynamic_connection')->forceConnect($dbname, $dbuser, $dbpass, $dbhost);

        // Agora verifica o método de login
        if (!empty($site)) {

            $site = $em
                ->getRepository('CacicMultiBundle:Sites')
                ->findOneBy(
                    array(
                        'username' => $site
                    )
                );
        } else {
            return;
        }

        // Debug
        $logger->debug("MULTI-SITE DEBUG: detected domain $dbname");

        if (empty($site)) {
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

        $container->get('doctrine.dbal.dynamic_connection')->forceConnect($dbname, $dbuser, $dbpass, $dbhost);
    }

} 