<?php
/**
 * Created by PhpStorm.
 * User: eduardo
 * Date: 07/11/14
 * Time: 23:38
 */

namespace Cacic\WSBundle\DataFixtures\ORM;

use Cacic\CommonBundle\Entity\TipoSo;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

use Cacic\CommonBundle\Entity\RedeVersaoModulo;


class LoadRedeVersaoModuloData extends AbstractFixture implements FixtureInterface, ContainerAwareInterface, OrderedFixtureInterface {

    /*
     * Array de dados das classes que serão carregadas
     */
    private $modulos = array(
        array('nmModulo' => 'cacic-service.exe',
            'teVersaoModulo' => '3.0a1',
            'csTipoSo' => 'windows',
            'teHash' => '79df3561f83ac86eb19e2996b17d5e30',
            'tipo' => 'cacic',
            'tipoSo' => 'windows',
            'filepath' => 'cacic/current/windows/cacic-service.exe'
        ),
        array('nmModulo' => 'install-cacic.exe',
            'teVersaoModulo' => '3.0a1',
            'csTipoSo' => 'windows',
            'teHash' => '50cf34bf584880fd401619eb367b2c2d',
            'tipo' => 'cacic',
            'tipoSo' => 'windows',
            'filepath' => 'cacic/current/windows/install-cacic.exe'
        ),
        array('nmModulo' => 'cacic-service',
            'teVersaoModulo' => '3.0a1',
            'csTipoSo' => 'linux-64-bit',
            'teHash' => 'd61f05787b452246bd75d0cfb16bf415',
            'tipo' => 'cacic',
            'tipoSo' => 'linux-64-bit',
            'filepath' => 'cacic/current/linux-64-bit/cacic-service'
        ),
        array('nmModulo' => 'install-cacic',
            'teVersaoModulo' => '3.0a1',
            'csTipoSo' => 'linux-64-bit',
            'teHash' => '548b95c40a9a3336ec85bcd3e87a62e3',
            'tipo' => 'cacic',
            'tipoSo' => 'linux-64-bit',
            'filepath' => 'cacic/current/linux-64-bit/install-cacic'
        ),
    );

    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {
        $rede = $manager->getRepository('CacicCommonBundle:Rede')->findOneBy(array(
            'teIpRede' => '0.0.0.0'
        ));
        foreach ($this->modulos as $elemento){
            // Crio os objetos e atributos para a classe
            $classe = new RedeVersaoModulo(null, null, null, null, null, $rede);
            $classe->setNmModulo($elemento['nmModulo']);
            $classe->setTeVersaoModulo($elemento['teVersaoModulo']);
            $classe->setDtAtualizacao(new \DateTime());
            $classe->setCsTipoSo($elemento['csTipoSo']);
            $classe->setTeHash($elemento['teHash']);
            $classe->setTipo('cacic');
            $classe->setTipoSo($this->getReference($elemento['tipoSo']));
            $classe->setFilepath($elemento['filepath']);

            // Grava os dados
            $manager->persist($classe);
        }

        $manager->flush();

    }

    public function getOrder()
    {
        return 100;
    }

} 