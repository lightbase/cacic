<?php

namespace Cacic\CommonBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 *
 * Formulário de Consulta de usuários logado
 * @author lightbase
 *
 */
class LogUserLogadoType extends AbstractType
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
                    'teIpComputador'=>'Endereço IP do Coputador',
                    'nmComputador'=>'Nome do Computador',
                    'usuario'=>'Nome do Usuário',
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
            'usuario',
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
        return 'LogUsuarioLogado';
    }

}