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
class UserPesquisaType extends AbstractType
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
        $builder->add(
            'teIpComputador',
            null,
            array(
                'label'  =>'IP do Computador',
                'max_length'=>30,
                'required'  => false
            )
        );
        $builder->add(
            'teNodeAddress',
            null,
            array(
                'label'   =>'MAC Address',
                'max_length'=>30,
                'required'  => false
            )
        );
        $builder->add(
            'nmComputador',
            null,
            array(
                'label'   =>'Nome do Computador',
                'max_length'=>30,
                'required'  => false
            )
        );
        $builder->add(
            'sala',
            null,
            array(
                'label'  =>'Sala do Responsável',
                'max_length'=>30,
                'required'  => false
            )
        );
        $builder->add(
            'coordenacao',
            null,
            array(
                'label' => 'Coordenação do Responsável',
                'max_length'=>30,
                'required'  => false
            )
        );
        $builder->add(
            'usuarioPatrimonio',
            null,
            array(
                'label'  =>'Nome do Responsável',
                'max_length'=>30,
                'required'  => false)
        );
        $builder->add(
            'usuarioName',
            null,
            array(
                'label'  =>'CPF do Responsável',
                'max_length'=>30,
                'required'  => false
            )
        );
        $builder->add(
            'usuarioLogado',
            null,
            array(
                'label'  =>'Usuário Logado',
                'max_length'=>30,
                'required'  => false
            )
        );
        $builder->add(
            'macCompDinamico',
            null,
            array(
                'label'   =>'MAC Address',
                'max_length'=>30,
                'required'  => false
            )
        );
        $builder->add(
            'ipCompDinamico',
            null,
            array(
                'label'   =>'IP do Computador',
                'max_length'=>30,
                'required'  => false
            )
        );
    }

    /**
     * (non-PHPdoc)
     * @see Symfony\Component\Form.FormTypeInterface::getName()
     */
    public function getName()
    {
        return 'UserPesquisa';
    }

}