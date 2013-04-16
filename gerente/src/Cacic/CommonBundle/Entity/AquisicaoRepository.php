<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 *
 * Repositório de métodos de consulta em DQL
 * @author lightbase
 *
 */
class AquisicaoRepository extends EntityRepository
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
        $_dql = "SELECT a
				FROM CacicCommonBundle:Aquisicao a
				GROUP BY a.idAquisicao";

        $query = $this->getEntityManager()->createQuery( $_dql );
        return $query->getArrayResult();
    }


}