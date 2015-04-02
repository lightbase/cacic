<?php
/**
 * Created by PhpStorm.
 * User: Bruno Noronha
 * Date: 19/03/15
 * Time: 11:36
 */

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\EntityRepository;

class TeSoRepository extends EntityRepository
{
    /**
     * Se for menor que a versão 3.X, busca na coluna te_so_28
     */
    public function teso28($te_so)
    {
        $query = $this->createQueryBuilder('teso')
            ->select('teso.teSo28')
            ->Where("teso.teSo31 LIKE '$te_so'");

        return $query->getQuery()->execute();

    }

    /**
     * Se for maior, busca na coluna te_so_31
     */
    public function teso31($te_so)
    {
        $query = $this->createQueryBuilder('teso')
            ->select('teso.teSo31')
            ->Where("teso.teSo28 LIKE '$te_so'");

        return $query->getQuery()->execute();

    }

}
