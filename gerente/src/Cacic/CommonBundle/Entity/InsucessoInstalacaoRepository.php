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
     * Realiza pesquisa por LOGs segundo parâmetros informados
     * @param array $data
     */
    public function pesquisar( $dataInicio, $dataFim )
    {

        $filtros = array();
        if ( $dataInicio )	$filtros[] = 'i.dtDatahora >= :dtInicio';
        if ( $dataFim)	$filtros[] = 'i.dtDatahora <= :dtFim';

        if ( count( $filtros ) ) $filtros = 'WHERE '. implode( ' AND ', $filtros );
        else $filtros = '';

        $_dql = "SELECT i
				FROM CacicCommonBundle:InsucessoInstalacao i
				{$filtros}";

        $query = $this->getEntityManager()->createQuery( $_dql )
            ->setParameter('dtInicio', $dataInicio)
            ->setParameter('dtFim', $dataFim);

        return $query->getArrayResult();


    }

}