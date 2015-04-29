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

use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\Common\Util\Debug;

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

        $qb->andWhere("comp.ativo IS NULL or comp.ativo = 't'");

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
            ->andWhere("comp.ativo IS NULL or comp.ativo = 't'")
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
                'comp.dtHrUltAcesso',
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

        $query->andWhere("comp.ativo IS NULL or comp.ativo = 't'");

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

        $query->andWhere("comp.ativo IS NULL or comp.ativo = 't'");

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
            ->andWhere("comp.ativo IS NULL or comp.ativo = 't'")
            ->groupBy('rede');

        if ( $idLocal !== null )
            $qb->andWhere('rede.idLocal = :idLocal')->setParameter( 'idLocal', $idLocal);

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
            ->andWhere("comp.ativo IS NULL or comp.ativo = 't'")
            ->groupBy('so')
            ->orderBy('numComp', 'DESC');


        $qb = $qb->getQuery();
        $qb->useResultCache(true);
        $qb->setResultCacheLifetime(600);

        return $qb->getResult();
    }

    public function countPorSOCsv()
    {
        $qb = $this->createQueryBuilder('comp')
            ->select('so.teDescSo,  COUNT(comp.idComputador) as numComp')
            ->innerJoin('comp.idSo', 'so')
            ->andWhere("comp.ativo IS NULL or comp.ativo = 't'")
            ->groupBy('so')
            ->orderBy('numComp', 'DESC');

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
            ->andWhere("comp.ativo IS NULL or comp.ativo = 't'")
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
            ->andWhere("comp.ativo IS NULL or comp.ativo = 't'")
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
            ->andWhere("comp.ativo IS NULL or comp.ativo = 't'")
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
        $qb = $this->createQueryBuilder('comp')->select('COUNT(distinct comp.idComputador)')
            ->andWhere("comp.ativo IS NULL or comp.ativo = 't'");
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
            ->andWhere('comp.idRede = :idRede')
            ->andWhere("comp.ativo IS NULL or comp.ativo = 't'")
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
            ->innerJoin('computador.idSo', 'so')
            ->andWhere("computador.ativo IS NULL or computador.ativo = 't'");

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
    public function getComputadorPreCole( Request $request , $te_so , $te_node_adress, $rede, $so, $ip_computador )
    {
        //recebe dados via POST, deCripata dados, e attribui a variaveis
        $computer_system   = OldCacicHelper::deCrypt( $request, $request->request->get('ComputerSystem'), true  );
        $network_adapter   = OldCacicHelper::deCrypt( $request, $request->request->get('NetworkAdapterConfiguration'), true  );
        $operating_system  = OldCacicHelper::deCrypt( $request, $request->request->get('OperatingSystem'), true  );
        $te_versao_cacic   = $request->request->get('te_versao_cacic');
        $te_versao_gercols = $request->request->get('te_versao_gercols');
        $data = new \DateTime('NOW'); //armazena data Atual

        //vefifica se existe SO coletado se não, insere novo SO
        $computador = $this->findOneBy( array( 'teNodeAddress'=> $te_node_adress, 'idSo'=> $so->getIdSo()) );

        //inserção de dado se for um novo computador
        if( empty ( $computador ) )
        {
            $computador = new Computador();

            $computador->setTeNodeAddress( $te_node_adress );
            $computador->setIdSo( $so );
            $computador->setIdRede( $rede );
            $computador->setDtHrInclusao( $data);
            $computador->setTePalavraChave( $request->get('PHP_AUTH_PW') );
            $computador->setAtivo(true);

            $this->getEntityManager()->persist( $computador );

        }

        // Atualiza subrede se houve mudança de subrede para o computador
        #if ($computador->getIdRede() != $rede) {
            #error_log("Atualizando subrede paara o computador ".$computador->getTeIpComputador()." Antiga: ".$computador->getIdRede()->getNmRede()." | Nova: ".$rede->getNmRede());
        #    $computador->setIdRede($rede);
        #}

        $computador->setDtHrUltAcesso( $data );
        $computador->setTeVersaoCacic( $te_versao_cacic );
        $computador->setTeVersaoGercols( $te_versao_gercols );
        $computador->setTeUltimoLogin( TagValueHelper::getValueFromTags( 'UserName' ,$computer_system ) );
        $computador->setTeIpComputador( $ip_computador );
        $computador->setNmComputador( TagValueHelper::getValueFromTags( 'Caption' ,$computer_system ));
        $computador->setAtivo(true);
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
            ->innerJoin('rede.idLocal', 'loc')
            ->andWhere("comp.ativo = 'f'");

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
        //$query->andWhere('log.idComputador IS NULL');

        // Agrupa todos os campos
        $query->groupBy('rede', 'loc.nmLocal', 'loc.sgLocal');

        return $query->getQuery()->execute();
    }

    public function gerarRelatorioRede( $filtros, $idRede,$dataInicio, $dataFim ) {

        // Monta a Consulta básica...
        $query = $this->createQueryBuilder('comp')
            ->select('rede.idRede', 'rede.nmRede', 'rede.teIpRede', 'loc.nmLocal', 'loc.sgLocal', 'comp.idComputador', 'comp.nmComputador', 'comp.teNodeAddress', 'comp.teIpComputador', 'so.idSo', 'so.inMswindows', 'so.sgSo', 'comp.dtHrUltAcesso')
            ->innerJoin('comp.idSo', 'so')
            ->innerJoin('comp.idRede', 'rede')
            ->innerJoin('rede.idLocal', 'loc')
            ->andWhere("comp.ativo = 'f'");

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
            ->innerJoin('rede.idLocal', 'loc')
            ->andWhere("comp.ativo = 'f'");

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
        //$query->andWhere('log.idComputador IS NULL');

        // Agrupa todos os campos
        $query->groupBy('rede', 'loc.nmLocal', 'loc.sgLocal');

        return $query->getQuery()->execute();
    }
    public function listarInativosCsv( $filtros, $idRede,$dataInicio, $dataFim ) {

        // Monta a Consulta básica...
        $query = $this->createQueryBuilder('comp')

            ->select(  'comp.nmComputador', 'comp.teNodeAddress', 'comp.teIpComputador', 'so.sgSo', 'loc.sgLocal', 'rede.nmRede','rede.teIpRede', 'comp.dtHrUltAcesso')
            ->innerJoin('comp.idSo', 'so')
            ->innerJoin('comp.idRede', 'rede')
            ->innerJoin('rede.idLocal', 'loc')
            ->andWhere("comp.ativo = 'f'");

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
        //$query->andWhere('log.idComputador IS NULL');

        $query->groupBy('rede.idRede', 'rede.nmRede', 'rede.teIpRede', 'loc.nmLocal', 'loc.sgLocal', 'comp.idComputador', 'comp.nmComputador', 'comp.teNodeAddress', 'comp.teIpComputador', 'so.idSo', 'so.inMswindows', 'so.sgSo');


        return $query->getQuery()->execute();
    }

    public function semMac($ip_computador, $so) {
        $data = new \DateTime('NOW'); //armazena data Atual

        // Primeiro tenta encontrar pelo IP
        $computador = $this->findOneBy( array( 'teIpComputador'=> $ip_computador, 'idSo'=> $so->getIdSo()) );

        if (empty($computador)) {
            // Pega o primeiro computador da Rede Padrão
            $qb = $this->createQueryBuilder('computador')
                ->select('computador')
                ->andwhere('computador.teIpComputador = :ip_computador')
                ->andWhere('computador.idSo = :idSo')
                ->innerJoin('CacicCommonBundle:Rede', 'rede', 'WITH', "computador.idRede = rede.idRede AND rede.teIpRede = '0.0.0.0'")
                ->setMaxResults(1)
                ->orderBy('computador.idComputador')
                ->setParameter('ip_computador', $ip_computador)
                ->setParameter('idSo', $so->getIdSo());

            try {
                $computador =  $qb->getQuery()->getSingleResult();
            }
            catch(\Doctrine\ORM\NoResultException $e) {
                // Em último caso pega primeiro computador com menor Id
                $qb = $this->createQueryBuilder('computador')
                    ->select('computador')
                    ->andWhere('computador.idSo = :idSo')
                    ->setMaxResults(1)
                    ->orderBy('computador.idComputador')
                    ->setParameter('idSo', $so->getIdSo());

                $computador =  $qb->getQuery()->getOneOrNullResult();
            }

        }

        return $computador;
    }

    /*
   * Listar estações para carga no SGConf_PGFN
   */
    public function estacaoSGConf(){

        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('networkadapterconfiguration_macaddress', 'networkadapterconfiguration_macaddress');
        $rsm->addScalarResult('id_so', 'id_so');
        $rsm->addScalarResult('networkadapterconfiguration_defaultipgateway', 'networkadapterconfiguration_defaultipgateway');
        $rsm->addScalarResult('networkadapterconfiguration_ipaddress', 'networkadapterconfiguration_ipaddress');
        $rsm->addScalarResult('networkadapterconfiguration_dnshostname', 'networkadapterconfiguration_dnshostname');
        $rsm->addScalarResult('win32_processor_name', 'win32_processor_name');
        $rsm->addScalarResult('win32_processor_maxclockspeed', 'win32_processor_maxclockspeed');
        $rsm->addScalarResult('win32_physicalmemory_capacity', 'win32_physicalmemory_capacity');
        $rsm->addScalarResult('dt_hr_ult_acesso', 'dt_hr_ult_acesso');

        $sql = 'SELECT    rc.networkadapterconfiguration_macaddress,
                          c.id_so,
                          rc.networkadapterconfiguration_defaultipgateway,
                          rc.networkadapterconfiguration_ipaddress,
                          rc.networkadapterconfiguration_dnshostname,
                          rc.win32_processor_name,
                          rc.win32_processor_maxclockspeed,
                          rc.win32_physicalmemory_capacity,
                          c.dt_hr_ult_acesso
                          FROM relatorio_coleta rc
                          INNER JOIN computador c ON rc.id_computador = c.id_computador';

        $query = $this->getEntityManager()->createNativeQuery($sql, $rsm);
        return $query->execute();
    }

    /**
     * Busca computadores com mesmo MAC Address e SO
     */
    public function filtroMac( $id_so ){

        $qb = $this->createQueryBuilder('comp')
            ->select('comp.teNodeAddress', 'COUNT(comp.idComputador) as contIdComp')
            ->andWhere('comp.idSo = :idSo')
            ->setParameter('idSo', $id_so)
            ->groupBy('comp.teNodeAddress')
            ->having('COUNT(comp.idComputador) > 1')
            ->orderBy('contIdComp');

        return $qb->getQuery()->getArrayResult();
    }

    /**
     * Busca computador mais recentes
     */
    public function computadorRecente( $teNodeAddress, $id_so ){

        $qb = $this->createQueryBuilder('comp')
            ->select('comp.idComputador')
            ->andwhere('comp.teNodeAddress = :teNodeAddress')
            ->andwhere('comp.idSo = :idSo')
            ->setMaxResults(1)
            ->setParameter('teNodeAddress', $teNodeAddress)
            ->setParameter('idSo', $id_so)
            ->orderBy('comp.dtHrUltAcesso', 'desc');

        return $qb->getQuery()->getSingleScalarResult();
    }

    /**
     * Busca computadores antigos
     */
    public function computadorAntigo( $teNodeAddress, $id_so, $ArrCompRecente ){

        $qb = $this->createQueryBuilder('comp')
            ->select('comp.idComputador')
            ->andwhere('comp.teNodeAddress = :teNodeAddress')
            ->andwhere('comp.idSo = :idSo')
            ->andwhere('comp.idComputador <> :idComputador')
            ->setParameter('teNodeAddress', $teNodeAddress)
            ->setParameter('idSo', $id_so)
            ->setParameter('idComputador', $ArrCompRecente);

        return $qb->getQuery()->getArrayResult();
    }

    public function listar($idLocal, $inicio, $fim) {
        $qb = $this->createQueryBuilder('comp')
            ->select('rede.idRede', 'rede.nmRede', 'rede.teIpRede', 'loc.nmLocal', 'loc.sgLocal', 'COUNT(DISTINCT comp.idComputador) as numComp')
            ->andWhere("comp.ativo IS NULL or comp.ativo = 't'")
            ->innerJoin("CacicCommonBundle:Rede", 'rede', 'WITH', 'rede.idRede = comp.idRede')
            ->innerJoin("CacicCommonBundle:Local", 'loc', 'WITH', 'rede.idLocal = loc.idLocal')
            ->groupBy('rede', 'loc.nmLocal', 'loc.sgLocal');

        if ( !empty($inicio) ) {
            $qb->andWhere( 'comp.dtHrUltAcesso >= :inicio' )->setParameter('inicio', ( $inicio.' 00:00:00' ));
        }

        if ( !empty($fim) ) {
            $qb->andWhere( 'comp.dtHrUltAcesso <= :fim' )->setParameter('fim', ( $fim.' 23:59:59' ));
        }

        if (!empty($idLocal)) {
            $locais = implode(", ", $idLocal);
            $qb->andWhere("loc.idLocal in ($locais)");
        }

        return $qb->getQuery()->getArrayResult();

    }

    public function listarCsv($idLocal, $inicio, $fim) {

        $qb = $this->createQueryBuilder('comp')
            ->select('loc.nmLocal', 'rede.nmRede', 'rede.teIpRede', 'COUNT(DISTINCT comp.idComputador) as numComp')
            ->andWhere("comp.ativo IS NULL or comp.ativo = 't'")
            ->innerJoin("CacicCommonBundle:Rede", 'rede', 'WITH', 'rede.idRede = comp.idRede')
            ->innerJoin("CacicCommonBundle:Local", 'loc', 'WITH', 'rede.idLocal = loc.idLocal')
            ->groupBy('rede', 'loc.nmLocal', 'loc.sgLocal');

        if ( !empty($inicio) ) {
            $qb->andWhere( 'comp.dtHrUltAcesso >= :inicio' )->setParameter('inicio', ( $inicio.' 00:00:00' ));
        }

        if ( !empty($fim) ) {
            $qb->andWhere( 'comp.dtHrUltAcesso <= :fim' )->setParameter('fim', ( $fim.' 23:59:59' ));
        }

        if (!empty($idLocal)) {
            $locais = implode(", ", $idLocal);
            $qb->andWhere("loc.idLocal in ($locais)");
        }

        return $qb->getQuery()->getArrayResult();

    }

    public function gerarRelatorioComputadores( $filtros, $idRede, $dataInicio, $dataFim ) {

        // Monta a Consulta básica...
        $query = $this->createQueryBuilder('comp')
            ->select('rede.idRede', 'rede.nmRede', 'rede.teIpRede', 'loc.nmLocal', 'loc.sgLocal', 'comp.idComputador', 'comp.nmComputador', 'comp.teNodeAddress', 'comp.teIpComputador', 'so.idSo', 'so.inMswindows', 'so.sgSo', 'comp.dtHrUltAcesso')
            ->innerJoin('comp.idSo', 'so')
            ->innerJoin('comp.idRede', 'rede')
            ->innerJoin('rede.idLocal', 'loc')
            ->andWhere("comp.ativo IS NULL or comp.ativo = 't'");

        /**
         * Verifica os filtros que foram parametrizados
         */

        if (!empty($dataInicio)) {
            $query->andWhere('comp.dtHrUltAcesso >= :dataInicio')
                ->setParameter('dataInicio', $dataInicio);
        }

        if (!empty($dataFim)) {
            $query->andWhere('comp.dtHrUltAcesso <= :dataFim')
                ->setParameter('dataFim', $dataFim);
        }


        if ( $idRede ) {
            $query->andWhere( 'comp.idRede IN (:rede)' )->setParameter('rede', $idRede);
        }

        $query->groupBy('rede.idRede', 'rede.nmRede', 'rede.teIpRede', 'loc.nmLocal', 'loc.sgLocal', 'comp.idComputador', 'comp.nmComputador', 'comp.teNodeAddress', 'comp.teIpComputador', 'so.idSo', 'so.inMswindows', 'so.sgSo');


        return $query->getQuery()->execute();
    }

    public function gerarRelatorioComputadoresCsv( $filtros, $idRede,$dataInicio, $dataFim ) {

        // Monta a Consulta básica...
        $query = $this->createQueryBuilder('comp')
            ->select('comp.nmComputador', 'comp.teNodeAddress', 'comp.teIpComputador', 'so.teDescSo', 'loc.nmLocal', 'rede.nmRede', 'rede.teIpRede', 'comp.dtHrUltAcesso')
            ->innerJoin('comp.idSo', 'so')
            ->innerJoin('comp.idRede', 'rede')
            ->innerJoin('rede.idLocal', 'loc')
            ->andWhere("comp.ativo IS NULL or comp.ativo = 't'");

        /**
         * Verifica os filtros que foram parametrizados
         */

        if (!empty($dataInicio)) {
            $query->andWhere('comp.dtHrUltAcesso >= :dataInicio')
                ->setParameter('dataInicio', $dataInicio);
        }

        if (!empty($dataFim)) {
            $query->andWhere('comp.dtHrUltAcesso <= :dataFim')
                ->setParameter('dataFim', $dataFim);
        }


        if ( $idRede ) {
            $query->andWhere( 'comp.idRede IN (:rede)' )->setParameter('rede', $idRede);
        }

        $query->groupBy('rede.idRede', 'rede.nmRede', 'rede.teIpRede', 'loc.nmLocal', 'loc.sgLocal', 'comp.idComputador', 'comp.nmComputador', 'comp.teNodeAddress', 'comp.teIpComputador', 'so.idSo', 'so.inMswindows', 'so.sgSo');


        return $query->getQuery()->execute();
    }

    /**
     * Gera relatório de propriedades WMI coletadas dos computadores
     * @param array $filtros
     * @param $classe
     */
    public function gerarRelatorioWMI( $filtros, $classe )
    {

        $_dql = "SELECT so.idSo,
                        so.inMswindows,
                        so.sgSo,
                        so.teDescSo,
                        rede.idRede,
                        rede.nmRede,
                        rede.teIpRede,
                local.nmLocal,
                local.idLocal,
                COUNT(DISTINCT c.idComputador) AS numComp,
                (CASE WHEN property.nmPropertyName IS NULL THEN 'Não identificado' ELSE property.nmPropertyName END) as nmPropertyName,
                (CASE WHEN cl.teClassPropertyValue IS NULL THEN 'Não identificado' ELSE cl.teClassPropertyValue END) as teClassPropertyValue
                FROM CacicCommonBundle:Computador c
                INNER JOIN CacicCommonBundle:Rede rede WITH (c.idRede = rede.idRede";
                if ( array_key_exists('redes', $filtros) && !empty($filtros['redes']) ){
                    $redes = $filtros['redes'];
                    $_dql .= " AND rede.idRede IN ($redes)";
                }

                $_dql .= ") INNER JOIN CacicCommonBundle:So so WITH (c.idSo = so.idSo";
                if ( array_key_exists('so', $filtros) && !empty($filtros['so']) ){
                    $so = $filtros['so'];
                    $_dql .= " AND c.idSo IN ($so)";
                }

                $_dql .= ") INNER JOIN CacicCommonBundle:Local local WITH (rede.idLocal = local.idLocal";
                if ( array_key_exists('locais', $filtros) && !empty($filtros['locais']) ){
                    $locais = $filtros['locais'];
                    $_dql .= " AND local.idLocal IN ($locais)";
                }

                $_dql .= ") LEFT JOIN CacicCommonBundle:ComputadorColeta cl WITH (c.idComputador = cl.computador";
                if ( array_key_exists('conf', $filtros) && !empty($filtros['conf']) ){
                    $conf = $filtros['conf'];
                    $_dql .= " AND cl.classProperty IN ($conf)";
                }

                $_dql .= ") LEFT JOIN CacicCommonBundle:ClassProperty property WITH cl.classProperty = property.idClassProperty
                LEFT JOIN CacicCommonBundle:Classe classe WITH cl.idClass = classe.idClass
                WHERE c.ativo IS NULL OR c.ativo = 't' AND classe.nmClassName = '$classe'
                GROUP BY property.nmPropertyName,
                            cl.teClassPropertyValue,
                            so.idSo,
                            so.inMswindows,
                            so.sgSo,
                            rede.idRede,
                            rede.nmRede,
                            rede.teIpRede,
                            local.nmLocal,
                            local.idLocal";

        return $this->getEntityManager()->createQuery( $_dql )
            ->getArrayResult();

    }

    /**
     * Gera relatório de propriedades WMI coletadas dos computadores detalhado
     * @param array $filtros
     * @param $classe
     */
    public function gerarRelatorioWMIDetalhe( $filtros, $classe )
    {
        $_dql = "SELECT IDENTITY(cl.computador),
                        c.nmComputador,
                        c.isNotebook,
                        c.teNodeAddress,
                        c.teIpComputador,
                        so.idSo,
                        so.inMswindows,
                        so.sgSo,
                        so.teDescSo,
                        rede.idRede,
                        rede.nmRede,
                        rede.teIpRede,
                        local.nmLocal,
                        local.idLocal,
                (CASE WHEN property.nmPropertyName IS NULL THEN 'Não identificado' ELSE property.nmPropertyName END) as nmPropertyName,
                (CASE WHEN cl.teClassPropertyValue IS NULL THEN 'Não identificado' ELSE cl.teClassPropertyValue END) as teClassPropertyValue
                FROM CacicCommonBundle:Computador c
                INNER JOIN CacicCommonBundle:Rede rede WITH (c.idRede = rede.idRede";
        if ( array_key_exists('rede', $filtros) && !empty($filtros['rede']) ){
            $redes = $filtros['rede'];
            $_dql .= " AND rede.idRede = $redes";
        }

        $_dql .= ") INNER JOIN CacicCommonBundle:So so WITH (c.idSo = so.idSo";
        if ( array_key_exists('so', $filtros) && !empty($filtros['so']) ){
            $so = $filtros['so'];
            $_dql .= " AND c.idSo = $so";
        }

        $_dql .= ") INNER JOIN CacicCommonBundle:Local local WITH (rede.idLocal = local.idLocal";
        if ( array_key_exists('locais', $filtros) && !empty($filtros['locais']) ){
            $locais = $filtros['locais'];
            $_dql .= " AND local.idLocal = $locais";
        }

        $_dql .= ") INNER JOIN CacicCommonBundle:ComputadorColeta cl WITH c.idComputador = cl.computador ";
        if ( array_key_exists('valor', $filtros) && !empty($filtros['valor']) ){
            $valor = $filtros['valor'];
            $_dql .= " AND cl.teClassPropertyValue = '$valor'";
        }

        $_dql .= " INNER JOIN CacicCommonBundle:ClassProperty property WITH cl.classProperty = property.idClassProperty ";
        if ( array_key_exists('conf', $filtros) && !empty($filtros['conf']) ){
            $conf = $filtros['conf'];
            $_dql .= " AND property.nmPropertyName = '$conf'";
        }

        $_dql .= " INNER JOIN CacicCommonBundle:Classe classe WITH property.idClass = classe.idClass
                WHERE (c.ativo IS NULL OR c.ativo = 't') AND classe.nmClassName = '$classe'";

        return $this->getEntityManager()->createQuery( $_dql )
            ->getArrayResult();
    }
}
