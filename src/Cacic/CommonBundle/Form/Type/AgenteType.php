<?php
/**
 * Created by PhpStorm.
 * User: eduardo
 * Date: 18/10/14
 * Time: 23:36
 */

namespace Cacic\CommonBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Formulário para upload dos agentes
 *
 * Class AgenteType
 * @package Cacic\CommonBundle\Form\Type
 */
class AgenteType extends AbstractType {

    /**
     * Nome do Formulário
     * @return string
     */

    public function getName() {
        return 'agentes';
    }

    public function buildForm( FormBuilderInterface $builder, array $options )
    {
        $tipo_so = $options['tipo_so'];

        $builder->add('version', 'text',
            array(
                'label' => 'Versão dos Agentes',
                'required' => false
            )
        );

        foreach($tipo_so as $so) {
            $builder->add($so->getTipo(), 'file',
                array(
                    'label' => 'Agentes para SO Tipo '.$so->getTipo(),
                    'required' => false
                )
            );
        }

    }

    /**
     * Add TipoSo as required option
     *
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver
            ->setRequired(array(
                'tipo_so',
            ));

    }


} 