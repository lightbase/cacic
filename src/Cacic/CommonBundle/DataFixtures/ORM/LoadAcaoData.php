<?php

namespace Cacic\CommonBundle\DataFixtures\ORM;

use Cacic\CommonBundle\Entity\Acao;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Validator\Constraints\DateTime;

/*
 * Carrega Configurações-Padrão
 */

class LoadAcaoData extends AbstractFixture implements FixtureInterface, OrderedFixtureInterface
{
	
	private $acoes = array(
		array(
            'id_acao'=>'col_anvi',
            'te_descricao_breve'=>'Antivírus TrendMicro OfficeScan',
            'te_descricao'=>'Essa ação permite que sejam coletadas informações sobre o antivírus OfficeScan nos computadores onde os agentes estão instalados. São coletadas informações como a versão do engine, versão do pattern, endereço do servidor, data da instalação, etc.',
            'te_nome_curto_modulo'=>'ANVI',
            'dt_hr_alteracao'=>null,
            'cs_opcional'=>'S',
            'ativo' => false
        ),
		array(
            'id_acao'=>'col_comp',
            'te_descricao_breve'=>'Compartilhamentos de Diretórios e Impressoras',
            'te_descricao'=>'Essa ação permite que sejam coletadas informações sobre compartilhamentos de diretórios e impressoras dos computadores onde os agentes estão instalados.',
            'te_nome_curto_modulo'=>'COMP',
            'dt_hr_alteracao'=>null,
            'cs_opcional'=>'S',
            'ativo' => false
        ),
		array(
            'id_acao'=>'col_env_not_optional',
            'te_descricao_breve'=>'Variáveis de Ambiente',
            'te_descricao'=>'Essa ação é não-opcional, para que os agentes coletem informações sobre variáveis de ambiente nas estações de trabalho.',
            'te_nome_curto_modulo'=>'ENV_NOT_OPTIONAL',
            'dt_hr_alteracao'=>null,
            'cs_opcional'=>'N',
            'ativo' => false
        ),
		array(
            'id_acao'=>'col_hard',
            'te_descricao_breve'=>'Hardwares',
            'te_descricao'=>'Essa ação permite que sejam coletadas diversas informações sobre o hardware dos computadores onde os agentes estão instalados, tais como Memória, Placa de Vídeo, CPU, Discos Rígidos, BIOS, Placa de Rede, Placa Mãe, etc.',
            'te_nome_curto_modulo'=>'HARD',
            'dt_hr_alteracao'=>null,
            'cs_opcional'=>'S',
            'ativo' => true
        ),
		array(
            'id_acao'=>'col_moni',
            'te_descricao_breve'=>'Sistemas Monitorados',
            'te_descricao'=>'Essa ação permite que sejam coletadas, nas estações onde os agentes Cacic estão instalados, as informações acerca dos perfís de sistemas, previamente cadastrados pela Administração Central.',
            'te_nome_curto_modulo'=>'MONI',
            'dt_hr_alteracao'=>null,
            'cs_opcional'=>'S',
            'ativo' => false
        ),
		array(
            'id_acao'=>'col_patr',
            'te_descricao_breve'=>'Dados de Patrimônio e Localização Física',
            'te_descricao'=>'Essa ação permite que sejam coletadas informações patrimoniais e de localização física do computador como sala, setor, ramal e etc.',
            'te_nome_curto_modulo'=>'PATR',
            'dt_hr_alteracao'=>null,
            'cs_opcional'=>'S',
            'ativo' => true
        ),
		array(
            'id_acao'=>'col_soft',
            'te_descricao_breve'=>'Softwares Instalados (Inventário)',
            'te_descricao'=>'Essa ação permite que seja coletada a lista de softwares instalados nos computadores onde os agentes são executados.',
            'te_nome_curto_modulo'=>'SOFT',
            'dt_hr_alteracao'=>null,
            'cs_opcional'=>'S',
            'ativo' => true
        ),
		array(
            'id_acao'=>'col_soft_not_optional',
            'te_descricao_breve'=>'Softwares Básicos',
            'te_descricao'=>'Essa ação é não-opcional, para que os agentes coletem informações sobre softwares básicos nas estações de trabalho.',
            'te_nome_curto_modulo'=>'SOFT_NOT_OPTIONAL',
            'dt_hr_alteracao'=>null,
            'cs_opcional'=>'N',
            'ativo' => false
        ),
		array(
            'id_acao'=>'srcacic',
            'te_descricao_breve'=>'Suporte Remoto Seguro',
            'te_descricao'=>'Esta ação permite a realização de suporte remoto na estação de trabalho, com registro de logs de sessão efetuado pelo Gerente WEB.',
            'te_nome_curto_modulo'=>'SR_CACIC',
            'dt_hr_alteracao'=>null,
            'cs_opcional'=>'S',
            'ativo' => false
        )
	);

	/*
     * Carrega as configurações-padrão
     */
	public function load(ObjectManager $manager)
    {
        foreach ( $this->acoes as $a )
        {
        	$acao = new Acao();
        	$acao->setIdAcao($a['id_acao']);
        	$acao->setTeDescricaoBreve($a['te_descricao_breve']);
        	$acao->setTeDescricao($a['te_descricao']);
        	$acao->setTeNomeCurtoModulo($a['te_nome_curto_modulo']);
        	//$acao->setDtHrAlteracao( new \DateTime( $a['dt_hr_alteracao'] ) );
        	$acao->setCsOpcional($a['cs_opcional']);
            $acao->setAtivo($a['ativo']);

            // Eduardo: 06/09/2013
            // Adiciona referência ao tipo de ação para ser utilizada no mapeamento de ações e classes WMI
            $this->addReference($a['id_acao'], $acao);

            $manager->persist($acao);
        }

        $manager->flush();
    }

    public function getOrder()
    {
        return 5;
    }
}
