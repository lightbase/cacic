<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\EntityRepository;

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
	
}