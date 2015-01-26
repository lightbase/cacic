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
				GROUP BY l
				ORDER BY l.nmLocal";
		
		return $this->getEntityManager()->createQuery( $_dql )->getArrayResult();
	}

    /**
     * Retorna o último local cadastrado
     *
     * @return mixed
     */

    public function getMaxLocal($local) {
        $_dql = "SELECT l
                FROM CacicCommonBundle:Local l
                WHERE l.idLocal <> :local
                ORDER BY l.idLocal desc
                ";

        return $this->getEntityManager()
            ->createQuery( $_dql )
            ->setMaxResults(1)
            ->setParameter('local', $local)
            ->getSingleResult();
    }

    /**
     * Move os usuários de local
     *
     * @param $local
     */

    public function moveUsuarios($local) {
        $em = $this->getEntityManager();
        $usuarios = $local->getUsuarios();
        $maxLocal = $em->getRepository('CacicCommonBundle:Local')->getMaxLocal($local->getIdLocal());
        // Coloca cada usuário em no último local cadastrado
        foreach ($usuarios as $modificar) {
            $modificar->setIdLocal($maxLocal);
            $em->persist($modificar);
        }
        $em->flush();
    }

    /**
     * Excluir local
     *
     * @param $local
     */

    public function excluirLocal($local) {
        $em = $this->getEntityManager();
        $em->getRepository('CacicCommonBundle:ConfiguracaoLocal')->removerConfiguracoesDoLocal( $local );
        $em->getRepository('CacicCommonBundle:Local')->moveUsuarios( $local );

        $config = $em->getRepository('CacicCommonBundle:PatrimonioConfigInterface')->findBy(array('local' => $local->getIdLocal()));
        foreach ($config as $excluir) {
            $em->remove($excluir);
        }

        $config = $em->getRepository('CacicCommonBundle:UnidOrganizacionalNivel2')->findBy(array('idLocal' => $local->getIdLocal()));
        foreach ($config as $excluir) {
            $em->remove($excluir);
        }

        $config = $em->getRepository('CacicCommonBundle:Rede')->findBy(array('idLocal' => $local->getIdLocal()));
        foreach ($config as $excluir) {
            $excluir->setIdLocal(null);
            $em->persist($excluir);
        }

        $em->flush();
    }

    /*
     * Listar locais para carga no SGConf_PGFN
     */
    public function localSGConf (){

        $_dql = "SELECT l.idLocal, l.nmLocal, l.sgLocal
                 FROM CacicCommonBundle:Local l
                 GROUP BY l";

        return $this->getEntityManager()->createQuery( $_dql )->getArrayResult();
    }
}