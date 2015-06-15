<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 *
 * Repositório de métodos de consulta em DQL
 * @author lightbase
 *
 */
class SoftwareRepository extends EntityRepository
{
    public function paginar( \Knp\Component\Pager\Paginator $paginator, $page = 1 )
    {
        $_dql = "SELECT s, tipoSW.teDescricaoTipoSoftware AS tipoSoftware
				FROM CacicCommonBundle:Software s
				LEFT JOIN s.idTipoSoftware tipoSW
				ORDER BY s.nmSoftware ASC";

        return $paginator->paginate(
            $this->getEntityManager()->createQuery( $_dql ),
            $page,
            10
        );
    }
    /**
     *
     * Método de listagem dos Software cadastrados e respectivas informações
     */
    public function listar()
    {
        $_dql = "SELECT s, tipoSW.teDescricaoTipoSoftware AS tipoSoftware
				FROM CacicCommonBundle:Software s
				LEFT JOIN s.idTipoSoftware tipoSW
				ORDER BY s.nmSoftware ASC";

        return $this->getEntityManager()->createQuery( $_dql )->getArrayResult();
    }

    public function listarSoftware()
    {
        $qb = $this->createQueryBuilder('sw')
            ->select('sw.nmSoftware','sw.idSoftware')
            ->innerJoin('CacicCommonBundle:PropriedadeSoftware', 'prop', 'WITH', 'sw.idSoftware = prop.software')
            ->innerJoin('CacicCommonBundle:ClassProperty', 'class','WITH', 'prop.classProperty = class.idClassProperty')
            ->innerJoin('CacicCommonBundle:Computador', 'comp', 'WITH', 'comp.idComputador = prop.computador')
            ->andWhere("(comp.ativo IS NULL or comp.ativo = 't')")
            ->groupBy('sw.nmSoftware, sw.idSoftware')
            ->orderBy('sw.nmSoftware');

        return $qb->getQuery()->execute();
    }

    /**
     *
     * Método de listagem dos Softwares cadastrados que não foram classificados (sem Tipo de Software)
     */
    public function listarNaoClassificados()
    {
        $qb = $this->createQueryBuilder('sw')
            ->select('sw.nmSoftware','sw.idSoftware')
            ->innerJoin('CacicCommonBundle:PropriedadeSoftware', 'prop', 'WITH', 'sw.idSoftware = prop.software')
            ->innerJoin('CacicCommonBundle:ClassProperty', 'class','WITH', 'prop.classProperty = class.idClassProperty')
            ->innerJoin('CacicCommonBundle:Computador', 'comp', 'WITH', 'comp.idComputador = prop.computador')
            ->andWhere('sw.idTipoSoftware is null')
            ->andWhere("(comp.ativo IS NULL or comp.ativo = 't')")
            ->groupBy('sw.nmSoftware,sw.idSoftware')
            ->orderBy('sw.nmSoftware');

        return $qb->getQuery()->execute();
    }

    /**
     *
     * Método de listagem dos Softwares cadastrados que não estão associados a nenhuma máquina
     */
    public function listarNaoUsados()
    {
        $qb = $this->createQueryBuilder('sw')
            ->select('sw.nmSoftware, sw.idSoftware')
            ->leftJoin( 'sw.estacoes ','se')
            ->where('se is null')
            ->groupBy('sw.nmSoftware,sw.idSoftware')
            ->orderBy('sw.nmSoftware','ASC');

        return $qb->getQuery()->execute();

    }

