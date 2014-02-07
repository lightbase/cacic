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
    public function paginar( \Knp\Component\Pager\Paginator $paginator, $page = 1 )
    {
        $_dql = "SELECT g
				FROM CacicCommonBundle:GrupoUsuario g";

        return $paginator->paginate(
            $this->getEntityManager()->createQuery( $_dql )->getArrayResult(),
            $page,
            10
        );
    }

	/**
	 *
	 * Método de listagem dos Grupos de Usuários cadastrados
	 */
	public function listar()
	{
		$_dql = "SELECT g.idGrupoUsuario, g.teDescricaoGrupo
				FROM CacicCommonBundle:GrupoUsuario g
				GROUP BY g.idGrupoUsuario, g.teDescricaoGrupo";

		return $this->getEntityManager()->createQuery( $_dql )->getArrayResult();
	}
    public function nivel($grupoUsuario){
        $_dql = "SELECT  g.teGrupoUsuarios
				FROM CacicCommonBundle:GrupoUsuario g
				WHERE g.idGrupoUsuario = :idGrupoUsuario
				GROUP BY g.teGrupoUsuarios";

        return $this->getEntityManager()
            ->createQuery( $_dql )
            ->setParameter( 'idGrupoUsuario', $grupoUsuario )
            ->getArrayResult();
    }
}