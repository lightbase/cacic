<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;

/**
 * LogUserLogadoRepository
 *
 * Métodos de repositório
 */
class LogUserLogadoRepository extends EntityRepository
{
    /*
     * Função que retorna o último acesso para o computador solicitado
     * @param $computador
     */
    public function ultimoAcesso( $computador ) {
        $qb = $this->createQueryBuilder('acesso')
            ->select('acesso')
            ->where('acesso.idComputador = :computador')
            ->orderBy('acesso.data', 'desc')
            ->setMaxResults(1)
            ->setParameter('computador', $computador );

        return $qb->getQuery()->getOneOrNullResult();
    }



    /*
    * Função que retorna a busca por usuário logago
    */
    public function selectUserLogado( $idComputador, $teIpComputador, $nmComputador, $usuario, $dtHrInclusao, $dtHrInclusaoFim )
    {

        error_log('>>>>>>>>>>>>>>Sdsdsd',$idComputador);

        $query = $this->createQueryBuilder('log')
            ->select('comp.nmComputador', 'comp.teIpComputador', 'log.data', 'log.usuario')
            ->innerJoin('CacicCommonBundle:Computador','comp', 'WITH', 'log.idComputador = comp.idComputador');

        if ( $idComputador != null){

            $query->Where("log.idComputador = '$idComputador'");

        }

        if ( $teIpComputador != null){

            $query->Where("comp.teIpComputador  LIKE   '%$teIpComputador%'");

        }
        if ( $nmComputador != null){
            $query->Where("comp.nmComputador LIKE   '%$nmComputador%'");
        }
        if ( $usuario != null){
            $query->Where("log.usuario  LIKE   '%$usuario%'");
        }
        if ( $dtHrInclusao != null){
            $query->andWhere( 'log.data >= (:dtHrInclusao)' )->setParameter('dtHrInclusao', ( $dtHrInclusao.' 00:00:00' ));
        }
        if ( $dtHrInclusaoFim != null){
            $query->andWhere( 'log.data<= (:dtHrInclusaoFim)' )->setParameter('dtHrInclusaoFim', ( $dtHrInclusaoFim.' 23:59:59' ));
        }


        return $query->getQuery()->execute();
    }

    /*
     * Consulta para relatório de contendo ultimo usuário logado
     */
    public function gerarRelatorioUsuario( $filtros, $filtroLocais, $dataInicio, $dataFim, $te_ultimo_login, $nmComputador, $teIpComputador, $teNodeAddress, $usuarioPatrimonio, $usuarioName, $coordenacao, $sala )
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('te_node_address', 'teNodeAddress');
        //$rsm->addScalarResult('id_computador', 'idComputador');
        $rsm->addScalarResult('te_ip_computador', 'teIpComputador');
        $rsm->addScalarResult('nm_computador', 'nmComputador');
        //$rsm->addScalarResult('id_so', 'idSo');
        $rsm->addScalarResult('sg_so', 'sgSo');
        //$rsm->addScalarResult('id_rede', 'idRede');
        $rsm->addScalarResult('nm_rede', 'nmRede');
        $rsm->addScalarResult('te_ip_rede', 'teIpRede');
        $rsm->addScalarResult('max_data', 'data');
        $rsm->addScalarResult('nm_local', 'nmLocal');
        //$rsm->addScalarResult('id_local', 'idLocal');
        $rsm->addScalarResult('te_ultimo_login', 'te_ultimo_login');
        $rsm->addScalarResult('usuario_patrimonio', 'usuarioPatrimonio');
        $rsm->addScalarResult('usuario_name', 'usuarioName');
        $rsm->addScalarResult('coordenacao_setor', 'coordenacao');
        $rsm->addScalarResult('sala', 'sala');
        $rsm->addScalarResult('dt_hr_inclusao', 'dt_hr_inclusao');


