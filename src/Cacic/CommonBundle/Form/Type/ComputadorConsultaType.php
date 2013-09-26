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
            $builder->create(
                'dtHrInclusao',
                'text',
                array(
                    'data' => date('Y-m-d'),
                    'label' => '',
                    'max_length' => 50
                )
            )
                ->addModelTransformer( new CxDatePtBrTransformer() )
        );
        $builder->add(
            $builder->create(
                'dtHrInclusaoFim',
                'text',
                array(
                    'data' => date('Y-m-d'),
                    'label' => '',

                )
            )
                ->addModelTransformer( new CxDatePtBrTransformer() )
        );
        $builder->add(
            $builder->create(
                'dtHrUltAcesso',
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
        return 'ComputadorConsulta';
    }

}