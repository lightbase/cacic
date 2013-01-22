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
	 * Método de listagem dos Locais cadastrados e respectivas informações de usuários primários e secundários
	 */
	public function listar()
	{
		$_dql = "SELECT l, COUNT(u.idUsuario) AS numUsuariosPrimarios
				FROM CacicCommonBundle:Locais l
				LEFT JOIN l.usuariosPrimarios u
				GROUP BY l.idLocal";
		
        $query = $this->getEntityManager()->createQuery( $_dql );
        return $query->getArrayResult();
		
	}
	
}