        $sql = "SELECT c0_.te_node_address AS te_node_address,
	string_agg(DISTINCT c0_.te_ip_computador, ', ') as te_ip_computador,
	string_agg(DISTINCT c0_.nm_computador, ', ') AS nm_computador,
	string_agg(DISTINCT s2_.sg_so, ', ') AS sg_so,
	string_agg(DISTINCT r3_.nm_rede, ', ') AS nm_rede,
	string_agg(DISTINCT l4_.nm_local, ', ') AS nm_local,
	string_agg(DISTINCT r3_.te_ip_rede, ', ') AS te_ip_rede,
    (SELECT max(cc5_.dt_hr_inclusao) FROM computador_coleta cc5_
          INNER JOIN class_property cp5_ ON cc5_.id_class_property = cp5_.id_class_property
          WHERE cp5_.nm_property_name = 'UserLogado' AND cc5_.id_computador = cc_.id_computador) as dt_hr_inclusao,
    (SELECT cc1_.te_class_property_value FROM computador_coleta cc1_ INNER JOIN class_property cp1_ ON cc1_.id_class_property = cp1_.id_class_property WHERE cp1_.nm_property_name = 'UserName' AND cc1_.id_computador = cc_.id_computador";

        if ($usuarioPatrimonio) {
            $sql = $sql . " AND lower(cc1_.te_class_property_value) LIKE lower('%$usuarioPatrimonio%')";
        }

        $sql = $sql . " LIMIT 1) AS usuario_patrimonio,";

        $sql = $sql . "(SELECT cc2_.te_class_property_value FROM computador_coleta cc2_ INNER JOIN class_property cp2_ ON cc2_.id_class_property = cp2_.id_class_property WHERE cp2_.nm_property_name = 'UserLogado' AND cc2_.id_computador = cc_.id_computador";

        if ($usuarioName) {
            $sql = $sql . " AND lower(cc2_.te_class_property_value) LIKE lower('%$usuarioName%')";
        }

        $sql = $sql . " LIMIT 1) AS usuario_name,";

        $sql = $sql . "(SELECT cc3_.te_class_property_value FROM computador_coleta cc3_ INNER JOIN class_property cp3_ ON cc3_.id_class_property = cp3_.id_class_property WHERE cp3_.nm_property_name = 'Coordenacao_Setor' AND cc3_.id_computador = cc_.id_computador";

        if ($coordenacao) {
            $sql = $sql . " AND lower(cc3_.te_class_property_value) LIKE lower('%$coordenacao%')";
        }

        $sql = $sql . " LIMIT 1) AS coordenacao_setor,";

        $sql = $sql . "(SELECT cc4_.te_class_property_value FROM computador_coleta cc4_ INNER JOIN class_property cp4_ ON cc4_.id_class_property = cp4_.id_class_property WHERE cp4_.nm_property_name = 'Sala' AND cc4_.id_computador = cc_.id_computador";

        if ($sala) {
            $sql = $sql . " AND lower(cc4_.te_class_property_value) LIKE lower('%$sala%')";
        }


        $sql = $sql . " LIMIT 1) AS sala,
	max(l1_.data) AS max_data,
	l1_.usuario as te_ultimo_login
FROM log_user_logado l1_
INNER JOIN computador c0_ ON l1_.id_computador = c0_.id_computador
INNER JOIN so s2_ ON c0_.id_so = s2_.id_so
INNER JOIN rede r3_ ON c0_.id_rede = r3_.id_rede
INNER JOIN local l4_ ON r3_.id_local = l4_.id_local
INNER JOIN log_acesso la5_ ON c0_.id_computador = la5_.id_computador
INNER JOIN computador_coleta cc_ ON cc_.id_computador = c0_.id_computador
INNER JOIN class_property cp_ ON cp_.id_class_property = cc_.id_class_property
WHERE  1 = 1
";

        /**
         * Verifica os filtros que foram parametrizados
         */
        if ( $dataInicio ) {
            $sql .= " AND l1_.data >= ? ";
        }

        if ( $dataFim ) {
            $sql .= " AND l1_.data <= ? ";
        }

        if ( $nmComputador ) {
            $sql .= " AND lower(c0_.nm_computador) LIKE lower(?) ";
        }

        if ( $teIpComputador ) {
            $sql .= " AND c0_.te_ip_computador = ? ";
        }

        if ( $teNodeAddress ) {
            $sql .= " AND c0_.te_node_address = ? ";
        }

        if ( $te_ultimo_login ) {
            $sql .= " AND lower(c0_.te_ultimo_login) LIKE lower(?)";
        }

        if ( $filtroLocais ) {
            $sql .= " AND r3_.id_local IN (?)";
        }

        if ( $usuarioPatrimonio ) {
            $sql .= " AND cp_.nm_property_name = 'UserName' AND lower(cc_.te_class_property_value) LIKE lower('%$usuarioPatrimonio%') ";
        }

        if ( $usuarioName ) {
            $sql .= " AND cp_.nm_property_name = 'UserLogado' AND lower(cc_.te_class_property_value) LIKE lower('%$usuarioName%')";
        }

        if ( $coordenacao ) {
            $sql .= " AND cp_.nm_property_name = 'Coordenacao_Setor' AND lower(cc_.te_class_property_value) LIKE lower('%$coordenacao%')";
        }

        if ( $sala ) {
            $sql .= " AND cp_.nm_property_name = 'Sala' AND lower(cc_.te_class_property_value) LIKE lower('%$sala%')";
        }

        $sql .= "
GROUP BY c0_.te_node_address,
    l1_.usuario,
	l4_.nm_local,
	l4_.id_local,
	cc_.id_computador
	";

        $query = $this->getEntityManager()->createNativeQuery($sql, $rsm);

        /**
         * Verifica os filtros que foram parametrizados
         */
        if ( $dataInicio ) {
            $query->setParameter(1, ( $dataInicio.' 00:00:00' ));
        }

        if ( $dataFim )
            $query->setParameter(2, ( $dataFim.' 23:59:59' ));

        if ( $nmComputador )
            $query->setParameter(3, "%$nmComputador%" );

        if ( $teIpComputador )
            $query->setParameter(4, "$teIpComputador" );

        if ( $teNodeAddress )
            $query->setParameter(5, "$teNodeAddress" );


        if ( $te_ultimo_login )
            $query->setParameter(6, "%$te_ultimo_login%" );

        if ( $filtroLocais )
            $query->setParameter(7, $filtroLocais);


        return $query->execute();
    }

