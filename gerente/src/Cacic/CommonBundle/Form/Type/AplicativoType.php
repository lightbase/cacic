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
class AplicativoType extends AbstractType
{

	public function buildForm( FormBuilderInterface $builder, array $options )
	{
        $builder->add( 'nmAplicativo', 'text',
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
                'label' => 'Disponibilizar Informações no Systray?
                            (ícone na bandeja da estação):'
            )
        );
        $builder->add('inDisponibilizaInfoUsuarioComum', 'choice',
            array(
                'choices' => array('s'=>'Sim',
                                   'n'=>'Não'),
                'preferred_choices' => array('n'),
                'label' => 'Disponibilizar Informações ao Usuário Comum?
                            (diferente de Administrador):'
            )
        );
        $builder->add('teDescritivo', 'textarea',
            array(
                'label' => 'Descrição:',
                'required'  => false,
                'max_length' => 200
            )
        );
        $builder->add('selCsIdeLicenca', 'choice',
            array(
                'choices' => array(
                                   '1'=>'Caminho\Chave\Valor em Registry',
                                   '2'=>'Nome/Seção/Chave de Arquivo INI'),
                'label' => 'Identificador de licença:',
                'required'  => false,
                'mapped'=>false
            )
        );
        $builder->add('csIdeLicenca', 'text',
            array(
                'label'=>' ',
                'required'  => false,
                'max_length' => 200
            )
        );
        $builder->add('selTeCarInstW9x', 'choice',
            array(
                'choices' => array(
                                '1'=>'Nome Executável',
                                '2'=>'Nome de Arquivo de Configuração',
                                '3'=>'Caminho\Chave\Valor em Registry'),
                'empty_value' => ' ',
                'required'  => false,
                'mapped'=>false,
                'label' => 'Identificador de Instalação:'
            )
        );
        $builder->add('teCarInstW9x', 'text',
            array(
                'label'=>' ',
                'required'  => false,
                'max_length' => 200
            )
        );
        $builder->add('selCsCarVerW9x', 'choice',
            array(
                'choices' => array(
                                '1'=>'Data de Arquivo',
                                '2'=>'Caminho\Chave\Valor em Registry',
                                '3'=>'Nome/Seção/Chave de Arquivo INI',
                                '4'=>'Versão de Executável'),
                'empty_value' => ' ',
                'required'  => false,
                'mapped'=>false,
                'label' => 'Identificador de Versão/Configuração:'
            )
        );
        $builder->add('csCarVerW9x', 'text',
            array(
                'label'=>' ',
                'required'  => false,
                'max_length' => 200
            )
        );
        $builder->add('selTeCarInstWnt', 'choice',
            array(
                'choices' => array(
                                '1'=>'Nome Executável',
                                '2'=>'Nome de Arquivo de Configuração',
                                '3'=>'Caminho\Chave\Valor em Registry'),
                'empty_value' => ' ',
                'required'  => false,
                'mapped'=>false,
                'label' => 'Identificador de Instalação:'
            )
        );
        $builder->add('teCarInstWnt', 'text',
            array(
                'label'=>' ',
                'required'  => false,
                'max_length' => 200
            )
        );
        $builder->add('selCsCarVerWnt', 'choice',
            array(
                'choices' => array(
                                '1'=>'Data de Arquivo',
                                '2'=>'Caminho\Chave\Valor em Registry',
                                '3'=>'Nome/Seção/Chave de Arquivo INI',
                                '4'=>'Versão de Executável'),
                'empty_value' => ' ',
                'required'  => false,
                'mapped'=>false,
                'label' => 'Identificador de Versão/Configuração:'
            )
        );
        $builder->add('csCarVerWnt', 'text',
            array(
                'label'=>' ',
                'required'  => false,
                'max_length' => 200
            )
        );
        $builder->add( 'idRede', 'entity',
            array(
                'class' => 'CacicCommonBundle:Rede',
                'property' => 'nmRede',
                'required'  => false,
                'expanded'  => true,
                'multiple'  => true,
                'label'=> 'Selecione as Redes:'
            )
        );
        $builder->add('dtAtualizacao', 'hidden');

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