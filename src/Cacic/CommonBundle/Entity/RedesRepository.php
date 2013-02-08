<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 *
 * Repositório de métodos de consulta em DQL
 * @author lightbase
 *
 */
class RedesRepository extends EntityRepository
{

    public function paginar( $page )
    {

    }

    /**
     *
     * Método de listagem das Redes cadastradas e respectivas informações dos locais associados
     */
    public function listar()
    {
        $_dql = "SELECT r, l.nmLocal
				FROM CacicCommonBundle:Redes r
				LEFT JOIN r.local l
				GROUP BY r.idRede";

        return $this->getEntityManager()->createQuery( $_dql )->getArrayResult();
    }
    
    /**
     * 
     * Método de listagem de Redes associadas a determinado Local 
     * @param integer $idLocal
     */
    public function listarPorLocal( $idLocal )
    {
    	$_dql = "SELECT r
				FROM CacicCommonBundle:Redes r
				WHERE r.idLocal = :idLocal";

        return $this->getEntityManager()
        			->createQuery( $_dql )
        			->setParameter( 'idLocal', $idLocal )
        			->getArrayResult();
    }
    /**
     *
     * Método de listagem de Redes associadas a determinado Servidor Autenticacao
     * @param integer $idServidorAutenticacao
     */
    public function listarPorServidorAutenticacao( $idServidorAutenticacao )
    {
        $_dql = "SELECT r
				FROM CacicCommonBundle:Redes r
				WHERE r.idServidorAutenticacao = :idServidorAutenticacao";

        return $this->getEntityManager()
            ->createQuery( $_dql )
            ->setParameter( 'idServidorAutenticacao', $idServidorAutenticacao )
            ->getArrayResult();
    }

}