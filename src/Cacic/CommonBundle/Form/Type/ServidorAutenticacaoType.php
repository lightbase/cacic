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

        $builder->add('teIpServidorAutenticacao', 'text',
                      array(
                            'label' =>'Endereço IP:',
                            'max_length' => 30
                      )
        );

        $builder->add('idTipoProtocolo', 'entity',
                       array(
                           'class' => 'CacicCommonBundle:ServidoresAutenticacao',
                           'property' => 'idTipoProtocolo',
                           'label' => 'Protocolo:'
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