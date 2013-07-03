<?php

namespace Cacic\WSBundle\Controller;

use Cacic\CommonBundle\Entity\Classe;
use Cacic\CommonBundle\Entity\Computador;
use Cacic\CommonBundle\Entity\Rede;
use Cacic\CommonBundle\Entity\ComputadorColeta;
use Cacic\CommonBundle\Entity\So;
use Cacic\CommonBundle\Helper\Constantes;
use Cacic\CommonBundle\Helper\TagValue;
use Cacic\CommonBundle\Entity\ComputadorColetaHistorico;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Cacic\CommonBundle\Entity\InsucessoInstalacao;
use Symfony\Component\Validator\Constraints\Date;
use Cacic\CommonBundle\Helper\Criptografia;
use Cacic\CommonBundle\Entity\AcaoSo;
/**
 *
 * Classe responsável por Rerceber as coletas Agente
 * @author lightbase
 *
 */
class ColetasController extends Controller
{
    /**
     *  Método responsável por inserir falhas na instalação do Agente CACIC
     *
     */
    public function instalaCacicAction( )
    {
        $request = new Request();
       if( $request->isMethod('POST')  )
        {
            $data = new \DateTime('NOW');

            $insucesso =  new InsucessoInstalacao();

            $insucesso->setTeIpComputador( $_SERVER["REMOTE_ADDR"] );
            $insucesso->setTeSo( $request->request->get('te_so') );
            $insucesso->setIdUsuario( $request->request->get('id_usuario') );
            $insucesso->setCsIndicador( $request->request->get('cs_indicador') );
            $insucesso->setDtDatahora( $data  );

            $this->getDoctrine()->getManager()->persist( $insucesso );
            $this->getDoctrine()->getManager()->flush();
        }

    }

    /**
     *  Método responsável por Verificar se houve comunicação com o Agente CACIC
     *
     */
    public function getTestAction(){


        $request = new Request();/*
        $common_ws = new CommonWs( );
        $strXML_Values = $common_ws->commonTop( $request );
        $strPaddingKey = $request->request->get('padding_key');

        $strPaddingKey   = empty( $strPaddingKey ) ?  $strPaddingKey : '';*/    $strXML_Values = '<? xml version="1.0" encoding="iso-8859-1" ?>';
        $strXML_Values .= '<Comm_Status>' . 'OK' . '<'	.	'/Comm_Status>';

        if ( file_exists( Constantes::CACIC_PATH . Constantes::CACIC_PATH_RELATIVO_DOWNLOADS . 'versions_and_hashes.ini') ) //adptar ao symfony!!!
        {
            $arrVersionsAndHashes = parse_ini_file( Constantes::CACIC_PATH . Constantes::CACIC_PATH_RELATIVO_DOWNLOADS . 'versions_and_hashes.ini');
            $strXML_Values .= '<INSTALLCACIC.EXE_HASH>'	. 	Criptografia::enCrypt( $request, $arrVersionsAndHashes['installcacic.exe_HASH'],
                    $request->request->get('cs_cipher'),
                    $request->request->get('cs_compress') ,
                    $strPaddingKey ,
                    true ) 	. '<' 	. 	'/INSTALLCACIC.EXE_HASH>';
            $strXML_Values .= '<MainProgramName>'  		. 	Constantes::CACIC_MAIN_PROGRAM_NAME.'.exe'	. '<' 	. 	'/MainProgramName>';
            $strXML_Values .= '<LocalFolderName>' 		. 	Constantes::CACIC_LOCAL_FOLDER_NAME			. '<' 	. 	'/LocalFolderName>';
        }

        //$strXML_Values .= CommonWs::commonBottom( $request );

        return new Response( $strXML_Values );
    }

