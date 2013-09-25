<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 *
 * Repositório de métodos de consulta em DQL
 * @author lightbase
 *
 */
class SoftwareEstacaoRepository extends EntityRepository
{

    /**
     *
     * Método de listagem dos Software Estacao cadastrados e respectivas informações
     */
    public function listar()
    {
        $_dql = "SELECT s, sof.nmSoftware
				FROM CacicCommonBundle:SoftwareEstacao s
				LEFT JOIN s.idSoftware sof";

        return $this->getEntityManager()->createQuery( $_dql )->getArrayResult();
    }
    
    /**
     * 
     * Relatorio de Autorizacoes Cadastradas
     */
    public function gerarRelatorioAutorizacoes()
    {
    	$_dql = "SELECT se.nrPatrimonio, se.nmComputador, se.teObservacao
    			FROM CacicCommonBundle:SoftwareEstacao se
    			WHERE se.dtDesinstalacao IS NULL
    			ORDER BY se.nrPatrimonio";
    	
    	return $this->getEntityManager()->createQuery( $_dql )->getArrayResult();
    }
}