<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 *
 * Repositório de métodos de consulta em DQL
 * @author lightbase
 *
 */
class AcaoSoRepository extends EntityRepository
{
	
	/**
	 * Recupera os SOs associados à determinada Ação e, opcionalmente, à determinada Rede
	 * Retorna um array associativo na forma array([idRede] => nmRede (teIpRede))
	 * @param int $acao
	 * @param int $rede
	 * @return array
	 */
	public function getArrayChaveValorSoPorAcao( $acao, $rede = null )
	{
		// Monta a Consulta básica...
    	$query = $this->createQueryBuilder('aso')->select('so.idSo', 'so.teDescSo', 'so.sgSo', 'so.teSo')
        								->innerJoin('aso.so', 'so')
        								->innerJoin('aso.acao', 'a')
        								->where("a.idAcao = :idAcao")
        								->setParameter('idAcao', $acao)
        								->orderBy('so.teDescSo')
        								->groupBy('so');

		if ( $rede !== null )
		{ // Se o filtro por rede for informado
			$query->innerJoin('aso.rede', 'r')
					->andWhere('r.idRede = :idRede')
					->setParameter('idRede', $rede);
		}

        $tmp = $query->getQuery()->execute();
        
        $return = array();
        foreach( $tmp as $so )
        {
        	$return[$so['idSo']] = "{$so['teDescSo']} ({$so['sgSo']} - {$so['teSo']})";
        }
        
        return $return;
	}
	
	/**
	 * 
	 * Atualiza os SOs (do local informado) associadas a determinada Ação
	 * @param int|Cacic\CommonBundle\Entity\Acao $acao
	 * @param int|Cacic\CommonBundle\Entity\Local $local
	 * @param array $novasRedes
	 * @param array $novosSO
	 */
	public function atualizarPorLocal( $acao, $local, $novasRedes, $novosSO )
	{
		$em = $this->getEntityManager();
		$redesLocal = $em->getRepository( 'CacicCommonBundle:Rede' )->getArrayChaveValorPorLocal( $local );
		
		foreach ( $redesLocal as $idRede => $nmRede )
		{
			$arr = $this->findBy( array( 'acao'=>$acao, 'rede'=>$idRede ) );
			foreach ( $arr as $obj )
				$em->remove( $obj );
		}
		
		$em->flush();
		
		foreach( $novasRedes as $rede )
		{
			foreach ( $novosSO as $so )
			{
				$new = new AcaoSo();
				$new->setAcao( $em->getRepository( 'CacicCommonBundle:Acao' )->find( $acao ) );
				$new->setRede( $em->getRepository( 'CacicCommonBundle:Rede' )->find( $rede ) );
				$new->setSo( $em->getRepository( 'CacicCommonBundle:So' )->find( $so ) );
				$em->persist( $new );
			}
		}
		
		$em->flush();
	}
}