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
class ModuloType extends AbstractType
{
	
	public function buildForm( FormBuilderInterface $builder, array $options )
    {
        
    }
    
	public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => false,
        ));
    }

    /**
     * (non-PHPdoc)
     * @see Symfony\Component\Form.FormTypeInterface::getName()
     */
    public function getName()
    {
        return 'modulo';
    }
    
}