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
        $builder->add('windows_version', 'text',
            array(
                'label' => 'Versão dos Agentes Windows',
                'required' => false
            )
        );

        $builder->add('windows', 'file',
            array(
                'label' => 'Agentes Windows',
                'required' => false
            )
        );

        $builder->add('linux_version', 'text',
            array(
                'label' => 'Versão dos Agentes Linux',
                'required' => false
            )
        );

        $builder->add('linux', 'file',
            array(
                'label' => 'Agentes Linux',
                'required' => false
            )
        );
    }

} 