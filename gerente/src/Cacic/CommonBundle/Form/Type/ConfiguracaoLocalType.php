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
class ConfiguracaoLocalType extends AbstractType
{
	
	public function buildForm( FormBuilderInterface $builder, array $options )
    {
        $builder->add( 
        	'nmLocal', 
        	null, 
        	array( 'label'=>'Nome do Local', 'max_length'=>100 )
        );
    }

    /**
     * (non-PHPdoc)
     * @see Symfony\Component\Form.FormTypeInterface::getName()
     */
    public function getName()
    {
        return 'configuracaolocal';
    }
    
}