<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 *
 * Repositório de métodos de consulta em DQL
 * @author lightbase
 *
 */
class TipoLicencaRepository extends EntityRepository
{

    public function paginar( \Knp\Component\Pager\Paginator $paginator, $page = 1 )
    {
        $_dql = "SELECT distinct(t.idTipoLicenca)
				FROM CacicCommonBundle:TipoLicenca t
				ORDER BY t.idTipoLicenca";

        return $paginator->paginate(
            $this->getEntityManager()->createQuery( $_dql )->getArrayResult(),
            $page,
            10
        );
    }
    /**
     *
     * Método de listagem dos Tipo de licenca cadastrados e respectivas informações
     */
    public function listar()
    {
        $_dql = "SELECT t.idTipoLicenca
				FROM CacicCommonBundle:TipoLicenca t
				GROUP BY t.idTipoLicenca";

        return $this->getEntityManager()->createQuery( $_dql )->getArrayResult();
    }
}