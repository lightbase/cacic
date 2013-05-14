<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 *
 * Repositório de métodos de consulta em DQL
 * @author lightbase
 *
 */
class SoftwareRepository extends EntityRepository
{

    /**
     *
     * Método de listagem dos Software cadastrados e respectivas informações
     */
    public function listar()
    {
    	$_dql = "SELECT s, tipoSW.teDescricaoTipoSoftware AS tipoSoftware
				FROM CacicCommonBundle:Software s
				LEFT JOIN s.idTipoSoftware tipoSW
				ORDER BY s.nmSoftware ASC";

        return $this->getEntityManager()->createQuery( $_dql )->getArrayResult();
    }
    
    /**
     * 
     * Método de listagem dos Softwares cadastrados que não foram classificados (sem Tipo de Software)
     */
    public function listarNaoClassificados()
    {
    	$_dql = "SELECT s
				FROM CacicCommonBundle:Software s
				WHERE s.idTipoSoftware IS NULL
				ORDER BY s.nmSoftware ASC";

        return $this->getEntityManager()->createQuery( $_dql )->getArrayResult();
    }
    
    /**
     * 
     * Método de listagem dos Softwares cadastrados que não estão associados a nenhuma máquina
     */
    public function listarNaoUsados()
    {
    	$_dql = "SELECT s
				FROM CacicCommonBundle:Software s
				LEFT JOIN s.estacoes se
				WHERE se IS NULL
				ORDER BY s.nmSoftware ASC";

        return $this->getEntityManager()->createQuery( $_dql )->getArrayResult();
    }
    
}