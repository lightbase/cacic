<?php
/**
 * Created by JetBrains PhpStorm.
 * User: eduardo
 * Date: 13/07/13
 * Time: 21:25
 * To change this template use File | Settings | File Templates.
 */

namespace Cacic\CommonBundle\DataFixtures\ORM;

use Cacic\CommonBundle\Entity\Local;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/*
 * Carrega local para teste
 */

class LoadLocalData extends AbstractFixture implements FixtureInterface,  OrderedFixtureInterface
{

    /*
     * Carrega local padrão
     *
     */

    public function load(ObjectManager $manager)
    {
        $local = new Local();
        $local->setNmLocal('Empresa');
        $local->setSgLocal('LTDA');

        // Adiciona referência ao local que será carregado depois
        $this->addReference('local', $local);

        $manager->persist($local);
        $manager->flush();
    }

    public function getOrder()
    {
        return 1;
    }
}