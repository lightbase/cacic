<?php

namespace Cacic\CommonBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class UsuarioController extends Controller
{
	/**
	 * 
	 * Listagem dos usuários
	 */
    public function indexAction()
    {
        return $this->render('CacicCommonBundle:Default:index.html.twig', array('name' => $name));
    }
    
    /**
     * 
     * Página de alteração de senha.
     * Caso o idUsuario não seja informado, carrega os dados do usuário logado.
     * @param int $idUsuario
     */
    public function trocasenhaAction($idUsuario)
    {
    	$objUsuario = $this->getDoctrine()->getRepository('CacicCommonBundle:Usuarios')->find( $idUsuario );
    	echo"<pre>";var_dump($objUsuario);die;
    }
}
