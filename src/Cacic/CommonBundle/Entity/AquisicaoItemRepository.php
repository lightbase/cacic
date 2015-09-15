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
class AquisicaoItemRepository extends EntityRepository
{

    public function paginar( \Knp\Component\Pager\Paginator $paginator, $page = 1 )
    {
        $_dql = "SELECT item, aquisicao, software, tipoLicenca
                    FROM CacicCommonBundle:AquisicaoItem item
                    LEFT JOIN item.idSoftwareRelatorio software
                    LEFT JOIN item.idAquisicao aquisicao
                    LEFT JOIN item.idTipoLicenca tipoLicenca
                    ORDER BY aquisicao.nrProcesso
				";

        return $paginator->paginate(
            $this->getEntityManager()->createQuery( $_dql )->getArrayResult(),
            $page,
            10
        );
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

    /**
     * Lista detalhada de itens adquiridos
     *
     * @param $idAquisicao
     * @param $idTipoLicenca
     * @return array
     */
    public function aquisicoesDetalhado($idAquisicaoItem) {

        $qb = $this->createQueryBuilder('aqit')
            ->select('DISTINCT comp', 'aqit')
            ->innerJoin('aqit.idSoftwareRelatorio', 'rel')
            ->innerJoin('rel.softwares', 'sw')
            ->innerJoin('CacicCommonBundle:PropriedadeSoftware', 'prop', 'WITH', 'sw.idSoftware = prop.software')
            ->innerJoin('CacicCommonBundle:ComputadorColeta', 'c', 'WITH', "(prop.computador = c.computador AND prop.classProperty = c.classProperty)")
            ->innerJoin('CacicCommonBundle:Computador', 'comp', 'WITH', 'c.computador = comp.idComputador')
            ->innerJoin('CacicCommonBundle:So', 'so', 'WITH', 'comp.idSo = so.idSo')
            ->andWhere('aqit.idAquisicaoItem = :idAquisicaoItem')
            ->andWhere("comp.ativo IS NULL or comp.ativo = 't'")
            ->setParameter('idAquisicaoItem', $idAquisicaoItem);

        return $qb->getQuery()->getResult();

    }

    /**
     * Relatório detalhado de aquisições
     *
     * @param $idAquisicaoItem
     * @return mixed
     */
    public function aquisicoesDetalhadoCsv($idAquisicaoItem) {

        $qb = $this->createQueryBuilder('aqit')
            ->select('DISTINCT comp.idComputador',
                'comp.nmComputador',
                'comp.teIpComputador',
                'comp.teNodeAddress',
                'so.teDescSo',
                'l.nmLocal',
                'rede.nmRede',
                'comp.dtHrUltAcesso'
            )
            ->innerJoin('aqit.idSoftwareRelatorio', 'rel')
            ->innerJoin('rel.softwares', 'sw')
            ->innerJoin('CacicCommonBundle:PropriedadeSoftware', 'prop', 'WITH', 'sw.idSoftware = prop.software')
            ->innerJoin('CacicCommonBundle:ComputadorColeta', 'c', 'WITH', "(prop.computador = c.computador AND prop.classProperty = c.classProperty)")
            ->innerJoin('CacicCommonBundle:Computador', 'comp', 'WITH', 'c.computador = comp.idComputador')
            ->innerJoin('CacicCommonBundle:So', 'so', 'WITH', 'comp.idSo = so.idSo')
            ->innerJoin('CacicCommonBundle:Rede', 'rede', 'WITH', 'comp.idRede = rede.idRede')
            ->innerJoin('CacicCommonBundle:Local', 'l', 'WITH', 'rede.idLocal = l.idLocal')
            ->andWhere('aqit.idAquisicaoItem = :idAquisicaoItem')
            ->andWhere("comp.ativo IS NULL or comp.ativo = 't'")
            ->setParameter('idAquisicaoItem', $idAquisicaoItem);

        return $qb->getQuery()->execute();

    }

    /**
     * Relatório de softwares licenciados que agrupa pela última ocorrẽncia no processo de aquisição
     *
     * @param $idAquisicaoItem
     * @return mixed
     */
    public function licencasDetalhado($idAquisicaoItem) {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('id_computador', 'idComputador');
        $rsm->addScalarResult('nm_computador', 'nmComputador');
        $rsm->addScalarResult('te_ip_computador', 'teIpComputador');
        $rsm->addScalarResult('te_node_address', 'teNodeAddress');
        $rsm->addScalarResult('te_desc_so', 'teDescSo');
        $rsm->addScalarResult('id_so', 'idSo');
        $rsm->addScalarResult('nm_local', 'nmLocal');
        $rsm->addScalarResult('nm_rede', 'nmRede');
        $rsm->addScalarResult('dt_hr_ult_acesso', 'dtHrUltAcesso');
        $rsm->addScalarResult('nm_aquisicao', 'nmAquisicao');
        $rsm->addScalarResult('id_aquisicao_item', 'idAquisicaoItem');
        $rsm->addScalarResult('id_aquisicao', 'idAquisicao');
        $rsm->addScalarResult('te_tipo_licenca', 'teTipoLicenca');
        $rsm->addScalarResult('nr_processo', 'nrProcesso');

        $sql = "
            SELECT comp.id_computador,
              comp.nm_computador,
              comp.te_ip_computador,
              comp.te_node_address,
              so.te_desc_so,
              so.id_so,
              l.nm_local,
              rede.nm_rede,
              comp.dt_hr_ult_acesso,
              aqit.nm_aquisicao,
              sl.id_aquisicao_item,
              aq.nr_processo,
              t.te_tipo_licenca,
              sl.id_aquisicao
            FROM software_licencas sl
              INNER JOIN computador comp ON sl.id_computador = comp.id_computador
              INNER JOIN so ON so.id_so = comp.id_so
              INNER JOIN rede ON rede.id_rede = comp.id_rede
              INNER JOIN local l ON l.id_local = rede.id_local
              INNER JOIN aquisicao aq ON sl.id_aquisicao = aq.id_aquisicao
              INNER JOIN aquisicao_item aqit ON aqit.id_aquisicao_item = sl.id_aquisicao_item
              INNER JOIN tipo_licenca t ON sl.id_tipo_licenca = t.id_tipo_licenca
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
                ORDER BY sl2.data_coleta DESC
                LIMIT 1
              )
              AND sl.id_aquisicao_item = $idAquisicaoItem
            GROUP BY comp.id_computador,
              comp.nm_computador,
              comp.te_ip_computador,
              comp.te_node_address,
              so.te_desc_so,
              so.id_so,
              l.nm_local,
              rede.nm_rede,
              comp.dt_hr_ult_acesso,
              aqit.nm_aquisicao,
              sl.id_aquisicao_item,
              aq.nr_processo,
              t.te_tipo_licenca,
              sl.id_aquisicao,
              sl.prop_ativo,
              sl.comp_ativo
        ";

        $query = $this->getEntityManager()->createNativeQuery($sql, $rsm);

        $result =  $query->execute();

        return $result;
    }


