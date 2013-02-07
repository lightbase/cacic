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
class RedeType extends AbstractType
{

	public function buildForm( FormBuilderInterface $builder, array $options )
	{
		$builder->add( 'idLocal', 'entity',
			array(
				'empty_value' => 'Selecione o Local',
				'class' => 'CacicCommonBundle:Locais',
				'property' => 'nmLocal',
				'label' => 'Local'
			)
		);
		$builder->add( 'idServidorAutenticacao', 'entity',
			array(
				'empty_value' => 'Selecione o Servidor',
				'class' => 'CacicCommonBundle:ServidoresAutenticacao',
				'property' => 'nmServidorAutenticacao',
				'label'=>'Servidor para Autenticação:')
		);
		$builder->add(  'teIpRede',  null,
			array(
				 'label'=> 'Subrede:',
				 'max_length'=> 30
			)
		);
		$builder->add(  'nmRede',  null,
			array(
				'label'=> 'Descrição:',
				'max_length'=> 60
			)
		);
		$builder->add('teMascaraRede', null,
			array(
				'label'=>'Máscara',
				'max_length'=>20,
				'data'=>'255.255.255.0'
			)
		);
		$builder->add('teServCacic', null,
			 array(
				 'label'=>'Servidor de Aplicação: ',
				 'max_length'=>20,
			 )
		);
        $builder->add('teServCacic', 'entity',
             array(
                 'empty_value' => '==>Selecione <==',
                 'label'=>' ',
                 'class' => 'CacicCommonBundle:Redes',
                 'property' => 'teServCacic'
             )
        );
        $builder->add('teServUpdates', null,
            array(
                'label'=>'Servidor de Updates (FTP): ',
                'max_length'=>20,
            )
        );
        $builder->add('idRede', 'entity',
            array(
                'empty_value' => '==>Selecione <==',
                'label'=>' ',
                'class' => 'CacicCommonBundle:Redes',
                'property' => 'teServUpdates'
            )
        );
        $builder->add('nuPortaServUpdates', null,
             array(
                 'label' => 'Porta',
                 'data'=>'21',
                 'max_length' => 5
             )
        );
        $builder->add('nuLimiteFtp', null,
            array(
                'label' => 'Limite FTP:',
                'data'=>'100',
                'max_length' => 5
            )
        );
        $builder->add('nmUsuarioLoginServUpdates', null,
            array(
                'label' => 'Usuário do Servidor de Updates: (para AGENTE)',
                'max_length' => 20
            )
        );
        $builder->add('nmUsuarioLoginServUpdates', null,
            array(
                'label' => 'Usuário do Servidor de Updates: (para AGENTE)',
                'max_length' => 20
            )
        );
	}

	/**
	 * (non-PHPdoc)
	 * @see Symfony\Component\Form.FormTypeInterface::getName()
	 */
	public function getName()
	{
		return 'rede';
	}


}