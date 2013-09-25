<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 *
 * Repositório de métodos de consulta em DQL
 * @author lightbase
 *
 */
class LogRepository extends EntityRepository
{

	/**
	 * 
	 * Realiza pesquisa por LOGs de ACESSO ou ATIVIDADES segundo parâmetros informados
	 * @param string|array $tipoPesquisa
	 * @param date $dataInicio
	 * @param date $dataFim
	 * @param array $locais
	 */
    public function pesquisar( $tipoPesquisa, $dataInicio, $dataFim, $locais )
    {
    	// Monta a Consulta básica...
    	$query = $this->createQueryBuilder('log')->select('log', 'usr.nmUsuarioCompleto', 'loc.nmLocal', 'loc.sgLocal')
        								->innerJoin('log.idUsuario', 'usr')
        								->innerJoin('usr.idLocal', 'loc')
        								->where('log.csAcao IN (:tipoPesquisa)')
        								->setParameter('tipoPesquisa', $tipoPesquisa);
        								
        /**
         * Verifica os filtros que foram parametrizados
         */
        if ( $dataInicio )
        	$query->andWhere( 'log.dtAcao >= :dtInicio' )->setParameter('dtInicio', ( $dataInicio.' 00:00:00' ));
        
        if ( $dataFim )
        	$query->andWhere( 'log.dtAcao <= :dtFim' )->setParameter('dtFim', ( $dataFim.' 23:59:59' ));
        	
        if ( count($locais) )
        	$query->andWhere( 'loc.idLocal IN (:locais)' )->setParameter('locais', $locais);

        return $query->getQuery()->execute();
    }

}