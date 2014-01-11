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

    public function paginar( \Knp\Component\Pager\Paginator $paginator, $page = 1 )
    {
        $_dql = "SELECT u, l.nmLocal, g.nmGrupoUsuarios, COUNT(ls.idLocal) as numLocSec
				FROM CacicCommonBundle:Usuario u
				JOIN u.idLocal l
				JOIN u.idGrupoUsuario g
				LEFT JOIN u.locaisSecundarios ls
				GROUP BY u, l.nmLocal, g.nmGrupoUsuarios";

        return $paginator->paginate(
            $this->getEntityManager()->createQuery( $_dql ),
            $page,
            10
        );
    }
	/**
	 *
	 * Método de listagem dos Usuários cadastrados e respectivas informações de Login, Nome, Locais e Níveis de acesso
	 */
	public function listar()
	{
		$_dql = "SELECT u, l.nmLocal, g.teGrupoUsuarios, COUNT(ls.idLocal) as numLocSec
				FROM CacicCommonBundle:Usuario u
				JOIN u.idLocal l
				JOIN u.idGrupoUsuario g
				LEFT JOIN u.locaisSecundarios ls
				GROUP BY u, l.nmLocal, g.teGrupoUsuarios";

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
				LEFT JOIN u.locaisSecundarios ls
				WHERE u.idLocal = :idLocalPrimario OR ls = :idLocalSecundario";

        return $this->getEntityManager()
        			->createQuery( $_dql )
        			->setParameter( 'idLocalPrimario', $idLocal )
        			->setParameter( 'idLocalSecundario' , $idLocal )
        			->getArrayResult();
	}

	public function trocarSenha()
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