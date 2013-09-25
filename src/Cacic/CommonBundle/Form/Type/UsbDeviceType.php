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
        $builder->add('idUsbVendor', 'entity',
            array(
                'empty_value' => 'Selecione o Fabricante',
                'required'  => false,
                'class' => 'CacicCommonBundle:UsbVendor',
                'property' => 'nmUsbVendor',
                'label'=>'Identificador do Fabricante')
        );
        $builder->add('idUsbDevice', null,
            array(
                'label'=>'Identificador do Dispositivo',
                'max_length'=>5
            )
        );
        $builder->add('nmUsbDevice', null,
            array(
                'label'=>'Nome do Dispositivo',
                'max_length'=>127
            )
        );
        $builder->add('teObservacao', 'textarea',
            array(
                'label'=>'Observações',
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