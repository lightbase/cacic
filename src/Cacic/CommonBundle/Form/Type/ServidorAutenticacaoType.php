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
        $builder->add('nmServidorAutenticacao', null, array('label' => 'Nome'));
        $builder->add('nmServidorAutenticacaoDns', null, array('label' => 'Identificador DNS'));
        $builder->add('teIpServidorAutenticacao', null,array('label' =>'Endereço IP'));
        
        $builder->add('idTipoProtocolo', 'choice',
                       array(
                           'empty_value' => 'Selecione o protocolo',
                           'label' => 'Protocolo',
                           'choices' =>array( 'LDAP' =>'LDAP', 'Open LDAP' => 'Open LDAP'),
                       )
        );
        
        $builder->add('nuPortaServidorAutenticacao', null,
            array(
                'data'=> '389',
                'read_only' => true,
                'label' => 'Porta'
            )
        );
        
        $builder->add('nuVersaoProtocolo', null,
                       array(
                           'label' => 'Versão'
                       )
        );

        $builder->add('usuario', null, array( 'label'=>'Usuário do LDAP' ));
        $builder->add('senha', null, array( 'label'=>'Senha do usuário' ));
        $builder->add('teObservacao', null, array( 'label'=>'Observação' ));
        $builder->add('teAtributoIdentificador', null,array('label'=>'Identificador'));
        $builder->add('teAtributoRetornaNome', 'text',array('label'=>'Retorno de Nome Completo'));
        $builder->add('teAtributoRetornaEmail', 'email',array('label'=>'Retorno de Email'));
        $builder->add('teAtributoStatusConta', 'hidden',array('data'=> 'accountstatus'));
        $builder->add('teAtributoValorStatusContaValida', 'hidden',array('data'=> 'active'));
        
        $builder->add('inAtivo', 'choice',
            array(
                'choices' => array ( 'S' => 'Sim', 'N' =>'Não'),
                'label'=>'Servidor Ativo:',
                'required'  => true,
                'multiple'=> false,
                'expanded'  => true
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