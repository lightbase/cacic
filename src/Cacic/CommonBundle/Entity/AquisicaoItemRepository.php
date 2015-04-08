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
                    LEFT JOIN item.idSoftware software
                    LEFT JOIN item.idAquisicao aquisicao
                    LEFT JOIN item.idTipoLicenca tipoLicenca
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
            ->andWhere('aqit.idAquisicao = :idAquisicao')
            ->andWhere('aqit.idTipoLicenca = :idTipoLicenca')
            ->andWhere("comp.ativo IS NULL or comp.ativo = 't'")
            ->setParameter('idAquisicao', $idAquisicao)
            ->setParameter('idTipoLicenca', $idTipoLicenca);

        return $qb->getQuery()->getResult();

    }
}