<?php

namespace Cacic\WSBundle\Twig;

use Cacic\WSBundle\Helper\OldCacicHelper;

class OldCacicExtension extends \Twig_Extension
{
	
	public function getFilters()
	{
		return array(
			'camel2underscore' => new \Twig_Filter_Method( $this, 'camelCaseToUnderscoreFilter', array('is_safe' => array('html')) )
		);
	}
	
	public function camelCaseToUnderscoreFilter( $string )
	{
		return OldCacicHelper::camelCaseToUnderscore( $string );
	}
	
	public function getName()
	{
		return 'old_cacic_extension';
	}
}