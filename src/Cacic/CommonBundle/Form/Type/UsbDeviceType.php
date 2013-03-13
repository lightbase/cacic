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
        $builder->add('idUsbVendor', 'text',
            array(
                'label'=>'Identificador do Fabricante:',
                'max_length'=>100
            )
        );
        $builder->add('nmUsbVendor', 'text',
              array(
                  'label'=>'Nome do Fabricante:',
                  'max_length'=>100,
              )
        );
        $builder->add('idUsbDevice', null,
            array(
                'label'=>'Identificador do Dispositivo:',
                'max_length'=>100
            )
        );
        $builder->add('nmUsbDevice', null,
            array(
                'label'=>'Nome do Dispositivo:',
                'max_length'=>100
            )
        );
        $builder->add('teObservacao', 'textarea',
            array(
                'label'=>'Observações:',
                'max_length'=>500,
                'required'  => false
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