<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 *
 * Repositório de métodos de consulta em DQL
 * @author lightbase
 *
 */
class GrupoUsuarioRepository extends EntityRepository
{

	public function paginar( $page )
	{

	}

	/**
	 *
	 * Método de listagem dos Grupos de Usuários cadastrados
	 */
	public function listar()
	{
		$_dql = "SELECT g
				FROM CacicCommonBundle:GrupoUsuario g
				GROUP BY g.idGrupoUsuario";

		return $this->getEntityManager()->createQuery( $_dql )->getArrayResult();
	}
	
}