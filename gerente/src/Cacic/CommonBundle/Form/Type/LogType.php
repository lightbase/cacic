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
class LogType extends AbstractType
{

    public function buildForm( FormBuilderInterface $builder, array $options )
    {
       $builder->add('dtAcao', 'text',
                array(
                    'label'=>' ',
                    'data'=>date('d/m/Y'),
                    'required'=>false,
                )
       );
        $builder->add('dtAcao1', 'text',
            array(
                'label'=>' ',
                'required'=>false,
                'data'=>date('d/m/Y'),
                'mapped'=>false
            )
        );

        $builder->add( 'idRede', 'entity',
            array(
                'empty_value' => ' ',
                'class' => 'CacicCommonBundle:Rede',
                'property' => 'nmrede',
                'multiple' => true,
                'required'  => false,
                'mapped'=>false,
                'label'=> 'Disponíveis:'
            )
        );
        $builder->add( 'idRede1', 'choice',
            array(
                'empty_value' => ' ',
                'multiple' => true,
                'required'  => false,
                'mapped'=>false,
                'label'=> 'Selecionada:'
            )
        );
    }

    /**
     * (non-PHPdoc)
     * @see Symfony\Component\Form.FormTypeInterface::getName()
     */
    public function getName()
    {
        return 'log';
    }

}