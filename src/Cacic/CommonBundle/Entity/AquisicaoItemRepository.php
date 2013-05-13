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
     * Método de listagem dos Itens de Aquisição cadastrados
     */
    public function listar()
    {
        $_dql = "SELECT item, aquisicao, software, tipoLicenca
                    FROM CacicCommonBundle:AquisicaoItem item
                    LEFT JOIN item.idSoftware software
                    LEFT JOIN item.idAquisicao aquisicao
                    LEFT JOIN item.idTipoLicenca tipoLicenca
				";

        $query = $this->getEntityManager()->createQuery( $_dql );
        return $query->getArrayResult();
    }
}