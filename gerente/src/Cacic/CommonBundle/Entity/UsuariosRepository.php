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

        $query = $this->getEntityManager()->createQuery( $_dql );
        return $query->getArrayResult();

    }

    public function trocarsenha()
    {

    }

}