    /**
     *  Método responsável por inserir coletas  do Agente CACIC
     *
     */
    public function gerColsSetColletAction()
    {
        $data = new \DateTime('NOW');
        $request = new Request();

        $coleta = $request->request->get('strFieldsAndValuesToRequest'); //atribuido String coletada a varivel $coleta que será enviado via POST pelo Agente_Cacic
        $te_node_address = TagValue::getValueFromTags( 'MACAddress',TagValue::getClassValue( 'NetworkAdapterConfiguration', $coleta ) ); //extraio MacAdess de coleta para futura compara

        $classes = $this->getDoctrine()->getRepository('CacicCommonBundle:Classe')->findAll(); //lista de todas classes
        $computador = $this->getDoctrine()->getRepository('CacicCommonBundle:Computador')->findBy( array ( 'te_node_address' => $te_node_address ) ); //pesquiso pelo MacAddress e atribuo o resultado a computador
        $computador = empty( $computador ) ? new Computador() : $computador;

        foreach ( $classes as $classe )
        {
            $computador_coleta_historico = new ComputadorColetaHistorico();
            $computador_coleta = $this->getDoctrine()->getRepository('CacicCommonBundle:ComputadorColeta')->findBy( array( 'id' => $computador->getIdComputador() , 'id_class' => $classe->getIdClass() ) ); //procura pelo IdComputador e idClasse
            $computador_coleta = empty( $computador_coleta ) ? new ComputadorColeta() : $computador_coleta; // se o computador não existir sera instanciado um novo Computador()

            //persistindo coleta de computador
            $computador_coleta->setIdClass( $classe->getIdClass() );
            $computador_coleta->setTeClassValues( TagValue::getClassValue( $classe->getNmClassName(), $coleta ) );
            $computador_coleta->setIdComputador( $computador->getIdComputador() );
            $this->getDoctrine()->getManager()->persist( $computador_coleta );

            //persistendo Historico de Coletas
            $computador_coleta_historico->setIdComputador( $computador->getIdComputador() );
            $computador_coleta_historico->setTeClassValues( TagValue::getClassValue( $classe->getNmClassName(), $coleta ) );
            $computador_coleta_historico->setIdComputador( $computador->getIdComputador() );
            $computador_coleta_historico->setDtHrInclusao( $data );
            $this->getDoctrine()->getManager()->persist( $computador_coleta_historico );

            $this->getDoctrine()->getManager()->flush(); //efetua alterações no Banco de Dados

        }


        //persistindo em Computador
        $te_so = TagValue::getTagsFromValues( 'Version' ,TagValue::getClassValue('OperatingSystem', $coleta) ); //extraido da coleta versão do Sitema Operacional
        $so = $this->getDoctrine()->getRepository('CacicCommonBundle:So')->findBy( array( 'te_so' => $te_so ) );
        $so = empty( $so ) ? new So() : $so;
//        $computador->set

    }


    /**
     *  Método responsável por retornar configurações necessarias ao Agente CACIC
     *
     */
    public function getConfigAction()
    {
        $request = new Request();


    }

    /**
     *  Método responsável por ************ do Agente CACIC
     *
     */
    public function gerColsSetSrcacicAction()
    {

    }

    /**
     *  Método responsável por ************ do Agente CACIC
     *
     */
    public function gerColsSetUsbDetectAction()
    {

    }

    /**
     *  Método responsável por ************ do Agente CACIC
     *
     */
    public function mapaCacicAcessoAction()
    {

    }

    /**
     *  Método responsável por ************ do Agente CACIC
     *
     */
    public function srCacicSetSessionAction()
    {

    }

    /**
     *  Método responsável por ************ do Agente CACIC
     *
     */
    public function srCacicAuthClientAction()
    {

    }

    /*
     * Métodos auxiliares a coleta
     */
    protected function checkSoExistInsetNew( $te_so )
    {
        $so = $this->getDoctrine()->getRepository('CacicCommonBundle:So')->findBy( array ( 'te_so' => $te_so ) );
        if( empty( $so ) )
        {
            $so = new So();
            $so->setTeSo($te_so);
            $so->setSgSo("Sigla a Cadastrar");
            $so->getTeDescSo("S.O. a Cadastrar");
            $this->getDoctrine()->getManager()->persist( $so );

            $this->getDoctrine()->getManager()->flush();

        }

        return $so;

    }

