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
use Ijanki\Bundle\FtpBundle\Exception\FtpException;
use Symfony\Component\Validator\Constraints\Null;
use Symfony\Component\Finder\Finder;

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
            array( 'rede' => $this->getDoctrine()->getRepository( 'CacicCommonBundle:Rede' )->paginar( $this->get( 'knp_paginator' ), $page ),
                   'uorgs' => $this->getDoctrine()->getRepository( 'CacicCommonBundle:Uorg' )->vincular()
            ));

    }

    public function cadastrarAction(Request $request)
    {
        $logger = $this->get('logger');
        $rede = new Rede();
        $form = $this->createForm( new RedeType(), $rede );

        if ( $request->isMethod('POST') )
        {
            $form->bind( $request );
            if ( $form->isValid() )
            {
                $this->getDoctrine()->getManager()->persist( $rede );
                $this->getDoctrine()->getManager()->flush(); //Persiste os dados do Usuário

                // Marca todas as ações para a rede
                $habilitar = $form['habilitar']->getData();
                $nmRede = $rede->getNmRede();
                if ($habilitar) {
                    $logger->debug("Habilitando todas as ações para a rede $nmRede ...");
                    $this->getDoctrine()->getRepository( 'CacicCommonBundle:AcaoRede' )->atualizarPorRede( array( $rede ) );
                }

                // Grava os dados da tabela rede versão módulo
                $logger->debug("Realizando o update de subredes para a rede $nmRede ...");

                // Grava os dados da tabela rede versão módulo
				$this->updateSubredesNeo($request, $rede);

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
        $logger = $this->get('logger');
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

                // Marca todas as ações para a rede
                $habilitar = $form['habilitar']->getData();
                $nmRede = $rede->getNmRede();
                if ($habilitar) {
                    $logger->debug("Habilitando todas as ações para a rede $nmRede ...");
                    $this->getDoctrine()->getRepository( 'CacicCommonBundle:AcaoRede' )->atualizarPorRede( array( $rede ) );
                }

                // Grava os dados da tabela rede versão módulo
                //$logger->debug("Realizando o update de subredes para a rede $nmRede ...");
				//$this->updateSubredes($rede);

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
        if ( ! $request->isXmlHttpRequest() ) {
            throw $this->createNotFoundException( 'Página não encontrada' );
        }

        $rede = $this->getDoctrine()->getRepository('CacicCommonBundle:Rede')->find( $request->get('id') );

        if ( ! $rede ) {
            throw $this->createNotFoundException( 'Subrede não encontrada' );
        }

        if ($rede->getTeIprede() == '0.0.0.0') {
            //$this->get('session')->getFlashBag()->add('error', 'Nao e permitido remover a rede padrao!');

            $response = new Response(
                json_encode(
                    array(
                        'status' => 'error',
                        'message' => 'Não é permitido remover a rede padrão'
                    )
                )
            );
            $response->headers->set('Content-Type', 'application/json');

            return $response;
        }

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

    public function manutencaoAction(Request $request)
    {
        $logger = $this->get('logger');

        // Primeiro carrega lista dos módulos
        $modulos = $this->modulosArray();


        if ( $request->isMethod('POST') )
        {
            if ( count( $request->get('subrede') ) )
            {

                foreach ( $request->get('subrede') as $resultado )
                {
                    $logger->debug("Atualizando a subrede {$resultado} ...");

                    // Junta os módulos windows e linux para enviar para o update de subredes
                    $atualizaWindows = $request->get('windows');
                    $atualizaLinux = $request->get('linux');

                    // FIXME: Na requisição só vem o nome dos módulos. Precisa carregar as outras informações.

                    // Evita Warning do array merge se um dos dois for vazio
                    if (empty($atualizaLinux)) {
                        $atualiza = $atualizaWindows;
                    } elseif (empty($atualizaWindows)) {
                        $atualiza = $atualizaLinux;
                    } else {
                        $atualiza = array_merge($atualizaWindows, $atualizaLinux);
                    }

                    // Passa a rede como parâmetro
                    $redeAtualizar =  $this->getDoctrine()->getManager()->find('CacicCommonBundle:Rede', $resultado);


                    // Executa a atualização de todos os módulos marcados para a subrede marcada
                    $this->updateSubredes($redeAtualizar, $atualiza);

                }
                $this->get('session')->getFlashBag()->add('success', 'Dados salvos com sucesso!');
            }
        }

        // Lista de subredes e módulos
        $subredesOrig = $this->getDoctrine()->getRepository('CacicCommonBundle:Rede')->comLocal();

        // Varro todas as subredes para cada módulo
        $subredes = array();
        $windows = array();
        $linux = array();
        foreach ($subredesOrig as $redeItem) {
            // Busca o módulo em cada uma das redes
            $codigos = array();
            foreach ($modulos as $key => $value) {
                $idRede = $redeItem['idRede'];
                // Verifico se o módulo existe na subrede
                $rede = $this->getDoctrine()->getRepository('CacicCommonBundle:RedeVersaoModulo')->subrede($idRede, $key);

                if (empty($rede)) {
                    // O módulo não foi encontrado. Adiciona o código 1
                    array_push($codigos, 0);
                    //$rede = $redeItem[0];
                } else {
                    if ($value['hash'] == $rede[0]['teHash']) {
                        // Se o hash for igual, adiciona o código 2
                        array_push($codigos, 2);

                    } else {
                        // Se o hash for diferente, adiciona o código 1
                        array_push($codigos, 1);
                    }
                }

                // Cria um array para Windows e outro para Linux
                if ($value['tipoSo'] == 'windows') {
                    $windows[$key] =  $value;
                } else {
                    $linux[$key] = $value;
                }

            }

            // Define o elemento HTML para os módulos
            if (in_array(0, $codigos)) {
                // Se o código 0 for encontrato, marcamos o módulo como inexistente
                if (empty($rede)) {
                    $rede[0] = $redeItem;
                }
                $subredes["$idRede"]['teIpRede'] = $rede[0]['teIpRede'];
                $subredes["$idRede"]['nmRede'] = $rede[0]['nmRede'];
                $subredes["$idRede"]['teServUpdates'] = $rede[0]['teServUpdates'];
                $subredes["$idRede"]['tePathServUpdates'] = $rede[0]['tePathServUpdates'];
                $subredes["$idRede"]['nmLocal'] = $rede[0]['nmLocal'];
                $subredes["$idRede"]['codigo'] = "<span class='label label-important'>Módulos inexistentes</span>";
            } elseif (in_array(1, $codigos)) {
                // Se o código 1 for encontrado, alguns módulos estão desatualizados
                $subredes["$idRede"]['teIpRede'] = $rede[0]['teIpRede'];
                $subredes["$idRede"]['nmRede'] = $rede[0]['nmRede'];
                $subredes["$idRede"]['teServUpdates'] = $rede[0]['teServUpdates'];
                $subredes["$idRede"]['tePathServUpdates'] = $rede[0]['tePathServUpdates'];
                $subredes["$idRede"]['nmLocal'] = $rede[0]['nmLocal'];
                $subredes["$idRede"]['codigo'] = "<span class='label label-warning'>Módulos desatualizados</span>";
            } else {
                // Se não existe nenhum módulo inexistente ou desatualizado, está tudo 100% atualizado
                $subredes["$idRede"]['teIpRede'] = $rede[0]['teIpRede'];
                $subredes["$idRede"]['nmRede'] = $rede[0]['nmRede'];
                $subredes["$idRede"]['teServUpdates'] = $rede[0]['teServUpdates'];
                $subredes["$idRede"]['tePathServUpdates'] = $rede[0]['tePathServUpdates'];
                $subredes["$idRede"]['nmLocal'] = $rede[0]['nmLocal'];
                $subredes["$idRede"]['codigo'] = "<span class='label label-success'>Módulos atualizados</span>";
            }
        }

        return $this->render( 'CacicCommonBundle:Rede:manutencao.html.twig',
            array(  'windows'=> $windows,
                    'linux' => $linux,
                    'subredes' => $subredes
            )
        );

    }

    /*
     * Função que retorna um array multidimensional com o nome dos executáveis,
     * o hash e versão constantes do arquivo versions_and_hashes.ini
     *
     * @param nmModulo Nome do módulo para trazer informações
     *
     * @return Array multidimensional com os dados
     */

    public function modulosArray($nmModulos = null)
    {
        $logger = $this->get('logger');
        // Abre e faz o parsing do arquivo
        $cacic_helper = new Helper\OldCacicHelper($this->container->get('kernel'));
        $iniFile = $cacic_helper->iniFile();
        $itemArray = parse_ini_file($iniFile);
        $teste = parse_ini_file($iniFile, true);

        // Gera um array com todos os múdlos
        $modulos = array();
        $intLoopVersionsIni = 0;
        foreach ($teste["ItemsDefinitions"] as &$arrItemDefinitions)
        {
            $intLoopVersionsIni ++;
            $arrItemDefinitions = explode(',',$itemArray['Item_' . $intLoopVersionsIni]);

            // Cria um array multidimensional com os atributos
            if ($arrItemDefinitions[0] <> '')
            {
                $arquivo = Helper\OldCacicHelper::getOnlyFileName(trim($arrItemDefinitions[0]));

                // Verifica se o arquivo deve ser carregado
                if ($nmModulos != null) {
                    if (in_array($arquivo, $nmModulos)) {
                        $logger->debug("Módulo {$arquivo} encontrado ...");
                        // Se for vazio ou o nome for fornecido, carrega
                        preg_match('/\.[^\.]+$/i',$arquivo,$ext);
                        if ($ext[0] == '.exe') {
                            $tipoSo = 'windows';
                        } else {
                            $tipoSo = 'linux';
                        }
                        $modulos[$arquivo]['versao'] = $itemArray[$arquivo . '_VER'];
                        $modulos[$arquivo]['hash'] = $itemArray[$arquivo . '_HASH'];
                        $modulos[$arquivo]['tipoSo'] = $tipoSo;
                    }
                } else {
                    // Se for vazio ou o nome for fornecido, carrega
                    preg_match('/\.[^\.]+$/i',$arquivo,$ext);
                    if ($ext[0] == '.exe') {
                        $tipoSo = 'windows';
                    } else {
                        $tipoSo = 'linux';
                    }
                    $modulos[$arquivo]['versao'] = $itemArray[$arquivo . '_VER'];
                    $modulos[$arquivo]['hash'] = $itemArray[$arquivo . '_HASH'];
                    $modulos[$arquivo]['tipoSo'] = $tipoSo;
                }


            }

        }

        // Retorna o array com todos os resultados
        return $modulos;
    }


    /**
     * --------------------------------------------------------------------------------------
     * Função usada para fazer updates de subredes...
	 * Recebe como parâmetro o objeto da rede
     *--------------------------------------------------------------------------------------
     */
    public function updateSubredes($rede, $modulos = null)
    {
        $logger = $this->get('logger');
        $pIntIdRede = $rede->getIdRede();
        $cacic_helper = new Helper\OldCacicHelper($this->container->get('kernel'));
        $iniFile = $cacic_helper->iniFile();

        if (!file_exists($iniFile)) {
            $this->get('session')->getFlashBag()->add(
                'error',
                'O arquivo versions_and_hashes.ini não foi encontrado. Você fez o upload dos agentes 2.8?'
            );

            $logger->error("Arquivo versions_and_hashes.ini não foi encontrado no update de subredes");
        }

        $itemArray = parse_ini_file($iniFile);

        $teste = parse_ini_file($iniFile, true);

        $intLoopSEL 		= 1;
        $intLoopVersionsIni = 0;
        $sessStrTripaItensEnviados = '';

        // Carrega todos os metadados dos módulos fornecidos ou de todos os módulos
        $modulos = $this->modulosArray($modulos);

        foreach ($teste["ItemsDefinitions"] as &$arrItemDefinitions)
        {
            $intLoopVersionsIni ++;
            $arrItemDefinitions = explode(',',$itemArray['Item_' . $intLoopVersionsIni]);

            // Nome do módulo sendo carregado
            $pStrNmItem = Helper\OldCacicHelper::getOnlyFileName(trim($arrItemDefinitions[0]));
            $logger->debug("Nome do módulo: $pStrNmItem");
            if ($modulos[$pStrNmItem])
            {
                $logger->debug("Carregando módulo $pStrNmItem");

                // Carrega dados da rede
                $em = $this->getDoctrine()->getManager();
                //$arrDadosRede = array( 'rede' => $em->getRepository( 'CacicCommonBundle:Rede' )->listar() );
				//Debug::dump($arrDadosRede['rede'][0][0]);
				//$arrDadosRede = $arrDadosRede['rede'][0];
                $arrDadosRede = array(
                    'teServUpdates' => $rede->getTeServUpdates(),
                    'tePathServUpdates' => $rede->getTePathServUpdates(),
                    'nmUsuarioLoginServUpdatesGerente' => $rede->getNmUsuarioLoginServUpdatesGerente(),
                    'teSenhaLoginServUpdatesGerente' => $rede->getTeSenhaLoginServUpdatesGerente(),
                    'nuPortaServUpdates' => $rede->getNuPortaServUpdates(),
                );

                // Caso o servidor de updates ainda não tenha sido trabalhado...
                if(!(Helper\OldCacicHelper::stripos2($sessStrTripaItensEnviados,$arrDadosRede['teServUpdates'].'_'.$arrDadosRede['tePathServUpdates'].'_'.$pStrNmItem.'_',false)))
                {
                    $sessStrTripaItensEnviados .= $arrDadosRede['teServUpdates'].'_'.$arrDadosRede['tePathServUpdates'].'_'.$pStrNmItem . '_';

                    $strResult = $this->checkAndSend($pStrNmItem,
                        $cacic_helper->getRootDir() . $cacic_helper::CACIC_PATH_RELATIVO_DOWNLOADS . ($pStrNmItem),
                        $arrDadosRede['teServUpdates'],
                        $arrDadosRede['tePathServUpdates'],
                        $arrDadosRede['nmUsuarioLoginServUpdatesGerente'],
                        $arrDadosRede['teSenhaLoginServUpdatesGerente'],
                        $arrDadosRede['nuPortaServUpdates']
                    );
                }
                else
                    $strResult = 'Ja Enviado ao Servidor!_=_Ok!_=_Resended';

                $arrResult = explode('_=_',$strResult);

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
					} else {
                        // Carrego o objeto encontrado
                        $redeVersaoModulo = $redeVersaoModulo[0];
                    }

                    // Adicione o restante dos atributos
                    $redeVersaoModulo->setNmModulo($pStrNmItem);
                    $redeVersaoModulo->setTeVersaoModulo($itemArray[$pStrNmItem . '_VER']);
                    $redeVersaoModulo->setDtAtualizacao(new \DateTime('NOW'));
                    $redeVersaoModulo->setCsTipoSo( $pStrNmItem,'.exe',false ? 'MS-Windows' : 'GNU/LINUX');
                    $redeVersaoModulo->setTeHash($itemArray[$pStrNmItem . '_HASH']);

					$em->persist($redeVersaoModulo);
					$em->flush();

                } else {
                    $logger->error("Erro no envio do módulo via FTP \n".$arrResult[1]);
                }

                //echo $_GET['pIntIdRede'] . '_=_' . $_GET['pStrNmItem'] . '_=_' . $strResult;
                #FIXME: Inserir hash do módulo pyCacyc. Atualmente dá erro mas não trava

            }

           $intLoopSEL++;
        }



		return;
    }

    /*
     * Função que permite a execução do FTP e faz o download dos executáveis
     * para a estação do Agente
     *
     * @param $pStrNmItem
     * @param $pStrFullItemName
     * @param $pStrTeServer
     * @param $pStrTePathServer
     * @param $pStrNmUsuarioLogin
     * @param $pStrTeSenhaLogin
     * @param $pStrNuPortaServer
     *
     */

    public function checkAndSend($pStrNmItem,
                          $pStrFullItemName,
                          $pStrTeServer,
                          $pStrTePathServer,
                          $pStrNmUsuarioLogin,
                          $pStrTeSenhaLogin,
                          $pStrNuPortaServer)
    {
        $logger = $this->get('logger');

        // Pega objetos do FTP
        $ftp = $this->container->get('ijanki_ftp');

        $strSendProcess   = 'Nao Enviado!';
        $strProcessStatus = '';
        $strProcessCode	  = '';
        try
        {
            $logger->debug("Enviando módulo $pStrFullItemName para o servidor $pStrTeServer na pasta $pStrTePathServer");


            $conn = $ftp->connect($pStrTeServer, $pStrNuPortaServer);
            // Retorno esperado....: 230 => FTP_USER_LOGGED_IN
            // Retorno NÃO esperado: 530 => FTP_USER_NOT_LOGGED_IN
	    
	        # TODO: Acrescentar verificação de sucesso em cada operação
	        $result = $ftp->login($pStrNmUsuarioLogin,$pStrTeSenhaLogin);

            // Retorno esperado: 250 => FTP_FILE_ACTION_OK
            // Retorno NÃO esperado: 550 => FTP_PERMISSION_DENIED (ou a pasta não existe!)
            $result = $ftp->chdir($pStrTePathServer);
            $result = $ftp->put($pStrNmItem, $pStrFullItemName, FTP_BINARY);

            $strSendProcess   = 'Enviado com Sucesso!';
            $strProcessStatus = 'Ok!';
        }
        catch (FTPException $e)
        {
            $strSendProcess   = 'Falha no envio!';
            $strProcessStatus = 'ERRO: Problema durante a conexao! (' . $e->getMessage() . ')';
        }

        return $strSendProcess . '_=_' . $strProcessStatus . '_=_' . $strProcessCode;
    }

    public function vincularAction(Request $request)
    {

        if ( ! $request->isXmlHttpRequest() ) // Verifica se se trata de uma requisição AJAX
            throw $this->createNotFoundException( 'Página não encontrada' );

        foreach($request->get('uorg') as $idUorg){
/*       $uorgs = implode(',', $request->get('uorg'));

            // 1- pega rede
            $rede = $this->getDoctrine()->getRepository('CacicCommonBundle:Rede')->find($request->get('id'));
            // 2- Add uorg na rede
            $rede ->addUorg( $this->getDoctrine()->getRepository('CacicCommonBundle:Uorg')->find($uorgs) );*/

            //1- Pego Uorg
            $uorg = $this->getDoctrine()->getRepository('CacicCommonBundle:Uorg')->find($idUorg);

            //2- Add a rede na uorg
            $uorg ->setRede( $this->getDoctrine()->getRepository('CacicCommonBundle:Rede')->find($request->get('id')) );

            $em = $this->getDoctrine()->getManager();
            $em->persist( $uorg );
            $em->flush();
        }
        $response = new Response( json_encode( array('status' => 'ok') ) );
        $response->headers->set('Content-Type', 'application/json');

        return $response;

    }


    public function coletarAction()
    {
        $subredesOrig = $this->getDoctrine()->getRepository('CacicCommonBundle:Rede')->comLocal();

        $subredes = array();

        foreach ($subredesOrig as $redeItem) {
            $idRede = $redeItem['idRede'];
            $subredes["$idRede"]['teIpRede'] = $redeItem['teIpRede'];
            $subredes["$idRede"]['nmRede'] = $redeItem['nmRede'];
            $subredes["$idRede"]['nmLocal'] = $redeItem['nmLocal'];
        }

        return $this->render( 'CacicCommonBundle:Rede:coletar.html.twig',
            array(  'subredes' => $subredes ));

    }

    public function submitAction(Request $request)
    {
        $subredes = $request->get('subrede');

        foreach ($subredes as $subrede){

            $computadores = $this->getDoctrine()->getRepository('CacicCommonBundle:Computador')->listarPorSubrede($subrede);

            foreach($computadores as $computador){

                $computador->setForcaColeta('true');
                $this->getDoctrine()->getManager()->persist( $computador );

            }

        }

        $this->getDoctrine()->getManager()->flush();

        $this->get('session')->getFlashBag()->add('success', 'Dados salvos com sucesso!');

        return $this->redirect($this->generateUrl('cacic_rede_coletar') );

    }

    public function computadoresAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $computadores = $em->getRepository('CacicCommonBundle:Rede')->computadoresSubredes();

        if ($request->isMethod('POST'))  {
            // Processa subrede
            foreach($request->request->get('subrede') as $elm) {
                $out = explode('#',$elm);
                $id_subrede = $out[0];
                $id_computador = $out[1];
                $this->get('logger')->error("Atualizando subrede = $id_subrede para o computador = $id_computador");

                // Atualiza subrede para o computador
                $computador = $em->find('CacicCommonBundle:Computador', $id_computador);
                $subrede = $em->find('CacicCommonBundle:Rede', $id_subrede);
                $computador->setIdRede($subrede);
                $em->persist($computador);
            }
            $em->flush();

            $this->get('session')->getFlashBag()->add('success', 'Computadores atualizados com sucesso!');
        }

        return $this->render('CacicCommonBundle:Rede:computadores.html.twig',
            array(
                'computadores' => $computadores
            )
        );
    }

    /**
     * Função nova para atualização das subredes
     *
     * @param Request $request
     * @return Response
     */
    public function manutencaoNeoAction(Request $request)
    {
        $logger = $this->get('logger');

        // Primeiro carrega lista dos módulos
        $modulos = $this->modulosNeoArray($request);

        if ( $request->isMethod('POST') )
        {
            if ( count( $request->get('subrede') ) )
            {
                $retorno = true;
                foreach ( $request->get('subrede') as $resultado )
                {
                    $logger->debug("Atualizando a subrede {$resultado} ...");

                    // Junta os módulos windows e linux para enviar para o update de subredes
                    $atualizaWindows = $request->get('windows');
                    $atualizaLinux = $request->get('linux');

                    // FIXME: Na requisição só vem o nome dos módulos. Precisa carregar as outras informações.

                    // Evita Warning do array merge se um dos dois for vazio
                    if (empty($atualizaLinux)) {
                        $atualiza = $atualizaWindows;
                    } elseif (empty($atualizaWindows)) {
                        $atualiza = $atualizaLinux;
                    } else {
                        $atualiza = array_merge($atualizaWindows, $atualizaLinux);
                    }

                    // Passa a rede como parâmetro
                    $redeAtualizar =  $this->getDoctrine()->getManager()->find('CacicCommonBundle:Rede', $resultado);


                    // Executa a atualização de todos os módulos marcados para a subrede marcada
                    $result = $this->updateSubredesNeo($request, $redeAtualizar, $atualiza);
                    if (!$result) {
                        $retorno = $result;
                    }
                }
                if ($retorno) {
                    $this->get('session')->getFlashBag()->add('success', 'Dados salvos com sucesso!');
                } else {
                    $this->get('session')->getFlashBag()->add('error', 'Erro na atualização das subredes');
                }

            }
        }

        // Lista de subredes e módulos
        $subredesOrig = $this->getDoctrine()->getRepository('CacicCommonBundle:Rede')->comLocal();

        // Varro todas as subredes para cada módulo
        $subredes = array();
        $saida = array();
        foreach ($subredesOrig as $redeItem) {
            // Busca o tipo de SO
            $codigos = array();
            $idRede = $redeItem['idRede'];

            // Se não existir nenhum módulo, é preciso adicionar o código 0 para todas as subredes
            if (empty($modulos)) {
                array_push($codigos, 0);
            }

            foreach($modulos as $tipo_so => $file) {
                // Busca o módulo em cada uma das redes
                foreach ($file as $key => $value) {
                    // Verifico se o módulo existe na subrede
                    $rede = $this->getDoctrine()->getRepository('CacicCommonBundle:RedeVersaoModulo')->subredeFilePath($idRede, $value['filename']);

                    if (empty($rede)) {
                        // O módulo não foi encontrado. Adiciona o código 1
                        array_push($codigos, 0);
                        //$rede = $redeItem[0];
                    } else {
                        if ($value['hash'] == $rede[0]['teHash']) {
                            // Se o hash for igual, adiciona o código 2
                            array_push($codigos, 2);

                        } else {
                            // Se o hash for diferente, adiciona o código 1
                            array_push($codigos, 1);
                        }
                    }

                    // Array de saída
                    $saida[$tipo_so][$value['name']] = $value;

                }
            }


            // Define o elemento HTML para os módulos
            if (in_array(0, $codigos)) {
                // Se o código 0 for encontrato, marcamos o módulo como inexistente
                if (empty($rede)) {
                    $rede[0] = $redeItem;
                }
                $subredes["$idRede"]['teIpRede'] = $rede[0]['teIpRede'];
                $subredes["$idRede"]['nmRede'] = $rede[0]['nmRede'];
                $subredes["$idRede"]['teServUpdates'] = $rede[0]['teServUpdates'];
                $subredes["$idRede"]['tePathServUpdates'] = $rede[0]['tePathServUpdates'];
                $subredes["$idRede"]['nmLocal'] = $rede[0]['nmLocal'];
                $subredes["$idRede"]['codigo'] = "<span class='label label-important'>Módulos inexistentes</span>";
            } elseif (in_array(1, $codigos)) {
                // Se o código 1 for encontrado, alguns módulos estão desatualizados
                $subredes["$idRede"]['teIpRede'] = $rede[0]['teIpRede'];
                $subredes["$idRede"]['nmRede'] = $rede[0]['nmRede'];
                $subredes["$idRede"]['teServUpdates'] = $rede[0]['teServUpdates'];
                $subredes["$idRede"]['tePathServUpdates'] = $rede[0]['tePathServUpdates'];
                $subredes["$idRede"]['nmLocal'] = $rede[0]['nmLocal'];
                $subredes["$idRede"]['codigo'] = "<span class='label label-warning'>Módulos desatualizados</span>";
            } else {
                // Se não existe nenhum módulo inexistente ou desatualizado, está tudo 100% atualizado
                $subredes["$idRede"]['teIpRede'] = $rede[0]['teIpRede'];
                $subredes["$idRede"]['nmRede'] = $rede[0]['nmRede'];
                $subredes["$idRede"]['teServUpdates'] = $rede[0]['teServUpdates'];
                $subredes["$idRede"]['tePathServUpdates'] = $rede[0]['tePathServUpdates'];
                $subredes["$idRede"]['nmLocal'] = $rede[0]['nmLocal'];
                $subredes["$idRede"]['codigo'] = "<span class='label label-success'>Módulos atualizados</span>";
            }
        }

        //$logger->debug("111111111111111111111111111111111111111 \n".print_r($saida, true));

        $user = $this->getUser();

        return $this->render( 'CacicCommonBundle:Rede:manutencaoNeo.html.twig',
            array(
                'saida'=> $saida,
                'subredes' => $subredes,
                'user' => $user
            )
        );

    }

    /*
     * Função que retorna um array multidimensional com o nome dos executáveis,
     * o hash e versão constantes do arquivo versions_and_hashes.ini
     *
     * @param nmModulo Nome do módulo para trazer informações
     *
     * @return Array multidimensional com os dados
     */

    public function modulosNeoArray(Request $request, $nmModulos = null)
    {
        $logger = $this->get('logger');
        // Abre e faz o parsing do arquivo
        $cacic_helper = new Helper\OldCacicHelper($this->container->get('kernel'));
        $iniFile = $cacic_helper->iniFile();
        //$itemArray = parse_ini_file($iniFile);
        //$teste = parse_ini_file($iniFile, true);

        // Varre o diretório em busca dos módulos
        $rootDir = $this->container->get('kernel')->getRootDir();
        $webDir = $rootDir . "/../web/";
        $downloadsDir = $webDir . "downloads/";
        $cacicDir = $downloadsDir . "cacic/";
        $linuxDir = $cacicDir . "linux/";
        $windowsDir = $cacicDir . "windows/";
        $outrosDir = $downloadsDir . "outros/";

        // Constrói array de arquivos e hashes
        $saida = array();
        $base_url = $request->getBaseUrl();
        $base_url = preg_replace('/\/app.*.php/', "", $base_url);

        // Verifica se diretório existe
        $current_dir = $cacicDir."current";
        if (!file_exists($current_dir)) {
            $this->get('session')->getFlashBag()->add(
                'notice',
                'Não foi possível encontrar o diretório dos Agentes. Faça upload dos agentes.'
            );

            $logger->info("Não foi possível encontrar o diretório dos Agentes. Faça upload dos agentes.");

            return array();
        }

        // Primeiro tratamos agentes Linux
        // A regra é que o agente mais atual estará na pasta current
        $current = basename(readlink($current_dir));
        $finder = new Finder();
        $finder->depth('== 0');
        $finder->directories()->in($cacicDir);
        foreach($finder as $version) {
            if ($version->getFileName() == 'current') {
                $tipos = new Finder();
                $tipos->depth('== 0');
                $tipos->directories()->in($version->getRealPath());
                foreach($tipos as $tipo_so) {
                    // Aqui considera somente a última versão
                    $agentes = new Finder();
                    $agentes->depth('== 0');
                    $agentes->files()->in($tipo_so->getRealPath());
                    foreach ($agentes as $file) {
                        $filename = 'cacic/' . $version->getFileName() . '/' . $tipo_so->getFileName() . "/" . $file->getFileName();
                        if (!empty($nmModulos)) {
                            // Filtra por nome de módulo
                            if (!in_array($filename, $nmModulos)) {
                                continue;
                            }
                        }
                        $saida[$tipo_so->getFileName()][$file->getFileName()] = array(
                            'name' => $file->getFileName(),
                            'download_url' => $base_url . '/downloads/cacic/' . $version->getFileName() . '/' . $tipo_so->getFileName() . "/" . $file->getFileName(),
                            'hash' => md5_file($file->getRealPath()),
                            'size' => $file->getSize(),
                            'filename' => $filename,
                            'versao' => $current,
                            'tipoSo' => $tipo_so->getFileName()
                        );
                    }
                }

            } else {
                continue;
            }

        }


        // Retorna o array com todos os resultados
        return $saida;
    }

    public function updateSubredesNeo(Request $request, $rede, $modulos = null)
    {
        $logger = $this->get('logger');
        $em = $this->getDoctrine()->getManager();
        $pIntIdRede = $rede->getIdRede();

        // Varre o diretório em busca dos módulos
        $rootDir = $this->container->get('kernel')->getRootDir();
        $webDir = $rootDir . "/../web/";
        $downloadsDir = $webDir . "downloads/";
        $cacicDir = $downloadsDir . "cacic/";
        $linuxDir = $cacicDir . "linux/";
        $windowsDir = $cacicDir . "windows/";
        $outrosDir = $downloadsDir . "outros/";

        // Consertar CRUD no Symfony
        $redeVersaoModulo = $em->getRepository('CacicCommonBundle:RedeVersaoModulo')->findBy(
            array(
                'idRede' => $pIntIdRede
            )
        );

        // Se não existir, instancia o objeto
        if (!empty($redeVersaoModulo)) {
        	// Nesse caso, remove todos os módulos para a subrede
        	foreach ($redeVersaoModulo as $redeRemove)  {
                $em->remove($redeRemove);
            }
            $em->flush();
        }


        // Carrega todos os metadados dos módulos fornecidos ou de todos os módulos
        $modulos = $this->modulosNeoArray($request, $modulos);
        //$logger->debug("6666666666666666666666666666666666666 ".print_r($modulos, true));

        foreach($modulos as $tipo => $modulo) {

            foreach ($modulo as $key => $value)
            {
                $logger->debug("Nome do módulo: ".$value['filename']);
                $redeVersaoModulo = new RedeVersaoModulo(null, null, null, null, null, $rede);

                // Carrega dados da rede
                //$arrDadosRede = array( 'rede' => $em->getRepository( 'CacicCommonBundle:Rede' )->listar() );
                //Debug::dump($arrDadosRede['rede'][0][0]);
                //$arrDadosRede = $arrDadosRede['rede'][0];
                $arrDadosRede = array(
                    'teServUpdates' => $rede->getTeServUpdates(),
                    'tePathServUpdates' => $rede->getTePathServUpdates(),
                    'nmUsuarioLoginServUpdatesGerente' => $rede->getNmUsuarioLoginServUpdatesGerente(),
                    'teSenhaLoginServUpdatesGerente' => $rede->getTeSenhaLoginServUpdatesGerente(),
                    'nuPortaServUpdates' => $rede->getNuPortaServUpdates(),
                );

                if ($rede->getDownloadMethod() == 'ftp') {
                    $strResult = $this->checkAndSend(
                        $value['name'],
                        $downloadsDir . $value['filename'],
                        $arrDadosRede['teServUpdates'],
                        $arrDadosRede['tePathServUpdates'],
                        $arrDadosRede['nmUsuarioLoginServUpdatesGerente'],
                        $arrDadosRede['teSenhaLoginServUpdatesGerente'],
                        $arrDadosRede['nuPortaServUpdates']
                    );

                    $arrResult = explode('_=_',$strResult);
                } else {
                    $arrResult[1] = 'Ok!';
                }



                if ($arrResult[1] == 'Ok!')
                {

                    $tipo_so = $em->getRepository('CacicCommonBundle:TipoSo')->findOneBy(array(
                        'tipo' => $tipo
                    ));

                    // Adicione o restante dos atributos
                    $redeVersaoModulo->setNmModulo($value['name']);
                    $redeVersaoModulo->setTeVersaoModulo($value['versao']);
                    $redeVersaoModulo->setDtAtualizacao(new \DateTime('NOW'));
                    $redeVersaoModulo->setCsTipoSo( $value['tipoSo'] );
                    $redeVersaoModulo->setTeHash($value['hash']);
                    $redeVersaoModulo->setTipoSo($tipo_so);
                    $redeVersaoModulo->setFilepath($value['filename']);
                    $redeVersaoModulo->setTipo('cacic');

                    $em->persist($redeVersaoModulo);
                    $em->flush();
                } else {
                    $logger->error("Erro no envio dos módulos!\n".$arrResult[1]);
                    return false;
                }
            }
        }

        return true;
    }

}