    public function licencasDetalhadoCsv($idAquisicaoItem) {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('id_computador', 'idComputador');
        $rsm->addScalarResult('nm_computador', 'nmComputador');
        $rsm->addScalarResult('te_ip_computador', 'teIpComputador');
        $rsm->addScalarResult('te_node_address', 'teNodeAddress');
        $rsm->addScalarResult('te_desc_so', 'teDescSo');
        $rsm->addScalarResult('id_so', 'idSo');
        $rsm->addScalarResult('nm_local', 'nmLocal');
        $rsm->addScalarResult('nm_rede', 'nmRede');
        $rsm->addScalarResult('dt_hr_ult_acesso', 'dtHrUltAcesso');

        $sql = "
            SELECT comp.id_computador,
              comp.nm_computador,
              comp.te_ip_computador,
              comp.te_node_address,
              so.te_desc_so,
              l.nm_local,
              rede.nm_rede,
              comp.dt_hr_ult_acesso
            FROM software_licencas sl
              INNER JOIN computador comp ON sl.id_computador = comp.id_computador
              INNER JOIN so ON so.id_so = comp.id_so
              INNER JOIN rede ON rede.id_rede = comp.id_rede
              INNER JOIN local l ON l.id_local = rede.id_local
              INNER JOIN aquisicao aq ON sl.id_aquisicao = aq.id_aquisicao
              INNER JOIN aquisicao_item aqit ON aqit.id_aquisicao_item = sl.id_aquisicao_item
              INNER JOIN tipo_licenca t ON sl.id_tipo_licenca = t.id_tipo_licenca
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
                ORDER BY sl2.data_coleta DESC
                LIMIT 1
              )
              AND sl.id_aquisicao_item = $idAquisicaoItem
            GROUP BY comp.id_computador,
              comp.nm_computador,
              comp.te_ip_computador,
              comp.te_node_address,
              so.te_desc_so,
              so.id_so,
              l.nm_local,
              rede.nm_rede,
              comp.dt_hr_ult_acesso,
              aqit.nm_aquisicao,
              sl.id_aquisicao_item,
              aq.nr_processo,
              t.te_tipo_licenca,
              sl.id_aquisicao,
              sl.prop_ativo,
              sl.comp_ativo
        ";

        $query = $this->getEntityManager()->createNativeQuery($sql, $rsm);

        $result =  $query->getArrayResult();

        return $result;
    }
}