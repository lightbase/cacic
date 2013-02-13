<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 *
 * Repositório de métodos de consulta em DQL
 * @author lightbase
 *
 */
class PerfisAplicativosMonitoradosRepository extends EntityRepository
{

    public function paginar( $page )
    {

    }
    /**
     *
     * Método de listagem dos Perfis Aplicativos Monitorados cadastrados
     */
    public function listar()
    {
        $_dql = "SELECT p
				FROM CacicCommonBundle:PerfisAplicativosMonitorados p
				GROUP BY p.idAplicativo";

        $query = $this->getEntityManager()->createQuery( $_dql );
        return $query->getArrayResult();
    }


}