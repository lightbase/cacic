<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 *
 * Repositório de métodos de consulta em DQL
 * @author lightbase
 *
 */
class PatrimonioConfigInterfaceRepository extends EntityRepository
{

    public function paginar( $page )
    {

    }
    /**
     *
     * Método de listagem das
     */
    public function listar()
    {
        $_dql = "SELECT a
				FROM CacicCommonBundle:PatrimonioConfigInterface a";

        $query = $this->getEntityManager()->createQuery( $_dql );
        return $query->getArrayResult();
    }


}