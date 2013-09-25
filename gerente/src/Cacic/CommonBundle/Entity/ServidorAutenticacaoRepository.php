<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 *
 * Reposit�rio de m�todos de consulta em DQL
 * @author lightbase
 *
 */
class ServidorAutenticacaoRepository extends EntityRepository
{

    public function paginar( $page )
    {

    }
    /**
     *
     * M�todo de listagem dos servidores cadastrados
     */
    public function listar()
    {
        $_dql = "SELECT s
				FROM CacicCommonBundle:ServidorAutenticacao s
				GROUP BY s.idServidorAutenticacao";

        $query = $this->getEntityManager()->createQuery( $_dql );
        return $query->getArrayResult();
    }


}