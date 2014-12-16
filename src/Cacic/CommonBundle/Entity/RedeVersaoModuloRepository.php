<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 *
 * Repositório de métodos de consulta em DQL
 * @author lightbase
 *
 */
class RedeVersaoModuloRepository extends EntityRepository
{

    /**
     *
     * Método de listagem dos Modulos cadastrados e respectivas informações
     */
    public function listarWindows()
    {
        $_dql = "SELECT r
				FROM CacicCommonBundle:RedeVersaoModulo r
				where r.csTipoSo = 'MS-Windows'
				ORDER BY r.nmModulo ASC";

        return $this->getEntityManager()->createQuery( $_dql )->getArrayResult();
    }
    public function listarLinux()
    {
        $_dql = "SELECT r
				FROM CacicCommonBundle:RedeVersaoModulo r
				where r.csTipoSo = 'GNU/LINUX'
				ORDER BY r.nmModulo ASC";

        return $this->getEntityManager()->createQuery( $_dql )->getArrayResult();
    }
    /*
     * Traz a lista de módulos para a subrede fornecida
     */
    public function subrede($id = null, $modulo = null)
    {
        $qb = $this->createQueryBuilder('r')
            ->select('red.idRede',
                'r.nmModulo',
                'r.teVersaoModulo',
                'r.teHash',
                'red.teIpRede',
                'red.nmRede',
                'red.teServUpdates',
                'red.tePathServUpdates',
                'l.nmLocal')
            ->innerJoin('CacicCommonBundle:Rede', 'red', 'WITH', 'red.idRede = r.idRede')
            ->innerJoin('CacicCommonBundle:Local', 'l', 'WITH', 'red.idLocal = l.idLocal')
            ->groupBy('r', 'l', 'red')
            ->orderBy('red.nmRede');

        // Adiciona filtro por módulo se fornecido
        if ($modulo != null) {
            // Aqui trago somente a lista de todos os módulos naquela subrede
            $qb->andWhere('r.nmModulo = :modulo')->setParameter('modulo', $modulo);
        }

        // Adiciona filtro por subrede se fornecido
        if ($id != null) {
            // Somente os módulos desa subrede
            $qb->andWhere('r.idRede = :id')->setParameter('id', $id);
        }

        return $qb->getQuery()->getArrayResult();
    }

}