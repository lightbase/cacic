<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 *
 * Repositório de métodos de consulta em DQL
 * @author lightbase
 *
 */
class UsuarioRepository extends EntityRepository
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
				FROM CacicCommonBundle:Usuario u
				JOIN u.idLocal l
				JOIN u.idGrupoUsuario g
				GROUP BY u.idUsuario";

		return $this->getEntityManager()->createQuery( $_dql )->getArrayResult();
	}
	
	/**
	 * 
	 * Lista os Usuários cadastrados associados a determinado Local
	 * @param integer $idLocal
	 */
	public function listarPorLocal( $idLocal )
	{
		$_dql = "SELECT u, g.teGrupoUsuarios, l.idLocal
				FROM CacicCommonBundle:Usuario u
				JOIN u.idGrupoUsuario g
				JOIN u.idLocal l
				WHERE u.idLocal = :idLocalPrimario OR u.teLocaisSecundarios LIKE :idLocalSecundario";

        return $this->getEntityManager()
        			->createQuery( $_dql )
        			->setParameter( 'idLocalPrimario', $idLocal )
        			->setParameter( 'idLocalSecundario' , "%{$idLocal}%")
        			->getArrayResult();
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
				FROM CacicCommonBundle:Locals l
				WHERE l.idLocal IN ( '. $usuario->getTeLocaisSecundarios() .' )';
		
		return $this->getEntityManager()->createQuery( $_dql )->getArrayResult();
	}
}