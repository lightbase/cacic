<?php

namespace Cacic\CommonBundle\Twig;

use Symfony\Component\DependencyInjection\ContainerInterface;

class BundlesExtension extends \Twig_Extension {
    protected $container;

    public function __construct(ContainerInterface $container) {
        $this->container = $container;
    }

    public function getFunctions()
    {
        return array(
                new \Twig_SimpleFunction('bundleExists', array($this, 'bundleExists')),

        );
    }


    public function bundleExists($bundle){
        if (array_key_exists($bundle, $this->container->getParameter('kernel.bundles'))) {
            return true;
        }
        return false;
    }
    public function getName() {
        return 'cacic_bundles';
    }
}
