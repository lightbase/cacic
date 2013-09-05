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
    public function paginar( \Knp\Component\Pager\Paginator $paginator, $page = 1 )
    {
        $_dql = "SELECT s
				FROM CacicCommonBundle:ServidorAutenticacao s
				GROUP BY s";

        return $paginator->paginate(
            $this->getEntityManager()->createQuery( $_dql ),
            $page,
            10
        );
    }
    /**
     *
     * M�todo de listagem dos servidores cadastrados
     */
    public function listar()
    {
        $_dql = "SELECT s
				FROM CacicCommonBundle:ServidorAutenticacao s
				GROUP BY s";

        $query = $this->getEntityManager()->createQuery( $_dql );
        return $query->getArrayResult();
    }


}