<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * 
 * Repositório de métodos de consulta em DQL
 * @author lightbase
 *
 */
class LocalRepository extends EntityRepository
{
	
	public function paginar( \Knp\Component\Pager\Paginator $paginator, $page = 1 )
	{
		$_dql = "SELECT l, COUNT(u.idUsuario) AS numUsuariosPrimarios, COUNT(r.idRede) as numRedes
				FROM CacicCommonBundle:Local l
				LEFT JOIN l.usuarios u
				LEFT JOIN l.redes r
				GROUP BY l";
		
		return $paginator->paginate(
			$this->getEntityManager()->createQuery( $_dql ),
			$page,
			10
		);
	}
	
	/**
	 * 
	 * Método de listagem dos Locais cadastrados e respectivas informações de usuários primários e secundários e redes associadas
	 */
	public function listar()
	{
		$_dql = "SELECT l, COUNT(u.idUsuario) AS numUsuariosPrimarios, COUNT(r.idRede) as numRedes
				FROM CacicCommonBundle:Local l
				LEFT JOIN l.usuarios u
				LEFT JOIN l.redes r
				GROUP BY l";
		
		return $this->paginar()->getArrayResult();
	}
	
}