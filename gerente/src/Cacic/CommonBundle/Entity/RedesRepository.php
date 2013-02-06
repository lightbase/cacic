<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 *
 * Reposit�rio de m�todos de consulta em DQL
 * @author lightbase
 *
 */
class RedesRepository extends EntityRepository
{

    public function paginar( $page )
    {

    }

    /**
     *
     * Método de listagem dos Locais cadastrados e respectivas informações de Subredes
     */
    public function listar()
    {
        $_dql = "SELECT r, l.nmLocal
				FROM CacicCommonBundle:Redes r
				LEFT JOIN r.local l
				GROUP BY r.idRede";

        $query = $this->getEntityManager()->createQuery( $_dql );
        return $query->getArrayResult();

    }

}