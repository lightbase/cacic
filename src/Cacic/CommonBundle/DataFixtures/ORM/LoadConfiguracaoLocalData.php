<?php

namespace Cacic\CommonBundle\DataFixtures\ORM;

use Cacic\CommonBundle\Entity\ConfiguracaoLocal;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/*
 * Carrega Configurações-Local
 */

class LoadConfiguracaoLocalData extends AbstractFixture implements ContainerAwareInterface, FixtureInterface, OrderedFixtureInterface
{
	private $container;
	
	/**
	 * (non-PHPdoc)
	 * @see \Symfony\Component\DependencyInjection\ContainerAwareInterface::setContainer()
	 */
	public function setContainer(ContainerInterface $container = null)
	{
		$this->container = $container;
	}

	/*
     * Carrega as configurações-local para o Local criado a partir do data-fixture Cacic\CommonBundle\DataFixtures\ORM\LoadLocalData
     */
	public function load(ObjectManager $manager)
    {
    	$em = $this->container->get('doctrine')->getManager();
    	$configuracoesPadrao = $em->getRepository('CacicCommonBundle:ConfiguracaoPadrao')->findAll();
    	
    	$local = $this->getReference('local'); // Recupera o Local criado via data-fixture
    	
        foreach ( $configuracoesPadrao as $conf )
        {
        	$configuracaoLocal = new ConfiguracaoLocal();
        	$configuracaoLocal->setIdConfiguracao( $conf );
        	$configuracaoLocal->setIdLocal( $local );
        	$configuracaoLocal->setVlConfiguracao( $conf->getVlConfiguracao() );
        	
        	$manager->persist($configuracaoLocal);
        }

        $manager->flush();
    }

    public function getOrder()
    {
        return 5;
    }
}