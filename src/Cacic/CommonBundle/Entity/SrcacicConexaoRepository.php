<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 *
 * Repositório de métodos de consulta em DQL
 * @author lightbase
 *
 */
class SrcacicConexaoRepository extends EntityRepository
{

    /**
	 * 
	 * Realiza pesquisa por LOGs de CONEXÕES REMOTAS
	 * @param date $dataInicio
	 * @param date $dataFim
	 * @param array $locais
	 */
    public function pesquisar( $dataInicio, $dataFim, $locais )
    {
    	// Monta a Consulta básica...
    	$query = $this->createQueryBuilder('conexao')
    									->innerJoin('conexao.so', 'so')
        								->innerJoin('conexao.usuario', 'usr')
        								->innerJoin('usr.idLocal', 'loc')
        								->leftJoin('conexao.idSrcacicSessao', 'sessao')
        								->leftJoin('sessao.idComputador', 'comp');
        								
        /**
         * Verifica os filtros que foram parametrizados
         */
        if ( $dataInicio )
        	$query->andWhere( 'conexao.dtHrInicioConexao >= :dtInicio' )->setParameter('dtInicio', ( $dataInicio.' 00:00:00' ));
        
        if ( $dataFim )
        	$query->andWhere( 'conexao.dtHrInicioConexao <= :dtFim' )->setParameter('dtFim', ( $dataFim.' 23:59:59' ));
        	
        if ( count($locais) )
        	$query->andWhere( 'loc.idLocal IN (:locais)' )->setParameter('locais', $locais);

        return $query->getQuery()->execute();
    }

}