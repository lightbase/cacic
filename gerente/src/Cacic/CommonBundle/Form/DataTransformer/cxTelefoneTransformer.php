<?php 

namespace Cacic\CommonBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;

/**
 * 
 * Trata o Número de TELEFONE: Remove ou adiciona a máscara (xx)xxxx-xxxx
 * @author CreatiX
 *
 */
class cxTelefoneTransformer implements DataTransformerInterface
{

    public function __construct(){}

    /**
     * Adiciona a máscara ao TELEFONE (Adiciona a marcação da máscara)
     *
     * @param  string $tel
     * @return string
     */
    public function transform( $tel )
    {
    	if ( $tel!== null )
        	return '('. substr( $tel, 0, 2 ) .')'. substr( $tel, 2, 4 ) .'-'. substr( $tel, 6 );
        else return '';
    }

    /**
     * Remove os caracteres não numéricos do número de telefone (Remove a marcação da máscara)
     *
     * @param  string $cep
     * @return string
     */
    public function reverseTransform( $tel )
    {
    	if ( $tel!== null )
    		return preg_replace( '#\D+#', '', $tel );
    	else return '';
    }
}