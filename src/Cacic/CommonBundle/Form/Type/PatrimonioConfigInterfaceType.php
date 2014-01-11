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
class PatrimonioConfigInterfaceType extends AbstractType
{

    public function buildForm( FormBuilderInterface $builder, array $options )
    {

        $builder->add(
            'teEtiqueta',
            null,
            array( 'label'=>' ', 'max_length'=>30 )
        );
        $builder->add(
            'tePluralEtiqueta',
            null,
            array( 'label'=>' ', 'max_length'=>30 )
        );
        $builder->add(
            'teHelpEtiqueta',
            null,
            array( 'label'=>' ', 'max_length'=>50 )
        );
        $builder->add(
            'inExibirEtiqueta',
            'choice',
            array( 'choices'=>array('S'=>'Sim','N'=>'Não' ),'label'=>' ' )
        );
        $builder->add(
            'inDestacarDuplicidade',
            'choice',
            array( 'choices'=>array('S'=>'Sim','N'=>'Não' ),
                'preferred_choices' => array('S'),
                'label'=>' ' )
        );
        $builder->add(
            'inObrigatorio',
            'choice',
            array( 'choices'=>array('S'=>'Sim','N'=>'Não' ),
                'preferred_choices' => array('S'),
                'label'=>' '
            )
        );
    }

    /**
     * (non-PHPdoc)
     * @see Symfony\Component\Form.FormTypeInterface::getName()
     */
    public function getName()
    {
        return 'idEtiqueta';
    }

}