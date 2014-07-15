<?php



namespace Cacic\CommonBundle\Entity;


use Doctrine\ORM\EntityRepository;
use Cacic\WSBundle\Helper\OldCacicHelper;
use Cacic\WSBundle\Helper\TagValueHelper;

use Symfony\Component\HttpFoundation\Request;
use Cacic\CommonBundle\Entity\AcaoSo;
use Cacic\CommonBundle\Entity\Acao;
use Cacic\CommonBundle\Entity\So;
use Cacic\CommonBundle\Entity\ComputadorColeta;

/**
 *
 * Repositório de métodos de consulta em DQL
 * @author lightbase
 *
 */
class ComputadorRepository extends EntityRepository
{

    /**
     * Realiza pesquisa por Computadores conforme filtros parametrizados
     * @param array $filtros
     * @return Ambigous <multitype:, \Doctrine\ORM\mixed, \Doctrine\ORM\Internal\Hydration\mixed, \Doctrine\DBAL\Driver\Statement, \Doctrine\Common\Cache\mixed>
     */
    public function pesquisarComputadores( $filtros )
    {
        $qb = $this->createQueryBuilder('comp');

        foreach ( $filtros as $campo => $valor )
            $qb->andWhere("comp.{$campo} LIKE '%$valor%'");

        return $qb->getQuery()->getResult();
    }

    /**
     *
     * Conta os computadores associados a cada Local
     * @return array
     */
    public function countPorLocal()
    {
        $qb = $this->createQueryBuilder('comp')
            ->select('loc.idLocal, loc.nmLocal, COUNT(comp.idComputador) as numComp')
            ->innerJoin('comp.idRede', 'rede')
            ->innerJoin('rede.idLocal', 'loc')
            ->groupBy('loc');

        return $qb->getQuery()->getResult();
    }
    public function selectIp( $teIpComputador , $nmComputador ,$teNodeAddress )    {


        /*$query = $this->createQueryBuilder('comp')->select('comp.idComputador',
            'comp.nmComputador',
            'comp.teIpComputador',
            'comp.teVersaoCacic',
            'comp.teNodeAddress')
            ->expr()->like('comp.teIpComputador', expr()->literal($teIpComputador));
            /*->where('comp.teIpComputador LIKE % (:tipoPesquisa) ')
            ->setParameter('comp.teIpComputador', $teIpComputador);*/

        $query = $this->createQueryBuilder('comp')
            ->select('comp.idComputador',
                'comp.nmComputador',
                'comp.teIpComputador',
                'comp.teVersaoCacic',
                'comp.teNodeAddress'
            );
        if ( $teIpComputador != null){

            $query->Where("comp.teIpComputador  LIKE   '%$teIpComputador%'");

        }
        if ( $nmComputador != null){
            $query->Where("comp.nmComputador LIKE   '%$nmComputador%'");
        }
        if ( $teNodeAddress != null){
            $query->Where("comp.teNodeAddress  LIKE   '%$teNodeAddress%'");
        }




        return $query->getQuery()->execute();
    }
    public function selectIpAvancada( $teIpComputador , $nmComputador ,$teNodeAddress, $dtHrInclusao ,$dtHrInclusaoFim )
    {

        $query = $this->createQueryBuilder('comp')
            ->select('comp.idComputador',
                'comp.nmComputador',
                'comp.teIpComputador',
                'comp.teVersaoCacic',
                'comp.teNodeAddress'

            );

        if ( $teIpComputador != null){

            $query->Where("comp.teIpComputador  LIKE   '%$teIpComputador%'");

        }
        if ( $nmComputador != null){
            $query->Where("comp.nmComputador LIKE   '%$nmComputador%'");
        }
        if ( $teNodeAddress != null){
            $query->Where("comp.teNodeAddress  LIKE   '%$teNodeAddress%'");
        }
        if ( $dtHrInclusao != null){
            $query->andWhere( 'comp.dtHrInclusao >= (:dtHrInclusao)' )->setParameter('dtHrInclusao', ( $dtHrInclusao.' 00:00:00' ));
        }
        if ( $dtHrInclusaoFim != null){
            $query->andWhere( 'comp.dtHrInclusao<= (:dtHrInclusaoFim)' )->setParameter('dtHrInclusaoFim', ( $dtHrInclusaoFim.' 23:59:59' ));
        }


        return $query->getQuery()->execute();
    }