    /**
     *
     * Método de consulta à base de dados de softwares inventariados
     * @param array $filtros
     */
    public function gerarRelatorioSoftwaresInventariados( $filtros )
    {
        // Monta a Consulta básica...
        $qb = $this->createQueryBuilder('sw')
            ->select('COALESCE(sw.nmSoftware) as nmSoftware', 'r.idRede', 'r.nmRede', 'r.teIpRede', 'l.nmLocal','COUNT(DISTINCT col.computador) AS numComp')
            ->innerJoin('CacicCommonBundle:PropriedadeSoftware', 'prop', 'WITH', 'sw.idSoftware = prop.software')
            ->innerJoin('CacicCommonBundle:ClassProperty', 'class','WITH', 'prop.classProperty = class.idClassProperty')
            ->innerJoin('CacicCommonBundle:ComputadorColeta', 'col', 'WITH', 'col.computador = prop.computador')
            ->innerJoin('CacicCommonBundle:Computador', 'comp', 'WITH', 'col.computador = comp.idComputador')
            ->innerJoin('comp.idRede','r')
            ->innerJoin('r.idLocal', 'l')
            ->andWhere("(comp.ativo IS NULL or comp.ativo = 't')")
            ->groupBy('sw.nmSoftware, r.idRede, r.nmRede, r.teIpRede, l.nmLocal')
            ->orderBy('sw.nmSoftware, l.nmLocal');

        /**
         * Verifica os filtros que foram parametrizados
         */
        if ( array_key_exists('softwares', $filtros) && !empty($filtros['softwares']) )
            $qb->andWhere('class.idClassProperty IN (:softwares)')->setParameter('softwares', explode( ',', array_unique($filtros['softwares']) ));

        if ( array_key_exists('locais', $filtros) && !empty($filtros['locais']) )
            $qb->andWhere('l.idLocal IN (:locais)')->setParameter('locais', explode( ',', array_unique($filtros['locais']) ));

        if ( array_key_exists('redes', $filtros) && !empty($filtros['redes']) )
            $qb->andWhere('r.idRede IN (:redes)')->setParameter('redes', explode( ',', array_unique($filtros['redes']) ));

        if ( array_key_exists('so', $filtros) && !empty($filtros['so']) )
            $qb->andWhere('comp.idSo IN (:so)')->setParameter('so', explode( ',', array_unique($filtros['so']) ));

        return $qb->getQuery()->execute();
    }

    /**
     *
     * Método de consulta à base de dados de softwares licenciados
     * @param array $filtros
     */
    public function gerarRelatorioSoftwaresLicenciados( $filtros )
    {
        // Monta a Consulta básica...
        $qb = $this->createQueryBuilder('sw')
            ->select('sw.nmSoftware', 'aqit.qtLicenca', 'aqit.dtVencimentoLicenca', 'aq.nrProcesso', 'tpl.teTipoLicenca')
            ->innerJoin('CacicCommonBundle:AquisicaoItem','aqit','WITH','sw.idSoftware = aqit.idSoftware')
            ->innerJoin('CacicCommonBundle:Aquisicao','aq','WITH','aq.idAquisicao = aqit.idAquisicao')
            ->innerJoin('CacicCommonBundle:TipoLicenca','tpl','WITH','tpl.idTipoLicenca = aqit.idTipoLicenca')
            ->orderBy('sw.nmSoftware');
    
        /**
         * Verifica os filtros que foram parametrizados
         */
        if ( array_key_exists('softwares', $filtros) && !empty($filtros['softwares']) )
            $qb->andWhere('sw.idSoftware IN (:softwares)')->setParameter('softwares', explode( ',', $filtros['softwares'] ));

        return $qb->getQuery()->execute();
    }

    /**
     *
     * Método de consulta à base de dados de softwares por órgão
     * @param array $filtros
     */
    public function gerarRelatorioSoftwaresPorOrgao( $filtros )
    {
        // Monta a Consulta básica...
        $qb = $this->createQueryBuilder('sw')
            ->select('sw', 'se', 'comp')
            ->innerJoin('sw.estacoes', 'se')
            ->innerJoin('se.idComputador', 'comp')
            ->andWhere("(comp.ativo IS NULL or comp.ativo = 't')")
            ->orderBy('sw.nmSoftware')->addOrderBy('comp.nmComputador')->addOrderBy('comp.teIpComputador');

        /**
         * Verifica os filtros que foram parametrizados
         */
        if ( array_key_exists('TipoSoftware', $filtros) && !empty($filtros['TipoSoftware']) )
            $qb->innerJoin('sw.idTipoSoftware', 'tpsw')->andWhere('tpsw.idTipoSoftware IN (:tpsw)')->setParameter('tpsw', explode( ',', $filtros['TipoSoftware'] ));

        if ( array_key_exists('nmComputador', $filtros) && !empty($filtros['nmComputador']) )
            $qb->andWhere('comp.nmComputador LIKE :nmComputador')->setParameter('nmComputador', "%{$filtros['nmComputador']}%" );

        return $qb->getQuery()->execute();
    }

