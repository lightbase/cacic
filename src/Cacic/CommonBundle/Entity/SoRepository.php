<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 *
 * Repositório de métodos de consulta em DQL
 * @author lightbase
 *
 */
class SoRepository extends EntityRepository
{
    public function paginar( \Knp\Component\Pager\Paginator $paginator, $page = 1 )
    {
        $_dql = "SELECT s
				FROM CacicCommonBundle:So s
				ORDER BY s.teDescSo";

        return $paginator->paginate(
            $this->getEntityManager()->createQuery( $_dql ),
            $page,
            10
        );
    }

    /**
     *
     * Método de listagem dos Tipo de Software cadastrados e respectivas informações
     */
    public function listar()
    {
        $_dql = "SELECT s
				FROM CacicCommonBundle:So s
				ORDER BY s.teDescSo";

        return $this->getEntityManager()->createQuery( $_dql )->getArrayResult();
    }

    public function createIfNotExist( $te_so )
    {
        $so = $this->findBy( array ( 'teSo' => $te_so ) );
        if( empty( $so ) )
        {
            $so = new So();
            $so->setTeSo($te_so);
            $so->setSgSo("Sigla a Cadastrar");
            $so->setTeDescSo("S.O. a Cadastrar");
            $so->setInMswindows("S");
            $this->getEntityManager()->persist( $so );

            $this->getEntityManager()->flush();

	} else {
	    // Return first element, which should be SO object
	    $so = $so[0];
	}

        return $so;

    }
}
