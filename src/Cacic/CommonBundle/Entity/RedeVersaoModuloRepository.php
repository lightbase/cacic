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
    public function subrede()
    {
        $_dql = "SELECT r.nmModulo, r.teVersaoModulo, r.teHash, l.sgLocal, red.teIpRede, red.nmRede, red.teServUpdates
				FROM CacicCommonBundle:RedeVersaoModulo r
				Left join r.idRede red
			    Left join red.idLocal l
			    GROUP BY r, l, red
				ORDER BY red.nmRede ASC";

        return $this->getEntityManager()->createQuery( $_dql )->getArrayResult();
    }
}