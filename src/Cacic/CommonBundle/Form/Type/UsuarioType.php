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
class  UsuarioType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
		# Monta o COMBOBOX (Select) com os locais primários
		$builder->add( 'localPrimario', 'entity',
						array(
							'empty_value' => 'Selecione o Local',
							'class' => 'CacicCommonBundle:Locais',
							'property' => 'nmlocal',
							'label'=> 'Local Principal'
						)
		);
		# Configura o campo locais secundários como hidden para ser tratado no próprio formulário
		$builder->add( 'idLocal', 'hidden' );

		$builder->add( 'idservidorAutenticacao', 'entity',
						array(
							'empty_value' => 'Selecione o Servidor',
							'class' => 'CacicCommonBundle:ServidoresAutenticacao',
							'property' => 'nmServidorAutenticacao',
							'label' => 'Servidor de Autenticação'
						)
		);
		# Configura o campo Servidor Autenticacao como hidden para ser tratado no próprio formulário
		$builder->add( 'idServidorAutenticacao', 'hidden' );
		
		# Monta o COMBOBOX (Select) com os locais secundários - não mapeado pois trata-se de um campo text
		$builder->add( 'SelectLocaisSecundarios', 'entity',
						array(
							'class' => 'CacicCommonBundle:Locais',
							'property' => 'nmLocal',
							'multiple' => true,
							'label'=> 'Locais Secundários',
							'required' => false,
							'mapped' => false
						)
		);
		# Configura o campo locais secundários como hidden para ser tratado no próprio formulário
		$builder->add( 'TeLocaisSecundarios', 'hidden', array( 'required' => 'false' ) );
		
		$builder->add( 'nmUsuarioAcesso', 'text', array( 'label' => 'Login', 'max_length' => 20 ) );
		
		$builder->add( 'nmUsuarioCompleto', 'text', array( 'label' => 'Nome Completo', 'max_length' => 60 ) );
		
		$builder->add( 'teEmailsContato', 'email', array( 'label' => 'Email', 'required' => false, 'max_length' => 100 ) );
		
		$builder->add( 'teTelefonesContato', 'text', array( 'label' => 'Telefones para Contato', 'required' => false, 'max_length' => 100 ) );
		
		$builder->add( 'teSenha', 'password', array( 'label' => 'Senha', 'required' => false, 'max_length' => 60 ) );
		$builder->add( 'teSenhaConfirma', 'password', array( 'label' => ' Confirmação da Senha', 'required' => false, 'max_length' => 60, 'mapped' => false ) );
		
		$builder->add( 'grupo', 'entity',
						array(
							'empty_value' => 'Selecione Acesso',
							'class' => 'CacicCommonBundle:GrupoUsuarios',
							'property' => 'teGrupoUsuarios',
							'label'=> 'Selecione Tipo de Acesso'
						)
		);
		$builder->add( 'idGrupoUsuarios', 'hidden' );
		
		$builder->add( 'te_descricao_grupo', 'textarea',
						array(
							'label' => 'Descrição do tipo de acesso',
							'read_only' => true,
							'mapped' => false
						)
		);
	}
	
	/**
	* (non-PHPdoc)
	* @see Symfony\Component\Form.FormTypeInterface::getName()
	*/
	public function getName()
	{
		return 'usuario';
	}
	
}