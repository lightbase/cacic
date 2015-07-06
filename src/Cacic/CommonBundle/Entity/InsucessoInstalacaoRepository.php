<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 *
 * Repositório de métodos de consulta em DQL
 * @author lightbase
 *
 */
class InsucessoInstalacaoRepository extends EntityRepository
{

    /**
	 * 
	 * Realiza pesquisa por LOGs de INSUCESSOS DE INSTALAÇÃO segundo parâmetros informados
	 * @param date $dataInicio
	 * @param date $dataFim
	 */
    public function pesquisar( $dataInicio, $dataFim )
    {
    	// Monta a Consulta básica...
    	$query = $this->createQueryBuilder('insucesso');
        								
        /**
         * Verifica os filtros que foram parametrizados
         */
        if ( $dataInicio )
        	$query->andWhere( 'insucesso.dtDatahora >= :dtInicio' )->setParameter('dtInicio', ( $dataInicio.' 00:00:00' ));
        
        if ( $dataFim )
        	$query->andWhere( 'insucesso.dtDatahora <= :dtFim' )->setParameter('dtFim', ( $dataFim.' 23:59:59' ));

        return $query->getQuery()->execute();
    }
    
    /**
     * 
     * Conta as tentativas de instalação malsucedidas
     */
    public function countAll()
    {
    	return $this->createQueryBuilder('insucesso')->select('COUNT(insucesso.idInsucessoInstalacao)')->getQuery()->getSingleScalarResult();
    }


    public function count24h()
    {
        return $this->createQueryBuilder('insucesso')
            ->select('COUNT(insucesso.idInsucessoInstalacao)')
            ->andWhere("insucesso.dtDatahora >= (current_date() - 7)")
            ->getQuery()
            ->getSingleScalarResult();
    }

}