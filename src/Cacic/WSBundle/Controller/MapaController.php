<?php
namespace Cacic\WSBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Cacic\WSBundle\Helper\OldCacicHelper;
use Cacic\WSBundle\Helper\TagValueHelper;
class MapaController extends Controller {
    /**
     *  Método responsável por enviar as configurações de Patrimônio necessarias ao Agente CACIC
     *  @param Symfony\Component\HttpFoundation\Request $request
     */
    public function mapaAction ( Request $request ) {
        $logger = $this->get('logger');
        $em = $this->getDoctrine()->getManager();
        OldCacicHelper::autenticaAgente( $request ) ;
        $strNetworkAdapterConfiguration  = OldCacicHelper::deCrypt( $request, $request->get('NetworkAdapterConfiguration') );
        $strComputerSystem  			 = OldCacicHelper::deCrypt( $request, $request->get('ComputerSystem') );
        $strOperatingSystem  			 = OldCacicHelper::deCrypt( $request, $request->request->get('OperatingSystem') );


        $te_node_address = TagValueHelper::getValueFromTags( 'MACAddress', $strNetworkAdapterConfiguration );
        $netmask = TagValueHelper::getValueFromTags( 'IPSubnet', $strNetworkAdapterConfiguration );
        $te_so = $request->get( 'te_so' );
        $ultimo_login = TagValueHelper::getValueFromTags( 'UserName'  , $strComputerSystem);
        $ip_computador = $request->get('te_ip_computador');
        $versaoAgente = $request->get('te_versao_cacic');

        if ( empty($ip_computador) ){
            $ip_computador = TagValueHelper::getValueFromTags( 'IPAddress', $strNetworkAdapterConfiguration );

        }

        if (empty($ip_computador)) {
            $ip_computador = $request->getClientIp();
        }

        // Caso não tenha encontrado, tenta pegar a variável da requisição
        if (empty($te_node_address)) {
            $te_node_address = $request->get('te_node_address');
        }

        if (empty($netmask)) {
            $netmask = $request->get('netmask');
        }

        //vefifica se existe SO coletado se não, insere novo SO
        $so = $em->getRepository('CacicCommonBundle:So')->createIfNotExist( $te_so );
        $rede = $em->getRepository('CacicCommonBundle:Rede')->getDadosRedePreColeta( $ip_computador, $netmask );

        // Retorna falso por padrão
        $modPatrimonio = "false";

        if (empty($te_node_address) || empty($so)) {
            $this->get('logger')->error("Erro na operação de getMapa. IP = $ip_computador Máscara = $netmask. MAC = $te_node_address. SO = $te_so");

            $response = new Response();
            $response->headers->set('Content-Type', 'xml');

            return  $this->render('CacicWSBundle:Default:mapa.xml.twig', array(
                'mensagem'=> "",
                'modPatrimonio' => "false",
            ), $response);
        }

        $computador = $em->getRepository('CacicCommonBundle:Computador')->getComputadorPreCole(
            $request,
            $te_so,
            $te_node_address,
            $rede,
            $so,
            $ip_computador
        );

        $idComputador = $computador->getIdComputador();
        $logger->debug("Teste de Conexão GET-MAPA ! Ip do computador: $ip_computador Máscara da rede: $netmask MAC Address: $te_node_address ID Computador: $idComputador");

        $patr = $em->getRepository('CacicCommonBundle:AcaoRede')->findOneBy(array(
            'rede'=>$rede->getIdRede(),
            'acao'=>'col_patr'
        ));

        /**
         * Se o módulo estiver habilitado, verifica se existe coleta de patrimônio
         */
        if (!empty($patr)){

            $result = $em->getRepository('CacicCommonBundle:ComputadorColeta')->getDadosColetaComputador(
                $computador,
                'Patrimonio'
            );

            /**
             * Caso não exista nenhuma coleta, envia "true" para o agente.
             */
            if (empty($result)) {
                $logger->debug("COLETA DE PATRIMÔNIO INEXISTENTE!!! COLETANDO...");
                $modPatrimonio = "true";
            }
        }

        /**
         * Força coleta do patrimônio
         */

        $forcaPatrimonio = $computador->getForcaPatrimonio();

        if ($forcaPatrimonio == "S"){
            $logger->debug("COLETA FORÇADA DE PATRIMÔNIO: $forcaPatrimonio");

            $modPatrimonio = "true";
            $computador->setForcaPatrimonio('N');
            $em->persist( $computador );
        }

        $em->flush();

        /**
         * Mensagem a ser exibida na tela de Pop-Up do patrimônio
         */
        $mensagem = $em->getRepository('CacicCommonBundle:ConfiguracaoPadrao')->findOneBy(array(
            'idConfiguracao' => 'msg_popup_patrimonio'
        ));
        if (!empty($mensagem)) {
            $mensagem = $mensagem->getVlConfiguracao();
        } else {
            $mensagem = "Pop-up de patrimônio";
        }
        //$mensagem = implode('',$mensagem);

        $logger->debug("RESULTADO DO PATRIMÔNIO: $modPatrimonio");

        // Retorna patrimônio
        $response = new Response();
        $response->headers->set('Content-Type', 'xml');
        return  $this->render('CacicWSBundle:Default:mapa.xml.twig', array(
            'mensagem'=>$mensagem,
            'modPatrimonio' => $modPatrimonio,
        ), $response);
    }
}
