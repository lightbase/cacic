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

    public function paginar( \Knp\Component\Pager\Paginator $paginator, $page = 1 )
    {
        $_dql = "SELECT se,
        				comp.idComputador, COALESCE( comp.nmComputador, comp.teIpComputador, comp.teNodeAddress ) AS descComputador,
        				sw.idSoftware, sw.nmSoftware,
        				aq.nrProcesso
				FROM CacicCommonBundle:SoftwareEstacao se
				INNER JOIN se.idSoftware sw
				INNER JOIN se.idComputador comp
				LEFT JOIN se.idAquisicao aq";

        return $paginator->paginate(
            $this->getEntityManager()->createQuery( $_dql )->getArrayResult(),
            $page,
            10
        );
    }
    /**
     *
     * Método de listagem dos Software Estacao cadastrados e respectivas informações
     */
    public function listar()
    {
        $_dql = "SELECT se,
        				comp.idComputador, COALESCE( comp.nmComputador, comp.teIpComputador, comp.teNodeAddress ) AS descComputador,
        				sw.idSoftware, sw.nmSoftware,
        				aq.nrProcesso
				FROM CacicCommonBundle:SoftwareEstacao se
				INNER JOIN se.idSoftware sw
				INNER JOIN se.idComputador comp
				LEFT JOIN se.idAquisicao aq";

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