    /**
     *
     * Conta os computadores associados a cada Rede
     * Se o idLocal for informado, verifica apenas as redes associadas a este
     * @param int|\Cacic\CommonBundle\Entity\Local $idLocal
     * @return array
     */
    public function countPorSubrede( $idLocal = null )
    {
        $qb = $this->createQueryBuilder('comp')
            ->select('rede.idRede, rede.teIpRede, rede.nmRede, COUNT(comp.idComputador) as numComp')
            ->innerJoin('comp.idRede', 'rede')
            ->groupBy('rede');

        if ( $idLocal !== null )
            $qb->where('rede.idLocal = :idLocal')->setParameter( 'idLocal', $idLocal);

        return $qb->getQuery()->getResult();
    }

    /**
     *
     * Conta os computadores associados a cada Sistema Operacional
     */
    public function countPorSO()
    {
        $qb = $this->createQueryBuilder('comp')
            ->select('so.idSo, so.teDescSo, so.sgSo, so.teSo, COUNT(comp.idComputador) as numComp')
            ->innerJoin('comp.idSo', 'so')
            ->groupBy('so');


        $qb = $qb->getQuery();
        $qb->useResultCache(true);
        $qb->setResultCacheLifetime(600);

        return $qb->getResult();
    }

    /**
     *
     * Conta os computadores associados a cada Versão do Agente
     */
    public function countPorVersaoCacic()
    {
        $qb = $this->createQueryBuilder('comp')
            ->select('comp.teVersaoCacic, COUNT(DISTINCT comp.idComputador) as total')
            ->groupBy('comp.teVersaoCacic');


        $qb = $qb->getQuery();
        $qb->useResultCache(true);
        $qb->setResultCacheLifetime(600);

        return $qb->getResult();
    }

    /**
     *
     * Conta os computadores associados a cada Versão do Agente com acesso nos ultimos 30 dias
     */
    public function countPorVersao30dias()
    {
        $qb = $this->createQueryBuilder('comp')
            ->select('comp.teVersaoCacic, COUNT(DISTINCT comp.idComputador) as total')
            ->innerJoin('CacicCommonBundle:LogAcesso','log', 'WITH', 'log.idComputador = comp.idComputador')
            ->andWhere( 'log.data >= (current_date() - 30)' )
            ->groupBy('comp.teVersaoCacic');


        $qb = $qb->getQuery();
        $qb->useResultCache(true);
        $qb->setResultCacheLifetime(600);

        return $qb->getResult();
    }

    /**
     *
     * Conta os computadores associados a cada Sistema Operacional com acesso nos ultimos 30 dias
     */
    public function countPorSO30Dias()
    {
        $qb = $this->createQueryBuilder('comp')
            ->select('so.idSo, so.teDescSo, so.sgSo, so.teSo, COUNT(DISTINCT comp.idComputador) as numComp')
            ->innerJoin('comp.idSo', 'so')
            ->innerJoin('CacicCommonBundle:LogAcesso','log', 'WITH', 'log.idComputador = comp.idComputador')
            ->andWhere( 'log.data >= (current_date() - 30)' )
            ->groupBy('so');


        $qb = $qb->getQuery();
        $qb->useResultCache(true);
        $qb->setResultCacheLifetime(600);

        return $qb->getResult();
    }

    /**
     *
     * Conta todos os computadores monitorados
     * @return int
     */
    public function countAll()
    {
        $qb = $this->createQueryBuilder('comp')->select('COUNT(comp.idComputador)');
        return $qb->getQuery()->getSingleScalarResult();
    }

