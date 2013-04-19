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
class PatrimonioType extends AbstractType
{

    public function buildForm( FormBuilderInterface $builder, array $options )
    {
        $builder->add(
            'idEtiqueta', 'hidden'
        );
        $builder->add(
            'teEtiqueta',
            null,
            array( 'label'=>' ', 'max_length'=>100 )
        );
        $builder->add(
            'tePluralEtiqueta',
            null,
            array( 'label'=>' ', 'max_length'=>100 )
        );
        $builder->add(
            'teHelpEtiqueta',
            null,
            array( 'label'=>' ', 'max_length'=>100 )
        );
        $builder->add(
            'inExibirEtiqueta',
            'choice',
            array( 'choices'=>array('S'=>'Sim','N'=>'Não' ),'label'=>' ' )
        );
        $builder->add(
            'inDestacarDuplicidade',
            'choice',
            array( 'choices'=>array('S'=>'Sim','N'=>'Não' ), 'label'=>' ' )
        );
    }

    /**
     * (non-PHPdoc)
     * @see Symfony\Component\Form.FormTypeInterface::getName()
     */
    public function getName()
    {
        return 'Patrimonio';
    }

}