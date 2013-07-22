<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 *
 * Repositório de métodos de consulta em DQL
 * @author lightbase
 *
 */
class AcaoRepository extends EntityRepository
{
	
	/**
	 * 
	 * Lista as Ações opcionais (cs_opcional=S)
	 * @param int $idLocal
	 */
	public function listarModulosOpcionais( $idLocal = null )
	{
		// Monta a Consulta básica...
    	$query = $this->createQueryBuilder('acao')->select('acao', 'COUNT(acao_rede.rede) AS totalRedesAtivadas')
        								->leftJoin('acao.redes', 'acao_rede')
        								->where("acao.csOpcional = 'S'")
        								->groupBy('acao.idAcao');
		
		if ( $idLocal !== null )
		{
			$query->leftJoin('acao_rede.rede', 'rede')
					->leftJoin('rede.idLocal', 'local')
					->andWhere( 'local.idLocal = :idLocal OR local.idLocal IS NULL' )
					->setParameter( 'idLocal', $idLocal );
		}
		
        return $query->getQuery()->execute();
	}

};