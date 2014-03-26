<?php

namespace Cacic\RelatorioBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 *
 * @author lightbase
 *
 */
class PatrimonioType extends AbstractType
{

    public function buildForm( FormBuilderInterface $builder, array $options )
    {
        $builder->add(
            'idLocal',
            'entity',
            array(
                'class' => 'CacicCommonBundle:Local',
                'property' => 'nmLocal',
                'multiple' => true,
                'required'  => true,
                'expanded'  => true,
                'label'=> 'Selecione o Local:'
            )
        );
    }

    /**
     * (non-PHPdoc)
     * @see Symfony\Component\Form.FormTypeInterface::getName()
     */
    public function getName()
    {
        return 'rel_patrimonio';
    }

}