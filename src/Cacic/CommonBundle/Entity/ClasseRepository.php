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
	
}