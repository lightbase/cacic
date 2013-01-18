<?php

namespace Cacic\CommonBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Cacic\CommonBundle\Common;

class UsuarioController extends Controller
{
	/**
	 * 
	 * Listagem dos usuários
	 */
    public function indexAction()
    {
       	 return $this->render('CacicCommonBundle:Default:index.html.twig');
       	 
       	 

    }
    
    /**
     * 
     * Página de alteração de senha.
     * Caso o idUsuario não seja informado, carrega os dados do usuário logado.
     * @param int $idUsuario
     */
    public function trocarsenhaAction($idUsuario)
    {
    	$objUsuario = $this->getDoctrine()->getRepository('CacicCommonBundle:Usuarios')->find( $idUsuario );
    	echo"<pre>";var_dump($objUsuario);die;
    }

    /**
     * Página de Cadastrar novo usuario.
     *
     */
    public function cadastrarAction()
    {
		return $this->render('CacicCommonBundle:Default:index.html.twig');
    }

    /**
     *  Página de editar dados do Usuario
     *  @param int $idusuario
     */
    public function editarAction($idUsuario)
    {
		return $this->render('CacicCommonBundle:Default:index.html.twig');
    }

    /**
     *  Página de recuperação de senha
     */
    public function recuperarsenhaAction()
    {

    }
}
