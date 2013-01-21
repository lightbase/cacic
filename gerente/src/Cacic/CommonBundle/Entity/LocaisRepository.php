<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * 
 * Repositório de métodos de consulta em DQL
 * @author lightbase
 *
 */
class LocaisRepository extends EntityRepository
{
	
	public function paginar( $page )
	{
		
	}
	
	/**
	 * 
	 * 
	 */
	public function listar()
	{
		$_dql = "SELECT l.*
				FROM Locais as l";
        $query = $this->getEntityManager()->createQuery( $_dql );
        return $query->getArrayResult();
	}
	
}