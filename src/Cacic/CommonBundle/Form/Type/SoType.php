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
            array( 'label'=>'Descrição:', 'max_length'=>30 )
        );
        $builder->add(
            'sgSo',
            null,
            array( 'label'=>'Sigla:', 'max_length'=>30 )
        );
        $builder->add(
            'teSo',
            null,
            array( 'label'=>'Id Interna:', 'max_length'=>30 )
        );
        $builder->add(
            'idSo',
            null,
            array( 'label'=>'Id Externa:',
                    'max_length'=>10,
                    'read_only' =>true,
            )
        );
        $builder->add('inMswindows', 'choice',
                array(
                    'choices'=>array('S' => 'Sim',
                                    'N' => 'Não'
                    ),
                    'expanded'  => false,
                    'multiple'  => false,
                    'required'  => false,
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