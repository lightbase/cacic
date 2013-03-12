<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 *
 * Reposit�rio de m�todos de consulta em DQL
 * @author lightbase
 *
 */
class ServidoresAutenticacaoRepository extends EntityRepository
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
				FROM CacicCommonBundle:ServidoresAutenticacao s
				GROUP BY s.idServidorAutenticacao";

        $query = $this->getEntityManager()->createQuery( $_dql );
        return $query->getArrayResult();
    }


}