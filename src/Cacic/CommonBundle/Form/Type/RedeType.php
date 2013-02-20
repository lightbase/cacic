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
        $builder->add('selTeServCacic', 'entity',
             array(
                 'empty_value' => '==>Selecione <==',
                 'label'=>' ',
                 'mapped'=>false,
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
        $builder->add('selTeServUpdates', 'entity',
            array(
                'empty_value' => '==>Selecione <==',
                'label'=>' ',
                'mapped'=>false,
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
        $builder->add('teSenhaLoginServUpdates', 'password',
            array(
                'label' => 'Senha para Login:',
                'max_length' => 20
            )
        );
        $builder->add('nmUsuarioLoginServUpdatesGerente', null,
            array(
                'label' => 'Usuário do Servidor de Updates: (para GERENTE)',
                'max_length' => 20
            )
        );
        $builder->add('teSenhaLoginServUpdatesGerente', 'password',
            array(
                'label' => 'Senha para Login:',
                'max_length' => 20
            )
        );
        $builder->add('tePathServUpdates', null,
             array(
                 'label' => 'Path no Servidor de Updates:',
                 'max_length' => 20
             )
         );
        $builder->add('teObservacao', 'textarea',
            array(
                'label' => 'Observações:',
                'required'  => false,
                'max_length' => 200
            )
        );
        $builder->add('nmPessoaContato1', null,
            array(
                'label' => 'Contato 1:',
                'required'  => false,
                'max_length' => 20
            )
        );
        $builder->add('nuTelefone1', 'text',
            array(
                'label' => 'Telefone:',
                'required'  => false,
                'max_length' => 10
            )
        );
        $builder->add('teEmailContato1', 'email',
            array(
                'label' => 'E-mail:',
                'required'  => false,
                'max_length' => 10
            )
        );
        $builder->add('nmPessoaContato2', null,
            array(
                'label' => 'Contato 2:',
                'required'  => false,
                'max_length' => 20
            )
        );
        $builder->add('nuTelefone2', 'text',
            array(
                'label' => 'Telefone:',
                'required'  => false,
                'max_length' => 10
            )
        );
        $builder->add('teEmailContato2', 'email',
            array(
                'label' => 'E-mail:',
                'max_length' => 20,
                'required'  => false
            )
        );
        $builder->add('habilitar', 'choice',
            array(
                'choices'   => array('s' => 'Sim', 'n' => 'Não'),
                'required'  => false,
                'expanded'  => true,
                'mapped'=>false,
                'label' => ' '

            )
        );
        $builder->add('csPermitirDesativarSrcacic', 'choice',
            array(
                'choices'   => array('s' => 'Sim', 'n' => 'Não'),
                'required'  => false,
                'expanded'  => true,
                'label' => ' '

            )
        );
        $builder->add('idAplicativo', 'entity',
             array(
                 'class' => 'CacicCommonBundle:PerfisAplicativosMonitorados',
                 'property' => 'nmAplicativo',
                 'required'  => false,
                 'mapped'=>false,
                 'expanded'  => true,
                 'label' => ' '
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