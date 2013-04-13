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
	 * Realiza pesquisa por LOGs segundo parâmetros informados
	 * @param array $data
	 */
    public function acessoPesquisar( $dataInicio, $dataFim, $locais )
    {
        $filtros = array();
        if ( $dataInicio )	$filtros[] = 'log.dtAcao >= :dtInicio';
        if ( $dataFim)	$filtros[] = 'log.dtAcao <= :dtFim';
        if ( count($locais) ) $filtros[] = 'loc.idLocal IN (:idLocal)';

        if ( count( $filtros ) ) $filtros = 'WHERE '."log.csAcao = 'ACE'  AND ". implode( ' AND ', $filtros );
        else $filtros = '';

        $_dql = "SELECT log, usr.nmUsuarioCompleto, loc.nmLocal
				FROM CacicCommonBundle:Log log
				LEFT JOIN log.idUsuario usr
				LEFT JOIN usr.idLocal loc
				{$filtros}";

        $query = $this->getEntityManager()->createQuery( $_dql )


        									->setParameter('dtInicio', $dataInicio)
        									->setParameter('dtFim', $dataFim)
        									->setParameter('idLocal', $locais);

        return $query->getArrayResult();


    }
    public function AtividadePesquisar( $dataInicio, $dataFim, $locais )
    {
        $filtros = array();
        if ( $dataInicio )	$filtros[] = 'log.dtAcao >= :dtInicio';
        if ( $dataFim)	$filtros[] = 'log.dtAcao <= :dtFim';
        if ( count($locais) ) $filtros[] = 'loc.idLocal IN (:idLocal)';

        if ( count( $filtros ) ) $filtros = 'WHERE '."log.csAcao <> 'ACE'  AND ". implode( ' AND ', $filtros );
        else $filtros = '';


        $_dql = "SELECT log, usr.nmUsuarioCompleto, loc.nmLocal
				FROM CacicCommonBundle:Log log
				LEFT JOIN log.idUsuario usr
				LEFT JOIN usr.idLocal loc
				{$filtros}";

        $query = $this->getEntityManager()->createQuery( $_dql )
            ->setParameter('dtInicio', $dataInicio)
            ->setParameter('dtFim', $dataFim)
            ->setParameter('idLocal', $locais);


        return $query->getArrayResult();



    }

}