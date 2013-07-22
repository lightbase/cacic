<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 *
 * Repositório de métodos de consulta em DQL
 * @author lightbase
 *
 */
class AcaoRedeRepository extends EntityRepository
{
	
	/**
	 * Recupera as Redes associadas à determinada Ação
	 * Retorna um array associativo na forma array([idRede] => nmRede (teIpRede))
	 * @param int $acao
	 * @return array
	 */
	public function getArrayChaveValorRedesPorAcao( $acao )
	{
		// Monta a Consulta básica...
    	$query = $this->createQueryBuilder('ar')->select('r.idRede', 'r.nmRede', 'r.teIpRede')
        								->innerJoin('ar.acao', 'a')
        								->innerJoin('ar.rede', 'r')
        								->where("a.idAcao = :idAcao")
        								->setParameter('idAcao', $acao)
        								->orderBy('r.nmRede')
        								->groupBy('r.idRede');

        $tmp = $query->getQuery()->execute();
        
        $return = array();
        foreach( $tmp as $rede )
        {
        	$return[$rede['idRede']] = "{$rede['nmRede']} ({$rede['teIpRede']})";
        }
        
        return $return;
	}
	
	/**
	 * 
	 * Atualiza as Redes (do local informado) associadas a determinada Ação
	 * @param int|Cacic\CommonBundle\Entity\Acao $acao
	 * @param int|Cacic\CommonBundle\Entity\Local $local
	 * @param array $novasRedes
	 */
	public function atualizarPorLocal( $acao, $local, $novasRedes )
	{
		$em = $this->getEntityManager();
		$redesLocal = $em->getRepository( 'CacicCommonBundle:Rede' )->getArrayChaveValorPorLocal( $local );
		
		foreach ( $redesLocal as $idRede => $nmRede )
		{
			$obj = $this->find( array( 'acao'=>$acao, 'rede'=>$idRede ) );
			if ( $obj ) $em->remove( $obj );
		}
		
		$em->flush();
		
		foreach ( $novasRedes as $rede )
		{
			$new = new AcaoRede();
			$new->setAcao( $em->getRepository( 'CacicCommonBundle:Acao' )->find( $acao ) );
			$new->setRede( $em->getRepository( 'CacicCommonBundle:Rede' )->find( $rede ) );
			$em->persist( $new );
		}
		
		$em->flush();
	}

}