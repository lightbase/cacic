<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\EntityRepository;
#use Symfony\Component\HttpFoundation\Request;
use Cacic\WSBundle\Helper\IPv4;
use Doctrine\ORM\Query\ResultSetMapping;

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
     * Conta todas as redes de todos os locais
     * @return mixed
     */
    public function countByLocalADM() {
        $query = $this->createQueryBuilder('rede')->select('COUNT(rede.idRede)');
        return $query->getQuery()->getSingleScalarResult();
    }

    /**
     *
     * Método de listagem das Redes cadastradas e respectivas informações dos locais associados
     */

    public function paginar()
    {
        $qb = $this->createQueryBuilder('r')
            ->select('r.idRede','r.nmRede','r.teIpRede','r.teServCacic', 'r.teServUpdates',
                'r.teMascaraRede', 'l.sgLocal', 'COUNT(comp.idComputador) AS numComp', 's.nmServidorAutenticacao','uorg.nmUorg')
            ->innerJoin('CacicCommonBundle:Local', 'l', 'WITH', 'l.idLocal = r.idLocal')
            ->leftJoin('CacicCommonBundle:ServidorAutenticacao', 's', 'WITH', 's.idServidorAutenticacao = r.idServidorAutenticacao')
            ->leftJoin('CacicCommonBundle:Computador', 'comp', 'WITH', 'comp.idRede = r.idRede')
            ->leftJoin('CacicCommonBundle:Uorg', 'uorg', 'WITH', 'uorg.rede = r.idRede')
            ->andWhere("(comp.ativo IS NULL or comp.ativo = 't')")
            ->groupBy('r.idRede, r.nmRede, r.teIpRede, r.teServCacic, r.teServUpdates, r.teMascaraRede, l.sgLocal, s.nmServidorAutenticacao, uorg.nmUorg')
            ->orderBy('r.teIpRede, l.sgLocal');

        return $this->getEntityManager()->createQuery( $qb )->getArrayResult();
//        return $paginator->paginate(
//            $qb->getQuery()->execute(),
//            $page,
//            10
//        );
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
				WHERE r.idLocal IN (:idLocal)";

        return $this->getEntityManager()
        			->createQuery( $_dql )
        			->setParameter( 'idLocal', array($idLocal) )
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
    public function getDadosRedePreColeta( $ip_computador, $netmask = '255.255.255.0' )
    {

        //obtem IP da Rede que a maquina coletada pertence
        $cidr = IPv4::mask2cidr($netmask);
        $ipv4 = new IPv4($ip_computador, $cidr);

        $te_ip_rede = $ipv4->network();
        //error_log("Endereço IP do computador: $ip_computador \nEndereço Broadcast da rede: $te_ip_rede");
        $rede =  $this->findOneBy(  array( 'teIpRede'=> $te_ip_rede ) ); //procura rede

        // Tenta uma última vez com outras máscaras
        if (empty($rede)) {
            $cidr = IPv4::mask2cidr('255.255.255.128');
            $ipv4 = new IPv4($ip_computador, $cidr);

            $te_ip_rede = $ipv4->network();
            $rede =  $this->findOneBy(  array( 'teIpRede'=> $te_ip_rede ) ); //procura rede
        }

        // Se a rede não existir, procuro uma com endereço 0.0.0.0
        if (empty($rede)) {
            $rede = $this->findOneBy( array( 'teIpRede'=> '0.0.0.0' ) );
        }
        //$rede = empty( $rede ) ? $this->getPrimeiraRedeValida() : $rede  ;  // se rede não existir instancio uma nova rede

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
            ->andWhere("(comp.ativo IS NULL or comp.ativo = 't')")
            ->andWhere('comp.idRede = :rede')
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

    public function computadoresSubredes(){
        $rsm = new ResultSetMapping();

        $rsm->addScalarResult('id_computador', 'idComputador');
        $rsm->addScalarResult('te_ip_computador', 'teIpComputador');
        $rsm->addScalarResult('te_mascara_rede', 'teMascaraRede');
        $rsm->addScalarResult('cidr_bits', 'cidrBits');
        $rsm->addScalarResult('rede_velha', 'redeVelha');
        $rsm->addScalarResult('id_rede', 'idRede');
        $rsm->addScalarResult('rede_nova', 'redeNova');
        $rsm->addScalarResult('id_rede2', 'idRedeNova');

        $sql = "
        select c.id_computador,
	      c.te_ip_computador,
	      r.te_mascara_rede,
	      (netmask_bits(inet_to_longip(r.te_mascara_rede::inet))::text) as cidr_bits,
	      r.te_ip_rede as rede_velha,
	      r.id_rede,
	      host(network((c.te_ip_computador||'/'||(netmask_bits(inet_to_longip(r.te_mascara_rede::inet))::text))::inet)) as rede_nova,
	      (SELECT id_rede FROM rede WHERE te_ip_rede = host(network((c.te_ip_computador||'/'||(netmask_bits(inet_to_longip(r.te_mascara_rede::inet))::text))::inet))) as id_rede2
        from computador c
        inner join rede r on c.id_rede = r.id_rede
        where c.te_ip_computador is not null
        and (SELECT id_rede FROM rede WHERE te_ip_rede = host(network((c.te_ip_computador||'/'||(netmask_bits(inet_to_longip(r.te_mascara_rede::inet))::text))::inet))) is not null
        and (c.ativo IS NULL or c.ativo = 't')
        order by rede_nova;
        ";

        $query = $this->getEntityManager()->createNativeQuery($sql, $rsm);

        return $query->execute();
    }

    /*
   * Listar redes para carga no SGConf_PGFN
   */
    public function redeSGConf() {

        $_dql = "SELECT IDENTITY(r.idLocal), r.teIpRede, r.nmRede
                FROM CacicCommonBundle:Rede r
                GROUP BY r";

        return $this->getEntityManager()->createQuery( $_dql )->getArrayResult();

    }

    /**
     * Retorna lista de servidores de atualização
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getServUpdateList() {

        $qb = $this->createQueryBuilder('rede')
            ->select('DISTINCT (rede.teServUpdates) as teServUpdates');

        return $qb;
    }

}
