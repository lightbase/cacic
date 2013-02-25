<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 *
 * Repositório de métodos de consulta em DQL
 * @author lightbase
 *
 */
class SoftwaresRepository extends EntityRepository
{

    public function paginar( $page )
    {

    }

    /**
     *
     * Método de listagem dos Software cadastrados e respectivas informações
     */
    public function listar()
    {
        $_dql = "SELECT s
				FROM CacicCommonBundle:Softwares s
				GROUP BY s.idSoftware";

        return $this->getEntityManager()->createQuery( $_dql )->getArrayResult();
    }
}