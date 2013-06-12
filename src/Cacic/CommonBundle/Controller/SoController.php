<?php

namespace Cacic\CommonBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Cacic\CommonBundle\Entity\So;
use Cacic\CommonBundle\Form\Type\SoType;


class SoController extends Controller
{
    public function indexAction( $page )
    {
        $arrso = $this->getDoctrine()->getRepository( 'CacicCommonBundle:So' )->listar();
        return $this->render( 'CacicCommonBundle:So:index.html.twig', array( 'So' => $arrso ) );

    }
    public function cadastrarAction(Request $request)
    {
        $so = new So();
        $form = $this->createForm(new SoType(), $so);

        if ( $request->isMethod('POST') )
        {
            $form->bind( $request );
            if ( $form->isValid() )
            {
                $this->getDoctrine()->getManager()->persist( $so );
                $this->getDoctrine()->getManager()->flush(); //Persiste os dados do sistema operacional

                $this->get('session')->getFlashBag()->add('success', 'Dados salvos com sucesso!');

                return $this->redirect( $this->generateUrl( 'cacic_sistemaoperacional_index') );
            }
        }

        return $this->render( 'CacicCommonBundle:So:cadastrar.html.twig', array( 'form' => $form->createView() ) );
    }
    /**
     *  Página de editar dados do sistema operacional
     *  @param int $idSo
     */
    public function editarAction( $idSo, Request $request )
    {
        $so = $this->getDoctrine()->getRepository('CacicCommonBundle:So')->find( $idSo );
        if ( ! $so )
            throw $this->createNotFoundException( 'sistema operacional não encontrado' );
        $form = $this->createForm( new SoType(), $so);

        if ( $request->isMethod('POST') )
        {
            $form->bind( $request );

            if ( $form->isValid() )
            {
                $this->getDoctrine()->getManager()->persist( $so );
                $this->getDoctrine()->getManager()->flush();// Efetuar a edição do sistema operacional


                $this->get('session')->getFlashBag()->add('success', 'Dados salvos com sucesso!');

                return $this->redirect($this->generateUrl('cacic_sistemaoperacional_editar', array( 'idSo'=>$so->getIdSo() ) ) );
            }
        }

        return $this->render( 'CacicCommonBundle:So:cadastrar.html.twig', array( 'form' => $form->createView() ) );
    }

    /**
     *
     * [AJAX] Exclusão de sistema operacional já cadastrado
     * @param integer $idSo
     */
    public function excluirAction( Request $request )
    {
        if ( ! $request->isXmlHttpRequest() ) // Verifica se se trata de uma requisição AJAX
            throw $this->createNotFoundException( 'Página não encontrada' );

        $So = $this->getDoctrine()->getRepository('CacicCommonBundle:So')->find( $request->get('id') );
        if ( ! $So )
            throw $this->createNotFoundException( ' sistema operacional não encontrado' );

        $em = $this->getDoctrine()->getManager();
        $em->remove( $So );
        $em->flush();

        $response = new Response( json_encode( array('status' => 'ok') ) );
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
    
	/**
	 * 
	 * Tela de importação de arquivo CSV com registros de Sistemas Operacionais
	 */
	public function importarcsvAction( Request $request )
	{
		$form = $this->createFormBuilder()
			        ->add('arquivocsv', 'file', array('label' => 'Arquivo', 'attr' => array( 'accept' => '.csv' )))
			        ->getForm();
		
		if ( $request->isMethod('POST') )
		{
			$form->bindRequest( $request );
			if ( $form['arquivocsv']->getData() instanceof \Symfony\Component\HttpFoundation\File\UploadedFile )
			{
				// Executa a importação do arquivo - grava no diretório web/upload/migracao
				$dirMigracao = realpath( dirname(__FILE__) .'/../../../../web/upload/migracao/' );
				$fileName = 'SO_U'.$this->getUser()->getIdUsuario().'T'.time().'.csv';
				$form['arquivocsv']->getData()->move( $dirMigracao, $fileName );
				
				$em = $this->getDoctrine()->getManager();
				
				// Abre o arquivo salvo e começa a rotina de importação dos dados do CSV
				$csv = file( $dirMigracao.'/'.$fileName );
				foreach( $csv as $k => $v )
				{ 
					// Valida a linha
					$v = explode( ';', trim( str_replace( '"', '', $v ) ) );
					if ( count( $v ) != 5 )
						continue;
					
					$so = new So();
					$so->setIdSo( $v[0] );
					$so->setTeDescSo( $v[1] );
					$so->setSgSo( $v[2] );
					$so->setTeSo( $v[3] );
					$so->getInMswindows( (int) $v[4] );
					
					$em->persist( $so );
				}
				$em->flush(); // Persiste os dados dos Sistemas Operacionais
				
				$this->get('session')->getFlashBag()->add('success', 'Importação realizada com sucesso!');
			}
			else $this->get('session')->getFlashBag()->add('error', 'Arquivo CSV inválido!');
	    }
		
		return $this->render(
        	'CacicCommonBundle:Rede:importarcsv.html.twig',
        	array( 'form' => $form->createView() )
        );
	}

}