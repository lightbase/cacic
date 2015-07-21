<?php
/**
 * Created by PhpStorm.
 * User: eduardo
 * Date: 21/07/15
 * Time: 12:52
 */

namespace Cacic\RelatorioBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;


class SoftwareRelatorioType extends AbstractType
{
    public function buildForm( FormBuilderInterface $builder, array $options) {
        $builder->add(
            'nomeRelatorio',
            'text',
            array(
                'label' => 'Nome do Relatório'
            )
        );

    }

    public function getName() {
        return 'relatorio_software';
    }

}