    /*
     * Método responsável por coletar verificar dados de rede
     */
    protected function getDadosRedePreColeta( Request $request , $te_node_adress, $id_so )
    {
        //obtem IP da maquina coletada
        $ip_computador = $request->request->get('te_ip_computador');
        $ip_computador = empty( $ip_computador ) ? $_SERVER['REMOTE_ADDR'] : $$ip_computador;

        //obtem IP da Rede que a maquina coletada pertence
        $ip = explode( '.', $ip_computador );
        $te_ip_rede = $ip[0].".".$ip[1].".".$ip[2].".0"; //Pega ip da REDE sendo esse X.X.X.0

        //procura computador pelo MACADRESS e SO, se nao existir na base instancio um novo
        $computador = $this->getDoctrine()->getRepository('CacicCommonBundle: Computador')->findBy( array( 'te_node_adress'=> $te_node_adress, 'id_so'=>$id_so));
        $computador = empty($computador) ? new Computador() : $computador;
        $rede =  $this->getDoctrine()->getRepository('CacicCommonBundle: Rede')->findBy( array( 'te_ip_rede'=> $te_ip_rede ) ); //procura rede
        $rede = empty( $rede ) ? new Rede() : $rede;  // se rede não existir instancio uma nova rede

        return $rede;
    }

    /*
     * Metodo responsável por inserir coletas iniciais, assim que o cacic é instalado
     */
    protected function getDadosPreColeta( Request $request , $te_so , $te_node_adress )
    {
        //recebe dados via POST, deCripata dados, e attribui a variaveis
        $computer_system   = Criptografia::deCrypt( $request, $request->request->get('ComputerSystem'),$request->request->get('cs_cipher'),  $request->request->get('cs_compress') , true  );
        $te_versao_cacic   = Criptografia::deCrypt( $request, $request->request->get('te_versao_cacic'),$request->request->get('cs_cipher'),  $request->request->get('cs_compress') , true  );
        $te_versao_gercols = Criptografia::deCrypt( $request, $request->request->get('te_versao_gercols'),$request->request->get('cs_cipher'),  $request->request->get('cs_compress') , true  );
        $network_adapter   = Criptografia::deCrypt( $request, $request->request->get('NetworkAdapterConfiguration'),$request->request->get('cs_cipher'),  $request->request->get('cs_compress') , true  );
        $operating_system  = Criptografia::deCrypt( $request, $request->request->get('OperatingSystem'),$request->request->get('cs_cipher'),  $request->request->get('cs_compress') , true  );
        $data = new \DateTime('NOW'); //armazena data Atual

        //vefifica se existe SO coletado se não, insere novo SO
        $so = $this->checkSoExistInsetNew( $te_so );
        $id_so= $so['id_so'];

        $rede = $this->getDadosRedePreColeta( $request , $te_node_adress, $id_so );

        //inserção de dado se for um novo computador
        if( empty($computador['dt_hr_inclusao']) )
        {
            $computador->setTeNodeAddress( $te_node_adress );
            $computador->getIdSo( $id_so );
            $computador->setIdRede( $rede['id_rede'] );
            $computador->setDtHrInclusao( $data);
        }

        //inserção de dados na tabela computador_coleta
        $computadorColeta = new ComputadorColeta();
        $computadorColeta->setIdComputador( $computador->getIdComputador() );
        $computadorColeta->setTeClassValues( $network_adapter );
        $computadorColeta->setIdClass(
            $this->getDoctrine()->getRepository('CacicCommonBundle: Classe')->findBy( array( 'nm_class_name'=> 'NetworkAdapterConfiguration') )
        );
        $this->getDoctrine()->getManager()->persist( $computadorColeta );

        $computadorColeta = new ComputadorColeta();
        $computadorColeta->setIdComputador( $computador->getIdComputador() );
        $computadorColeta->setTeClassValues( $operating_system );
        $computadorColeta->setIdClass(
            $this->getDoctrine()->getRepository('CacicCommonBundle: Classe')->findBy( array( 'nm_class_name'=> 'OperatingSystem') )
        );
        $this->getDoctrine()->getManager()->persist( $computadorColeta );

        $computadorColeta = new ComputadorColeta();
        $computadorColeta->setIdComputador( $computador->getIdComputador() );
        $computadorColeta->setTeClassValues( $computer_system );
        $computadorColeta->setIdClass(
            $this->getDoctrine()->getRepository('CacicCommonBundle: Classe')->findBy( array( 'nm_class_name'=> 'ComputerSystem') )
        );
        $this->getDoctrine()->getManager()->persist( $computadorColeta );


        $computador->setDtHrUltAcesso( $data );
        $computador->setTeVersaoCacic( $te_versao_cacic );
        $computador->setTeVersaoGercols( $te_versao_gercols );
        $computador->setTeUltimoLogin( TagValue::getValueFromTags( 'UserName' ,$computer_system ) );
        $this->getDoctrine()->getManager()->persist( $computador );

        $acoes = $this->getDoctrine()->getRepository('CacicCommonBundle: Acao')->findAll();

        //inserção ações de coleta a nova maquina
        $acao_so = new AcaoSo();
        $acao_so->setRede( $rede->getIdRede() );
        $acao_so->setSo( $so->getIdSo() );
        $acao_so->setAcao( $acoes );

        return $computador;
    }

