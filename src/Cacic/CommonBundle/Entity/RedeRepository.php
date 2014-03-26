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
        								;

        return $query->getQuery()->getSingleScalarResult();
	}

    /**
     *
     * Método de listagem das Redes cadastradas e respectivas informações dos locais associados
     */

    public function paginar( \Knp\Component\Pager\Paginator $paginator, $page = 1 )
    {
        $qb = $this->createQueryBuilder('r')
            ->select('r.idRede','r.nmRede','r.teIpRede','r.teServCacic', 'r.teServUpdates',
                'r.teMascaraRede', 'l.nmLocal', 'COUNT(comp.idComputador) AS numComp', 's.nmServidorAutenticacao','uorg.nmUorg')
            ->innerJoin('CacicCommonBundle:Local', 'l', 'WITH', 'l.idLocal = r.idLocal')
            ->leftJoin('CacicCommonBundle:ServidorAutenticacao', 's', 'WITH', 's.idServidorAutenticacao = r.idServidorAutenticacao')
            ->leftJoin('CacicCommonBundle:Computador', 'comp', 'WITH', 'comp.idRede = r.idRede')
            ->leftJoin('CacicCommonBundle:Uorg', 'uorg', 'WITH', 'uorg.rede = r.idRede')
            ->groupBy('r.idRede, r.nmRede, r.teIpRede, r.teServCacic, r.teServUpdates, r.teMascaraRede, l.nmLocal, s.nmServidorAutenticacao, uorg.nmUorg')
            ->orderBy('r.teIpRede, l.nmLocal');

        return $paginator->paginate(
            $qb->getQuery()->execute(),
            $page,
            10
        );
    }

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
    public function listarPorLocalADM()
    {
        $_dql = "SELECT r
				FROM CacicCommonBundle:Rede r";

        return $this->getEntityManager()->createQuery( $_dql )->getArrayResult();
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

    /*
     * Retorna lista de redes e nome do Local
     * @param idLocal Se fornecido o idLocal, retorna somente os locais para aquele local
     */

    public function comLocal ($idLocal = null)
    {
        $qb = $this->createQueryBuilder('r')
            ->select('r.idRede',
                'r.teIpRede',
                'r.nmRede',
                'r.teServUpdates',
                'r.tePathServUpdates',
                'l.nmLocal')
            ->innerJoin('CacicCommonBundle:Local', 'l', 'WITH', 'r.idLocal = l.idLocal')
            ->orderBy('r.nmRede');

        if ($idLocal != null) {
            $qb->andWhere('r.idLocal = :idLocal')->setParameter('idLocal', $idLocal);
        }

        return $qb->getQuery()->getArrayResult();
    }

    public function gerarRelatorioRede( $filtros, $idRede )
    {
        $qb = $this->createQueryBuilder('rede')
            ->select('DISTINCT (comp.idComputador)', 'comp.nmComputador', 'comp.teNodeAddress','comp.teIpComputador', 'comp.dtHrUltAcesso', 'so.idSo', 'so.inMswindows', 'so.sgSo', 'rede.idRede'
                , 'rede.nmRede', 'rede.teIpRede', 'local.nmLocal', 'local.idLocal')
            ->innerJoin('CacicCommonBundle:Computador', 'comp','WITH','rede.idRede = comp.idRede')
            ->innerJoin('CacicCommonBundle:ComputadorColetaHistorico','hist', 'WITH', 'comp.idComputador = hist.computador')
            ->innerJoin('comp.idSo', 'so')
            ->innerJoin('rede.idLocal', 'local')
            ->where('comp.idRede = :rede')
            ->setParameter('rede', $idRede);
        /**
         * Verifica os filtros
         */
        if ( array_key_exists('locais', $filtros) && !empty($filtros['locais']) )
            $qb->andWhere('local.idLocal IN (:locais)')->setParameter('locais', explode( ',', $filtros['locais'] ));



        if ( array_key_exists('so', $filtros) && !empty($filtros['so']) )
            $qb->andWhere('comp.idSo IN (:so)')->setParameter('so', explode( ',', $filtros['so'] ));

        if ( array_key_exists('conf', $filtros) && !empty($filtros['conf']) )
            $qb->andWhere('property.idClassProperty IN (:conf)')->setParameter('conf', explode( ',', $filtros['conf'] ));


        return $qb->getQuery()->execute();
    }
}