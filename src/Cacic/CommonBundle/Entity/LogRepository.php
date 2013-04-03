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

    public function pesquisar()
    {
        $_dql = "SELECT l,
				FROM CacicCommonBundle:Log l";

        $query = $this->getEntityManager()->createQuery( $_dql );
        return $query->getArrayResult();

    }

}