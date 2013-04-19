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
		
		$builder->add( 'nmUorg', null, array( 'label'=> 'Nome da Unidade', 'max_length' => 50 ) );
		
		$builder->add( 'teEndereco', null, array( 'label'=> 'Endereço', 'max_length' => 80, 'required'=>false ) );
		
		$builder->add( 'teBairro', null, array( 'label'=> 'Bairro', 'max_length' => 30, 'required'=>false ) );
		
		$builder->add( 'teCidade', null, array( 'label'=> 'Cidade', 'max_length' => 50, 'required'=>false ) );
		
		$builder->add( 'teUf', new cxUfType(), array( 'label'=> 'UF', 'required'=>false ) );
		
		$builder->add( 'nmResponsavel', null, array( 'label'=> 'Nome', 'max_length' => 80, 'required'=>false ) );
		
		$builder->add( 'teResponsavelEmail', 'email', array( 'label'=> 'Email', 'max_length' => 50, 'required'=>false ) );
		
		$builder->add(
        	$builder->create(
        		'numResponsavelTel1', 
        		null, 
        		array( 'label'=>'Telefone 1', 'required'=>false, 'max_length' => 10 )
        	)
            ->addModelTransformer( new cxTelefoneTransformer() )
        );
        
        $builder->add(
        	$builder->create(
        		'numResponsavelTel2', 
        		null, 
        		array( 'label'=>'Telefone 2', 'required'=>false, 'max_length' => 10 )
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