    /*
     * Responsável por autenticação do agente CACIC
     */
    protected function autenticaAgente($p_PaddingKey='', Request $request)
    {
        if( ( strtoupper( Criptografia::deCrypt( $request, $request->request->get('HTTP_USER_AGENT') , $request->request->get('cs_cipher'),  $request->request->get('cs_compress') ,$p_PaddingKey , true ) ) != 'AGENTE_CACIC') ||
            ( strtoupper( Criptografia::deCrypt( $request, $request->request->get('PHP_AUTH_USER'  ) , $request->request->get('cs_cipher'),  $request->request->get('cs_compress') ,$p_PaddingKey , true ) ) != 'USER_CACIC') ||
            ( strtoupper( Criptografia::deCrypt( $request, $request->request->get('PHP_AUTH_PW'    ) , $request->request->get('cs_cipher'),  $request->request->get('cs_compress') ,$p_PaddingKey , true ) ) != 'PW_CACIC'))
        {
            echo ' Acesso Não Autorizado.'; // deve ser mostrado no browser //verificar Mensagem padrão de erro no Symfony
        }
    }

    /*
     * Método responsável por retornar TOPO do XML das coletas.
     */
    protected function commonTop( Request $request, $v_compress_level = 0 )
    {
        $v_cs_cipher	= ( trim( $request->request->get('cs_cipher') )   <> '' ? trim( $request->request->get('cs_cipher') )   : '4');
        $v_cs_compress	= ( trim( $request->request->get('cs_compress') ) <> '' ? trim( $request->request->get('cs_compress') ) : '4');

        // O agente PyCACIC envia o valor "padding_key" para preenchimento da palavra chave para decriptação/encriptação
        // Valores específicos para trabalho com o PyCACIC - 04 de abril de 2008 - Rogério Lino - Dataprev/ES
        // A versão inicial do agente em Python exige esse complemento na chave...
        $strPaddingKey   = ( $request->request->get('padding_key') ?  $request->request->get('padding_key') : '');
        $boolAgenteLinux = ( trim( $request->request->get('AgenteLinux') ) <> '' ? true : false );

        // Autenticação da chamada:
        $this->autenticaAgente( $strPaddingKey, $request );

        $strNetworkAdapterConfiguration  = Criptografia::deCrypt( $request, $request->request->get('NetworkAdapterConfiguration')   , $v_cs_cipher, $v_cs_compress,$strPaddingKey );
        $strComputerSystem  			 = Criptografia::deCrypt( $request, $request->request->get('ComputerSystem')				, $v_cs_cipher, $v_cs_compress,$strPaddingKey );
        $strOperatingSystem  			 = Criptografia::deCrypt( $request, $request->request->get('OperatingSystem')			    , $v_cs_cipher, $v_cs_compress,$strPaddingKey );

        $arrDadosComputador 			 = $this->getDadosPreColeta(
            $request,
            TagValue::getValueFromTags( 'MACAddress', $strNetworkAdapterConfiguration ),
            $request->request->get( 'te_so' ),
            TagValue::getValueFromTags( 'UserName'  , $strComputerSystem)
        );

        $arrDadosRede  = $this->getDadosRedePreColeta( $request , $arrDadosComputador['te_node_adress'] , $arrDadosComputador['id_so'] ); // reescrever getDadosRede no Library

        if ( $request->request->get('te_palavra_chave') )
            $strTePalavraChave = Criptografia::deCrypt( $request, $request->request->get('te_palavra_chave') , $v_cs_cipher,$v_cs_compress,$strPaddingKey );

        // --------------- Retorno de Classificador de CRIPTOGRAFIA --------------------------------------------- //
        if ($v_cs_cipher <> '1') $v_cs_cipher --;
        // Comente/Descomente a linha abaixo para habilitar/desabilitar a criptografia de informa��es trafegadas
        //$v_cs_cipher = '0';
        // ----------------------------------------------------------------------------------------------------- //

        // --------------- Retorno de Classificador de COMPRESS�O ---------------------------------------------- //
        $pos = strpos($_SERVER['HTTP_ACCEPT_ENCODING'], "deflate");
        if ( $pos <> -1 && $v_cs_compress <>'1' ) $v_cs_compress -= 1;

        // Caso o n�vel de compress�o sera setado para 0(zero) o indicador deve retornar 0(zero)
        if ( $v_compress_level == '0' ) $v_cs_compress = '0';

        // Comente/Descomente a linha abaixo para habilitar/desabilitar a compacta��o de informa��es trafegadas
        //$v_cs_compress = '0';
        // ----------------------------------------------------------------------------------------------------- //

        $strXML_Begin  	 = 	'<? xml version="1.0" encoding="iso-8859-1" ?><CONFIGS>';
        $strXML_Values 	 = 	'';

        $strTeDebugging	 = 	( TagValue::getValueFromTags('DateToDebugging',$arrDadosComputador['te_debugging'] )  == date("Ymd") ? $arrDadosComputador['te_debugging']  	:
            ( TagValue::getValueFromTags('DateToDebugging',$arrDadosRede['te_debugging_local'] )  == date("Ymd") ?
                $arrDadosRede['te_debugging_local']  	:
                ( TagValue::getValueFromTags('DateToDebugging',$arrDadosRede['te_debugging_subnet'] ) == date("Ymd") ? $arrDadosRede['te_debugging_subnet'] 	: 	'') ) );

        $strXML_Values  .= 	( $strTeDebugging ? '<TeDebugging>' 																										: 	'');
        $strXML_Values  .= 	( $strTeDebugging ? TagValue::getValueFromTags('DetailsToDebugging',$strTeDebugging)																:	'');
        $strXML_Values  .= 	( $strTeDebugging ? '</TeDebugging>' 																									: 	'');

        $strXML_Values  .= 	'<IdComputador>' 		 . 	$arrDadosComputador['id_computador']	. '<'	.	'/IdComputador>';
        $strXML_Values  .= 	'<WebManagerAddress>'     .	$arrDadosRede['te_serv_cacic']		. '<' 	. 	'/WebManagerAddress>';
        $strXML_Values  .= 	'<WebServicesFolderName>' . CACIC_WEB_SERVICES_FOLDER_NAME		    . '<' 	. 	'/WebServicesFolderName>';

        return $strXML_Begin.$strXML_Values;

    }

    /*
     * Método responsável por retornar FIM do XML das coletas.
     */
    protected function commonBottom( Request $request )
    {
        $v_cs_cipher	= ( trim( $request->request->get('cs_cipher') )   <> '' ? trim( $request->request->get('cs_cipher') )   : '4');
        $v_cs_compress	= ( trim( $request->request->get('cs_compress') ) <> '' ? trim( $request->request->get('cs_compress') ) : '4');

        $strXML_Values = $this->commonTop( $request ).'<Comm_Status>' . 'OK' . '<'	.	'/Comm_Status>';

        $strXML_Values = str_replace('+','[[MAIS]]'  , $strXML_Values);
        $strXML_Values = str_replace(' ','[[ESPACE]]', $strXML_Values);

        $strXML_End 	 = 	'<cs_compress>'			 . 	$v_cs_compress . '<' 	.	'/cs_compress>';
        $strXML_End 	.= 	'<cs_cipher>'			 . 	$v_cs_cipher   . '<'	.	'/cs_cipher>';
        $strXML_End		.= 	'</CONFIGS>';

        return $strXML_Values . $strXML_End;
    }
}