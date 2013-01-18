<?php

namespace Cacic\CommonBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Cacic\CommonBundle\Common;

class UsuarioController extends Controller
{
	/**
	 * 
	 * Listagem dos usu�rios
	 */
    public function indexAction()
    {
       	 return $this->render('CacicCommonBundle:Default:index.html.twig');
       	 
       	 

    }
    
    /**
     * 
     * P�gina de altera��o de senha.
     * Caso o idUsuario n�o seja informado, carrega os dados do usu�rio logado.
     * @param int $idUsuario
     */
    public function trocarsenhaAction($idUsuario)
    {
    	$objUsuario = $this->getDoctrine()->getRepository('CacicCommonBundle:Usuarios')->find( $idUsuario );
    	echo"<pre>";var_dump($objUsuario);die;
    }

    /**
     * P�gina de Cadastrar novo usuario.
     *
     */
    public function cadastrarAction()
    {
		return $this->render('CacicCommonBundle:Default:index.html.twig');
    }

    /**
     *  P�gina de editar dados do Usuario
     *  @param int $idusuario
     */
    public function editarAction($idUsuario)
    {
		return $this->render('CacicCommonBundle:Default:index.html.twig');
    }

    /**
     *  P�gina de recupera��o de senha
     */
    public function recuperarsenhaAction()
    {

    }
}
