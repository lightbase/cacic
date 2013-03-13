<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 *
 * Repositório de métodos de consulta em DQL
 * @author lightbase
 *
 */
class TipoSoftwareRepository extends EntityRepository
{

    public function paginar( $page )
    {

    }

    /**
     *
     * Método de listagem dos Tipo de Software cadastrados e respectivas informações
     */
    public function listar()
    {
        $_dql = "SELECT t
				FROM CacicCommonBundle:TipoSoftware t
				GROUP BY t.idTipoSoftware";

        return $this->getEntityManager()->createQuery( $_dql )->getArrayResult();
    }
}