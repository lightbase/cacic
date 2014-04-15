<?php
/**
 * Created by PhpStorm.
 * User: eduardo
 * Date: 14/04/14
 * Time: 19:33
 */

namespace Cacic\CommonBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

use Cacic\CommonBundle\Entity\Rede;


class LoadRedeData extends AbstractFixture implements FixtureInterface, ContainerAwareInterface, OrderedFixtureInterface
{
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {
        $rede = new Rede();
        $rede->setTeIpRede('0.0.0.0');
        $rede->setTeMascaraRede('255.255.255.255');
        $rede->setTeServCacic('http://localhost');
        $rede->setTeServUpdates('http://localhost');
        $rede->setNuLimiteFtp(100);
        $rede->setCsPermitirDesativarSrcacic('S');
        $rede->setIdLocal($this->getReference('local'));

        $manager->persist($rede);
        $manager->flush();
    }

    public function getOrder()
    {
        return 5;
    }
} 