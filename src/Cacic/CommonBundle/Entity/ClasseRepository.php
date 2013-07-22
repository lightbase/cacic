<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * ClasseRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ClasseRepository extends EntityRepository
{
	
	/**
	 * 
	 * Método de listagem das classes de coleta
	 */
	public function listar()
	{
    	$qb = $this->createQueryBuilder('c')
    				->select('c')
        			->orderBy('c.teClassDescription');
        			
        return $qb->getQuery()->execute();
	}

    public function listaDetalhesClasse( $idAcao )
    {
        $_dql = "SELECT c.nmClassName,cp.nmPropertyName,cd.teWhereClause
				 FROM CacicCommonBundle:Classe c,
				 CacicCommonBundle:ClassProperty cp,
				 CacicCommonBundle:CollectDefClass cd
				 WHERE 	cd.idAcao = :idAcao AND
				 c.idClass = cd.idClass AND
				 cp.idClass = cd.idClass
				 ORDER BY c.nmClassName,cp.nmPropertyName";

        return $this->getEntityManager()
        ->createQuery( $_dql )
        ->setParameter( 'idAcao', $idAcao )
        ->getArrayResult();
    }
}
