<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * ConfiguracaoLocalRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ConfiguracaoLocalRepository extends EntityRepository
{
	/**
	 * 
	 * Lista as configurações do Local informado
	 * @param int $idLocal
	 * @return array
	 */
	public function listarPorLocal( $idLocal )
	{
		$_dql = "SELECT cl.vlConfiguracao, cp.idConfiguracao, cp.nmConfiguracao
				FROM CacicCommonBundle:ConfiguracaoLocal cl
				JOIN cl.idConfiguracao cp
				WHERE cl.idLocal = :idLocal";

        return $this->getEntityManager()
        			->createQuery( $_dql )
        			->setParameter( 'idLocal', $idLocal )
        			->getArrayResult();
	}
	
	/**
	 * 
	 * Recupera um array na forma [idConfiguracao] => [vlConfiguracao]
	 * @param int $idLocal
	 * @return array
	 */
	public function getArrayChaveValor( $idLocal )
	{
		$configuracoes = $this->listarPorLocal( $idLocal );
		$return = array();
		
		foreach ( $configuracoes as $config )
		{
			$return[ $config['idConfiguracao'] ] = $config[ 'vlConfiguracao' ];
		}
		
		return $return;
	}
	
	/**
	 * Verifica se o Local parametrizado possui configurações
	 * @param Cacic\CommonBundle\Entity\Local $local
	 * @return boolean
	 */
	public function hasConfiguracoes( $local )
	{
		$qb = $this->createQueryBuilder('cl')
					->select('COUNT(cl.idConfiguracao) AS numConfig')
					->where('cl.idLocal = :local')
					->setParameter('local', $local)
					->groupBy('cl.idLocal');
		
		return (bool) $qb->getQuery()->getOneOrNullResult();
	}
	
	/**
	 * 
	 * Cria as configurações locais à partir da configuração padrão do CACIC
	 * @param Cacic\CommonBundle\Entity\Local $local
	 */
	public function configurarLocalFromConfigPadrao( $local )
	{
		$em = $this->getEntityManager();
		
		$padrao = $em->getRepository('CacicCommonBundle:ConfiguracaoPadrao')->findAll(); // Recupera todas as Configurações-Padrão
		
		foreach( $padrao as $confPadrao )
		{
			$confLocal = new ConfiguracaoLocal();
			$confLocal->setIdLocal( $local );
			$confLocal->setIdConfiguracao( $confPadrao );
			$confLocal->setVlConfiguracao( $confPadrao->getVlConfiguracao() );
			$em->persist( $confLocal );
		}
		
		$em->flush();
	}
	
	/**
	 *
	 * Remove as configurações aplicadas ao Local parametrizado
	 * @param Cacic\CommonBundle\Entity\Local $local
	 */
	public function removerConfiguracoesDoLocal( $local )
	{
		$em = $this->getEntityManager();
		
		$confs = $this->findBy( array('idLocal'=>$local) ); // Recupera todas as configurações relacionadas ao Local
		foreach ( $confs as $conf )
			$em->remove($conf); // Remove todas as configurações
		
		$em->flush();
	}
	
    /**
     *
     * Lista Email's cadastrados para serem notificados, caso haja alteração de hardware
     * @param string $idLocal
     * @return Cacic\CommonBundle\Entity\ConfiguracaoLocal
     */
    public function listarNotificacaoEmailLocal( $idLocal )
    {
        $_dql = "SELECT cl, cp
				FROM CacicCommonBundle:ConfiguracaoLocal cl
				JOIN cl.idConfiguracao cp
				WHERE cl.idLocal = :idLocal AND
				cp.nmConfiguracao = 'te_notificar_mudancas_emails' AND
				cl.vlConfiguracao IS NOT NULL";

        return $this->getEntityManager()
            ->createQuery( $_dql )
            ->setMaxResults(1)
            ->setParameter( 'idLocal', $idLocal )
            ->getSingleResult();
    }
    /**
     *
     * Lista Propiedades a serem verificadas caso haja alteração
     * @param string $idLocal
     * @return Cacic\CommonBundle\Entity\ConfiguracaoLocal
     */
    public function listarNotificacaoPropertyLocal( $idLocal, $nmConfiguracao )
    {
        $_dql = "SELECT cl, cp
				FROM CacicCommonBundle:ConfiguracaoLocal cl
				JOIN cl.idConfiguracao cp
				WHERE cl.idLocal = :idLocal AND
				cp.nmConfiguracao = :nmConfiguracao AND
				cl.vlConfiguracao IS NOT NULL";

        return $this->getEntityManager()
            ->createQuery( $_dql )
            ->setMaxResults(1)
            ->setParameters( array( 'idLocal'=> $idLocal, 'nmConfiguracao' => $nmConfiguracao ) )
            ->getSingleResult();
    }

}
