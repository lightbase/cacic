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
    
    /**
     * 
     * Método de consulta à base de dados de softwares inventariados
     * @param array $filtros
     */
    public function gerarRelatorioSoftwaresInventariados( $filtros )
    {
    	// Monta a Consulta básica...
    	$qb = $this->createQueryBuilder('sw')
    				->select('sw.nmSoftware', 'l.nmLocal', 'COUNT(comp.idComputador) AS numComp')
        			->innerJoin('sw.estacoes', 'se')
        			->innerJoin('se.idComputador', 'comp')
        			->leftJoin('comp.idRede', 'r')
        			->leftJoin('r.idLocal', 'l')
        			->groupBy('l, sw')
        			->orderBy('sw.nmSoftware, l.nmLocal');
        			
        /**
         * Verifica os filtros que foram parametrizados
         */
        if ( array_key_exists('softwares', $filtros) && !empty($filtros['softwares']) )
        	$qb->andWhere('sw.idSoftware IN (:softwares)')->setParameter('softwares', explode( ',', $filtros['softwares'] ));
        
        if ( array_key_exists('locais', $filtros) && !empty($filtros['locais']) )
        	$qb->andWhere('l.idLocal IN (:locais)')->setParameter('locais', explode( ',', $filtros['locais'] ));
        
        if ( array_key_exists('so', $filtros) && !empty($filtros['so']) )
        	$qb->andWhere('comp.idSo IN (:so)')->setParameter('so', explode( ',', $filtros['so'] ));

        return $qb->getQuery()->execute();
    }
    
	/**
     * 
     * Método de consulta à base de dados de softwares licenciados
     * @param array $filtros
     */
    public function gerarRelatorioSoftwaresLicenciados( $filtros )
    {
    	// Monta a Consulta básica...
    	$qb = $this->createQueryBuilder('sw')
    				->select('sw.nmSoftware', 'aqit.qtLicenca', 'aqit.dtVencimentoLicenca', 'aq.nrProcesso', 'tpl.teTipoLicenca')
        			->innerJoin('sw.licencas', 'aqit')
        			->innerJoin('aqit.idAquisicao', 'aq')
        			->innerJoin('aqit.idTipoLicenca', 'tpl')
        			->orderBy('sw.nmSoftware');
        			
        /**
         * Verifica os filtros que foram parametrizados
         */
        if ( array_key_exists('softwares', $filtros) && !empty($filtros['softwares']) )
        	$qb->andWhere('sw.idSoftware IN (:softwares)')->setParameter('softwares', explode( ',', $filtros['softwares'] ));

        return $qb->getQuery()->execute();
    }
    
	/**
     * 
     * Método de consulta à base de dados de softwares por órgão
     * @param array $filtros
     */
    public function gerarRelatorioSoftwaresPorOrgao( $filtros )
    {
    	// Monta a Consulta básica...
    	$qb = $this->createQueryBuilder('sw')
    				->select('sw', 'se', 'comp')
        			->innerJoin('sw.estacoes', 'se')
        			->innerJoin('se.idComputador', 'comp')
        			->orderBy('sw.nmSoftware')->addOrderBy('comp.nmComputador')->addOrderBy('comp.teIpComputador');
        			
        /**
         * Verifica os filtros que foram parametrizados
         */
        if ( array_key_exists('TipoSoftware', $filtros) && !empty($filtros['TipoSoftware']) )
        	$qb->innerJoin('sw.idTipoSoftware', 'tpsw')->andWhere('tpsw.idTipoSoftware IN (:tpsw)')->setParameter('tpsw', explode( ',', $filtros['TipoSoftware'] ));
        
        if ( array_key_exists('nmComputador', $filtros) && !empty($filtros['nmComputador']) )
        	$qb->andWhere('comp.nmComputador LIKE :nmComputador')->setParameter('nmComputador', "%{$filtros['nmComputador']}%" );

        return $qb->getQuery()->execute();
    }
    
}