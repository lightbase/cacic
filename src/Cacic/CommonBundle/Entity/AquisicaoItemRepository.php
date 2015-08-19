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
    public function aquisicoesDetalhado($idAquisicao, $idTipoLicenca) {

        $qb = $this->createQueryBuilder('aqit')
            ->select('DISTINCT comp', 'aqit')
            ->innerJoin('aqit.idSoftware', 'sw')
            ->innerJoin('CacicCommonBundle:PropriedadeSoftware', 'prop', 'WITH', 'sw.idSoftware = prop.software')
            ->innerJoin('CacicCommonBundle:ComputadorColeta', 'c', 'WITH', "(prop.computador = c.computador AND prop.classProperty = c.classProperty)")
            ->innerJoin('CacicCommonBundle:Computador', 'comp', 'WITH', 'c.computador = comp.idComputador')
            ->innerJoin('CacicCommonBundle:So', 'so', 'WITH', 'comp.idSo = so.idSo')
            ->andWhere('aqit.idAquisicao = :idAquisicao')
            ->andWhere('aqit.idTipoLicenca = :idTipoLicenca')
            ->andWhere("comp.ativo IS NULL or comp.ativo = 't'")
            ->setParameter('idAquisicao', $idAquisicao)
            ->setParameter('idTipoLicenca', $idTipoLicenca);

        return $qb->getQuery()->getResult();

    }

    public function aquisicoesDetalhadoCsv($idAquisicao, $idTipoLicenca) {

        $qb = $this->createQueryBuilder('aqit')
            ->select('DISTINCT comp.idComputador', 'comp.nmComputador', 'comp.teIpComputador', 'comp.teNodeAddress', 'so.teDescSo', 'l.nmLocal', 'rede.nmRede', 'comp.dtHrUltAcesso')
            ->innerJoin('aqit.idSoftware', 'sw')
            ->innerJoin('CacicCommonBundle:PropriedadeSoftware', 'prop', 'WITH', 'sw.idSoftware = prop.software')
            ->innerJoin('CacicCommonBundle:ComputadorColeta', 'c', 'WITH', "(prop.computador = c.computador AND prop.classProperty = c.classProperty)")
            ->innerJoin('CacicCommonBundle:Computador', 'comp', 'WITH', 'c.computador = comp.idComputador')
            ->innerJoin('CacicCommonBundle:So', 'so', 'WITH', 'comp.idSo = so.idSo')
            ->innerJoin('CacicCommonBundle:Rede', 'rede', 'WITH', 'comp.idRede = rede.idRede')
            ->innerJoin('CacicCommonBundle:Local', 'l', 'WITH', 'rede.idLocal = l.idLocal')
            ->andWhere('aqit.idAquisicao = :idAquisicao')
            ->andWhere('aqit.idTipoLicenca = :idTipoLicenca')
            ->andWhere("comp.ativo IS NULL or comp.ativo = 't'")
            ->setParameter('idAquisicao', $idAquisicao)
            ->setParameter('idTipoLicenca', $idTipoLicenca);

        return $qb->getQuery()->execute();

    }
}