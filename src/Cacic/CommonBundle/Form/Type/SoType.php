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
class SoType extends AbstractType
{

    public function buildForm( FormBuilderInterface $builder, array $options )
    {
        $builder->add(
            'teDescSo',
            null,
            array( 'label'=>'Descrição' )
        );
        $builder->add(
            'sgSo',
            null,
            array( 'label'=>'Sigla' )
        );
        $builder->add(
            'teSo',
            null,
            array( 'label'=>'Id Interna' )
        );
        $builder->add(
            'idSo',
            null,
            array( 'label'=>'Id Externa',
                    'read_only' =>true
            )
        );
        $builder->add('inMswindows', 'choice',
                array(
                    'choices'=>array('S' => 'Sim',
                                    'N' => 'Não'
                    ),
                    'expanded'  => true,
                    'multiple'  => false,
                    'label'=>'Sistema Operacional MS-Windows'
                    )
        );
    }

    /**
     * (non-PHPdoc)
     * @see Symfony\Component\Form.FormTypeInterface::getName()
     */
    public function getName()
    {
        return 'So';
    }

}