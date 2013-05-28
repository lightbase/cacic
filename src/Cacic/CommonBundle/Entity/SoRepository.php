<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 *
 * Repositório de métodos de consulta em DQL
 * @author lightbase
 *
 */
class SoRepository extends EntityRepository
{

    /**
     *
     * Método de listagem dos Tipo de Software cadastrados e respectivas informações
     */
    public function listar()
    {
        $_dql = "SELECT s
				FROM CacicCommonBundle:So s
				ORDER BY s.teDescSo";

        return $this->getEntityManager()->createQuery( $_dql )->getArrayResult();
    }
}