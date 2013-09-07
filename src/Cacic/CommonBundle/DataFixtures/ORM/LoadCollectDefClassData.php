<?php

namespace Cacic\CommonBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

use Cacic\CommonBundle\Entity\CollectDefClass;

class LoadCollectDefClassData extends AbstractFixture implements FixtureInterface, ContainerAwareInterface, OrderedFixtureInterface
{
 /*
  * Array de ações e classes WMI associadas às ações
  * FIXME: É necessário fazer um mapeamento real e implementar uma chamada WMI para cada item da coleta
  *
  */
	private $acoes = array(
		array('id_acao'=>'col_anvi','classes'=> array('Software')),
		array('id_acao'=>'col_comp','classes'=> array('Software','OperatingSystem','ComputerSystem','Network')),
		array('id_acao'=>'col_env_not_optional','classes'=> array('OperatingSystem','ComputerSystem','Network')),
		array('id_acao'=>'col_hard','classes'=> array('ComputerSystem', 'OperatingSystem')),
		array('id_acao'=>'col_moni','classes'=> array('ComputerSystem','Software', 'OperatingSystem')),
		array('id_acao'=>'col_patr','classes'=> array('ComputerSystem')),
		array('id_acao'=>'col_soft','classes'=> array('OperatingSystem','Software')),
		array('id_acao'=>'col_soft_not_optional','classes'=> array('Software')),
		array('id_acao'=>'srcacic','classes'=> array('ComputerSystem'))
	);

    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /*
     * Carrega dados das classes WMI que serão utilizadas nas coletas do Gerente.
     */

    public function load(ObjectManager $manager)
    {
        foreach ( $this->acoes as $a )
        {
	 // Primeiro armazena o objeto de ação
	 $acao = $this->getReference($a['id_acao']);
	 foreach ($a['classes'] as $acao_classe)
	 {
	  // Agora instancia o objeto que será inserido
	  $collect = new CollectDefClass();
	  $collect->setIdAcao($acao);

	  // Agora recupero a classe que será mapeada a essa ação
	  $collect->setIdClass($this->getReference($acao_classe));

	  // O objeto está pronto para ser persistido
	  $manager->persist($collect);
	 }
	}

	// COMMIT
        $manager->flush();

        
    }

    public function getOrder()
    {
        return 7;
    }
}   
