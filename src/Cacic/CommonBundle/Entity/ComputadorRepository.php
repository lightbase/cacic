<?php

namespace Cacic\CommonBundle\Entity;

use Cacic\WSBundle\Helper\OldCacicHelper;
use Cacic\WSBundle\Helper\TagValueHelper;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Cacic\CommonBundle\Entity\AcaoSo;
use Cacic\CommonBundle\Entity\Acao;
use Cacic\CommonBundle\Entity\So;
use Cacic\CommonBundle\Entity\ComputadorColeta;

/**
 *
 * Repositório de métodos de consulta em DQL
 * @author lightbase
 *
 */
class ComputadorRepository extends EntityRepository
{
	
	/**
	 * 
	 * Conta os computadores associados a cada Local
	 * @return array
	 */
	public function countPorLocal()
	{
		$qb = $this->createQueryBuilder('comp')
					->select('loc.idLocal, loc.nmLocal, COUNT(comp.idComputador) as numComp')
					->innerJoin('comp.idRede', 'rede')
					->innerJoin('rede.idLocal', 'loc')
					->groupBy('loc');
		
		return $qb->getQuery()->getResult();
	}
	
	/**
	 * 
	 * Conta os computadores associados a cada Rede
	 * Se o idLocal for informado, verifica apenas as redes associadas a este
	 * @param int|\Cacic\CommonBundle\Entity\Local $idLocal
	 * @return array
	 */
	public function countPorSubrede( $idLocal = null )
	{
		$qb = $this->createQueryBuilder('comp')
					->select('rede.idRede, rede.teIpRede, rede.nmRede, COUNT(comp.idComputador) as numComp')
					->innerJoin('comp.idRede', 'rede')
					->groupBy('rede');
		
		if ( $idLocal !== null )
			$qb->where('rede.idLocal = :idLocal')->setParameter( 'idLocal', $idLocal);
		
		return $qb->getQuery()->getResult();
	}
	
	/**
	 * 
	 * Conta os computadores associados a cada Sistema Operacional
	 */
	public function countPorSO()
	{
		$qb = $this->createQueryBuilder('comp')
					->select('so.idSo, so.teDescSo, so.sgSo, so.teSo, COUNT(comp.idComputador) as numComp')
					->innerJoin('comp.idSo', 'so')
					->groupBy('so');
		
		return $qb->getQuery()->getResult();
	}
	
	/**
	 * 
	 * Conta todos os computadores monitorados
	 * @return int
	 */
	public function countAll()
	{
		$qb = $this->createQueryBuilder('comp')->select('COUNT(comp.idComputador)');
		return $qb->getQuery()->getSingleScalarResult();
	}
	
	/**
	 * 
	 * Lista os computadores monitorados alocados na subrede informada
	 * @param int|\Cacic\CommonBundle\Entity\Rede $idSubrede
	 */
	public function listarPorSubrede( $idSubrede )
	{
		$qb = $this->createQueryBuilder('comp')
					->select('comp', 'so')
					->leftJoin('comp.idSo', 'so')
					->where('comp.idRede = :idRede')
					->setParameter('idRede', $idSubrede)
					->orderBy('comp.nmComputador')->addOrderBy('comp.teIpComputador');
		
		return $qb->getQuery()->getResult();
	}
	
	/**
	 * 
	 * Gera relatório de configurações de hardware coletadas dos computadores 
	 * @param array $filtros
	 */
	public function gerarRelatorioConfiguracoes( $filtros )
	{
		$qb = $this->createQueryBuilder('computador')
					->select('computador, coleta, classe, rede, local, so')
					->leftJoin('computador.hardwares', 'coleta')
					->leftJoin('coleta.idClass', 'classe')
					->leftJoin('computador.idRede', 'rede')
					->leftJoin('rede.idLocal', 'local')
					->leftJoin('computador.idSo', 'so');

		/**
		 * Verifica os filtros
		 */
		if ( array_key_exists('locais', $filtros) && !empty($filtros['locais']) )
        	$qb->andWhere('local.idLocal IN (:locais)')->setParameter('locais', explode( ',', $filtros['locais'] ));
        
        if ( array_key_exists('so', $filtros) && !empty($filtros['so']) )
        	$qb->andWhere('computador.idSo IN (:so)')->setParameter('so', explode( ',', $filtros['so'] ));
        
        /*if ( array_key_exists('conf', $filtros) && !empty($filtros['conf']) )
        	$qb->andWhere('coleta.idClass IN (:conf)')->setParameter('conf', explode( ',', $filtros['conf'] ));*/
        	
		
		return $qb->getQuery()->execute();
	}

