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
		$builder->add( 'idLocal', 'entity',
						array(
							'empty_value' => 'Selecione o Local',
							'class' => 'CacicCommonBundle:Local',
							'property' => 'nmlocal',
							'label'=> 'Local Principal'
						)
		);
		
		$builder->add( 'idServidorAutenticacao', 'entity',
						array(
							'empty_value' => 'Base CACIC',
							'class' => 'CacicCommonBundle:ServidorAutenticacao',
							'property' => 'nmServidorAutenticacao',
							'required' => false,
							'label' => 'Servidor de Autenticação'
						)
		);
		
		# Monta o COMBOBOX (Select) com os locais secundários
		$builder->add( 'locaisSecundarios', null,
						array(
							'multiple' => true,
							'label'=> 'Locais Secundários',
							'required' => false
						)
		);
		
		$builder->add( 'nmUsuarioAcesso', 'text', array( 'label' => 'Login', 'max_length' => 20 ) );
		
		$builder->add( 'nmUsuarioCompleto', 'text', array( 'label' => 'Nome Completo', 'max_length' => 60 ) );
		
		$builder->add( 'teEmailsContato', 'email', array( 'label' => 'E-mail', 'required' => false, 'max_length' => 100 ) );
		
		$builder->add( 'teTelefonesContato', 'text', array( 'label' => 'Telefones para Contato', 'required' => false, 'max_length' => 100 ) );
		
		/*$builder->add( 'teSenha', 'password', array( 'label' => 'Senha', 'required' => false, 'max_length' => 60 ) );
		$builder->add( 'teSenhaConfirma', 'password', array( 'label' => ' Confirmação da Senha', 'required' => false, 'max_length' => 60, 'mapped' => false ) );*/
		
		$builder->add( 'idGrupoUsuario', 'entity',
						array(
							'empty_value' => 'Selecione o Nível de Acesso',
							'class' => 'CacicCommonBundle:GrupoUsuario',
							'property' => 'teGrupoUsuarios',
							'label'=> 'Nível de Acesso'
						)
		);
		
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