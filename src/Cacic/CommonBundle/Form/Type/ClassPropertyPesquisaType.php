<?php

namespace Cacic\CommonBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Cacic\CommonBundle\Form\DataTransformer\CxDatePtBrTransformer;

/**
 *
 * Formulário de PESQUISA por Propriedade das Classes WMI (relatorio_coleta)
 * @author lightbase
 *
 */
class ClassPropertyPesquisaType extends AbstractType
{

    public function buildForm( FormBuilderInterface $builder, array $options )
    {
       $builder->add(
        	$builder->create(
        		'dataColetaInicio',
				'text',
				array(
					'data' => date('Y-m-d'),
					'label' => ''
				)
        	)
            ->addModelTransformer( new CxDatePtBrTransformer() )
        );
        
        $builder->add(
        	$builder->create(
        		'dataColetaFim',
				'text',
				array(
					'data' => date('Y-m-d'),
					'label' => ''
				)
        	)
            ->addModelTransformer( new CxDatePtBrTransformer() )
        );
    }
    /**
     * (non-PHPdoc)
     * @see Symfony\Component\Form.FormTypeInterface::getName()
     */
    public function getName()
    {
        return 'ClassPropertyPesquisa';
    }
}