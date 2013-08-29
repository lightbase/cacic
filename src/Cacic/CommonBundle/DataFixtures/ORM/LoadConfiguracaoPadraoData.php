<?php

namespace Cacic\CommonBundle\DataFixtures\ORM;

use Cacic\CommonBundle\Entity\ConfiguracaoPadrao;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/*
 * Carrega Configurações-Padrão
 */

class LoadConfiguracaoPadraoData extends AbstractFixture implements FixtureInterface, OrderedFixtureInterface
{
	
	private $configuracoesPadrao = array(
		array('cs_abre_janela_patr', 'Exibe janela de patrimônio', 'S'),
		array('id_default_body_bgcolor', 'Código HEXADECIMAL da cor-de-fundo da página', null),
		array('id_default_topnavbar_bgcolor', 'Código HEXADECIMAL da cor-de-fundo da barra fixa superior', null),
		array('te_logo_path', 'Logotipo da Organização', null),
		array('in_exibe_bandeja', 'Exibir o icone do CACIC na bandeja (systray)', 'N'),
		array('in_exibe_erros_criticos', 'Exibir erros criticos aos usuarios', 'N'),
		array('nm_organizacao', 'Nome da organização', 'Organização CACIC'),
		array('nu_exec_apos', 'Inicio de execucao das ações (em minutos)', '1111'),
		array('nu_intervalo_exec', 'Intervalo de execução das ações (em horas)', '4'),
		array('nu_intervalo_renovacao_patrim', 'nu_intervalo_renovacao_patrim', '0'),
		array('nu_porta_srcacic', 'nu_porta_srcacic', '5900'),
		array('nu_rel_maxlinhas', 'Quantidade máxima de linhas em relatorios', '1000'),
		array('nu_resolucao_grafico_h', 'Resolucao dos graficos a serem exibidos (Altura)', '0'),
		array('nu_resolucao_grafico_w', 'Resolucao dos graficos a serem exibidos (Largura)', '0'),
		array('nu_timeout_srcacic', 'nu_timeout_srcacic', '30'),
		array('te_enderecos_mac_invalidos', 'Endereços MAC a desconsiderar', null),
		array('te_exibe_graficos', 'Gráficos a serem exibidos', 'so,acessos_locais,locais'),
		array('te_janelas_excecao', 'Aplicativos (janelas) a evitar', null),
		array('te_notificar_mudanca_hardware', 'E-mails para notificar alteracoes de hardware', null),
		array('te_notificar_utilizacao_usb', 'E-mails para notificar utilizacao de dispositivos USB', null),
		array('te_senha_adm_agente', 'Senha padrão para administrar o agente', '123abc123abc123a'),
		array('te_serv_cacic_padrao', 'Nome ou IP do servidor de aplicação (gerente)', null),
		array('te_serv_updates_padrao', 'Nome ou IP do servidor de atualização (FTP)', null)
	);

	/*
     * Carrega as configurações-padrão
     */
	public function load(ObjectManager $manager)
    {
        foreach ( $this->configuracoesPadrao as $conf )
        {
        	$configuracaoPadrao = new ConfiguracaoPadrao();
        	$configuracaoPadrao->setIdConfiguracao( $conf[0] );
        	$configuracaoPadrao->setNmConfiguracao( $conf[1] );
        	$configuracaoPadrao->setVlConfiguracao( $conf[2] );
        	
        	$manager->persist($configuracaoPadrao);
        }

        $manager->flush();
    }

    public function getOrder()
    {
        return 4;
    }
}