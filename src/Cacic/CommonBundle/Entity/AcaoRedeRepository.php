<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 *
 * Repositório de métodos de consulta em DQL
 * @author lightbase
 *
 */
class AcaoRedeRepository extends EntityRepository
{
	
	/**
	 * Recupera as Redes associadas à determinada Ação
	 * Retorna um array associativo na forma array([idRede] => nmRede (teIpRede))
	 * @param int $acao
	 * @return array
	 */
	public function getArrayChaveValorRedesPorAcao( $acao )
	{
		// Monta a Consulta básica...
    	$query = $this->createQueryBuilder('ar')->select('r.idRede', 'r.nmRede', 'r.teIpRede')
        								->innerJoin('ar.acao', 'a')
        								->innerJoin('ar.rede', 'r')
        								->where("a.idAcao IN (:idAcao)")
        								->setParameter('idAcao', array($acao))
        								->orderBy('r.nmRede')
        								->groupBy('r');

        $tmp = $query->getQuery()->execute();
        
        $return = array();
        foreach( $tmp as $rede )
        {
        	$return[$rede['idRede']] = "{$rede['nmRede']} ({$rede['teIpRede']})";
        }
        
        return $return;
	}
	
	/**
	 * 
	 * Atualiza as Redes (do local informado) associadas a determinada Ação
	 * @param int|Cacic\CommonBundle\Entity\Acao $acao
	 * @param int|Cacic\CommonBundle\Entity\Local $local
	 * @param array $novasRedes
	 */
	public function atualizarPorLocal( $acao, $local, $novasRedes )
	{
        $em = $this->getEntityManager();

        $apagaObj = $em->getRepository( 'CacicCommonBundle:AcaoRede' )->findBy( array( 'acao'=>$acao ) );

        // Primeiro apaga todas
        foreach ( $apagaObj as $acaoObj){
            if (!empty($acaoObj)) {
                $em->remove($acaoObj);
            }
        }

        $em->flush();

        // Agora insere uma de cada vez
        $novaAcao = $em->getRepository( 'CacicCommonBundle:Acao' )->findAcaoAtiva( $acao, true );
        if (!empty($novaAcao)) {
            foreach ( $novasRedes as $idRede )
            {
                $rede = $em->getRepository('CacicCommonBundle:Rede')->find($idRede);

                $new = new AcaoRede();
                $new->setAcao($novaAcao);
                $new->setRede($rede);
                $em->persist($new);
            }
        }

		$em->flush();
    }

    /**
     * Habilita todas as ações para a Rede fornecida ou conjunto de redes fornecidas
     *
     * @param array $redes
     *
     */
    public function atualizarPorRede ( $redes ) {
        $em = $this->getEntityManager();
        $acoes = $em->getRepository( 'CacicCommonBundle:Acao' )->findAll();

        foreach ($redes as $novaRede) {

            // Primeiro apaga todas as ações
            $acaoRede = $this->findBy( array( 'rede' => $novaRede ) );

            foreach ($acaoRede as $acao) {
                $em->remove($acao);
            }
            $em->flush();

            // Para cada rede, habilita as ações
            foreach ($acoes as $novaAcao){
                // com excessão do módulo patrimonio, que inicialmente é desabilitado
                if ( $novaAcao->getCsOpcional() == 'S' && $novaAcao->getAtivo() ){

                    $new = new AcaoRede();

                    // Agora cria a ação
                    $new->setAcao($novaAcao);
                    $new->setRede($novaRede);
                    $em->persist($new);

                    // Grava as mudanças
                    $em->flush();
                }
            }

        }
    }

}