    /**
     *
     * Método de consulta à base de dados de softwares por tipo
     * @param array $filtros
     */
    public function gerarRelatorioSoftwaresPorTipo( $filtros )
    {
        // Monta a Consulta básica...
        $qb = $this->createQueryBuilder('sw')
            ->select('COALESCE( sw.nmSoftware) as nmSoftware', 'tipo.teDescricaoTipoSoftware', 'COUNT(DISTINCT col.computador) AS numComp')
            ->innerJoin('CacicCommonBundle:PropriedadeSoftware', 'prop', 'WITH', 'sw.idSoftware = prop.software')
            ->innerJoin('CacicCommonBundle:ComputadorColeta', 'col', 'WITH', 'col.computador = prop.computador')
            ->innerJoin('CacicCommonBundle:Computador', 'comp', 'WITH', 'col.computador = comp.idComputador')
            ->innerJoin('CacicCommonBundle:TipoSoftware', 'tipo', 'WITH', 'sw.idTipoSoftware = tipo.idTipoSoftware')
            ->andWhere("(comp.ativo IS NULL or comp.ativo = 't')")
            ->groupBy('tipo.teDescricaoTipoSoftware, sw.nmSoftware, tipo.idTipoSoftware');

        /**
         * Verifica os filtros que foram parametrizados
         */
        if ( array_key_exists('TipoSoftware', $filtros) && !empty($filtros['TipoSoftware']) )
            $qb->andWhere('tipo.idTipoSoftware IN (:tpsw)')->setParameter('tpsw', explode( ',', $filtros['TipoSoftware'] ));

        return $qb->getQuery()->execute();
    }

    /**
     *
     * Método de consulta à base de dados de softwares não vinculados a nenhuma estação
     * @param array $filtros
     */
    public function gerarRelatorioSoftwaresNaoVinculados()
    {
        // Monta a Consulta básica...
        $qb = $this->createQueryBuilder('sw');
        $qb->select('sw', 'tpsw')
            ->innerJoin('CacicCommonBundle:PropriedadeSoftware', 'prop', 'WITH', 'sw.idSoftware = prop.software')
            ->innerJoin('CacicCommonBundle:Computador', 'comp', 'WITH', 'prop.computador = comp.idComputador')
            ->leftJoin('sw.idTipoSoftware', 'tpsw')
            ->andWhere('prop.computador IS NULL')
            ->andWhere("(comp.ativo IS NULL or comp.ativo = 't')")
            ->groupBy('sw', 'tpsw.idTipoSoftware', 'tpsw.teDescricaoTipoSoftware')
            ->orderBy('sw.nmSoftware');

        return $qb->getQuery()->execute();
    }
    public function gerarRelatorioPatrimonio( $filtros )
    {
        // Monta a Consulta básica...
        $qb = $this->createQueryBuilder('sw')
            ->select( 'DISTINCT(comp.nmComputador), sw.nmSoftware,class.nmPropertyName, coleta.teClassPropertyValue, so.sgSo, r.teIpRede, r.nmRede, l.nmLocal, comp.idComputador,so.inMswindows')
            ->innerJoin('CacicCommonBundle:PropriedadeSoftware', 'prop', 'WITH', 'sw.idSoftware = prop.software')
            ->innerJoin('CacicCommonBundle:ClassProperty', 'class','WITH', 'prop.classProperty = class.idClassProperty')
            ->innerJoin('CacicCommonBundle:ComputadorColeta', 'coleta','WITH', 'prop.classProperty = coleta.classProperty')
            ->innerJoin('CacicCommonBundle:Computador', 'comp','WITH','prop.computador = comp.idComputador')
            ->leftJoin('CacicCommonBundle:So', 'so','WITH','comp.idSo = so.idSo')
            ->leftJoin('CacicCommonBundle:Rede', 'r','WITH', 'comp.idRede = r.idRede')
            ->innerJoin('CacicCommonBundle:Local','l','WITH', 'r.idLocal = l.idLocal')
            ->andWhere("(comp.ativo IS NULL or comp.ativo = 't')")
            ->groupBy('comp.nmComputador, sw.nmSoftware, class.nmPropertyName, coleta.teClassPropertyValue,so.sgSo, r.teIpRede,  l.nmLocal, r.nmRede, comp.idComputador,so.inMswindows')
            ->orderBy('r.teIpRede, l.nmLocal, sw.nmSoftware');

        /*
         * Verifica os filtros que foram parametrizados
         */

        if ( array_key_exists('softwares', $filtros) && !empty($filtros['softwares']) )
            $qb->andWhere('class.idClassProperty IN (:softwares)')->setParameter('softwares', explode( ',', $filtros['softwares'] ));

        if ( array_key_exists('locais', $filtros) && !empty($filtros['locais']) )
            $qb->andWhere('l.idLocal IN (:locais)')->setParameter('locais', explode( ',', $filtros['locais'] ));

        if ( array_key_exists('so', $filtros) && !empty($filtros['so']) )
            $qb->andWhere('comp.idSo IN (:so)')->setParameter('so', explode( ',', $filtros['so'] ));

        if ( array_key_exists('conf', $filtros) && !empty($filtros['conf']) )
            $qb->andWhere('class.idClassProperty IN (:conf)')->setParameter('conf', explode( ',', $filtros['conf'] ));


        return $qb->getQuery()->execute();
    }

