<?php
/**
 * Created by PhpStorm.
 * User: cacic
 * Date: 28/01/15
 * Time: 14:25
 */

namespace Cacic\CommonBundle\Entity;

use Doctrine\Common\Util\Debug;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;


class ClassPropertyRepository extends EntityRepository {

    /**
     *
     * Relatório de Configurações das Classes WMI Dinâmico
     * Verifica as classes WMI existentes e suas propriedades
     */
    public function listar() {

        $_dql = "SELECT c.nmClassName, p.nmPropertyName
                FROM CacicCommonBundle:ClassProperty p
				INNER JOIN CacicCommonBundle:Classe c WITH p.idClass = c.idClass
				WHERE c.nmClassName NOT IN ('SoftwareList', 'Patrimonio')
				ORDER BY c.nmClassName";

        $result =  $this->getEntityManager()->createQuery( $_dql )->getArrayResult();

        $saida = array();
        foreach ($result as $elm) {
            if (empty($saida[$elm['nmClassName']])) {
                $saida[$elm['nmClassName']] = array();
            }

            array_push($saida[$elm['nmClassName']], $elm['nmPropertyName']);
        }

        return $saida;
    }

    /**
     * Lista propriedades que estão ativas ou inativas
     *
     * @param bool|true $ativos Retorna somente os ativos
     * @return array
     */
    public function listarAtivos($ativos = true) {

        $qb = $this->createQueryBuilder('p')
            ->innerJoin("CacicCommonBundle:Classe", "cl", "WITH", "p.idClass = cl.idClass")
            ->andWhere("cl.nmClassName != 'SoftwareList'")
            ->addOrderBy("cl.nmClassName")
            ->addOrderBy("p.nmPropertyName");

        if ($ativos) {
            $qb->andWhere("p.ativo = TRUE or p.ativo IS NULL");
        } else {
            $qb->andWhere("p.ativo = FALSE");
        }

        $result = $qb->getQuery()->getResult();

        $saida = array();
        foreach ($result as $elm) {
            if (empty($saida[$elm->getIdClass()->getNmClassName()])) {
                $saida[$elm->getIdClass()->getNmClassName()] = array();
            }

            array_push($saida[$elm->getIdClass()->getNmClassName()], $elm);
        }

        return $saida;
    }

    /**
     *
     * Relatório de Configurações das Classes WMI Dinâmico Detalhes
     *
     */
    public function relatorioWmiDinamico($property, $dataInicio, $dataFim){
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('nm_rede', 'nm_rede');
        $rsm->addScalarResult('dt_hr_ult_acesso', 'dt_hr_ult_acesso');
        $rsm->addScalarResult('id_computador', 'id_computador');
        $rsm->addScalarResult('nm_computador', 'nm_computador');
        $rsm->addScalarResult('te_node_address', 'te_node_address');
        $rsm->addScalarResult('te_ip_computador', 'te_ip_computador');
        $rsm->addScalarResult('te_ip_rede', 'te_ip_rede');
        $rsm->addScalarResult('nm_rede', 'nm_rede');

        $sql = 'SELECT c.id_computador, c.nm_computador, c.te_node_address, c.te_ip_computador, r.te_ip_rede, r.nm_rede, c.dt_hr_ult_acesso, ';
        foreach ($property as $elm) {
            $sql = $sql . "(CASE WHEN rc.$elm IS NOT NULL
            THEN rc.$elm
            ELSE 'Não identificado'
            END) as $elm, ";
            $rsm->addScalarResult($elm, $elm);
        }

        $size = strlen($sql);
        $sql = substr($sql,0, $size-2);

        $sql = $sql . " FROM relatorio_coleta rc
        INNER JOIN computador c ON rc.id_computador = c.id_computador
        INNER JOIN rede r ON r.id_rede = c.id_rede
        WHERE (c.ativo IS NULL or c.ativo = 't')";

        if (!empty($dataInicio)) {
            $sql .= " AND c.dt_hr_ult_acesso >= '$dataInicio 00:00:00'";
        }

        if (!empty($dataFim)) {
            $sql .= " AND c.dt_hr_ult_acesso <= '$dataFim 23:59:59'";
        }

        $result = $this->getEntityManager()->createNativeQuery($sql, $rsm)->execute();

        return $result;
    }

    /**
     * Pega lista de atributos pelo nome da classe
     *
     * @param $classe
     * @return array
     */
    public function getByClassName($classe) {
        $qb = $this->createQueryBuilder('prop')
            ->innerJoin("CacicCommonBundle:Classe", "cl", "WITH", "prop.idClass = cl.idClass")
            ->andWhere('cl.nmClassName = :classe')
            ->setParameter('classe', $classe);

        return $qb->getQuery()->getResult();
    }
} 