    /**
     * CSV do relatório de usuário dinâmico
     *
     * @return mixed
     */
    public function gerarUsuarioCsvDinamico(){
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('id_log_user_logado', 'id_log_user_logado');
        $rsm->addScalarResult('id_computador', 'id_computador');
        $rsm->addScalarResult('data', 'data');
        $rsm->addScalarResult('usuario', 'usuario');
        $rsm->addScalarResult('nm_computador', 'nm_computador');
        $rsm->addScalarResult('te_node_address', 'te_node_address');
        $rsm->addScalarResult('te_ip_computador', 'te_ip_computador');
        $rsm->addScalarResult('nm_rede', 'nm_rede');
        $rsm->addScalarResult('nm_local', 'nm_local');
        $rsm->addScalarResult('te_class_property_value', 'te_class_property_value');
        $rsm->addScalarResult('data_popup', 'data_popup');
        $rsm->addScalarResult('nm_property_name', 'nm_property_name');
        $rsm->addScalarResult('dt_hr_inclusao', 'dt_hr_inclusao');

        $sql = "SELECT c.nm_computador,
                       c.te_node_address,
                       c.te_ip_computador,
                       l.nm_local,
                       r.nm_rede,
                      (SELECT cc1_.te_class_property_value FROM computador_coleta cc1_
                          INNER JOIN class_property cp1_ ON cc1_.id_class_property = cp1_.id_class_property
                          WHERE cp1_.nm_property_name = 'UserLogado'
                          AND cc1_.id_computador = lg.id_computador
                          ORDER BY cc1_.dt_hr_inclusao DESC
                          LIMIT 1) as te_class_property_value,
                      (SELECT max(cc1_.dt_hr_inclusao) FROM computador_coleta cc1_
                          INNER JOIN class_property cp1_ ON cc1_.id_class_property = cp1_.id_class_property
                          WHERE cp1_.nm_property_name = 'UserLogado'
                          AND cc1_.id_computador = lg.id_computador) as data_popup,
                       lg.usuario,
                       lg.data
                FROM log_user_logado lg
                INNER JOIN computador c ON c.id_computador = lg.id_computador
                INNER JOIN rede r ON r.id_rede = c.id_rede
                INNER JOIN local l ON r.id_local = l.id_local
                INNER JOIN computador_coleta cc ON cc.id_computador = lg.id_computador
                INNER JOIN class_property cp ON cp.id_class_property = cc.id_class_property
                GROUP BY lg.id_log_user_logado,
                       lg.id_computador,
                       lg.data,
                       lg.usuario,
                       c.nm_computador,
                       c.te_node_address,
                       c.te_ip_computador,
                       l.nm_local,
                       r.nm_rede";

        $query = $this->getEntityManager()->createNativeQuery($sql, $rsm);

        return $query->execute();
    }

