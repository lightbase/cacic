<?php

namespace Cacic\CommonBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 *
 *
 * @author lightbase
 *
 */
class SoftwareEstacaoType extends AbstractType
{

    public function buildForm( FormBuilderInterface $builder, array $options )
    {
        $builder->add(
            'idSoftware',
            null,
            array(
                'property' => 'nmSoftware',
                'empty_value' => 'Selecione',
                'label'=>'Software' )
        );
        
        $builder->add(
            'idComputador',
            null,
            array(
                'empty_value' => 'Selecione',
                'label'=>'Computador' )
        );
        
        $builder->add(
            'nrPatrimonio',
            null,
            array( 'label'=>'Patrimônio' )
        );
		
        $builder->add(
            'idAquisicao',
            null,
            array( 'label'=>'Processo de aquisicao' )
        );
		
        $builder->add(
        	'dtAutorizacao',
	        'date',
	        array(
	        	'widget' => 'single_text',
            	'format' => 'dd/MM/yyyy',
            	'label'=>'Data de autorizacao',
	        	'required' => false,
	        	'attr' => array('class'=>'datepicker_on')
	        )
        );
        
        $builder->add('dtExpiracaoInstalacao',
            'date',
            array(
            	'widget' => 'single_text',
                'format' => 'dd/MM/yyyy',
                'label'=>'Data de expiracao',
            	'required' => false,
            	'attr' => array('class'=>'datepicker_on')
            )
        );
        
        $builder->add('dtDesinstalacao',
            'date',
            array(
            	'widget' => 'single_text',
                'format' => 'dd/MM/yyyy',
                'label'=>'Data de desinstalacao',
            	'required' => false,
            	'attr' => array('class'=>'datepicker_on')
            )
        );
        
        $builder->add(
            'nrPatrDestino',
            null,
            array( 'label'=>'Patrimonio de destino' )
        );
        
        $builder->add(
            'teObservacao',
            null,
            array( 'label'=>'Observação' )
        );
    }

    /**
     * (non-PHPdoc)
     * @see Symfony\Component\Form.FormTypeInterface::getName()
     */
    public function getName()
    {
        return 'SoftwareEstacao';
    }

}