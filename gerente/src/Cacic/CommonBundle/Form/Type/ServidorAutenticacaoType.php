<?php

namespace Cacic\CommonBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 *
 *
 * @author lightbase
 *
 */
class  ServidorAutenticacaoType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
        $builder->add('nmServidorAutenticacao', 'text',
                      array(
                            'label' => 'Nome:',
                            'max_length' => 60
                      )
        );
        $builder->add('nmServidorAutenticacaoDns', 'text',
            array(
                'label' => 'Identificador no DNS:',
                'max_length' => 60
            )
        );
        $builder->add('teIpServidorAutenticacao', 'text',
                      array(
                            'label' =>'Endereço IP:',
                            'max_length' => 30
                      )
        );
        $builder->add('idTipoProtocolo', 'choice',
                       array(
                           'empty_value' => 'Selecione o protocolo',
                           'label' => 'Protocolo:',
                           'choices' =>array( 'LDAP' =>'LDAP', 'Open LDAP' => 'Open LDAP'),
                       )
        );
        $builder->add('nuPortaServidorAutenticacao', 'text',
            array(
                'data'=> '389',
                'read_only' => true,
                'label' => 'Porta:',
                'max_length' => 10
            )
        );
        $builder->add('nuVersaoProtocolo', 'text',
                       array(
                           'label' => 'Versão:',
                           'max_length' => 10
                       )
        );
        $builder->add('teObservacao', 'textarea',
                       array(
                           'label'=>'Observação:',
                           'required' => false

                       )
        );
        $builder->add('teAtributoIdentificador', 'text',
                      array(
                           'label'=>'Identificador:'
                      )
        );
        $builder->add('teAtributoIdentificador', 'text',
            array(
                'label'=>'Identificador:'
            )
        );
        $builder->add('teAtributoRetornaNome', 'text',
            array(
                'label'=>'Retorno de Nome Completo:'
            )
        );
        $builder->add('teAtributoRetornaEmail', 'email',
            array(
                'label'=>'Retorno de Email:'
            )
        );
        $builder->add('teAtributoStatusConta', 'hidden',
            array(
                'data'=> 'accountstatus',
            )
        );
        $builder->add('teAtributoValorStatusContaValida', 'hidden',
            array(
                'data'=> 'active',
            )
        );
        $builder->add('inAtivo', 'hidden',
            array(
                'data'=> 'S'
            )
        );
        $builder->add('inAtivo', 'choice',
            array(
                'label' => 'Servidor Ativo:',
                'choices' =>array( 'S' =>'Sim',
                                   'N' =>'Não'),
                'required'  => false,
                'expanded'  => true,
            )
        );

    }
    /**
     * (non-PHPdoc)
     * @see Symfony\Component\Form.FormTypeInterface::getName()
     */
    public function getName()
    {
        return 'ServidorAutenticacao';
    }

}