    /**
     *
     * Lista os computadores monitorados alocados na subrede informada
     * @param int|\Cacic\CommonBundle\Entity\Rede $idSubrede
     */
    public function listarPorSubrede( $idSubrede )
    {
        $qb = $this->createQueryBuilder('comp')
            ->select('comp', 'so')
            ->leftJoin('comp.idSo', 'so')
            ->where('comp.idRede = :idRede')
            ->setParameter('idRede', $idSubrede)
            ->orderBy('comp.nmComputador')->addOrderBy('comp.teIpComputador');

        return $qb->getQuery()->getResult();
    }

    /**
     *
     * Gera relatório de configurações de hardware coletadas dos computadores
     * @param array $filtros
     */
    public function gerarRelatorioConfiguracoes( $filtros )
    {
        $qb = $this->createQueryBuilder('computador')
            ->select('computador, coleta, classe, rede, local, so')
            ->innerJoin('computador.hardwares', 'coleta')
            ->innerJoin('coleta.idClass', 'classe')
            ->innerJoin('computador.idRede', 'rede')
            ->innerJoin('rede.idLocal', 'local')
            ->innerJoin('computador.idSo', 'so');

        /**
         * Verifica os filtros
         */
        if ( array_key_exists('locais', $filtros) && !empty($filtros['locais']) )
            $qb->andWhere('local.idLocal IN (:locais)')->setParameter('locais', explode( ',', $filtros['locais'] ));

        if ( array_key_exists('so', $filtros) && !empty($filtros['so']) )
            $qb->andWhere('computador.idSo IN (:so)')->setParameter('so', explode( ',', $filtros['so'] ));

        /*if ( array_key_exists('conf', $filtros) && !empty($filtros['conf']) )
        	$qb->andWhere('coleta.idClass IN (:conf)')->setParameter('conf', explode( ',', $filtros['conf'] ));*/


        return $qb->getQuery()->execute();
    }

    /*
    * Metodo responsável por inserir coletas iniciais, assim que o cacic é instalado
    */
    public function getComputadorPreCole( Request $request , $te_so , $te_node_adress, $rede, $so )
    {
        //recebe dados via POST, deCripata dados, e attribui a variaveis
        $computer_system   = OldCacicHelper::deCrypt( $request, $request->request->get('ComputerSystem'), true  );
        $network_adapter   = OldCacicHelper::deCrypt( $request, $request->request->get('NetworkAdapterConfiguration'), true  );
        $operating_system  = OldCacicHelper::deCrypt( $request, $request->request->get('OperatingSystem'), true  );
        $te_versao_cacic   = $request->request->get('te_versao_cacic');
        $te_versao_gercols = $request->request->get('te_versao_gercols');
        $data = new \DateTime('NOW'); //armazena data Atual

        //vefifica se existe SO coletado se não, insere novo SO
        //$so = $this->getEntityManager()->getRepository('CacicCommonBundle:So')->createIfNotExist( $te_so );
        //$rede = $this->getEntityManager()->getRepository('CacicCommonBundle:Rede')->getDadosRedePreColeta( $request );
        $computador = $this->findOneBy( array( 'teNodeAddress'=> $te_node_adress, 'idSo'=> $so->getIdSo()) );
        //$classes = $this->getEntityManager()->getRepository('CacicCommonBundle:Classe')->findAll();

        //inserção de dado se for um novo computador
        if( empty ( $computador ) )
        {
            $computador = new Computador();

            $computador->setTeNodeAddress( $te_node_adress );
            $computador->setIdSo( $so );
            $computador->setIdRede( $rede );
            $computador->setDtHrInclusao( $data);
            $computador->setTePalavraChave( $request->get('PHP_AUTH_PW') );

            $this->getEntityManager()->persist( $computador );

        }

        $computador->setDtHrUltAcesso( $data );
        $computador->setTeVersaoCacic( $te_versao_cacic );
        $computador->setTeVersaoGercols( $te_versao_gercols );
        $computador->setTeUltimoLogin( TagValueHelper::getValueFromTags( 'UserName' ,$computer_system ) );
        $computador->setTeIpComputador( TagValueHelper::getValueFromTags( 'IPAddress' ,$network_adapter ) );
        $computador->setNmComputador( TagValueHelper::getValueFromTags( 'Caption' ,$computer_system ));
        $this->getEntityManager()->persist( $computador );

        $acoes = $this->getEntityManager()->getRepository('CacicCommonBundle:Acao')->findAll();

        //inserção ações de coleta a nova maquina
        foreach ($acoes as $acao)
        {
            $acao_so = $this->getEntityManager()->getRepository('CacicCommonBundle:AcaoSo')->findBy(array('rede'=>$rede,'so'=>$so,'acao'=>$acao));
            if(empty($acao_so))
            {
                $acao_so = new AcaoSo();
                $acao_so->setRede( $rede );
                $acao_so->setSo( $so );
                $acao_so->setAcao( $acao );
                $this->getEntityManager()->persist( $acao_so );
            }
        }

        //persistir dados
        $this->getEntityManager()->flush();

        return $computador;
    }

