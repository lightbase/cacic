<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;

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
                        'aq.idAquisicao',
                        'tpl.teTipoLicenca',
                        'tpl.idTipoLicenca',
                        'aqit.nmAquisicao',
                        'aqit.qtLicenca',
                        'aqit.dtVencimentoLicenca',
                        'aqit.idAquisicaoItem',
                        'aqit.nmAquisicao',
                        'count(DISTINCT c.idComputador) as nComp'
                    )
        			->innerJoin('aq.itens', 'aqit')
        			->innerJoin('aqit.idTipoLicenca', 'tpl')
                    ->innerJoin('aqit.idSoftwareRelatorio', 'rel')
                    ->innerJoin('rel.softwares', 'sw')
                    ->innerJoin('CacicCommonBundle:PropriedadeSoftware', 'prop', 'WITH', 'sw.idSoftware = prop.software')
                    ->groupBy('aq.nrProcesso',
                        'aq.dtAquisicao',
                        'aq.nmEmpresa',
                        'aq.nmProprietario',
                        'aq.nrNotafiscal',
                        'aq.idAquisicao',
                        'tpl.teTipoLicenca',
                        'tpl.idTipoLicenca',
                        'aqit.nmAquisicao',
                        'aqit.qtLicenca',
                        'aqit.dtVencimentoLicenca',
                        'aqit.idAquisicaoItem',
                        'aqit.nmAquisicao'
                    )
        			->orderBy('aq.dtAquisicao DESC, aqit.dtVencimentoLicenca');

        if (empty($data_inicio) and empty($data_fim)) {
            $qb->innerJoin('CacicCommonBundle:ComputadorColeta', 'comp', 'WITH', "(prop.computador = comp.computador AND prop.classProperty = comp.classProperty)");
            $qb->innerJoin('CacicCommonBundle:Computador', 'c','WITH','prop.computador = c.idComputador');
        } else {
            $qb->innerJoin('CacicCommonBundle:ComputadorColeta', 'comp', 'WITH', "(prop.computador = comp.computador AND prop.classProperty = comp.classProperty AND comp.dtHrInclusao BETWEEN '$data_inicio' AND '$data_fim')");
            $qb->innerJoin('CacicCommonBundle:Computador', 'c','WITH','prop.computador = c.idComputador');
        }

        // Adiciona filtro por computadores ativos
        $qb->andWhere("(c.ativo IS NULL or c.ativo = 't')");

        $result = $qb->getQuery()->getArrayResult();

        // Agora cria um array de saída agrupado pro processo de aquisição
        $saida = array();
        foreach($result as $row) {
            if (!array_key_exists($row['nrProcesso'], $saida)) {
                $saida[$row['nrProcesso']] = array(
                    'nrProcesso' => $row['nrProcesso'],
                    'dtAquisicao' => $row['dtAquisicao'],
                    'nmEmpresa' => $row['nmEmpresa'],
                    'nmProprietario' => $row['nmProprietario'],
                    'nrNotafiscal' => $row['nrNotafiscal'],
                    'idAquisicao'=> $row['idAquisicao']
                );
            }

            if (array_key_exists('itens', $saida[$row['nrProcesso']])) {
                // Adiciona o item no array
                array_push($saida[$row['nrProcesso']]['itens'], array(
                    'idTipoLicenca' => $row['idTipoLicenca'],
                    'teTipoLicenca' => $row['teTipoLicenca'],
                    'qtLicenca' => $row['qtLicenca'],
                    'dtVencimentoLicenca' => $row['dtVencimentoLicenca'],
                    'idAquisicaoItem' => $row['idAquisicaoItem'],
                    'nmAquisicao' => $row['nmAquisicao'],
                    'nComp' => $row['nComp'],
                ));
            } else {
                // Cria um novo array de itens multidimensional
                $saida[$row['nrProcesso']]['itens'] = array(array(
                    'idTipoLicenca' => $row['idTipoLicenca'],
                    'teTipoLicenca' => $row['teTipoLicenca'],
                    'qtLicenca' => $row['qtLicenca'],
                    'dtVencimentoLicenca' => $row['dtVencimentoLicenca'],
                    'idAquisicaoItem' => $row['idAquisicaoItem'],
                    'nmAquisicao' => $row['nmAquisicao'],
                    'nComp' => $row['nComp']
                ));
            }
        }

        // Retorna array processado
        return $saida;
    }

    /**
     * Relatório de licenças que agrupa por processo de aquisição
     *
     * @return array
     */
    public function gerarRelatorioLicencas() {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('id_aquisicao', 'idAquisicao');
        $rsm->addScalarResult('nr_processo', 'nrProcesso');
        $rsm->addScalarResult('nm_proprietario', 'nmProprietario');
        $rsm->addScalarResult('nr_notafiscal', 'nrNotaFiscal');
        $rsm->addScalarResult('nm_empresa', 'nmEmpresa');
        $rsm->addScalarResult('id_tipo_licenca', 'tipoLicenca');
        $rsm->addScalarResult('te_tipo_licenca', 'teTipoLicenca');
        $rsm->addScalarResult('id_aquisicao_item', 'idAquisicaoItem');
        $rsm->addScalarResult('dt_vencimento_licenca', 'dtVencimentoLicenca');
        $rsm->addScalarResult('nm_aquisicao', 'nmAquisicao');
        $rsm->addScalarResult('qt_licenca', 'qtLicenca');
        $rsm->addScalarResult('te_obs', 'teObs');
        $rsm->addScalarResult('n_comp', 'nComp');

        $sql = "
            SELECT sl.id_aquisicao,
              aq.nr_processo,
              aq.nm_proprietario,
              aq.nr_notafiscal,
              aq.dt_aquisicao,
              aq.nm_empresa,
              sl.id_tipo_licenca,
              t.te_tipo_licenca,
              sl.id_aquisicao_item,
              aqit.dt_vencimento_licenca,
              aqit.nm_aquisicao,
              aqit.qt_licenca,
              aqit.te_obs,
              count(DISTINCT sl.id_computador) as n_comp
            FROM software_licencas sl
              INNER JOIN aquisicao aq ON sl.id_aquisicao = aq.id_aquisicao
              INNER JOIN tipo_licenca t ON sl.id_tipo_licenca = t.id_tipo_licenca
              INNER JOIN aquisicao_item aqit ON aqit.id_aquisicao_item = sl.id_aquisicao_item
            WHERE
              (
                sl.comp_ativo = TRUE
                or sl.comp_ativo IS NULL
              )
              AND (
                sl.prop_ativo = TRUE
                or sl.prop_ativo IS NULL
              )
              AND sl.id_aquisicao_item = (
                SELECT sl2.id_aquisicao_item
                FROM software_licencas sl2
                WHERE sl2.id_computador = sl.id_computador
                  AND sl2.id_aquisicao = sl.id_aquisicao
                ORDER BY sl2.data_coleta DESC
                LIMIT 1
              )
            GROUP BY sl.id_aquisicao,
              aq.nr_processo,
              aq.nm_proprietario,
              aq.nr_notafiscal,
              aq.dt_aquisicao,
              aq.nm_empresa,
              sl.id_tipo_licenca,
              sl.id_tipo_licenca,
              t.te_tipo_licenca,
              sl.id_aquisicao_item,
              aqit.dt_vencimento_licenca,
              aqit.nm_aquisicao,
              aqit.qt_licenca,
              aqit.te_obs
            ORDER BY sl.id_aquisicao

        ";

        $query = $this->getEntityManager()->createNativeQuery($sql, $rsm);

        $result =  $query->execute();

        $saida = array();
        foreach($result as $row) {
            if (!array_key_exists($row['nrProcesso'], $saida)) {
                $saida[$row['nrProcesso']] = array(
                    'nrProcesso' => $row['nrProcesso'],
                    'dtAquisicao' => $row['dtAquisicao'],
                    'nmEmpresa' => $row['nmEmpresa'],
                    'nmProprietario' => $row['nmProprietario'],
                    'nrNotafiscal' => $row['nrNotafiscal'],
                    'idAquisicao'=> $row['idAquisicao']
                );
            }

            if (array_key_exists('itens', $saida[$row['nrProcesso']])) {
                // Adiciona o item no array
                array_push($saida[$row['nrProcesso']]['itens'], array(
                    'idTipoLicenca' => $row['idTipoLicenca'],
                    'teTipoLicenca' => $row['teTipoLicenca'],
                    'qtLicenca' => $row['qtLicenca'],
                    'dtVencimentoLicenca' => $row['dtVencimentoLicenca'],
                    'idAquisicaoItem' => $row['idAquisicaoItem'],
                    'nmAquisicao' => $row['nmAquisicao'],
                    'nComp' => $row['nComp'],
                ));
            } else {
                // Cria um novo array de itens multidimensional
                $saida[$row['nrProcesso']]['itens'] = array(array(
                    'idTipoLicenca' => $row['idTipoLicenca'],
                    'teTipoLicenca' => $row['teTipoLicenca'],
                    'qtLicenca' => $row['qtLicenca'],
                    'dtVencimentoLicenca' => $row['dtVencimentoLicenca'],
                    'idAquisicaoItem' => $row['idAquisicaoItem'],
                    'nmAquisicao' => $row['nmAquisicao'],
                    'nComp' => $row['nComp']
                ));
            }
        }

        // Retorna array processado
        return $saida;
    }

    /**
     * Gera relatorio de softwares removidos
     *
     * 1 - Considera removido todos os softwares cadastrados no processo de aquisição que não forem
     * os mais recentes
     *
     * 2 - Vai contar tanto computadores ativos como inativos
     *
     * 3 - Vai contar tanto softwares removidos como ativos
     *
     * @return array
     */
    public function gerarRelatorioRemovidos() {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('id_aquisicao', 'idAquisicao');
        $rsm->addScalarResult('nr_processo', 'nrProcesso');
        $rsm->addScalarResult('nm_proprietario', 'nmProprietario');
        $rsm->addScalarResult('nr_notafiscal', 'nrNotaFiscal');
        $rsm->addScalarResult('nm_empresa', 'nmEmpresa');
        $rsm->addScalarResult('id_tipo_licenca', 'tipoLicenca');
        $rsm->addScalarResult('te_tipo_licenca', 'teTipoLicenca');
        $rsm->addScalarResult('id_aquisicao_item', 'idAquisicaoItem');
        $rsm->addScalarResult('dt_vencimento_licenca', 'dtVencimentoLicenca');
        $rsm->addScalarResult('nm_aquisicao', 'nmAquisicao');
        $rsm->addScalarResult('qt_licenca', 'qtLicenca');
        $rsm->addScalarResult('te_obs', 'teObs');
        $rsm->addScalarResult('n_comp', 'nComp');

        $sql = "
            SELECT sl.id_aquisicao,
              aq.nr_processo,
              aq.nm_proprietario,
              aq.nr_notafiscal,
              aq.dt_aquisicao,
              aq.nm_empresa,
              sl.id_tipo_licenca,
              t.te_tipo_licenca,
              sl.id_aquisicao_item,
              aqit.dt_vencimento_licenca,
              aqit.nm_aquisicao,
              aqit.qt_licenca,
              aqit.te_obs,
              count(DISTINCT sl.id_computador) as n_comp
            FROM software_licencas sl
              INNER JOIN aquisicao aq ON sl.id_aquisicao = aq.id_aquisicao
              INNER JOIN tipo_licenca t ON sl.id_tipo_licenca = t.id_tipo_licenca
              INNER JOIN aquisicao_item aqit ON aqit.id_aquisicao_item = sl.id_aquisicao_item
            WHERE
              (
                sl.comp_ativo = FALSE
              )
              OR (
                sl.prop_ativo = FALSE
              )
              OR sl.id_aquisicao_item <> (
                SELECT sl2.id_aquisicao_item
                FROM software_licencas sl2
                WHERE sl2.id_computador = sl.id_computador
                  AND sl2.id_aquisicao = sl.id_aquisicao
                ORDER BY sl2.data_coleta DESC
                LIMIT 1
              )
            GROUP BY sl.id_aquisicao,
              aq.nr_processo,
              aq.nm_proprietario,
              aq.nr_notafiscal,
              aq.dt_aquisicao,
              aq.nm_empresa,
              sl.id_tipo_licenca,
              sl.id_tipo_licenca,
              t.te_tipo_licenca,
              sl.id_aquisicao_item,
              aqit.dt_vencimento_licenca,
              aqit.nm_aquisicao,
              aqit.qt_licenca,
              aqit.te_obs
            ORDER BY sl.id_aquisicao

        ";

        $query = $this->getEntityManager()->createNativeQuery($sql, $rsm);

        $result =  $query->execute();

        $saida = array();
        foreach($result as $row) {
            if (!array_key_exists($row['nrProcesso'], $saida)) {
                $saida[$row['nrProcesso']] = array(
                    'nrProcesso' => $row['nrProcesso'],
                    'dtAquisicao' => $row['dtAquisicao'],
                    'nmEmpresa' => $row['nmEmpresa'],
                    'nmProprietario' => $row['nmProprietario'],
                    'nrNotafiscal' => $row['nrNotafiscal'],
                    'idAquisicao'=> $row['idAquisicao']
                );
            }

            if (array_key_exists('itens', $saida[$row['nrProcesso']])) {
                // Adiciona o item no array
                array_push($saida[$row['nrProcesso']]['itens'], array(
                    'idTipoLicenca' => $row['idTipoLicenca'],
                    'teTipoLicenca' => $row['teTipoLicenca'],
                    'qtLicenca' => $row['qtLicenca'],
                    'dtVencimentoLicenca' => $row['dtVencimentoLicenca'],
                    'idAquisicaoItem' => $row['idAquisicaoItem'],
                    'nmAquisicao' => $row['nmAquisicao'],
                    'nComp' => $row['nComp'],
                ));
            } else {
                // Cria um novo array de itens multidimensional
                $saida[$row['nrProcesso']]['itens'] = array(array(
                    'idTipoLicenca' => $row['idTipoLicenca'],
                    'teTipoLicenca' => $row['teTipoLicenca'],
                    'qtLicenca' => $row['qtLicenca'],
                    'dtVencimentoLicenca' => $row['dtVencimentoLicenca'],
                    'idAquisicaoItem' => $row['idAquisicaoItem'],
                    'nmAquisicao' => $row['nmAquisicao'],
                    'nComp' => $row['nComp']
                ));
            }
        }

        // Retorna array processado
        return $saida;
    }

    public function licencasComputador($idComputador) {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('id_aquisicao', 'idAquisicao');
        $rsm->addScalarResult('nr_processo', 'nrProcesso');
        $rsm->addScalarResult('nm_proprietario', 'nmProprietario');
        $rsm->addScalarResult('nr_notafiscal', 'nrNotaFiscal');
        $rsm->addScalarResult('nm_empresa', 'nmEmpresa');
        $rsm->addScalarResult('id_tipo_licenca', 'tipoLicenca');
        $rsm->addScalarResult('te_tipo_licenca', 'teTipoLicenca');
        $rsm->addScalarResult('id_aquisicao_item', 'idAquisicaoItem');
        $rsm->addScalarResult('dt_vencimento_licenca', 'dtVencimentoLicenca');
        $rsm->addScalarResult('nm_aquisicao', 'nmAquisicao');
        $rsm->addScalarResult('qt_licenca', 'qtLicenca');
        $rsm->addScalarResult('te_obs', 'teObs');

        $sql = "
            SELECT sl.id_aquisicao,
              aq.nr_processo,
              aq.nm_proprietario,
              aq.nr_notafiscal,
              aq.dt_aquisicao,
              aq.nm_empresa,
              sl.id_tipo_licenca,
              t.te_tipo_licenca,
              sl.id_aquisicao_item,
              aqit.dt_vencimento_licenca,
              aqit.nm_aquisicao,
              aqit.qt_licenca,
              aqit.te_obs,
              sl.id_computador
            FROM software_licencas sl
              INNER JOIN aquisicao aq ON sl.id_aquisicao = aq.id_aquisicao
              INNER JOIN tipo_licenca t ON sl.id_tipo_licenca = t.id_tipo_licenca
              INNER JOIN aquisicao_item aqit ON aqit.id_aquisicao_item = sl.id_aquisicao_item
            WHERE
              (
                sl.comp_ativo = TRUE
                or sl.comp_ativo IS NULL
              )
              AND (
                sl.prop_ativo = TRUE
                or sl.prop_ativo IS NULL
              )
              AND sl.id_aquisicao_item = (
                SELECT sl2.id_aquisicao_item
                FROM software_licencas sl2
                WHERE sl2.id_computador = sl.id_computador
                      AND sl2.id_aquisicao = sl.id_aquisicao
                ORDER BY sl2.data_coleta DESC
                LIMIT 1
              )
              AND sl.id_computador = $idComputador
            GROUP BY sl.id_aquisicao,
              aq.nr_processo,
              aq.nm_proprietario,
              aq.nr_notafiscal,
              aq.dt_aquisicao,
              aq.nm_empresa,
              sl.id_tipo_licenca,
              sl.id_tipo_licenca,
              t.te_tipo_licenca,
              sl.id_aquisicao_item,
              aqit.dt_vencimento_licenca,
              aqit.nm_aquisicao,
              aqit.qt_licenca,
              aqit.te_obs,
              sl.id_computador
            ORDER BY sl.id_aquisicao
        ";

        $query = $this->getEntityManager()->createNativeQuery($sql, $rsm);
        $result = $query->execute();

        // Retorna array processado
        return $result;
    }

    public function removidosComputador($idComputador) {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('id_aquisicao', 'idAquisicao');
        $rsm->addScalarResult('nr_processo', 'nrProcesso');
        $rsm->addScalarResult('nm_proprietario', 'nmProprietario');
        $rsm->addScalarResult('nr_notafiscal', 'nrNotaFiscal');
        $rsm->addScalarResult('nm_empresa', 'nmEmpresa');
        $rsm->addScalarResult('id_tipo_licenca', 'tipoLicenca');
        $rsm->addScalarResult('te_tipo_licenca', 'teTipoLicenca');
        $rsm->addScalarResult('id_aquisicao_item', 'idAquisicaoItem');
        $rsm->addScalarResult('dt_vencimento_licenca', 'dtVencimentoLicenca');
        $rsm->addScalarResult('nm_aquisicao', 'nmAquisicao');
        $rsm->addScalarResult('qt_licenca', 'qtLicenca');
        $rsm->addScalarResult('te_obs', 'teObs');

        $sql = "
            SELECT sl.id_aquisicao,
              aq.nr_processo,
              aq.nm_proprietario,
              aq.nr_notafiscal,
              aq.dt_aquisicao,
              aq.nm_empresa,
              sl.id_tipo_licenca,
              t.te_tipo_licenca,
              sl.id_aquisicao_item,
              aqit.dt_vencimento_licenca,
              aqit.nm_aquisicao,
              aqit.qt_licenca,
              aqit.te_obs,
              sl.id_computador
            FROM software_licencas sl
              INNER JOIN aquisicao aq ON sl.id_aquisicao = aq.id_aquisicao
              INNER JOIN tipo_licenca t ON sl.id_tipo_licenca = t.id_tipo_licenca
              INNER JOIN aquisicao_item aqit ON aqit.id_aquisicao_item = sl.id_aquisicao_item
            WHERE
              (
                (
                  sl.comp_ativo = FALSE
                )
                OR (
                  sl.prop_ativo = FALSE
                )
                OR sl.id_aquisicao_item <> (
                  SELECT sl2.id_aquisicao_item
                  FROM software_licencas sl2
                  WHERE sl2.id_computador = sl.id_computador
                        AND sl2.id_aquisicao = sl.id_aquisicao
                  ORDER BY sl2.data_coleta DESC
                  LIMIT 1
                )
              )
              AND sl.id_computador = $idComputador
            GROUP BY sl.id_aquisicao,
              aq.nr_processo,
              aq.nm_proprietario,
              aq.nr_notafiscal,
              aq.dt_aquisicao,
              aq.nm_empresa,
              sl.id_tipo_licenca,
              sl.id_tipo_licenca,
              t.te_tipo_licenca,
              sl.id_aquisicao_item,
              aqit.dt_vencimento_licenca,
              aqit.nm_aquisicao,
              aqit.qt_licenca,
              aqit.te_obs,
              sl.id_computador
            ORDER BY sl.id_aquisicao
        ";

        $query = $this->getEntityManager()->createNativeQuery($sql, $rsm);
        $result = $query->execute();

        // Retorna array processado
        return $result;
    }

}