<?php
/**
 * Created by PhpStorm.
 * User: eduardo
 * Date: 07/11/14
 * Time: 23:38
 */

namespace Cacic\WSBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

use Cacic\CommonBundle\Entity\TipoSo;


class LoadTipoSo extends AbstractFixture implements FixtureInterface, ContainerAwareInterface, OrderedFixtureInterface {

    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {

        // Crio os objetos e atributos para a classe
        $classe = new TipoSo();
        $classe->setTipo('windows');

        // Adiciona referência
        $this->addReference('windows', $classe);

        // Grava os dados
        $manager->persist($classe);

        // Crio os objetos e atributos para a classe
        $classe = new TipoSo();
        $classe->setTipo('linux-x86_64');

        // Adiciona referência
        $this->addReference('linux-64-bit', $classe);

        // Grava os dados
        $manager->persist($classe);

        $manager->flush();

    }

    public function getOrder()
    {
        return 90;
    }

} 
