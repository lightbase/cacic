<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 *
 * Repositório de métodos de consulta em DQL
 * @author lightbase
 *
 */
class UsuariosRepository extends EntityRepository
{

	public function paginar( $page )
	{

	}

	/**
	 *
	 * Método de listagem dos Usuários cadastrados e respectivas informações de Login, Nome, Locais e Níveis de acesso
	 */
	public function listar()
	{
		$_dql = "SELECT u, l.nmLocal, g.teGrupoUsuarios
				FROM CacicCommonBundle:Usuarios u
				JOIN u.localPrimario l
				JOIN u.grupo g
				GROUP BY u.idUsuario";

		return $this->getEntityManager()->createQuery( $_dql )->getArrayResult();
	}

	public function trocarsenha()
	{

	}

	public function gerarsenha()
	{

	}
	
	/**
	 * 
	 * Recupera os locais secundários associados ao usuário
	 * @param Usuarios $usuario
	 */
	public function getLocaisSecundarios( Usuarios $usuario )
	{
		$_dql = 'SELECT l
				FROM CacicCommonBundle:Locais l
				WHERE l.idLocal IN ( '. $usuario->getTeLocaisSecundarios() .' )';
		
		return $this->getEntityManager()->createQuery( $_dql )->getArrayResult();
	}
}