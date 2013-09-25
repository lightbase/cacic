<?php 

namespace Cacic\CommonBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;

/**
 * 
 * Trata o CEP: Remove ou adiciona a máscara (xxxxx-xxx)
 * @author CreatiX
 *
 */
class cxCepTransformer implements DataTransformerInterface
{

    public function __construct(){}

    /**
     * Adiciona o hífen ao CEP (Adiciona a marcação da máscara)
     *
     * @param  string $cep
     * @return string
     */
    public function transform( $cep )
    {
        if ( $cep !== null )
        	return substr( $cep, 0, 5 ) .'-'. substr( $cep, 5 );
        else return '';
    }

    /**
     * Remove o hífen do CEP (Remove a marcação da máscara)
     *
     * @param  string $cep
     * @return string
     */
    public function reverseTransform( $cep )
    {
    	if ( $cep !== null )
    		return str_replace( '-', '', $cep );
    	else return '';
    }
}