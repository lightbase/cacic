<?php

namespace Cacic\CommonBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Cacic\CommonBundle\Common;

class UsuarioController extends Controller
{
	/**
	 * 
	 * Listagem dos usuários
     * @param $page
	 */
    public function indexAction( $page )
    {
        return $this->render(
            'CacicCommonBundle:Usuario:index.html.twig',
            array( 'usuarios' => $this->getDoctrine()->getRepository( 'CacicCommonBundle:Usuarios' )->listar() )
        );
    }
    
    /**
     * 
     * Página de alteraçõo de senha.
     * Caso o idUsuario não seja informado, carrega os dados do usuário logado.
     * @param int $idUsuario
     */
    public function trocarsenhaAction( $idUsuario )
    {
    	$objUsuario = $this->getDoctrine()->getRepository('CacicCommonBundle:Usuarios')->find( $idUsuario );
    	echo"<pre>";var_dump($objUsuario);die;
    }

    /**
     * Página de Cadastrar novo usuário.
     *
     */
    public function cadastrarAction()
    {
		return $this->render('CacicCommonBundle:Default:index.html.twig');
    }

    /**
     *  Página de editar dados do Usuário
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
