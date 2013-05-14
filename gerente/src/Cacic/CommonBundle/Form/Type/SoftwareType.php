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
            array( 'label'=>'Nome' )
        );
        
        $builder->add(
        	'idTipoSoftware',
        	null,
        	array( 'label'=>'Tipo de Software (Classificação)' )
        );
        
        $builder->add(
            'teDescricaoSoftware',
            null,
            array( 'label'=>'Descrição' )
        );
        
        $builder->add(
            'qtLicenca',
            null,
            array( 'label'=>'Quantidade de Licenças' )
        );
        
        $builder->add(
            'nrMidia',
            null,
            array( 'label'=>' Número da Mídia' )
        );
        
        $builder->add(
            'teLocalMidia',
            null,
            array( 'label'=>' Localização da Mídia' )
        );
        
        $builder->add(
            'teObs',
            null,
            array( 'label'=>' Observação' )
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