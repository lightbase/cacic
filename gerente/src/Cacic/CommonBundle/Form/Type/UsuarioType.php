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

		$builder->add( 'idLocal', 'entity',
						array(
							'empty_value' => 'Selecione Local',
							'class' => 'CacicCommonBundle:Locais',
							'property' => 'nmlocal',
							'label'=> 'Local Principal',
						)
		);

		$builder->add( 'idServidorAutenticacao', 'hidden', array( 'data' => '1', 'required' => false ) );
		
		$builder->add( 'TeLocaisSecundarios', 'entity',
						array(
							'class' => 'CacicCommonBundle:Locais',
							'property' => 'nmLocal',
							'multiple' => true,
							'label'=> 'Locais Secundários',
							'required' => false
						)
		);
		
		$builder->add( 'nmUsuarioAcesso', 'text', array( 'label' => 'Login', 'max_length' => 20 ) );
		
		$builder->add( 'nmUsuarioCompleto', 'text', array( 'label' => 'Nome Completo', 'max_length' => 60 ) );
		
		$builder->add( 'teEmailsContato', 'email', array( 'label' => 'Email', 'required' => false, 'max_length' => 100 ) );
		
		$builder->add( 'teTelefonesContato', 'number', array( 'label' => 'Telefone para Contato', 'required' => false, 'max_length' => 100 ) );
		
		$builder->add( 'teSenha', 'password', array( 'label' => 'Senha', 'required' => false, 'max_length' => 60 ) );
		
		$builder->add( 'Grupo', 'entity',
						array(
							'empty_value' => 'Selecione Acesso',
							'class' => 'CacicCommonBundle:GrupoUsuarios',
							'property' => 'teGrupoUsuarios',
							'label'=> 'Selecione Tipo de Acesso'
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