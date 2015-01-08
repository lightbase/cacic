<?php
/**
 * Created by PhpStorm.
 * User: eduardo
 * Date: 08/01/15
 * Time: 17:35
 */

namespace Cacic\MultiBundle\EventListener;

use Cacic\MultiBundle\Site\SiteManager;
use Doctrine\ORM\EntityManager;
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

    private $em;

    private $baseHost;

    public function __construct(SiteManager $siteManager, EntityManager $em, $baseHost)
    {
        $this->siteManager = $siteManager;
        $this->em = $em;
        $this->baseHost = $baseHost;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();

        /*
        $currentHost = $request->getHttpHost();
        $subdomain = str_replace('.'.$this->baseHost, '', $currentHost);


        $site = $this->em
            ->getRepository('QADayBundle:Site')
            ->findOneBy(array('subdomain' => $subdomain))
        ;
        if (!$site) {
            throw new NotFoundHttpException(sprintf(
                'No site for host "%s", subdomain "%s"',
                $this->baseHost,
                $subdomain
            ));
        }


        $this->siteManager->setCurrentSite($subdomain);
        */

        $domain = $request->getBasePath();
        $this->siteManager->setCurrentSite($domain);
    }

} 