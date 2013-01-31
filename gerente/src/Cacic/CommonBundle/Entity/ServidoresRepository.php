<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 *
 * Repositório de métodos de consulta em DQL
 * @author lightbase
 *
 */
class ServidoresRepository extends EntityRepository
{

    public function paginar( $page )
    {

    }
    /**
     *
     * Método de listagem dos servidores cadastrados
     */
    public function listar()
    {
        $_dql = "SELECT s
				FROM CacicCommonBundle:ServidoresAutenticacao s
				GROUP BY s.idServidorAutenticacao";

        $query = $this->getEntityManager()->createQuery( $_dql );
        return $query->getArrayResult();
    }

}