<?php

namespace Cacic\CommonBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Cacic\CommonBundle\Form\DataTransformer\CxDatePtBrTransformer;

/**
 *
 *
 * @author lightbase
 *
 */
class InsucessoInstalacaoPesquisaType extends AbstractType
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
    }

    /**
     * (non-PHPdoc)
     * @see Symfony\Component\Form.FormTypeInterface::getName()
     */
    public function getName()
    {
        return 'insucesso_instalacao_pesquisa';
    }

}