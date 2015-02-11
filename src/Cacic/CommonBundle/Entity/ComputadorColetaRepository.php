<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
use Cacic\CommonBundle\Entity\Computador;

/**
 * ComputadorColetaRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ComputadorColetaRepository extends EntityRepository
{
	
	/**
	 * Recupera os dados de coleta referentes ao Computador parametrizado
	 * @param \Cacic\CommonBundle\Entity\Computador $computador
     * @param $nmClassName Nome da classe para buscar
     * @return Mixed
	 */
	public function getDadosColetaComputador( Computador $computador, $nmClassName = null )
	{
		$qb = $this->createQueryBuilder('coleta')->select(
            'coleta',
            'propriedade',
            'classe',
            'software.displayName',
            'software.displayVersion',
            'software.URLInfoAbout',
            'software.publisher'
        )
            ->innerJoin('coleta.classProperty', 'propriedade')
            ->innerJoin('propriedade.idClass', 'classe')
            ->leftJoin('CacicCommonBundle:PropriedadeSoftware', 'software', 'WITH', 'propriedade.idClassProperty = software.classProperty')
            ->andWhere('coleta.computador = (:computador)')
            ->setParameter('computador', $computador)
            ->orderBy('classe.nmClassName')
            ->addOrderBy('propriedade.nmPropertyName');

        // Adiciona filtro por classe
        if (!empty($nmClassName)) {
            $qb->andWhere('classe.nmClassName = :nmClassName');
            $qb->setParameter('nmClassName', $nmClassName);
        }
	
		return $qb->getQuery()->execute();
	}

    /**
     *
     * Gera relatório de configurações de hardware coletadas dos computadores
     * @param array $filtros
     */
    public function gerarRelatorioConfiguracoes( $filtros )
    {
        $qb = $this->createQueryBuilder('coleta')
            ->select('IDENTITY(coleta.computador), coleta.teClassPropertyValue, comp.nmComputador, comp.teNodeAddress, comp.teIpComputador, so.idSo, so.inMswindows, so.sgSo, so.teDescSo, rede.idRede, local.nmLocal, local.idLocal')
            ->innerJoin('coleta.classProperty', 'property')
            ->innerJoin('property.idClass', 'classe')
            ->innerJoin('coleta.computador', 'comp')
            ->innerJoin('comp.idSo', 'so')
            ->innerJoin('comp.idRede', 'rede')
            ->innerJoin('rede.idLocal', 'local');

        /**
         * Verifica os filtros
         */
        if ( array_key_exists('locais', $filtros) && !empty($filtros['locais']) )
            $qb->andWhere('local.idLocal IN (:locais)')->setParameter('locais', explode( ',', $filtros['locais'] ));

        if ( array_key_exists('so', $filtros) && !empty($filtros['so']) )
            $qb->andWhere('comp.idSo IN (:so)')->setParameter('so', explode( ',', $filtros['so'] ));

        if ( array_key_exists('conf', $filtros) && !empty($filtros['conf']) )
        	$qb->andWhere('property.idClass IN (:conf)')->setParameter('conf', explode( ',', $filtros['conf'] ));


        return $qb->getQuery()->execute();
    }

    /*
     *  Retorna lista de atributos coletados para a classe fornecida
     *
     * @param $classe
     *
     */

    public function listarPropriedades($classe) {

        $qb = $this->createQueryBuilder('coleta')
            ->select('DISTINCT IDENTITY(coleta.classProperty) AS idClassProperty, property.nmPropertyName')
            ->innerJoin('coleta.classProperty', 'property')
            ->innerJoin('property.idClass', 'classe')
            ->where('classe.nmClassName = :classe')
            ->orderBy('property.nmPropertyName')
            ->setParameter('classe', $classe);

        return $qb->getQuery()->execute();
    }

    /*
    * Lista das classes que vão para o Menu de relatórios
    *
    * FIXME: Adicionar parâmetro para excluir classes do Menu
    */

    public function menu()
    {
        $_dql = "SELECT c
                FROM CacicCommonBundle:Classe c
				WHERE c.nmClassName NOT IN ('SoftwareList')
				ORDER BY c.nmClassName";

        $_dql = $this->getEntityManager()->createQuery( $_dql );

        $_dql->useResultCache(true);
        $_dql->setResultCacheLifetime(3600);

        return $_dql->getArrayResult();
    }

    /**
     *
     * Gera relatório de propriedades WMI coletadas dos computadores
     *
     * @param array $filtros
     * @param $classe
     */
    public function gerarRelatorioWMIDetalhe( $filtros, $classe )
    {
        $qb = $this->createQueryBuilder('coleta')
            ->select('IDENTITY(coleta.computador), property.nmPropertyName, coleta.teClassPropertyValue, comp.nmComputador, comp.isNotebook, comp.teNodeAddress, comp.teIpComputador, so.idSo, so.inMswindows, so.sgSo, rede.idRede, rede.nmRede, rede.teIpRede, local.nmLocal, local.idLocal')
            ->innerJoin('coleta.classProperty', 'property')
            ->innerJoin('property.idClass', 'classe')
            ->innerJoin('coleta.computador', 'comp')
            ->innerJoin('comp.idSo', 'so')
            ->innerJoin('comp.idRede', 'rede')
            ->innerJoin('rede.idLocal', 'local')
            ->where('classe.nmClassName = :classe')
            ->setParameter('classe', $classe);

        /**
         * Verifica os filtros
         */
        if ( array_key_exists('locais', $filtros) && !empty($filtros['locais']) )
            $qb->andWhere('local.idLocal IN (:locais)')->setParameter('locais', explode( ',', $filtros['locais'] ));

        if ( array_key_exists('rede', $filtros) && !empty($filtros['rede']) )
            $qb->andWhere('rede.idRede IN (:rede)')->setParameter('rede', explode( ',', $filtros['rede'] ));

        if ( array_key_exists('so', $filtros) && !empty($filtros['so']) )
            $qb->andWhere('comp.idSo IN (:so)')->setParameter('so', explode( ',', $filtros['so'] ));

        if ( array_key_exists('conf', $filtros) && !empty($filtros['conf']) )
            $qb->andWhere('property.nmPropertyName IN (:conf)')->setParameter('conf', explode( ',', $filtros['conf'] ));


        return $qb->getQuery()->execute();
    }

    /**
     * Relatório geral de softwares inventariados
     *
     * @param $filtros
     * @param $software
     * @param $local
     * @return mixed
     */

    public function gerarRelatorioSoftware( $filtros, $software)
    {
        $qb = $this->createQueryBuilder('coleta')
            ->select('DISTINCT IDENTITY(coleta.computador), comp.nmComputador, comp.teNodeAddress,
             comp.teIpComputador, so.inMswindows, so.sgSo, rede.idRede, rede.nmRede, rede.teIpRede, local.nmLocal, max(coleta.dtHrInclusao) as dtHrInclusao')
            ->innerJoin('coleta.classProperty', 'property')
            ->innerJoin('coleta.computador', 'comp')
            ->innerJoin('comp.idSo', 'so')
            ->innerJoin('comp.idRede', 'rede')
            ->innerJoin('rede.idLocal', 'local')
            ->innerJoin('CacicCommonBundle:PropriedadeSoftware', 'prop', 'WITH', 'prop.classProperty = coleta.classProperty')
            ->innerJoin('prop.software', 'soft')
            ->andWhere('soft.nmSoftware = :software')
            ->groupBy('coleta.computador, comp.nmComputador, comp.teNodeAddress,
             comp.teIpComputador, so.inMswindows, so.sgSo, rede.idRede, rede.nmRede, rede.teIpRede, local.nmLocal')
            ->orderBy('coleta.computador, local.nmLocal, rede.teIpRede')
            ->setParameter('software', $software);

        /**
         * Verifica os filtros
         */

        if ( array_key_exists('locais', $filtros) && !empty($filtros['locais']) )
            $qb->andWhere('local.nmLocal IN (:locais)')->setParameter('locais', explode( ',', $filtros['locais'] ));

        if ( array_key_exists('redes', $filtros) && !empty($filtros['redes']) )
            $qb->andWhere('rede.idRede IN (:redes)')->setParameter('redes', explode( ',', $filtros['redes'] ));

        if ( array_key_exists('so', $filtros) && !empty($filtros['so']) )
            $qb->andWhere('comp.idSo IN (:so)')->setParameter('so', explode( ',', $filtros['so'] ));

        if ( array_key_exists('conf', $filtros) && !empty($filtros['conf']) )
            $qb->andWhere('soft.idSoftware IN (:conf)')->setParameter('conf', explode( ',', $filtros['conf'] ));


        return $qb->getQuery()->execute();
    }

    /**
     * Gera relatório de softwares inventariados
     *
     * @param $filtros
     * @return mixed
     */

    public function gerarRelatorioSoftwaresInventariados( $filtros )
    {
        $qb = $this->createQueryBuilder('coleta')
            ->select('soft.nmSoftware', 'soft.idSoftware', 'rede.idRede', 'rede.nmRede', 'rede.teIpRede', 'local.nmLocal', 'local.idLocal','COUNT(DISTINCT coleta.computador) AS numComp')
            ->innerJoin('coleta.classProperty', 'property')
            ->innerJoin('property.idClass', 'classe')
            ->innerJoin('coleta.computador', 'comp')
            ->innerJoin('comp.idSo', 'so')
            ->innerJoin('comp.idRede', 'rede')
            ->innerJoin('rede.idLocal', 'local')
            ->innerJoin('CacicCommonBundle:PropriedadeSoftware', 'prop', 'WITH', 'prop.classProperty = coleta.classProperty')
            ->innerJoin('prop.software', 'soft')
            ->groupBy('soft.nmSoftware', 'soft.idSoftware', 'rede.idRede', 'rede.nmRede', 'rede.teIpRede', 'local.nmLocal', 'local.idLocal');

        /**
         * Verifica os filtros
         */

        if ( array_key_exists('softwares', $filtros) && !empty($filtros['softwares']) )
            $qb->andWhere('soft.idSoftware IN (:softwares)')->setParameter('softwares', explode( ',', $filtros['softwares'] ));

        if ( array_key_exists('local', $filtros) && !empty($filtros['local']) )
            $qb->andWhere('local.idLocal IN (:locais)')->setParameter('locais', explode( ',', $filtros['local'] ));

        if ( array_key_exists('redes', $filtros) && !empty($filtros['redes']) )
            $qb->andWhere('rede.idRede IN (:redes)')->setParameter('redes', explode( ',', $filtros['redes'] ));

        if ( array_key_exists('so', $filtros) && !empty($filtros['so']) )
            $qb->andWhere('comp.idSo IN (:so)')->setParameter('so', explode( ',', $filtros['so'] ));

        return $qb->getQuery()->execute();
    }

    /**
     *
     * Gera relatório de propriedades WMI coletadas dos computadores detalhado
     *
     * @param array $filtros
     * @param $classe
     */
    public function gerarRelatorioWMI( $filtros, $classe )
    {
        $qb = $this->createQueryBuilder('coleta')
            ->select('property.nmPropertyName', 'coleta.teClassPropertyValue', 'so.idSo', 'so.inMswindows', 'so.sgSo', 'so.teDescSo', 'rede.idRede', 'rede.nmRede', 'rede.teIpRede', 'local.nmLocal', 'local.idLocal', 'count(DISTINCT coleta.computador) as numComp')
            ->innerJoin('coleta.classProperty', 'property')
            ->innerJoin('property.idClass', 'classe')
            ->innerJoin('coleta.computador', 'comp')
            ->innerJoin('comp.idSo', 'so')
            ->innerJoin('comp.idRede', 'rede')
            ->innerJoin('rede.idLocal', 'local')
            ->where('classe.nmClassName = :classe')
            ->groupBy('property.nmPropertyName, coleta.teClassPropertyValue, so.idSo, so.inMswindows,so.sgSo, rede.idRede, rede.nmRede, rede.teIpRede, local.nmLocal, local.idLocal')
            ->setParameter('classe', $classe);

        /**
         * Verifica os filtros
         */
        if ( array_key_exists('locais', $filtros) && !empty($filtros['locais']) )
            $qb->andWhere('local.idLocal IN (:locais)')->setParameter('locais', explode( ',', $filtros['locais'] ));

        if ( array_key_exists('redes', $filtros) && !empty($filtros['redes']) )
            $qb->andWhere('rede.idRede IN (:redes)')->setParameter('redes', explode( ',', $filtros['redes'] ));

        if ( array_key_exists('so', $filtros) && !empty($filtros['so']) )
            $qb->andWhere('comp.idSo IN (:so)')->setParameter('so', explode( ',', $filtros['so'] ));

        if ( array_key_exists('conf', $filtros) && !empty($filtros['conf']) )
            $qb->andWhere('property.idClassProperty IN (:conf)')->setParameter('conf', explode( ',', $filtros['conf'] ));

        return $qb->getQuery()->execute();
    }

    public function gerarRelatorioPatrimonio($filtros)
    {
        // Monta a Consulta básica...
        $qb = $this->createQueryBuilder('coleta')
            ->select( 'DISTINCT(comp.nmComputador), so.sgSo, class.nmPropertyName, coleta.teClassPropertyValue,
                        r.teIpRede, r.idRede, r.nmRede, l.nmLocal, comp.idComputador,so.inMswindows, u.nmUorg')
            ->innerJoin('CacicCommonBundle:Computador', 'comp','WITH','coleta.computador = comp.idComputador')
            ->innerJoin('CacicCommonBundle:PropriedadeSoftware', 'prop', 'WITH', 'comp.idComputador = prop.computador')
            ->innerJoin('CacicCommonBundle:ClassProperty', 'class','WITH', 'coleta.classProperty = class.idClassProperty')
            ->innerJoin('CacicCommonBundle:Classe', 'classe','WITH', 'class.idClass = classe.idClass')
            ->leftJoin('CacicCommonBundle:So', 'so','WITH','comp.idSo = so.idSo')
            ->leftJoin('CacicCommonBundle:Rede', 'r','WITH', 'comp.idRede = r.idRede')
            ->leftJoin('CacicCommonBundle:Uorg','u','WITH', 'r.idRede = u.rede')
            ->innerJoin('CacicCommonBundle:Local','l','WITH', 'r.idLocal = l.idLocal')
            ->groupBy('comp.nmComputador,r.idRede, class.nmPropertyName, u.nmUorg, coleta.teClassPropertyValue,so.sgSo, r.teIpRede,  l.nmLocal, r.nmRede, comp.idComputador,so.inMswindows')
            ->orderBy('r.teIpRede, l.nmLocal');

        /*
         * Verifica os filtros que foram parametrizados
         */

        if ( array_key_exists('locais', $filtros) && !empty($filtros['locais']) )
            $qb->andWhere('l.idLocal IN (:locais)')->setParameter('locais', explode( ',', $filtros['locais'] ));

        if ( array_key_exists('so', $filtros) && !empty($filtros['so']) )
            $qb->andWhere('comp.idSo IN (:so)')->setParameter('so', explode( ',', $filtros['so'] ));

        if ( array_key_exists('conf', $filtros) && !empty($filtros['conf']) )
            $qb->andWhere('class.idClassProperty IN (:conf)')->setParameter('conf', explode( ',', $filtros['conf'] ));

        if ( array_key_exists('uorg', $filtros) && !empty($filtros['uorg']) )
            $qb->andWhere('u.idUorg IN (:uorg)')->setParameter('uorg', explode( ',', $filtros['uorg'] ));

        return $qb->getQuery()->execute();
    }

    public function gerarRelatorioSemPatrimonio($filtros)
    {
        // Monta a Consulta básica...
        $qb = $this->createQueryBuilder('coleta')
            ->select( 'DISTINCT(comp.nmComputador), so.sgSo,
                        r.teIpRede, r.idRede, r.nmRede, l.nmLocal, comp.idComputador,so.inMswindows, u.nmUorg')
            ->innerJoin('CacicCommonBundle:Computador', 'comp','WITH','coleta.computador = comp.idComputador')
            ->innerJoin('CacicCommonBundle:PropriedadeSoftware', 'prop', 'WITH', 'comp.idComputador = prop.computador')
            ->innerJoin('CacicCommonBundle:ClassProperty', 'class','WITH', 'coleta.classProperty = class.idClassProperty')
            ->innerJoin('CacicCommonBundle:Classe', 'classe','WITH', 'class.idClass = classe.idClass')
            ->leftJoin('CacicCommonBundle:So', 'so','WITH','comp.idSo = so.idSo')
            ->leftJoin('CacicCommonBundle:Rede', 'r','WITH', 'comp.idRede = r.idRede')
            ->innerJoin('CacicCommonBundle:Uorg','u','WITH', 'r.idRede = u.rede')
            ->innerJoin('CacicCommonBundle:Local','l','WITH', 'r.idLocal = l.idLocal')
            ->groupBy('comp.nmComputador,r.idRede, u.nmUorg,so.sgSo, r.teIpRede,  l.nmLocal, r.nmRede, comp.idComputador,so.inMswindows')
            ->orderBy('r.teIpRede, l.nmLocal');

        /*
         * Verifica os filtros que foram parametrizados
         */

        if ( array_key_exists('locais', $filtros) && !empty($filtros['locais']) )
            $qb->andWhere('l.idLocal IN (:locais)')->setParameter('locais', explode( ',', $filtros['locais'] ));

        if ( array_key_exists('so', $filtros) && !empty($filtros['so']) )
            $qb->andWhere('comp.idSo IN (:so)')->setParameter('so', explode( ',', $filtros['so'] ));


        if ( array_key_exists('uorg', $filtros) && !empty($filtros['uorg']) )
            $qb->andWhere('u.idUorg IN (:uorg)')->setParameter('uorg', explode( ',', $filtros['uorg'] ));

            return $qb->getQuery()->execute();
    }

    /**
     * Relatório de coleta geral
     *
     * @param $campos Array de campos a serem retornados na consulta. Devem estar no formato de list
     * @param $dataInicio Data de início para considerar na busca
     * @param $dataFim Última data a considerar na busca
     * @return mixed Array mapeado com os campos id_computador, data_coleta, campos selecionados e respectivas
     *              datas de coleta
     */
    public function relatorioColeta($campos, $dataInicio, $dataFim) {

        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('id_computador', 'id_computador');
        $rsm->addScalarResult('data_coleta', 'data_coleta');

        $sql = 'SELECT id_computador
                data_coleta';

        foreach ($campos as $atributo) {
            $sql = $sql . ", $atributo, $atributo" . "_data";
            $rsm->addScalarResult($atributo, $atributo);
            $rsm->addScalarResult($atributo."_data", $atributo."_data");
        }

        $sql = $sql . "FROM relatorio_coleta
        WHERE 1 = 1";

        if ( !empty($dataInicio) ) {
            $sql  = $sql . "AND data_coleta >= ?";
        }


        if ( !empty($dataFim) ) {
            $sql  = $sql . "AND data_coleta <= ?";
        }


        $query = $this->getEntityManager()->createNativeQuery($sql, $rsm);

        /**
         * Verifica os filtros que foram parametrizados
         */
        if ( !empty($dataInicio) ) {
            $query->setParameter(1, ( $dataInicio.' 00:00:00' ));
        }


        if ( !empty($dataFim) ) {
            $query->setParameter(2, ( $dataFim.' 23:59:59' ));
        }

        return $query->execute();

    }

    public function patrimonioComputador($idComputador) {
        $_dql = "SELECT comp.idComputador,
            c.teClassPropertyValue,
            prop.nmPropertyName,
            cl.nmClassName,
            comp.teIpComputador,
            comp.teUltimoLogin,
            cl.idClass,
            comp.nmComputador
        FROM CacicCommonBundle:ComputadorColeta c
        INNER JOIN CacicCommonBundle:Computador comp WITH c.computador = comp.idComputador
        INNER JOIN CacicCommonBundle:ClassProperty prop WITH c.classProperty = prop.idClassProperty
        INNER JOIN CacicCommonBundle:Classe cl WITH prop.idClass = cl.idClass
        WHERE cl.nmClassName = 'Patrimonio'
        AND comp.idComputador = :idComputador
        ";

        return $this->getEntityManager()->createQuery( $_dql )
            ->setParameter('idComputador', $idComputador)
            ->getArrayResult();
    }

    /**
     *
     * Gera relatório de dispositivos 3G
     *
     */
    public function listar3g() {
        $_dql = "SELECT c.teClassPropertyValue, p.displayName, p.publisher
        FROM CacicCommonBundle:ComputadorColeta c
        INNER JOIN CacicCommonBundle:ProriedadeSoftware p ON c.idClassProperty = p.idClassProperty
        WHERE LOWER(c.teClassPropertyValue) LIKE LOWER('%modem%')
        ";

        return $this->getEntityManager()->createQuery( $_dql );
    }
}
