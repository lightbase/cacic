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
class ConfiguracaoPadraoType extends AbstractType
{
	
	public function buildForm( FormBuilderInterface $builder, array $options )
    {
        
    }

    /**
     * (non-PHPdoc)
     * @see Symfony\Component\Form.FormTypeInterface::getName()
     */
    public function getName()
    {
        return 'configuracaopadrao';
    }
    
}