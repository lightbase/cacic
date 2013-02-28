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
class UsbDeviceType extends AbstractType
{

    public function buildForm( FormBuilderInterface $builder, array $options )
    {
        $builder->add('idVendor', null,
            array(
                'label'=>'Identificador do Fabricante:', 'max_length'=>100
            )
        );
        $builder->add('nmVendor', null,
              array(
                  'label'=>'Nome do Fabricante:', 'max_length'=>100,
                  'mapped'=>false,
              )
        );
        $builder->add('idDevice', null,
            array(
                'label'=>'Identificador do Dispositivo:', 'max_length'=>100
            )
        );
        $builder->add('nmDevice', null,
            array(
                'label'=>'Nome do Dispositivo:', 'max_length'=>100
            )
        );
        $builder->add('teObservacao', 'textarea',
            array(
                'label'=>'Observações:', 'max_length'=>500
            )
        );
    }

    /**
     * (non-PHPdoc)
     * @see Symfony\Component\Form.FormTypeInterface::getName()
     */
    public function getName()
    {
        return 'UsbDevice';
    }

}