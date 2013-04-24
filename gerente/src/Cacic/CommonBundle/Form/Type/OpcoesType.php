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
class OpcoesType extends AbstractType
{

    public function buildForm( FormBuilderInterface $builder, array $options )
    {
        $builder->add( 'nmLocal', 'text',
            array(
                'required'  => false,
                'read_only'=>true,
                'mapped'=>false,
                'label'=> ' '
            )
        );

        $builder->add( 'inDestacarDuplicidade', 'entity',
            array(
                'class' => 'CacicCommonBundle:PatrimonioConfigInterface',
                'property' => 'teEtiqueta',
                'required'  => false,
                'expanded'  => true,
                'multiple'  => true,
                'label'=> ' '
            )
        );
    }

    /**
     * (non-PHPdoc)
     * @see Symfony\Component\Form.FormTypeInterface::getName()
     */
    public function getName()
    {
        return 'opcoes';
    }

}