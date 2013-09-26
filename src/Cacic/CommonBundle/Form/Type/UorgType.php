<?php

namespace Cacic\CommonBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Cacic\CommonBundle\Form\DataTransformer\cxTelefoneTransformer;

/**
 * 
 * 
 * @author lightbase
 *
 */
class  UorgType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
    	# Monta o COMBOBOX (Select) com os TIPOS DE UORG
		$builder->add( 'tipoUorg', 'entity',
						array(
							'empty_value' => '-- Selecione --',
							'class' => 'CacicCommonBundle:TipoUorg',
							'property' => 'nmTipoUorg',
							'label' => 'Tipo da Unidade',
							'required' => true, 
						)
		);
		
		# Monta o COMBOBOX (Select) com os LOCAIS
		$builder->add( 'local', 'entity',
						array(
							'empty_value' => '-- Selecione --',
							'class' => 'CacicCommonBundle:Local',
							'property' => 'nmlocal',
							'label' => 'Local',
							'required' =>false, 
						)
		);
		
		$builder->add( 'nmUorg', null, array( 'label'=> 'Nome da Unidade' ) );
		
		$builder->add( 'teEndereco', null, array( 'label'=> 'Endereço', 'required'=>false ) );
		
		$builder->add( 'teBairro', null, array( 'label'=> 'Bairro', 'required'=>false ) );
		
		$builder->add( 'teCidade', null, array( 'label'=> 'Cidade', 'required'=>false ) );
		
		$builder->add( 'teUf', new cxUfType(), array( 'label'=> 'UF', 'required'=>false ) );
		
		$builder->add( 'nmResponsavel', null, array( 'label'=> 'Nome', 'required'=>false ) );
		
		$builder->add( 'teResponsavelEmail', 'email', array( 'label'=> 'Email', 'required'=>false ) );
		
		$builder->add(
        	$builder->create(
        		'nuResponsavelTel1', 
        		null, 
        		array( 'label'=>'Telefone 1', 'required'=>false )
        	)
            ->addModelTransformer( new cxTelefoneTransformer() )
        );
        
        $builder->add(
        	$builder->create(
        		'nuResponsavelTel2', 
        		null, 
        		array( 'label'=>'Telefone 2', 'required'=>false )
        	)
            ->addModelTransformer( new cxTelefoneTransformer() )
        );
		
	}
	
	/**
	* (non-PHPdoc)
	* @see Symfony\Component\Form.FormTypeInterface::getName()
	*/
	public function getName()
	{
		return 'uorg';
	}
	
}