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

    public function paginar( $page )
    {

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
        $_dql = "SELECT a, r
                 FROM CacicCommonBundle:Aplicativo a
                 JOIN a.idRede r
                 WHERE a.nmAplicativo NOT LIKE '%#DESATIVADO#%'
                 AND r.idRede = :idRede";

        return $this->getEntityManager()
            ->createQuery( $_dql )
            ->setParameter( 'idRede', $idRede )
            ->getArrayResult();
    }


}