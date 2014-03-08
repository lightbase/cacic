<?php
/**
 * Created by PhpStorm.
 * User: eduardo
 * Date: 11/02/14
 * Time: 17:37
 */

namespace Cacic\CommonBundle\Form\Type;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class AtesteType extends  AbstractType {

    /**
     * Formulário de Ateste
     */

    public function buildForm( FormBuilderInterface $builder, array $options )
    {

        $builder->add(
            'descricao',
            'text',
            array(
                'label'=>'Descrição:',
                'disabled' => true,
                'attr' => array(
                    'value' => 'Ateste'
                )
            )
        );

        $builder->add(
            'atestado',
            'checkbox',
            array(
                'label' => 'Ateste',
                'required' => false
            )
        );

        $builder->add(
           'detalhes',
           'textarea',
           array(
               'label' => 'Insira sua justificativa para discordância',
               'required' => false
           )
        );

        $builder->add(
            'qualidade_servico',
            'textarea',
            array(
                'label' => 'Insira seus comentários sobre a qualidade do serviço'
            )
        );

    }

    /**
     * Get Name
     */

    public function getName() {
        return 'Ateste';
    }

} 