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
class SoftwareType extends AbstractType
{

    public function buildForm( FormBuilderInterface $builder, array $options )
    {
        $builder->add(
            'nmSoftware',
            null,
            array( 'label'=>'Nome', 'max_length'=>150 )
        );
        $builder->add(
            'teDescricaoSoftware',
            null,
            array( 'label'=>'Descrição', 'max_length'=>255 )
        );
        $builder->add(
            'qtLicenca',
            null,
            array( 'label'=>'Quantidade de Licenças' )
        );
        $builder->add(
            'nrMidia',
            null,
            array( 'label'=>' Número da Mídia', 'max_length'=>10 )
        );
        $builder->add(
            'teLocalMidia',
            null,
            array( 'label'=>' Localização da Mídia', 'max_length'=>30 )
        );
        $builder->add(
            'teObs',
            null,
            array( 'label'=>' Observação', 'max_length'=>200 )
        );
    }

    /**
     * (non-PHPdoc)
     * @see Symfony\Component\Form.FormTypeInterface::getName()
     */
    public function getName()
    {
        return 'software';
    }

}