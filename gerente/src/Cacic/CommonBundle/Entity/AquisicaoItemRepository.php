<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 *
 * Repositório de métodos de consulta em DQL
 * @author lightbase
 *
 */
class AquisicaoItemRepository extends EntityRepository
{

    public function paginar( $page )
    {

    }
    /**
     *
     * Método de listagem das Aquisicões cadastradas
     */
    public function listar()
    {
        $_dql = "SELECT a, s, q, t
                    FROM CacicCommonBundle:AquisicaoItem AS a
                    LEFT JOIN a.idSoftware AS s
                    LEFT JOIN a.idAquisicao AS q
                    LEFT JOIN a.idTipoLicenca AS t
				";

        $query = $this->getEntityManager()->createQuery( $_dql );
        return $query->getArrayResult();
    }
}