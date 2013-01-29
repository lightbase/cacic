<?php

namespace Cacic\CommonBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use \Cacic\CommonBundle\Common;
use \Cacic\CommonBundle\Entity\Usuarios AS Usuarios;
use \Cacic\CommonBundle\Form\Type\UsuarioType;

class UsuarioController extends Controller
{
	/**
	 * 
	 * Listagem dos usuários
     * @param $page
	 */
    public function indexAction( $page )
    {
        $arrUsuarios = $this->getDoctrine()->getRepository( 'CacicCommonBundle:Usuarios' )->listar();
        return $this->render( 'CacicCommonBundle:Usuario:index.html.twig', array( 'usuarios' => $arrUsuarios ) );

    }
    
    /**
     * 
     * Página de alteraçõo de senha.
     * Caso o idUsuario não seja informado, carrega os dados do usuário logado.
     * @param int $idUsuario
     */
    public function trocarsenhaAction( $idUsuario )
    {
    	//$objUsuario = $this->getDoctrine()->getRepository('CacicCommonBundle:Usuarios')->find( $idUsuario );
        //return $this->render( 'CacicCommonBundle:Usuario:trocarsenha.html.twig', array( 'usuarios' => $objUsuario));
    	//echo"<pre>";var_dump($objUsuario);die;
        return $this->render( 'CacicCommonBundle:Usuario:trocarsenha.html.twig');
    }

    /**
     * Página de Cadastrar novo usuário.
     *
     */
    public function cadastrarAction(Request $request)
    {
        // Conexões com o banco
       $doctrine = $this->getDoctrine();
        $em = $doctrine->getManager();

        // Agora gera o formulário
        $usuarios = new Usuarios();
        $form = $this->createForm(new \Cacic\CommonBundle\Form\Type\UsuarioType(), $usuarios);


        // Essa parte trata dos dados do envio
        if ($request->getMethod() == 'POST') {
            $form->bind($request);
            $usuarios->getIdUsuario();

            // Grava no banco de dados
            $em->persist($usuarios);
            $em->flush();
        }

        return $this->render('CacicCommonBundle:Usuario:cadastrar.html.twig', array(
        'form' => $form->createView(),
    ));
    }

	/**
     *  Página de editar dados do Usuário
     *  @param int $idusuario
     */
    public function editarAction($idUsuario)
    {
        // Instancia o Doctrine para consultar o banco
        $doctrine = $this->getDoctrine();

        // Recupera o usuário em um objeto identificado por idUsuario
        $usuario = $doctrine->getRepository('CacicCommonBundle:Usuarios')->find( $idUsuario );

        // Cria formulário com o dado do usuário recuperado
        $form = $this->createForm(new UsuarioType(), $usuario);
        
        return $this->render('CacicCommonBundle:Usuario:editar.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     *  Página de recuperação de senha
     */
    public function recuperarsenhaAction()
    {

    }
}
