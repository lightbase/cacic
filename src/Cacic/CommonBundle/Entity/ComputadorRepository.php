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
					->orderBy('comp.teIpComputador');
		
		return $qb->getQuery()->getResult();
	}
	
}