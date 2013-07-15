<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;

/**
 *
 * Repositório de métodos de consulta em DQL
 * @author lightbase
 *
 */
class RedeRepository extends EntityRepository
{
	/**
	 * 
	 * Recupera o total de Redes de dado Local
	 * @param int $idLocal
	 * @return int
	 */
	public function countByLocal( $idLocal )
	{
		$query = $this->createQueryBuilder('rede')->select('COUNT(rede.idRede)')
        								->innerJoin('rede.idLocal', 'loc')
        								->where('loc.idLocal = :idLocal')
        								->setParameter('idLocal', $idLocal);

        return $query->getQuery()->getSingleScalarResult();
	}

    /**
     *
     * Método de listagem das Redes cadastradas e respectivas informações dos locais associados
     */
    public function listar()
    {
        $_dql = "SELECT r, count(l.nmLocal) AS local
				FROM CacicCommonBundle:Rede r
				LEFT JOIN r.idLocal l
				GROUP BY r";

        return $this->getEntityManager()->createQuery( $_dql )->getArrayResult();
    }
    
    /**
     * 
     * Método de listagem de Redes associadas a determinado Local 
     * @param integer $idLocal
     */
    public function listarPorLocal( $idLocal )
    {
    	$_dql = "SELECT r
				FROM CacicCommonBundle:Rede r
				WHERE r.idLocal = :idLocal";

        return $this->getEntityManager()
        			->createQuery( $_dql )
        			->setParameter( 'idLocal', $idLocal )
        			->getArrayResult();
    }
    
    /**
     *
     * Método de listagem de Redes associadas a determinado Servidor Autenticacao
     * @param integer $idServidorAutenticacao
     */
    public function listarPorServidorAutenticacao( $idServidorAutenticacao )
    {
        $_dql = "SELECT r
				FROM CacicCommonBundle:Rede r
				WHERE r.idServidorAutenticacao = :idServidorAutenticacao";

        return $this->getEntityManager()
            ->createQuery( $_dql )
            ->setParameter( 'idServidorAutenticacao', $idServidorAutenticacao )
            ->getArrayResult();
    }
    
    /**
     * Recupera as redes associadas ao local informado
     * Retorna um array associativo do tipo [idRede] => nmRede
     * @param int|Cacic\CommonBundle\Entity\Local $local
     * @return array
     */
    public function getArrayChaveValorPorLocal( $local )
    {
    	$redes = $this->listarPorLocal( $local );
    	$return = array();
    	
    	foreach( $redes as $rede )
    	{
    		$return[$rede['idRede']] = $rede['nmRede'];
    	}
    	
    	return $return;
    }

    public function getPrimeiraRedeValida()
    {
        $_dql = "SELECT r
				FROM CacicCommonBundle:Rede r
				WHERE r.nmRede <> ''
				AND r.nuPortaServUpdates <> ''
				AND r.nmUsuarioLoginServUpdatesGerente <> ''
				AND r.teSenhaLoginServUpdatesGerente <> ''";

        return $this->getEntityManager()
            ->createQuery( $_dql )
            ->setMaxResults(1)
            ->getSingleResult();
    }

    /*
    * Método responsável por coletar verificar dados de rede
    */
    public function getDadosRedePreColeta( Request $request )
    {
        //obtem IP da maquina coletada
        $ip_computador = $request->get('te_ip_computador');
        $ip_computador = !empty( $ip_computador ) ?: $_SERVER['REMOTE_ADDR'];

        //obtem IP da Rede que a maquina coletada pertence
        $ip = explode( '.', $ip_computador );
        $te_ip_rede = $ip[0].".".$ip[1].".".$ip[2].".0"; //Pega ip da REDE sendo esse X.X.X.0

        $rede =  $this->findOneBy(  array( 'teIpRede'=> $te_ip_rede ) ); //procura rede
        $rede = empty( $rede ) ? $this->getPrimeiraRedeValida() : $rede  ;  // se rede não existir instancio uma nova rede

        return $rede;
    }
}