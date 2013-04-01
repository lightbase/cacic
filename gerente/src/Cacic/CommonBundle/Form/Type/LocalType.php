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
class LocalType extends AbstractType
{
	
	public function buildForm( FormBuilderInterface $builder, array $options )
    {
        $builder->add( 
        	'nmLocal',
        	null, 
        	array( 'label'=>'Nome do Local', 'max_length'=>100 )
        );
        
        $builder->add(
        	'sgLocal',
        	null,
        	array( 'label'=>'Sigla do Local', 'max_length'=>20 )
        );
        
        $builder->add(
        	'teObservacao',
        	'textarea',
        	array( 'label'=>'Observação', 'max_length'=>255, 'required'=>false )
        );
    }

    /**
     * (non-PHPdoc)
     * @see Symfony\Component\Form.FormTypeInterface::getName()
     */
    public function getName()
    {
        return 'local';
    }
    
}