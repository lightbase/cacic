<?php

namespace Cacic\CommonBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class UsuarioController extends Controller
{
	/**
	 * 
	 * Listagem dos usu�rios
	 */
    public function indexAction()
    {
        return $this->render('CacicCommonBundle:Default:index.html.twig', array('name' => $name));
    }
    
    /**
     * 
     * P�gina de altera��o de senha.
     * Caso o idUsuario n�o seja informado, carrega os dados do usu�rio logado.
     * @param int $idUsuario
     */
    public function trocasenhaAction($idUsuario)
    {
    	$objUsuario = $this->getDoctrine()->getRepository('CacicCommonBundle:Usuarios')->find( $idUsuario );
    	echo"<pre>";var_dump($objUsuario);die;
    }
}
