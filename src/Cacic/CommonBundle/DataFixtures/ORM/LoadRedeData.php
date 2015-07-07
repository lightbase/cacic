<?php
/**
 * Created by PhpStorm.
 * User: eduardo
 * Date: 14/04/14
 * Time: 19:33
 */

namespace Cacic\CommonBundle\DataFixtures\ORM;

use Cacic\CommonBundle\Entity\AcaoRede;
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
        $rede->setTeServCacic('localhost');
        $rede->setTeServUpdates('localhost');
        $rede->setNuLimiteFtp(100);
        $rede->setCsPermitirDesativarSrcacic('S');
        $rede->setIdLocal($this->getReference('local'));
        $rede->setDownloadMethod('http');

        $manager->persist($rede);
        $manager->flush();

        // Adiciona ações que devem estar habilitadas na rede por padrão
        $col_soft = $this->getReference('col_soft');
        $col_hard = $this->getReference('col_hard');
        $col_patr = $this->getReference('col_patr');

        $acao_rede = new AcaoRede();
        $acao_rede->setAcao($col_soft);
        $acao_rede->setRede($rede);
        $manager->persist($acao_rede);

        $acao_rede = new AcaoRede();
        $acao_rede->setAcao($col_hard);
        $acao_rede->setRede($rede);
        $manager->persist($acao_rede);

        $acao_rede = new AcaoRede();
        $acao_rede->setAcao($col_patr);
        $acao_rede->setRede($rede);
        $manager->persist($acao_rede);

        $manager->flush();
    }

    public function getOrder()
    {
        return 6;
    }
} 