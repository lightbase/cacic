<?php
/**
 * Created by PhpStorm.
 * User: eduardo
 * Date: 28/08/15
 * Time: 12:46
 */

namespace Cacic\CommonBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ClasseType extends AbstractType
{
    public function buildForm( FormBuilderInterface $builder, array $options )
    {
        $builder->add(
            'nmClassName',
            'text',
            array(
                'label' => 'Nome da Classe',
                'required' => true
            )
        );

        $builder->add(
            'teClassDescription',
            'textarea',
            array(
                'label' => 'Descrição',
                'required' => true,
                'attr' => array(
                    'rows' => 8
                )
            )
        );

    }

    public function getName()
    {
        return 'classe';
    }

}