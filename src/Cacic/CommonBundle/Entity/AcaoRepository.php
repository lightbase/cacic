<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 *
 * Repositório de métodos de consulta em DQL
 * @author lightbase
 *
 */
class AcaoRepository extends EntityRepository
{

	/**
	 *
	 * Lista as Ações opcionais (cs_opcional=S)
	 * @param int $idLocal
	 */
	public function listarModulosOpcionais( $idLocal = null )
	{
		// Monta a Consulta básica...
    	$query = $this->createQueryBuilder('acao')
            ->select('acao', 'COUNT(acao_rede.rede) AS totalRedesAtivadas')
            ->leftJoin('acao.redes', 'acao_rede')
            ->andWhere("acao.ativo IS NULL or acao.ativo = 't'")
            ->groupBy('acao');

        if ( $idLocal !== null )
        {
            $query->leftJoin('acao_rede.rede', 'rede')
                    ->leftJoin('rede.idLocal', 'local')
                    ->andWhere( 'local.idLocal = :idLocal OR local.idLocal IS NULL' )
                    ->setParameter( 'idLocal', $idLocal );
        }


        return $query->getQuery()->execute();
	}

    public function listaAcaoRedeComputador( $idRede, $idSo )
    {
        // Monta a Consulta básica...
        $_dql = "SELECT DISTINCT
                a.idAcao,
                a.teNomeCurtoModulo,
                a.teDescricaoBreve,
                ar.dtHrColetaForcada
                FROM CacicCommonBundle:Acao a
                INNER JOIN a.so aso
                INNER JOIN aso.so so
                INNER JOIN a.redes ar
                INNER JOIN ar.rede r
                WHERE r.idRede = :idRede
                AND so.idSo = :idSo";

        return $this->getEntityManager()
            ->createQuery( $_dql )
            ->setParameters( array('idRede'=>$idRede, 'idSo'=>$idSo) )
            ->getArrayResult();
    }

    public function listaAcaoComputador( $idRede, $idSo, $te_node_address )
    {
        // Monta a Consulta básica...
        $_dql = "SELECT DISTINCT
                a.idAcao,
                a.teNomeCurtoModulo,
                a.teDescricaoBreve,
                ar.dtHrColetaForcada,
                aso as acaoExcecao
                FROM CacicCommonBundle:Acao a
                INNER JOIN a.redes ar
                INNER JOIN ar.rede r
                LEFT JOIN CacicCommonBundle:AcaoSo aso WITH (a.idAcao = aso.acao AND aso.so = :idSo)
                LEFT JOIN CacicCommonBundle:AcaoExcecao e
                  WITH (e.acao = a.idAcao AND e.rede = :idRede AND e.teNodeAddress = :te_node_address)
                WHERE r.idRede = :idRede
                AND e.acao IS NULL
                AND (a.ativo = 't' OR a.ativo IS NULL)";

        return $this->getEntityManager()
            ->createQuery( $_dql )
            ->setParameters(array(
                'idRede' => $idRede,
                'idSo' => $idSo,
                'te_node_address' => $te_node_address
            ))
            ->getArrayResult();
    }

    public function listarModulosInativos() {
        $query = $this->createQueryBuilder('acao')->select('acao', 'COUNT(acao_rede.rede) AS totalRedesAtivadas')
            ->leftJoin('acao.redes', 'acao_rede')
            ->andWhere("acao.ativo = 'f'")
            ->groupBy('acao');

        return $query->getQuery()->execute();
    }

    /**
     * Encontra ação se estiver ativa
     *
     * @param $acao idAcao a ser procurado
     * @param bool|true $opcionais Se deve ser buscado somente os opcionais ou não
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findAcaoAtiva($acao, $opcionais = true) {
        $qb = $this->createQueryBuilder('acao')
            ->andWhere("acao.ativo = 't' OR acao.ativo IS NULL")
            ->andWhere("acao.idAcao = :acao")
            ->setParameter('acao', $acao);

        if (!empty($opcionais) && $opcionais == true) {
            $qb->andWhere("acao.csOpcional = 'S'");
        }

        return $qb->getQuery()->getOneOrNullResult();

    }
};