<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * LogAcessoRepository
 *
 * Métodos de repositório
 */
class LogUserLogadoRepository extends EntityRepository
{
    /**
     * Função que retorna o último acesso para o computador solicitado
     *
     * @param $computador
     */
    public function ultimoAcesso( $computador ) {
        $qb = $this->createQueryBuilder('acesso')
            ->select('acesso')
            ->where('acesso.idComputador = :computador')
            ->orderBy('acesso.data', 'desc')
            ->setMaxResults(1)
            ->setParameter('computador', $computador );

        return $qb->getQuery()->getOneOrNullResult();
    }

}
