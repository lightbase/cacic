<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 *
 * Reposit�rio de m�todos de consulta em DQL
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
     * M�todo de listagem dos Usu�rios cadastrados e respectivas informa��es de Login, Nome, Locais e N�veis de acesso
     */
    public function listar()
    {
        $_dql = "SELECT u, l.nmLocal, g.teGrupoUsuarios
                FROM CacicCommonBundle:Usuarios u
                JOIN u.localPrimario l
                JOIN u.grupo g
                GROUP BY u.idUsuario";

        $query = $this->getEntityManager()->createQuery( $_dql );
        return $query->getArrayResult();

    }

    public function trocarsenha()
    {

    }

}