<?php

namespace Cacic\CommonBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Cacic\CommonBundle\Form\DataTransformer\CxDatePtBrTransformer;

/**
 *
 * Formulário de Consulta de computadores
 * @author lightbase
 *
 */
class ComputadorConsultaType extends AbstractType
{

    public function buildForm( FormBuilderInterface $builder, array $options )
    {

        $builder->add(
            'selConsulta',
            'choice',
            array(
                'empty_value' => 'Selecione',
                'required'  => true,
                'choices' => array(
                    'teIpComputador'=>'Consulta por IP',
                    'teNodeAddress'=>'MAC Address do Computador',
                    'nmComputador'=>'Nome do Computador'
                )
            )
        );
        $builder->add(
            'selbuscaAvancada',
            'choice',
            array(
                'empty_value' => 'Selecione',
                'required'  => true,
                'choices' => array(
                    'teIpComputador'=>'Consulta por IP',
                    'teNodeAddress'=>'MAC Address do Computador',
                    'nmComputador'=>'Nome do Computador',
                    'dtHrInclusao'=>'Data de Inclusão',
                )
            )
        );
        $builder->add(
            'teIpComputador',
            null,
            array( 'label'=>'',
                'max_length'=>30,
                'required'  => false
            )
        );
        $builder->add(
            'nmComputador',
            null,
            array( 'label'=>'', 'max_length'=>30, 'required'  => false)
        );
         $builder->add(
             'teNodeAddress',
             null,
             array( 'label'=>'', 'max_length'=>30, 'required'  => false)
         );
        $builder->add(
                'dtHrInclusao',
                'text',
                array(
                    'label' => '',
                    'required'  => false
                )
        );
        $builder->add(
                'dtHrInclusaoFim',
            'text',
                array(
                    'label' => '','required'  => false
                )
        );
    }

    /**
     * (non-PHPdoc)
     * @see Symfony\Component\Form.FormTypeInterface::getName()
     */
    public function getName()
    {
        return 'ComputadorConsulta';
    }

}