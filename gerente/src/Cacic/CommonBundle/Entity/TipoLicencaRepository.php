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

    public function paginar( $page )
    {

    }

    /**
     *
     * Método de listagem dos Tipo de licenca cadastrados e respectivas informações
     */
    public function listar()
    {
        $_dql = "SELECT t
				FROM CacicCommonBundle:TipoLicenca t
				GROUP BY t.idTipoLicenca";

        return $this->getEntityManager()->createQuery( $_dql )->getArrayResult();
    }
}