    /**
     * Realiza pesquisa por LOGs de ACESSO para máquinas inativas
     * @param date $dataInicio
     * @param date $dataFim
     * @param array $locais
     */
    public function pesquisarInativos( $dataInicio, $dataFim, $locais )
    {

        // Monta a Consulta básica...
        $query = $this->createQueryBuilder('comp')
            ->select('rede.idRede', 'rede.nmRede', 'rede.teIpRede', 'loc.nmLocal', 'loc.sgLocal', 'COUNT(DISTINCT comp.idComputador) as numComp')
            ->innerJoin('comp.idRede', 'rede')
            ->innerJoin('rede.idLocal', 'loc');

        /**
         * Verifica os filtros que foram parametrizados
         */

        if (empty($dataInicio) && empty($dataFim)) {
            // Aqui não preciso filtrar pela data
            $query->leftJoin('CacicCommonBundle:LogAcesso', 'log', 'WITH', 'comp.idComputador = log.idComputador');
        } else {

            $query->leftJoin('CacicCommonBundle:LogAcesso', 'log', 'WITH', 'comp.idComputador = log.idComputador AND log.data >= :dtInicio AND log.data <= :dtFim')
                ->setParameter('dtInicio', ( $dataInicio.' 00:00:00' ))
                ->setParameter('dtFim', ( $dataFim.' 23:59:59' ));

        }

        if ( count($locais) )
            $query->andWhere( 'loc.idLocal IN (:locais)' )->setParameter('locais', $locais);


        // Filtro que mostra somente máquinas sem coleta
        $query->andWhere('log.idComputador IS NULL');

        // Agrupa todos os campos
        $query->groupBy('rede', 'loc.nmLocal', 'loc.sgLocal');

        return $query->getQuery()->execute();
    }

