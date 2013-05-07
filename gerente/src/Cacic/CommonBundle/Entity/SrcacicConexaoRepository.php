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
     * Realiza pesquisa por LOGs segundo parâmetros informados
     * @param array $data
     */
    public function pesquisar( $dataInicio, $dataFim, $locais )
    {

        $filtros = array();
        if ( $dataInicio )	$filtros[] = 'log.dtAcao >= :dtInicio';
        if ( $dataFim)	$filtros[] = 'log.dtAcao <= :dtFim';
        if ( count($locais) ) $filtros[] = 'loc.idLocal IN (:idLocal)';

        if ( count( $filtros ) ) $filtros = 'WHERE '. implode( ' AND ', $filtros );
        else $filtros = '';

        $_dql = "SELECT conec, comp.teIpComputador, Usr.nmUsuarioCompleto,
                        sess.nmCompletoUsuarioSrv,so.sgSo
				FROM CacicCommonBundle:SrcacicConexao conec
				LEFT JOIN conec.idSrcacicSessao sess
				LEFT JOIN sess.idComputador comp
				LEFT JOIN comp.idUsuarioExclusao Usr
				LEFT JOIN Usr.idLocal loc
				LEFT JOIN comp.idSo so
				{$filtros}";

        $query = $this->getEntityManager()->createQuery( $_dql )
            ->setParameter('dtInicio', $dataInicio)
            ->setParameter('dtFim', $dataFim)
            ->setParameter('idLocal', $locais);

        return $query->getArrayResult();


    }

}