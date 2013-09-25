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
	
	public function getFilters()
	{
		return array(
			'traduzAtividade' => new \Twig_Filter_Method( $this, 'atividadeFilter', array('is_safe' => array('html')) )
		);
	}
	
	public function atividadeFilter( $sigla )
	{
		return '<span class="'. $this->atividades[$sigla]['class'] .'">'
					. $this->atividades[$sigla]['label'] .
				'</span>';
	}
	
	public function getName()
	{
		return 'siglas_extension';
	}
}