    public function gerarRelatorioRede( $filtros, $idRede,$dataInicio, $dataFim ) {

        // Monta a Consulta básica...
        $query = $this->createQueryBuilder('comp')
            ->select('rede.idRede', 'rede.nmRede', 'rede.teIpRede', 'loc.nmLocal', 'loc.sgLocal', 'comp.idComputador', 'comp.nmComputador', 'comp.teNodeAddress', 'comp.teIpComputador', 'so.idSo', 'so.inMswindows', 'so.sgSo')
            ->innerJoin('comp.idSo', 'so')
            ->innerJoin('comp.idRede', 'rede')
            ->innerJoin('rede.idLocal', 'loc');

        /**
         * Verifica os filtros que foram parametrizados
         */
        if (empty($dataInicio) && empty($dataFim)) {
            // Aqui não preciso filtrar pela data
            $query->leftJoin('CacicCommonBundle:LogAcesso', 'log', 'WITH', 'comp.idComputador = log.idComputador');
        } else {

            $query->leftJoin('CacicCommonBundle:LogAcesso', 'log', 'WITH', 'comp.idComputador = log.idComputador AND log.data >= :dtInicio AND log.data <= :dtFim')
                ->setParameter('dtInicio', ( $dataInicio.' 00:00:00' ))
                ->setParameter('dtFim', ( $dataFim.' 23:59:59' ));
        }

        if ( $idRede )
            $query->andWhere( 'comp.idRede IN (:rede)' )->setParameter('rede', $idRede);

        // Filtro que mostra somente máquinas sem coleta
        $query->andWhere('log.idComputador IS NULL');

        $query->groupBy('rede.idRede', 'rede.nmRede', 'rede.teIpRede', 'loc.nmLocal', 'loc.sgLocal', 'comp.idComputador', 'comp.nmComputador', 'comp.teNodeAddress', 'comp.teIpComputador', 'so.idSo', 'so.inMswindows', 'so.sgSo');


        return $query->getQuery()->execute();
    }
    public function inativosCsv( $dataInicio, $dataFim, $locais )
    {

        // Monta a Consulta básica...
        $query = $this->createQueryBuilder('comp')
            ->select('loc.nmLocal',  'rede.nmRede', 'rede.teIpRede', 'COUNT(DISTINCT comp.idComputador) as numComp')
            ->innerJoin('comp.idRede', 'rede')
            ->innerJoin('rede.idLocal', 'loc');

        /**
         * Verifica os filtros que foram parametrizados
         */

        if (empty($dataInicio) && empty($dataFim)) {
            // Aqui não preciso filtrar pela data
            $query->leftJoin('CacicCommonBundle:LogAcesso', 'log', 'WITH', 'comp.idComputador = log.idComputador');
        } else {

            $query->leftJoin('CacicCommonBundle:LogAcesso', 'log', 'WITH', 'comp.idComputador = log.idComputador AND log.data >= :dtInicio AND log.data <= :dtFim')
                ->setParameter('dtInicio', ( $dataInicio.' 00:00:00' ))
                ->setParameter('dtFim', ( $dataFim.' 23:59:59' ));

        }

        if ( count($locais) )
            $query->andWhere( 'loc.idLocal IN (:locais)' )->setParameter('locais', $locais);


        // Filtro que mostra somente máquinas sem coleta
        $query->andWhere('log.idComputador IS NULL');

        // Agrupa todos os campos
        $query->groupBy('rede', 'loc.nmLocal', 'loc.sgLocal');

        return $query->getQuery()->execute();
    }
    public function listarInativosCsv( $filtros, $idRede,$dataInicio, $dataFim ) {

        // Monta a Consulta básica...
        $query = $this->createQueryBuilder('comp')

            ->select(  'comp.nmComputador', 'comp.teNodeAddress', 'comp.teIpComputador', 'so.sgSo', 'loc.nmLocal', 'rede.nmRede','rede.teIpRede')
            ->innerJoin('comp.idSo', 'so')
            ->innerJoin('comp.idRede', 'rede')
            ->innerJoin('rede.idLocal', 'loc');

        /**
         * Verifica os filtros que foram parametrizados
         */
        if (empty($dataInicio) && empty($dataFim)) {
            // Aqui não preciso filtrar pela data
            $query->leftJoin('CacicCommonBundle:LogAcesso', 'log', 'WITH', 'comp.idComputador = log.idComputador');
        } else {

            $query->leftJoin('CacicCommonBundle:LogAcesso', 'log', 'WITH', 'comp.idComputador = log.idComputador AND log.data >= :dtInicio AND log.data <= :dtFim')
                ->setParameter('dtInicio', ( $dataInicio.' 00:00:00' ))
                ->setParameter('dtFim', ( $dataFim.' 23:59:59' ));
        }

        if ( $idRede )
            $query->andWhere( 'comp.idRede IN (:rede)' )->setParameter('rede', $idRede);

        // Filtro que mostra somente máquinas sem coleta
        $query->andWhere('log.idComputador IS NULL');

        $query->groupBy('rede.idRede', 'rede.nmRede', 'rede.teIpRede', 'loc.nmLocal', 'loc.sgLocal', 'comp.idComputador', 'comp.nmComputador', 'comp.teNodeAddress', 'comp.teIpComputador', 'so.idSo', 'so.inMswindows', 'so.sgSo');


        return $query->getQuery()->execute();
    }

}
