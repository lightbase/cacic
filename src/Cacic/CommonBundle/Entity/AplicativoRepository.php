<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 *
 * Repositório de métodos de consulta em DQL
 * @author lightbase
 *
 */
class AplicativoRepository extends EntityRepository
{

    public function paginar( \Knp\Component\Pager\Paginator $paginator, $page = 1 )
    {
        $_dql = "SELECT a.idAplicativo, a.nmAplicativo
				FROM CacicCommonBundle:Aplicativo a
				GROUP BY a.idAplicativo, a.nmAplicativo";

        return $paginator->paginate(
            $this->getEntityManager()->createQuery( $_dql )->getArrayResult(),
            $page,
            10
        );
    }
    /**
     *
     * Método de listagem dos Aplicativos cadastrados
     */
    public function listar()
    {
        $_dql = "SELECT a
				FROM CacicCommonBundle:Aplicativo a
				GROUP BY a.idAplicativo";

        $query = $this->getEntityManager()->createQuery( $_dql );
        return $query->getArrayResult();
    }

    public function listarAplicativosMonitorados( $idRede )
    {
        $_dql = "SELECT a, r, so
                 FROM CacicCommonBundle:Aplicativo a
                 JOIN a.idRede r
                 JOIN a.idSo so
                 WHERE a.nmAplicativo NOT LIKE '%#DESATIVADO#%'
                 AND r.idRede = :idRede";

        return $this->getEntityManager()
            ->createQuery( $_dql )
            ->setParameter( 'idRede', $idRede )
            ->getArrayResult();
    }


}