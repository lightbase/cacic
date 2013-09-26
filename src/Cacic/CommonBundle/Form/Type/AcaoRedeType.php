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
class AcaoRedeType extends AbstractType
{
	
	public function buildForm( FormBuilderInterface $builder, array $options )
    {
        
    }
    
	public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Cacic\CommonBundle\Entity\AcaoRede',
        ));
    }

    /**
     * (non-PHPdoc)
     * @see Symfony\Component\Form.FormTypeInterface::getName()
     */
    public function getName()
    {
        return 'acao_rede';
    }
    
}