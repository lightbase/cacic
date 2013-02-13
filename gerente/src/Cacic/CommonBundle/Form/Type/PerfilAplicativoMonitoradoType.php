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
class PerfilAplicativoMonitoradoType extends AbstractType
{

	public function buildForm( FormBuilderInterface $builder, array $options )
	{
        $builder->add( 'nmAplicativo', null,
            array(
                'max_length' => 50,
                'label' => 'Nome do sistema:'
            )
        );
        $builder->add('idSo', 'entity',
            array(
                'class' => 'CacicCommonBundle:So',
                'empty_value' => ' ',
                'property' => 'teDescSo',
                'label' => 'Qual é o sistema Operacional?'
            )
        );
        $builder->add('inDisponibilizaInfo', 'choice',
            array(
                'choices' => array('s'=>'Sim',
                                   'n'=>'Não'),
                'preferred_choices' => array('n'),
                'label' => 'Disponibilizar Informações no Systray? (ícone na bandeja da estação):'
            )
        );
        $builder->add('inDisponibilizaInfoUsuarioComum', 'choice',
            array(
                'choices' => array('s'=>'Sim',
                                   'n'=>'Não'),
                'preferred_choices' => array('n'),
                'label' => 'Disponibilizar Informações ao Usuário Comum? (diferente de Administrador):'
            )
        );
        $builder->add('teDescritivo', 'textarea',
            array(
                'label' => 'Descrição:',
                'required'  => false,
                'max_length' => 200
            )
        );
        $builder->add('csIdeLicenca', 'choice',
            array(
                'choices' => array('1'=>'Caminho\Chave\Valor em Registry',
                                   '2'=>'Nome/Seção/Chave de Arquivo INI'),
                'empty_value' => ' ',
                'required'  => false,
                'label' => 'Disponibilizar Informações ao Usuário Comum? (diferente de Administrador):'
            )
        );
        $builder->add('csIdeLicenca1', 'text',
            array(
                'label'=>' ',
                'required'  => false,
                'mapped'=>false,
                'max_length' => 200
            )
        );
        $builder->add('teCarInstW9x', 'choice',
            array(
                'choices' => array('1'=>'Nome Executável',
                    '2'=>'Nome de Arquivo de Confiração',
                    '3'=>'Caminho\Chave\Valor em Registry'),
                'empty_value' => ' ',
                'required'  => false,
                'label' => 'Identificador de Instalação:'
            )
        );
        $builder->add('teCarInstW9x1', 'text',
            array(
                'label'=>' ',
                'required'  => false,
                'mapped'=>false,
                'max_length' => 200
            )
        );
        $builder->add('csCarVerW9x', 'choice',
            array(
                'choices' => array('1'=>'Data de Arquivo',
                    '2'=>'Caminho\Chave\Valor em Registry',
                    '3'=>'Nome/Seção/Chave de Arquivo INI',
                    '2'=>'Versão de Executável'),
                'empty_value' => ' ',
                'required'  => false,
                'label' => 'Identificador de Versão/Configuração:'
            )
        );
        $builder->add('csCarVerW9x1', 'text',
            array(
                'label'=>' ',
                'required'  => false,
                'mapped'=>false,
                'max_length' => 200
            )
        );
        $builder->add('teCarInstWnt', 'choice',
            array(
                'choices' => array('1'=>'Nome Executável',
                    '2'=>'Nome de Arquivo de Confiração',
                    '3'=>'Caminho\Chave\Valor em Registry'),
                'empty_value' => ' ',
                'required'  => false,
                'label' => 'Identificador de Instalação:'
            )
        );
        $builder->add('teCarInstWnt1', 'text',
            array(
                'label'=>' ',
                'required'  => false,
                'mapped'=>false,
                'max_length' => 200
            )
        );
        $builder->add('csCarVerWnt', 'choice',
            array(
                'choices' => array('1'=>'Data de Arquivo',
                    '2'=>'Caminho\Chave\Valor em Registry',
                    '3'=>'Nome/Seção/Chave de Arquivo INI',
                    '2'=>'Versão de Executável'),
                'empty_value' => ' ',
                'required'  => false,
                'label' => 'Identificador de Versão/Configuração:'
            )
        );
        $builder->add('csCarVerWnt1', 'text',
            array(
                'label'=>' ',
                'required'  => false,
                'mapped'=>false,
                'max_length' => 200
            )
        );
}
    /**
     * (non-PHPdoc)
     * @see Symfony\Component\Form.FormTypeInterface::getName()
     */
    public function getName()
    {
        return 'aplicativo';
    }


}