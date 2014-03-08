<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 *
 * Repositório de métodos de consulta em DQL
 * @author lightbase
 *
 */
class PatrimonioConfigInterfaceRepository extends EntityRepository
{
/*
* Método de listagem dos Patrimonio de conf de interface cadastrados e respectivas informações
*/
    public function listar()
    {
        $_dql = "SELECT pci.teEtiqueta, pci.idEtiqueta
				FROM CacicCommonBundle:PatrimonioConfigInterface pci
				ORDER BY  pci.idEtiqueta ASC";

        return $this->getEntityManager()->createQuery( $_dql )->getArrayResult();
    }

    /**
     *
     * Método de listagem das OPÇÕES DE CONFIGURAÇÃO DE INTERFACE de dado Local
     * @param int $idLocal
     */
    public function listarPorLocal( $idLocal )
    {
        $_dql = "SELECT pci.inDestacarDuplicidade
				FROM CacicCommonBundle:PatrimonioConfigInterface pci
				WHERE pci.idLocal = :idLocal";

        $query = $this->getEntityManager()->createQuery( $_dql )->setParameter('idLocal', $idLocal);
        return $query->getArrayResult();
    }
    
    /**
     * 
     * Recupera um array com as OPÇÕES DE CONFIGURAÇÃO DO APP COLETOR DE INFORMAÇÕES DE PATRIMÔNIO
     * @param int|Cacic\CommonBundle\Entity\Local $local
     */
    public function getOpcoesDestaqueDuplicidade( $local = null )
    {
    	$query = $this->createQueryBuilder( 'pci' )
    					->select( 'pci.idEtiqueta', 'pci.teEtiqueta', 'pci.inDestacarDuplicidade' )
    					->join('pci.local', 'l')
    					->where('pci.inDestacarDuplicidade IS NOT NULL');
    					
    	if ( null !== $local ) $query->andWhere( 'l.idLocal = :idLocal' )->setParameter('idLocal', $local);
    	
    	return $query->getQuery()->execute();
    }
    
    /**
     * 
     * Configura as etiquetas para destacar duplicidade ou não
     * @param array $opcoesDestacar
     * @param int|Cacic\CommonBundle\Entity\Local $local
     */
    public function atualizarOpcoesDestacarDuplicidade( $opcoesDestacar, $local )
    {
    	$_opcoes = $this->getOpcoesDestaqueDuplicidade( $local ); // Recupera todas as etiquetas
    	
    	foreach ( $_opcoes as $conf )
    	{ // Verifica se cada Etiqueta está na lista de etiquetas a destacar, marcando 'S' se estiver e 'N' caso não esteja
    		$conf = $this->find( array( 'idEtiqueta'=> $conf['idEtiqueta'], 'local' => $local ) );
    		$conf->setInDestacarDuplicidade( in_array( $conf->getIdEtiqueta(), $opcoesDestacar ) ? 'S' : 'N' );
    		
    		$this->getEntityManager()->persist($conf); // Salva as alterações na opção
    	}
    	
    	$this->getEntityManager()->flush();
    }

}