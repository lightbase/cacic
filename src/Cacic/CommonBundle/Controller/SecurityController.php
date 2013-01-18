<?php

namespace Cacic\CommonBundle\Controller;

use Cacic\CommonBundle\Common;
use Symfony\Component\Security\Core\SecurityContext;

class SecurityController extends BaseController
{
	
	/**
	 * 
	 * Tela de login do CACIC
	 */
	public function loginAction()
	{
		$objRequest = $this->getRequest();
        $objSession = $objRequest->getSession();

        # Recupera a mensagem de erro, se existir
        if ( $objRequest->attributes->has( SecurityContext::AUTHENTICATION_ERROR ) )
        {
            $error = $objRequest->attributes->get( SecurityContext::AUTHENTICATION_ERROR );
        }
        else
        {
            $error = $objSession->get( SecurityContext::AUTHENTICATION_ERROR ); // Recupera a mensagem de erro da sess�o
            $objSession->remove( SecurityContext::AUTHENTICATION_ERROR ); // Apaga a mensagem de erro da sess�o
        }

        return $this->render(
            'CacicCommonBundle:Security:login.html.twig',
            array(
                'last_username' => $objSession->get( SecurityContext::LAST_USERNAME ), // �ltimo nome de usu�rio informado no formul�rio
                'error'         => $error,
            )
        );
	}
	
}