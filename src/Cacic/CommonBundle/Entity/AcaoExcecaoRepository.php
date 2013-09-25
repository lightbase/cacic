<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 *
 * Repositório de métodos de consulta em DQL
 * @author lightbase
 *
 */
class AcaoExcecaoRepository extends EntityRepository
{
	
	/**
	 * Recupera os Computadores (NODE ADDRESS - MAC ADDRESS) associadas à determinada Ação
	 * Retorna um array associativo na forma array([idExcecao] => teNodeAddress)
	 * @param int $acao
	 * @param int $rede
	 * @return array
	 */
	public function getArrayExcecoesPorAcao( $acao, $rede = null )
	{
		// Monta a Consulta básica...
    	$query = $this->createQueryBuilder('ae')->select('ae')
        								->innerJoin('ae.acao', 'a')
        								->where("a.idAcao = :idAcao")
        								->setParameter('idAcao', $acao)
        								->groupBy('ae');
        								
		if ( $rede !== null )
		{ // Se o filtro por rede for informado
			$query->innerJoin('ae.rede', 'r')
					->andWhere('r.idRede = :idRede')
					->setParameter('idRede', $rede);
		}

        $tmp = $query->getQuery()->execute();
        
        $return = array();
        foreach( $tmp as $mac )
        {
        	$return[] = $mac->getTeNodeAddress();
        }
        
        return $return;
	}
	
	/**
	 * 
	 * Atualiza a lista de Exceções (computadores do local informado) associadas a determinada Ação
	 * @param int|Cacic\CommonBundle\Entity\Acao $acao
	 * @param int|Cacic\CommonBundle\Entity\Local $local
	 * @param array $novasRedes
	 * @param array $novasExcecoes
	 */
	public function atualizarPorLocal( $acao, $local, $novasRedes, $novasExcecoes )
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
			foreach ( $novasExcecoes as $teNodeAddress )
			{
				$new = new AcaoExcecao();
				$new->setAcao( $em->getRepository( 'CacicCommonBundle:Acao' )->find( $acao ) );
				$new->setRede( $em->getRepository( 'CacicCommonBundle:Rede' )->find( $rede ) );
				$new->setTeNodeAddress( $teNodeAddress );
				$em->persist( $new );
			}
		}
		
		$em->flush();
	}
	
}