<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 *
 * Repositório de métodos de consulta em DQL
 * @author lightbase
 *
 */
class RedeVersaoModuloRepository extends EntityRepository
{

    /**
     *
     * Método de listagem dos Modulos cadastrados e respectivas informações
     */
    public function listar()
    {
        $_dql = "SELECT r
				FROM CacicCommonBundle:RedeVersaoModulo r
				ORDER BY r.nmModulo ASC";

        return $this->getEntityManager()->createQuery( $_dql )->getArrayResult();
    }
}