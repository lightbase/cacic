<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * LogUserLogadoRepository
 *
 * Métodos de repositório
 */
class LogUserLogadoRepository extends EntityRepository
{
    /*
     * Função que retorna o último acesso para o computador solicitado
     * @param $computador
     */
    public function ultimoAcesso( $computador ) {
        $qb = $this->createQueryBuilder('acesso')
            ->select('acesso')
            ->where('acesso.idComputador = :computador')
            ->orderBy('acesso.data', 'desc')
            ->setMaxResults(1)
            ->setParameter('computador', $computador );

        return $qb->getQuery()->getOneOrNullResult();
    }

    /*
    * Função que retorna a busca por usuário logago
    */
    public function selectUserLogado( $teIpComputador , $nmComputador ,$usuario, $dtHrInclusao ,$dtHrInclusaoFim )
    {

        $query = $this->createQueryBuilder('log')
            ->select('comp.nmComputador', 'comp.teIpComputador', 'log.data', 'log.usuario')
            ->innerJoin('CacicCommonBundle:Computador','comp', 'WITH', 'log.idComputador = comp.idComputador');

        if ( $teIpComputador != null){

            $query->Where("c.teIpComputador  LIKE   '%$teIpComputador%'");

        }
        if ( $nmComputador != null){
            $query->Where("c.nmComputador LIKE   '%$nmComputador%'");
        }
        if ( $usuario != null){
            $query->Where("log.usuario  LIKE   '%$usuario%'");
        }
        if ( $dtHrInclusao != null){
            $query->andWhere( 'log.data >= (:dtHrInclusao)' )->setParameter('dtHrInclusao', ( $dtHrInclusao.' 00:00:00' ));
        }
        if ( $dtHrInclusaoFim != null){
            $query->andWhere( 'log.data<= (:dtHrInclusaoFim)' )->setParameter('dtHrInclusaoFim', ( $dtHrInclusaoFim.' 23:59:59' ));
        }


        return $query->getQuery()->execute();
    }
}