    /**
     * Lista softwares que possuem o nome repetido no sistema
     */
    public function getNomesRepetidos() {

        $qb = $this->createQueryBuilder('sw')
            ->select('sw.nmSoftware, COUNT(prop) as n_repeticoes')
            ->innerJoin('CacicCommonBundle:PropriedadeSoftware','prop', 'WITH', 'sw.idSoftware = prop.software')
            ->having('COUNT(prop) > 1')
            ->groupBy('sw.nmSoftware')
            ->orderBy('sw.nmSoftware');

        return $qb->getQuery()->execute();

    }

    /**
     * Pega primeiro resultado de uma consulta por nome
     *
     * @param $nmSoftware
     */
    public function porNome( $nmSoftware ) {

        $qb = $this->createQueryBuilder('sw')
            ->select('sw')
            ->andWhere('sw.nmSoftware = :nmSoftware')
            ->setMaxResults(1)
            ->setParameter('nmSoftware', $nmSoftware);

        return $qb->getQuery()->getSingleResult();

    }

    /**
     * Busca computadores sem coleta
     *
     * @return mixed
     */
    public function semColeta() {
        $qb = $this->createQueryBuilder('sw')
            ->select('sw')
            ->leftJoin('CacicCommonBundle:PropriedadeSoftware', 'prop', 'WITH', 'sw.idSoftware = prop.software')
            ->leftJoin('CacicCommonBundle:Computador', 'comp','WITH','prop.computador = comp.idComputador')
            ->leftJoin('CacicCommonBundle:AquisicaoItem', 'aq', 'WITH', 'sw.idSoftware = aq.idSoftware')
            ->andWhere("(comp.ativo IS NULL or comp.ativo = 't')")
            ->andWhere('prop IS NULL')
            ->andWhere('aq IS NULL');

        return $qb->getQuery()->execute();
    }


    /**
     * Encontra o software pelo nome
     *
     * @param $name Nome a ser buscado
     * @return mixed objeto do software
     */
    public function findByName($name, $iterate = true) {

        $qb = $this->createQueryBuilder('sw')
            ->select('sw')
            ->andWhere("lower(sw.nmSoftware) LIKE lower('%$name%')");

        if ($iterate) {
            $result = $qb->getQuery()->iterate(array());
        } else {
            $result =  $qb->getQuery()->execute($name);
        }

        return $result;

    }

    public function getByName($name) {

        $qb = $this->createQueryBuilder('sw')
            ->select('sw')
            ->andWhere("sw.nmSoftware = :name")
            ->setMaxResults(1)
            ->orderBy('sw.idSoftware')
            ->setParameter('name', $name);

        $result = $qb->getQuery()->getOneOrNullResult();

        return $result;

    }

}
