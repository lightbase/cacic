<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 *
 * Repositório de métodos de consulta em DQL
 * @author lightbase
 *
 */
class TipoSoftwareRepository extends EntityRepository
{

    public function paginar( \Knp\Component\Pager\Paginator $paginator, $page = 1 )
    {
        $_dql = "SELECT t
				FROM CacicCommonBundle:TipoSoftware t
				ORDER BY t.teDescricaoTipoSoftware";

        return $paginator->paginate(
            $this->getEntityManager()->createQuery( $_dql ),
            $page,
            10
        );
    }
    /**
     *
     * Método de listagem dos Tipo de Software cadastrados e respectivas informações
     */
    public function listar()
    {
        $_dql = "SELECT t
				FROM CacicCommonBundle:TipoSoftware t
				ORDER BY t.teDescricaoTipoSoftware";

        return $this->getEntityManager()->createQuery( $_dql )->getArrayResult();
    }
}