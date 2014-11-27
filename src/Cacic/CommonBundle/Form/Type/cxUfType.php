<?php

namespace Cacic\CommonBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * 
 * Custom form type
 * Campo do tipo UF, que exibe lista das UFs brasileiras
 * @author CreatiX
 *
 */
class cxUfType extends AbstractType
{
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
        	array(
        		'choices' => array(
	                "AC"=>"Acre",
					"AL"=>"Alagoas",
					"AM"=>"Amazonas",
					"AP"=>"Amapá",
					"BA"=>"Bahia",
					"CE"=>"Ceará",
					"DF"=>"Distrito Federal",
					"ES"=>"Espírito Santo",
					"GO"=>"Goiás",
					"MA"=>"Maranhão",
					"MT"=>"Mato Grosso",
					"MS"=>"Mato Grosso do Sul",
					"MG"=>"Minas Gerais",
					"PA"=>"Pará",
					"PB"=>"Paraíba",
					"PR"=>"Paraná",
					"PE"=>"Pernambuco",
					"PI"=>"Piauí",
					"RJ"=>"Rio de Janeiro",
					"RN"=>"Rio Grande do Norte",
					"RO"=>"Rondônia",
					"RS"=>"Rio Grande do Sul",
					"RR"=>"Roraima",
					"SC"=>"Santa Catarina",
					"SE"=>"Sergipe",
					"SP"=>"São Paulo",
					"TO"=>"Tocantins"
        		)
            )
        );
    }

    public function getParent()
    {
        return 'choice';
    }

    public function getName()
    {
        return 'uf';
    }
}