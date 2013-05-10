<?php 

namespace Cacic\CommonBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;

/**
 * 
 * Trata a data: alterna entre padrão SQL (2000-01-01) e padrão brasileiro (01/01/2000)
 * @author CreatiX
 *
 */
class CxDatePtBrTransformer implements DataTransformerInterface
{

    public function __construct(){}

    /**
     * Transforma para o padrão brasileiro
     *
     * @param  string $dbDateAndTime
     * @return string
     */
    public function transform( $dbDateAndTime )
    {
    	if ( $dbDateAndTime !== null )
    	{
    		$_datetime = explode(' ', $dbDateAndTime);
	    	$_date = explode('-', $_datetime[0]);
	    	$_time = array_key_exists(1, $_datetime) ? $_datetime[1] : '';
    	
        	return $_date[2] .'/'. $_date[1] .'/'. $_date[0] .' '. $_time;
    	}
        else return '';
    }

    /**
     * Transforma para o padrão de data do SQL
     *
     * @param  string $cep
     * @return \DateTime
     */
    public function reverseTransform( $ptBrDateAndTime )
    {
    	if ( $ptBrDateAndTime !== null )
    	{
    		$_datetime = explode(' ', $ptBrDateAndTime);
	    	$_date = explode('/', $_datetime[0]);
	    	$_time = array_key_exists(1, $_datetime) ? $_datetime[1] : '';
    	
        	return ( $_date[2] .'-'. $_date[1] .'-'. $_date[0] .' '. $_time );
    	}
    	else return '';
    }
}