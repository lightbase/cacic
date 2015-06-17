<?php

namespace Cacic\CommonBundle\Twig;

class SiglasExtension extends \Twig_Extension
{
	/**
	 * Atividades possíveis (Operações no Banco de Dados)
	 * @var array
	 */
	private $atividades = array(
		'INS' => array( 'label' => 'INSERT', 'class' => 'green' ),
		'UPD' => array( 'label' => 'UPDATE', 'class' => 'yellow' ),
		'DEL' => array( 'label' => 'DELETE', 'class' => 'red' )
	);
	
	/**
	 * 
	 * Motivos de Insucesso de Instalação possíveis
	 * @var array
	 */
	private $motivosInsucessoInstalacao = array(
		0 => array( 'label' => 'Sem privilégio', 'class' => 'red' ),
        1 => array( 'label' => 'Usuário sem permissão Admin', 'class' => 'red' ),
        11 => array('label' => 'Não identificou interface de rede', 'class' => 'red'),
        99 => array('label' => 'Erro no script install-cacic-sa', 'class' => 'red')
	);
	
	/**
	 * 
	 * Sim ou Não
	 */
	private $flagSimNao = array(
		'S' => array( 'label' => 'Sim', 'class' => 'green' ),
		'N' => array( 'label' => 'Não', 'class' => 'red' )
	);
	
	public function getFilters()
	{
		return array(
			'traduzAtividade' => new \Twig_Filter_Method( $this, 'atividadeFilter', array('is_safe' => array('html')) ),
			'traduzMotivoInsucessoInstalacao' => new \Twig_Filter_Method( $this, 'motivoInsucessoInstalFilter', array('is_safe' => array('html')) ),
			'traduzFlagSimNao' => new \Twig_Filter_Method( $this, 'flagSimNaoFilter', array('is_safe' => array('html')) )
		);
	}
	
	public function atividadeFilter( $sigla )
	{
		return '<span class="'. $this->atividades[$sigla]['class'] .'">'
					. $this->atividades[$sigla]['label'] .
				'</span>';
	}
	
	public function motivoInsucessoInstalFilter( $sigla )
	{
		if ( ! array_key_exists( $sigla, $this->motivosInsucessoInstalacao ) ) {
			return 'FTP/Cópia Impossível';
		}
		
		return '<span class="'. $this->motivosInsucessoInstalacao[$sigla]['class'] .'">'
					. $this->motivosInsucessoInstalacao[$sigla]['label'] .
				'</span>';
	}
	
	public function flagSimNaoFilter( $sigla )
	{
		return '<span class="'. $this->flagSimNao[$sigla]['class'] .'">'
					. $this->flagSimNao[$sigla]['label'] .
				'</span>';
	}
	
	public function getName()
	{
		return 'siglas_extension';
	}
}