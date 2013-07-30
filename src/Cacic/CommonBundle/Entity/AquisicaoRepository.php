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

    public function paginar( \Knp\Component\Pager\Paginator $paginator, $page = 1 )
    {
        $_dql = "SELECT a
				FROM CacicCommonBundle:Aquisicao a
				GROUP BY a.idAquisicao";

        return $paginator->paginate(
            $this->getEntityManager()->createQuery( $_dql ),
            $page,
            10
        );
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

	/**
     * 
     * Método de consulta à base de dados por processos de Aquisição de Software
     */
    public function gerarRelatorioAquisicoes()
    {
    	// Monta a Consulta básica...
    	$qb = $this->createQueryBuilder('aq')
    				->select('aq', 'aqit', 'sw', 'tpl')
        			->innerJoin('aq.itens', 'aqit')
        			->innerJoin('aqit.idSoftware', 'sw')
        			->innerJoin('aqit.idTipoLicenca', 'tpl')
        			->orderBy('aq.dtAquisicao DESC, aqit.dtVencimentoLicenca');

        return $qb->getQuery()->execute();
    }

}