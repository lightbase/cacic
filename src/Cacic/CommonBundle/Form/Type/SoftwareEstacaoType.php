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
            'nrPatrimonio',
            null,
            array( 'label'=>'Patrimônio:', 'max_length'=>30 )
        );
        $builder->add(
            'idSoftware',
            'entity',
            array(
                'class' => 'CacicCommonBundle:Software',
                'property' => 'nmSoftware',
                'empty_value' => 'Selecione',
                'label'=>'Software:' )
        );
        $builder->add(
            'idAquisicao',
            null,
            array( 'label'=>'Processo de aquisicao:' )
        );

        $builder->add(
            'nmComputador',
            null,
            array( 'label'=>'Computador:', 'max_length'=>30 )
        );

        $builder->add(
            'nmComputador',
            null,
            array( 'label'=>'Computador:', 'max_length'=>30 )
        );
        
        $builder->add(
        	'dtAutorizacao',
	        'date',
	        array(
	        	'widget' => 'single_text',
            	'format' => 'dd/MM/yyyy',
            	'label'=>'Data de autorizacao:',
	        	'attr' => array('class'=>'datepicker_on')
	        )
        );
        
        $builder->add('dtExpiracaoInstalacao',
            'date',
            array(
            	'widget' => 'single_text',
                'required'=>false,
                'format' => 'dd/MM/yyyy','label'=>'Data de expiracao:',
            	'attr' => array('class'=>'datepicker_on')
            )
        );
        
        $builder->add('dtDesinstalacao',
            'date',
            array(
            	'widget' => 'single_text',
                'required'=>false,
                'format' => 'dd/MM/yyyy',
                'label'=>'Data de desinstalacao:',
            	'attr' => array('class'=>'datepicker_on')
            )
        );
        
        $builder->add(
            'nrPatrDestino',
            null,
            array( 'label'=>'Patrimonio de destino:', 'max_length'=>30 )
        );
        
        $builder->add(
            'teObservacao',
            null,
            array( 'label'=>'Observação:', 'max_length'=>200 )
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