    /**
     * CSV do relatório de Usuário Dinâmico
     *
     * @param $usuarioLogado
     * @param $dataFim
     * @param $dataInicio
     * @param $semData
     * @param $nmCompDinamico
     * @param $ipCompDinamico
     * @return mixed
     */
    public function gerarRelatorioUsuarioHistorico($usuarioLogado, $dataFim, $dataInicio, $semData, $macCompDinamico, $ipCompDinamico){
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('id_log_user_logado', 'id_log_user_logado');
        $rsm->addScalarResult('id_computador', 'id_computador');
        $rsm->addScalarResult('data', 'data');
        $rsm->addScalarResult('usuario', 'usuario');
        $rsm->addScalarResult('nm_computador', 'nm_computador');
        $rsm->addScalarResult('te_node_address', 'te_node_address');
        $rsm->addScalarResult('te_ip_computador', 'te_ip_computador');
        $rsm->addScalarResult('nm_rede', 'nm_rede');
        $rsm->addScalarResult('nm_local', 'nm_local');
        $rsm->addScalarResult('te_class_property_value', 'te_class_property_value');
        $rsm->addScalarResult('data_popup', 'data_popup');
        $rsm->addScalarResult('nm_property_name', 'nm_property_name');
        $rsm->addScalarResult('dt_hr_inclusao', 'dt_hr_inclusao');

        $sql = "SELECT lg.id_log_user_logado,
                       lg.id_computador,
                       lg.data,
                       lg.usuario,
                       c.nm_computador,
                       c.te_node_address,
                       c.te_ip_computador,
                       l.nm_local,
                       r.nm_rede,
                      (SELECT cc1_.te_class_property_value FROM computador_coleta cc1_
                          INNER JOIN class_property cp1_ ON cc1_.id_class_property = cp1_.id_class_property
                          WHERE cp1_.nm_property_name = 'UserLogado'
                          AND cc1_.id_computador = lg.id_computador
                          ORDER BY cc1_.dt_hr_inclusao DESC
                          LIMIT 1) as te_class_property_value,
                      (SELECT max(cc1_.dt_hr_inclusao) FROM computador_coleta cc1_
                          INNER JOIN class_property cp1_ ON cc1_.id_class_property = cp1_.id_class_property
                          WHERE cp1_.nm_property_name = 'UserLogado'
                          AND cc1_.id_computador = lg.id_computador) as data_popup
                FROM log_user_logado lg
                INNER JOIN computador c ON c.id_computador = lg.id_computador
                INNER JOIN rede r ON r.id_rede = C.id_rede
                INNER JOIN local l ON r.id_local = l.id_local
                INNER JOIN computador_coleta cc ON cc.id_computador = lg.id_computador
                INNER JOIN class_property cp ON cp.id_class_property = cc.id_class_property
                WHERE 1=1";

        if ( $usuarioLogado ) {
            $sql .= " AND lower(lg.usuario) LIKE lower('%".$usuarioLogado."%')";
        }

        if ( $macCompDinamico ) {
            $sql .= " AND lower(c.te_node_address) LIKE lower('%".$macCompDinamico."%')";
        }

        if ( $ipCompDinamico ) {
            $sql .= " AND lower(c.te_ip_computador) LIKE lower('%".$ipCompDinamico."%') ";
        }

        if ($semData == 'N'){
            $sql .= " AND lg.data >= '".$dataInicio."00:00:00' AND lg.data <= '".$dataFim."23:59:59'";
        }

        $sql .= "GROUP BY lg.id_log_user_logado,
                       lg.id_computador,
                       lg.data,
                       lg.usuario,
                       c.nm_computador,
                       c.te_node_address,
                       c.te_ip_computador,
                       l.nm_local,
                       r.nm_rede";

        $query = $this->getEntityManager()->createNativeQuery($sql, $rsm);

        return $query->execute();
    }
}
