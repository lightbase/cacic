<?php

namespace Cacic\CommonBundle\Controller;

use Cacic\CommonBundle\Entity\RedeVersaoModulo;
use Doctrine\Common\Util\Debug;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Cacic\CommonBundle\Entity\Rede;
use Cacic\CommonBundle\Form\Type\RedeType;
use Cacic\WSBundle\Helper;
use Cacic\CommonBundle\Helper as CacicHelper;

/**
 *
 * CRUD da Entidade Rede
 * @author lightbase
 *
 */
class RedeController extends Controller
{

    /**
     *
     * Tela de listagem
     * @param $page
     */
    public function indexAction( $page )
    {
        return $this->render(
            'CacicCommonBundle:Rede:index.html.twig',
            array( 'rede' => $this->getDoctrine()->getRepository( 'CacicCommonBundle:Rede' )->paginar( $this->get( 'knp_paginator' ), $page )
            ));

    }

    public function cadastrarAction(Request $request)
    {
        $rede = new Rede();
        $form = $this->createForm( new RedeType(), $rede );

        if ( $request->isMethod('POST') )
        {
            $form->bind( $request );
            if ( $form->isValid() )
            {
                $this->getDoctrine()->getManager()->persist( $rede );
                $this->getDoctrine()->getManager()->flush(); //Persiste os dados do Usuário

                // Grava os dados da tabela rede versão módulo
				$this->updateSubredes($rede);

                $this->get('session')->getFlashBag()->add('success', 'Dados salvos com sucesso!');

                return $this->redirect( $this->generateUrl( 'cacic_subrede_index') );
            }
        }

        return $this->render( 'CacicCommonBundle:Rede:cadastrar.html.twig', array( 'form' => $form->createView() ) );
    }

