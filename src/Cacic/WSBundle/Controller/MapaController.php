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
        OldCacicHelper::autenticaAgente($request);
        $strNetworkAdapterConfiguration  = OldCacicHelper::deCrypt( $request, $request->get('NetworkAdapterConfiguration') );
        $netmask = TagValueHelper::getValueFromTags( 'IPSubnet', $strNetworkAdapterConfiguration );
        $ip_computador = $request->get('te_ip_computador');
        if ( empty($ip_computador) ){
            $ip_computador = TagValueHelper::getValueFromTags( 'IPAddress', $strNetworkAdapterConfiguration );
        }
        if (empty($ip_computador)) {
            $ip_computador = $request->getClientIp();
        }
        $te_node_address = TagValueHelper::getValueFromTags( 'MACAddress', OldCacicHelper::deCrypt( $request, $request->get('NetworkAdapterConfiguration')));
        // Caso não tenha encontrado, tenta pegar a variável da requisição
        if (empty($te_node_address)) {
            $te_node_address = $request->get('te_node_address');
        }
        if (empty($netmask)) {
            $netmask = $request->get('netmask');
        }
        $modPatrimonio = "false";
        $so = $this->getDoctrine()->getRepository('CacicCommonBundle:So')->findOneBy( array('teSo'=>$request->get( 'te_so' )));
        $rede = $this->getDoctrine()->getRepository('CacicCommonBundle:Rede')->getDadosRedePreColeta( $ip_computador, $netmask );
        $computador = $this->getDoctrine()->getRepository('CacicCommonBundle:Computador')->getComputadorPreCole( $request, $request->get( 'te_so' ),$te_node_address, $rede, $so, $ip_computador );
        $idComputador = $computador->getIdComputador();
        $logger->debug("Teste de Conexão GET-MAPA ! Ip do computador: $ip_computador Máscara da rede: $netmask MAC Address: $te_node_address ID Computador: $idComputador");
        $em = $this->getDoctrine()->getManager();

        $patr = $this->getDoctrine()->getRepository('CacicCommonBundle:AcaoRede')->findOneBy( array('rede'=>$rede->getIdRede(), 'acao'=>'col_patr'));

        /**
         * Se o módulo estiver habilitado, verifica se existe coleta de patrimônio
         */
        if (!empty($patr)){
            $em = $this->getDoctrine()->getManager();
            $query = $em->createQuery(
                'SELECT cc FROM CacicCommonBundle:ComputadorColeta cc INNER JOIN CacicCommonBundle:ClassProperty cp WITH cc.classProperty = cp.idClassProperty WHERE cp.idClass = 15 AND cc.computador = :id'
            )->setParameter('id', $computador);
            $result = $query->getResult();

            /**
             * Caso não exista nenhuma coleta, envia "true" para o agente.
             */
            if (empty($result))
                $modPatrimonio = "true";
        }

        /**
         * Força coleta do patrimônio
         */
        if ($forcaPatrimonio = "S"){
            $modPatrimonio = "true";
            $computador->setForcaPatrimonio('N');
            $this->getDoctrine()->getManager()->persist( $computador );
            $this->getDoctrine()->getManager()->flush();
        }

        /**
         * Mensagem a ser exibida na tela de Pop-Up do patrimônio
         */
        $query = $em->createQuery(
            'SELECT cp.vlConfiguracao FROM CacicCommonBundle:ConfiguracaoPadrao cp WHERE cp.idConfiguracao = :idconfig'
        )->setParameter('idconfig', 'msg_popup_patrimonio');
        $result = $query->getSingleResult();
        $mensagem = implode('',$result);
        $response = new Response();
        $response->headers->set('Content-Type', 'xml');
        return  $this->render('CacicWSBundle:Default:mapa.xml.twig', array(
            'mensagem'=>$mensagem,
            'modPatrimonio' => $modPatrimonio,
        ), $response);
    }
}
