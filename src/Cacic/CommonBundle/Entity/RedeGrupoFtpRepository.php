<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\EntityRepository;


class RedeGrupoFtpRepository extends EntityRepository
{
    public function countRedeGrupoFtp( $idRede )
    {
        $_dql = "SELECT count(r)
				FROM CacicCommonBundle:RedeGrupoFtp r
				WHERE r.idRede = :idRede";

        return $this->getEntityManager()->createQuery( $_dql )->getSingleScalarResult();
    }
}