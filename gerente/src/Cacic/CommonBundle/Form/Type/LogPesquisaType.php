<?php

namespace Cacic\CommonBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Cacic\CommonBundle\Form\DataTransformer\CxDatePtBrTransformer;

/**
 *
 * Formulário de PESQUISA por LOGs de acesso ou atividades
 * @author lightbase
 *
 */
class LogPesquisaType extends AbstractType
{

    public function buildForm( FormBuilderInterface $builder, array $options )
    {
       $builder->add(
        	$builder->create(
        		'dtAcaoInicio',
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
        		'dtAcaoFim',
				'text',
				array(
					'data' => date('Y-m-d'),
					'label' => ''
				)
        	)
            ->addModelTransformer( new CxDatePtBrTransformer() )
        );
		
		$builder->add(
			'idLocal',
			'entity',
			array(
				'class' => 'CacicCommonBundle:Local',
				'property' => 'nmLocal',
                'multiple' => true,
                'required'  => true,
                'expanded'  => true,
                'label'=> 'Selecione o Local:'
			)
		);
    }

    /**
     * (non-PHPdoc)
     * @see Symfony\Component\Form.FormTypeInterface::getName()
     */
    public function getName()
    {
        return 'log_pesquisa';
    }

}