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
				GROUP BY a";

        return $paginator->paginate(
            $this->getEntityManager()->createQuery( $_dql )->getArrayResult(),
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
    				->select('aq.nrProcesso',
                        'aq.dtAquisicao',
                        'aq.nmEmpresa',
                        'aq.nmProprietario',
                        'aq.nrNotafiscal',
                        'tpl.teTipoLicenca',
                        'aqit.qtLicenca',
                        'aqit.dtVencimentoLicenca',
                        'count(DISTINCT comp.computador) as nComp'
                    )
        			->innerJoin('aq.itens', 'aqit')
        			->innerJoin('aqit.idTipoLicenca', 'tpl')
                    ->innerJoin('aqit.idSoftware', 'sw')
                    ->innerJoin('CacicCommonBundle:PropriedadeSoftware', 'prop', 'WITH', 'sw.idSoftware = prop.software')
                    ->groupBy('aq.nrProcesso',
                        'aq.dtAquisicao',
                        'aq.nmEmpresa',
                        'aq.nmProprietario',
                        'aq.nrNotafiscal',
                        'tpl.teTipoLicenca',
                        'aqit.qtLicenca',
                        'aqit.dtVencimentoLicenca'
                    )
        			->orderBy('aq.dtAquisicao DESC, aqit.dtVencimentoLicenca');

        if (empty($data_inicio) and empty($data_fim)) {
            $qb->innerJoin('CacicCommonBundle:ComputadorColeta', 'comp', 'WITH', "(prop.computador = comp.computador AND prop.classProperty = comp.classProperty)");
        } else {
            $qb->innerJoin('CacicCommonBundle:ComputadorColeta', 'comp', 'WITH', "(prop.computador = comp.computador AND prop.classProperty = comp.classProperty AND comp.dt_hr_inclusao BETWEEN '$data_inicio' AND '$data_fim')");
        }

        $result = $qb->getQuery()->getArrayResult();

        // Agora cria um array de saída agrupado pro process de aquisição
        $saida = array();
        foreach($result as $row) {
            if (!array_key_exists($row['nrProcesso'], $saida)) {
                $saida[$row['nrProcesso']] = array(
                    'nrProcesso' => $row['nrProcesso'],
                    'dtAquisicao' => $row['dtAquisicao'],
                    'nmEmpresa' => $row['nmEmpresa'],
                    'nmProprietario' => $row['nmProprietario'],
                    'nrNotaFiscal' => $row['nrNotaFiscal'],
                );
            }

            if (array_key_exists('itens', $saida[$row['nrProcesso']])) {
                // Adiciona o item no array
                array_push($saida[$row['nrProcesso']]['itens'], array(
                    'teTipoLicenca' => $row['teTipoLicenca'],
                    'qtLicenca' => $row['qtLicenca'],
                    'dtVencimentoLicenca' => $row['dtVencimentoLicenca'],
                    'nComp' => $row['nComp']
                ));
            } else {
                // Cria um novo array de itens multidimensional
                $saida[$row['nrProcesso']]['itens'] = array(array(
                    'teTipoLicenca' => $row['teTipoLicenca'],
                    'qtLicenca' => $row['qtLicenca'],
                    'dtVencimentoLicenca' => $row['dtVencimentoLicenca'],
                    'nComp' => $row['nComp']
                ));
            }
        }

        // Retorna array processado
        return $saida;
    }

}