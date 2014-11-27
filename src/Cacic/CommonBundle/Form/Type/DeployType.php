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
class DeployType extends AbstractType {

    /**
     * Nome do Formulário
     * @return string
     */

    public function getName() {
        return 'deploy';
    }

    public function buildForm( FormBuilderInterface $builder, array $options )
    {

        $builder->add('outros', 'file',
            array(
                'label' => 'Software para Deploy',
                'required' => false
            )
        );

    }

} 