    /*
    * Metodo responsável por inserir coletas iniciais, assim que o cacic é instalado
    */
    public function getComputadorPreCole( Request $request , $te_so , $te_node_adress )
    {
        //recebe dados via POST, deCripata dados, e attribui a variaveis
        $computer_system   = OldCacicHelper::deCrypt( $request, $request->request->get('ComputerSystem'), true  );
        $te_versao_cacic   = OldCacicHelper::deCrypt( $request, $request->request->get('te_versao_cacic'), true  );
        $te_versao_gercols = OldCacicHelper::deCrypt( $request, $request->request->get('te_versao_gercols'), true  );
        $network_adapter   = OldCacicHelper::deCrypt( $request, $request->request->get('NetworkAdapterConfiguration'), true  );
        $operating_system  = OldCacicHelper::deCrypt( $request, $request->request->get('OperatingSystem'), true  );
        $data = new \DateTime('NOW'); //armazena data Atual

        //vefifica se existe SO coletado se não, insere novo SO
        $so = $this->getEntityManager()->getRepository('CacicCommonBundle:So')->createIfNotExist( $te_so );
        $id_so= $so->getIdSo();

        $rede = $this->getEntityManager()->getRepository('CacicCommonBundle:Rede')->getDadosRedePreColeta( $request );

        $computador = $this->findOneBy( array( 'teNodeAddress'=> $te_node_adress, 'idSo'=> $id_so) );

        //inserção de dado se for um novo computador
       if( empty ( $computador ) )
       {
            $computador = new Computador();

            $computador->setTeNodeAddress( $te_node_adress );
            $computador->setIdSo( $so );
            $computador->setIdRede( $rede );
            $computador->setDtHrInclusao( $data);
            $computador->setTePalavraChave( $request->get('PHP_AUTH_PW') );

            $this->getEntityManager()->persist( $computador );

       }

        //inserção de dados na tabela computador_coleta
        $class_network_configuration = $this->getEntityManager()->getRepository('CacicCommonBundle:Classe')->findOneBy( array( 'nmClassName'=> 'NetworkAdapterConfiguration') );
        $computadorColeta = $this->getEntityManager()->getRepository('CacicCommonBundle:ComputadorColeta')->findOneBy( array( 'idClass'=> $class_network_configuration->getIdClass() ) );
        $computadorColeta = empty( $computadorColeta ) ? new ComputadorColeta() : $computadorColeta ;
        $computadorColeta->setIdComputador( $computador );
        $computadorColeta->setTeClassValues( $network_adapter );
        $computadorColeta->setIdClass( $class_network_configuration );
        $this->getEntityManager()->persist( $computadorColeta );
        // Persistencia de Historico
        $computadorColetaHistorico = new ComputadorColetaHistorico();
        $computadorColetaHistorico->setIdClass( $class_network_configuration );
        $computadorColetaHistorico->setIdComputadorColeta( $computadorColeta );
        $computadorColetaHistorico->setIdComputador( $computador );
        $computadorColetaHistorico->setTeClassValues( $network_adapter );
        $computadorColetaHistorico->setDtHrInclusao( $data);
        $this->getEntityManager()->persist( $computadorColetaHistorico );

        $class_operating_system = $this->getEntityManager()->getRepository('CacicCommonBundle:Classe')->findOneBy( array( 'nmClassName'=> 'OperatingSystem') );
        $computadorColeta = $this->getEntityManager()->getRepository('CacicCommonBundle:ComputadorColeta')->findOneBy( array( 'idClass'=> $class_operating_system->getIdClass() ) );
        $computadorColeta = empty( $computadorColeta ) ? new ComputadorColeta() : $computadorColeta;
        $computadorColeta->setIdComputador( $computador );
        $computadorColeta->setTeClassValues( $operating_system );
        $computadorColeta->setIdClass( $class_operating_system );
        $this->getEntityManager()->persist( $computadorColeta );
        // Persistencia de Historico
        $computadorColetaHistorico = new ComputadorColetaHistorico();
        $computadorColetaHistorico->setIdClass($class_operating_system);
        $computadorColetaHistorico->setIdComputadorColeta( $computadorColeta );
        $computadorColetaHistorico->setIdComputador( $computador );
        $computadorColetaHistorico->setTeClassValues( $operating_system);
        $computadorColetaHistorico->setDtHrInclusao( $data );
        $this->getEntityManager()->persist( $computadorColetaHistorico );

        $class_computer_system = $this->getEntityManager()->getRepository('CacicCommonBundle:Classe')->findOneBy( array( 'nmClassName'=> 'ComputerSystem') );
        $computadorColeta = $this->getEntityManager()->getRepository('CacicCommonBundle:ComputadorColeta')->findOneBy( array( 'idClass'=> $class_computer_system->getIdClass() ) );
        $computadorColeta = empty( $computadorColeta ) ? new ComputadorColeta() : $computadorColeta;
        $computadorColeta->setIdComputador( $computador );
        $computadorColeta->setTeClassValues( $computer_system );
        $computadorColeta->setIdClass( $class_computer_system );
        $this->getEntityManager()->persist( $computadorColeta );
        // Persistencia de Historico
        $computadorColetaHistorico = new ComputadorColetaHistorico();
        $computadorColetaHistorico->setIdClass($class_computer_system);
        $computadorColetaHistorico->setIdComputadorColeta( $computadorColeta );
        $computadorColetaHistorico->setIdComputador( $computador );
        $computadorColetaHistorico->setTeClassValues($computer_system);
        $computadorColetaHistorico->setDtHrInclusao( $data );
        $this->getEntityManager()->persist( $computadorColetaHistorico );


        $computador->setDtHrUltAcesso( $data );
        $computador->setTeVersaoCacic( $te_versao_cacic );
        $computador->setTeVersaoGercols( $te_versao_gercols );
        $computador->setTeUltimoLogin( TagValueHelper::getValueFromTags( 'UserName' ,$computer_system ) );
        $computador->setTeIpComputador( TagValueHelper::getValueFromTags( 'IPAddress' ,$network_adapter ) );
        $computador->setNmComputador( TagValueHelper::getValueFromTags( 'Caption' ,$computer_system ));
        $this->getEntityManager()->persist( $computador );


        $acoes = $this->getEntityManager()->getRepository('CacicCommonBundle:Acao')->findAll();

        //inserção ações de coleta a nova maquina
        $acao_so = new AcaoSo();
        foreach ($acoes as $acao)
        {
            $acao_so->setRede( $rede );
            $acao_so->setSo( $so );
            $acao_so->setAcao( $acao );
            $this->getEntityManager()->persist( $acao_so );
        }

        //persistir dados
        $this->getEntityManager()->flush();

        return $computador;
    }

}