    /**
     *  Página de editar dados do subrede
     *  @param int $idRede
     */
    public function editarAction( $idRede, Request $request )
    {
        $rede = $this->getDoctrine()->getRepository('CacicCommonBundle:Rede')->find( $idRede );
        if ( ! $rede )
            throw $this->createNotFoundException( 'Subrede não encontrado' );

        $form = $this->createForm( new RedeType(), $rede );

        if ( $request->isMethod('POST') )
        {
            $form->bind( $request );

            if ( $form->isValid() )
            {
                $this->getDoctrine()->getManager()->persist( $rede );
                $this->getDoctrine()->getManager()->flush();// Efetuar a edição do ServidorAutenticacao

                // Grava os dados da tabela rede versão módulo
				$this->updateSubredes($rede);

                $this->get('session')->getFlashBag()->add('success', 'Dados salvos com sucesso!');

                return $this->redirect($this->generateUrl('cacic_subrede_editar', array( 'idRede'=>$rede->getIdRede() ) ) );
            }
        }

        return $this->render( 'CacicCommonBundle:Rede:cadastrar.html.twig', array( 'form' => $form->createView() ) );
    }
    /**
     *
     * [AJAX] Exclusão de Rede já cadastrado
     * @param integer $idRede
     */
    public function excluirAction( Request $request )
    {
        if ( ! $request->isXmlHttpRequest() )
            throw $this->createNotFoundException( 'Página não encontrada' );

        $rede = $this->getDoctrine()->getRepository('CacicCommonBundle:Rede')->find( $request->get('id') );
        if ( ! $rede )
            throw $this->createNotFoundException( 'Subrede não encontrado' );

        $em = $this->getDoctrine()->getManager();
        $em->remove( $rede );
        $em->flush();

        $response = new Response( json_encode( array('status' => 'ok') ) );
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
    
	/**
	 * 
	 * Tela de importação de arquivo CSV com registros de Redes/Subredes
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
				$fileName = 'Subredes_U'.$this->getUser()->getIdUsuario().'T'.time().'.csv';
				$form['arquivocsv']->getData()->move( $dirMigracao, $fileName );
				
				$em = $this->getDoctrine()->getManager();
				
				// Abre o arquivo salvo e começa a rotina de importação dos dados do CSV
				$csv = file( $dirMigracao.'/'.$fileName );
				foreach( $csv as $k => $v )
				{ 
					// Valida a linha
					$v = explode( ';', trim( str_replace( '"', '', $v ) ) );
					if ( count( $v ) != 8 )
						continue;
					
					$local = $this->getDoctrine()->getRepository('CacicCommonBundle:Local')->find( (int) $v[0] );
					$servAut = $this->getDoctrine()->getRepository('CacicCommonBundle:ServidorAutenticacao')->find( (int) $v[1] );
					
					$rede = new Rede();
					if ( $local )	$rede->setIdLocal( $local );
					if ( $servAut ) $rede->setIdServidorAutenticacao( $servAut );
					$rede->setTeIpRede( $v[2] );
					$rede->setNmRede( $v[3] );
					$rede->setTeServCacic( $v[4] );
					$rede->setTeServUpdates( $v[5] );
					$rede->setNuLimiteFtp( (int) $v[6] );
					$rede->setCsPermitirDesativarSrcacic( $v[7] );
					
					$em->persist( $rede );
				}
				$em->flush(); // Persiste os dados das Redes
				
				$this->get('session')->getFlashBag()->add('success', 'Importação realizada com sucesso!');
			}
			else $this->get('session')->getFlashBag()->add('error', 'Arquivo CSV inválido!');
			
			return $this->redirect( $this->generateUrl( 'cacic_migracao_subrede') );
	    }
		
		return $this->render(
        	'CacicCommonBundle:Rede:importarcsv.html.twig',
        	array( 'form' => $form->createView() )
        );
	}

    /**
     * --------------------------------------------------------------------------------------
     * Função usada para fazer updates de subredes...
	 * Recebe como parâmetro o objeto da rede
     *--------------------------------------------------------------------------------------
     */
    public function updateSubredes($rede)
    {
        $pIntIdRede = $rede->getIdRede();
        $iniFile = Helper\OldCacicHelper::iniFile;

        $itemArray = parse_ini_file($iniFile);

        $intLoopSEL 		= 1;
        $intLoopVersionsIni = 0;
        $sessStrTripaItensEnviados = '';
        while ($intLoopVersionsIni >= 0)
        {
            $intLoopVersionsIni ++;
            $arrItemDefinitions = explode(',',$itemArray['Item_' . $intLoopVersionsIni]);
            if (($arrItemDefinitions[0] <> '') && ($arrItemDefinitions[1] <> 'S') && ($arrItemDefinitions[2] <> 'S'))
            {
                $pStrNmItem = Helper\OldCacicHelper::getOnlyFileName(trim($arrItemDefinitions[0]));
				//var_dump($pStrNmItem);

                //$boolEqualVersions = ($arrVersoesEnviadas[$strItemName]  == $itemArray[$strItemName . '_VER'] );
                //$boolEqualHashs	   = ($arrHashsEnviados[$strItemName]    == $itemArray[$strItemName . '_HASH']);

                $strSendProcess   = 'Nao Enviado!';
                $strProcessStatus = '';

                $em = $this->getDoctrine()->getManager();

                // Trocar esse array por um SELECT no Doctrine que retorna os dados das redes num array
                $arrDadosRede = array( 'rede' => $em->getRepository( 'CacicCommonBundle:Rede' )->listar() );
				//Debug::dump($arrDadosRede['rede'][0][0]);
				$arrDadosRede = $arrDadosRede['rede'][0];

                // Caso o servidor de updates ainda não tenha sido trabalhado...
                if(!(Helper\OldCacicHelper::stripos2($sessStrTripaItensEnviados,$arrDadosRede[0]['teServUpdates'].'_'.$arrDadosRede[0]['tePathServUpdates'].'_'.$pStrNmItem.'_',false)))
                {
                    $sessStrTripaItensEnviados .= $arrDadosRede[0]['teServUpdates'].'_'.$arrDadosRede[0]['tePathServUpdates'].'_'.$pStrNmItem . '_';
                    //require_once('../../include/ftp_check_and_send.php');

					$ftp_class = new CacicHelper\FTP();

                    $strResult = $ftp_class->checkAndSend($pStrNmItem,
                        Helper\OldCacicHelper::CACIC_PATH . Helper\OldCacicHelper::CACIC_PATH_RELATIVO_DOWNLOADS . ($pStrNmItem),
                        $arrDadosRede[0]['teServUpdates'],
                        $arrDadosRede[0]['tePathServUpdates'],
                        $arrDadosRede[0]['nmUsuarioLoginServUpdatesGerente'],
                        $arrDadosRede[0]['teSenhaLoginServUpdatesGerente'],
                        $arrDadosRede[0]['nuPortaServUpdates']);
                }
                else
                    $strResult = 'Ja Enviado ao Servidor!_=_Ok!_=_Resended';

                $arrResult = explode('_=_',$strResult);
				//$logger = $this->get('logger');
				//$logger->err('222222222222222222222222222222222222222 '.print_r($arrResult));
				//
				// Eduardo: Esquece o teste FTP só para fazer funcionar
				$arrResult[1] = 'Ok!';

                if ($arrResult[1] == 'Ok!')
                {
                    // Consertar CRUD no Symfony
                    $redeVersaoModulo = $em->getRepository('CacicCommonBundle:RedeVersaoModulo')->findBy(
                        array(
                            'idRede' => $pIntIdRede,
                            'nmModulo' => $pStrNmItem
                        )
                    );

					// Se não existir, instancia o objeto
					if (!$redeVersaoModulo) {
						$redeVersaoModulo = new RedeVersaoModulo(null, null, null, null, null, $rede);
					}

                    // Adicione o restante dos atributos
                    $redeVersaoModulo->setNmModulo($pStrNmItem);
                    $redeVersaoModulo->setTeVersaoModulo($itemArray[$pStrNmItem . '_VER']);
                    $redeVersaoModulo->setDtAtualizacao(new \DateTime('NOW'));
                    $redeVersaoModulo->setCsTipoSo( $pStrNmItem,'.exe',false ? 'MS-Windows' : 'GNU/LINUX');
                    $redeVersaoModulo->setTeHash($itemArray[$pStrNmItem . '_HASH']);

					$em->persist($redeVersaoModulo);
					$em->flush();

                }

                //echo $_GET['pIntIdRede'] . '_=_' . $_GET['pStrNmItem'] . '_=_' . $strResult;

            }  else {
                $intLoopVersionsIni = -1;
            }

           $intLoopSEL++;
        }

		return;
    }

}
