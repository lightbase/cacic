<?php

namespace Cacic\WSBundle\Controller;

use Cacic\CommonBundle\Entity\Classe;
use Cacic\CommonBundle\Entity\ComputadorColeta;
use Cacic\CommonBundle\Helper\TagValue;
use Proxies\__CG__\Cacic\CommonBundle\Entity\ComputadorColetaHistorico;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Cacic\CommonBundle\Entity\InsucessoInstalacao;
use Symfony\Component\Validator\Constraints\Date;
use Cacic\CommonBundle\Helper\Criptografia;
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
    public function instalaCacicAction( Request $request )
    {
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
    public function getTestAction( Request $request ){
        $retorno_xml_header  = '<?xml version="1.0" encoding="iso-8859-1" ?>';
        $retorno_xml_values	 = '';

        // Esta condição responde TRUE para o teste de comunicação efetuado pelo chkCACIC
        if ( trim( $request->request('strFieldsAndValuesToRequest') ) == 'in_instalacao=OK' )
            $retorno_xml_values .= '<STATUS>OK</STATUS>';

        $retorno_xml = $retorno_xml_header . $retorno_xml_values;

        return new Response( $retorno_xml);
    }

    /**
     *  Método responsável por inserir coletas  do Agente CACIC
     *
     */
    public function gerColsSetColletAction( Request $request )
    {

        $coleta = $request->request->get('strFieldsAndValuesToRequest'); //atribuido String coletada a varivel $coleta que será enviado via POST pelo Agente_Cacic
        $te_node_address = TagValue::getValueFromTags( 'MACAddress',TagValue::getClassValue( 'NetworkAdapterConfiguration', $coleta ) ); //extraio MacAdess de coleta para futura compara

        $classes = $this->getDoctrine()->getRepository('CacicCommonBundle:Classe')->findAll(); //lista de todas classes
        $computador = $this->getDoctrine()->getRepository('CacicCommonBundle:Computador')->findByTeNodeAddress( $te_node_address ); //pesquiso pelo MacAddress e atribuo o resultado a computador
        $computador = empty( $computador ) ? new Computador() : $computador;


        $classes_coletadas = TagValue::getClassNames( $coleta ); //estraido todas classes que foram coletadas pelo Agente_Cacic
        foreach ( $classes as $classe )
        {
            $computador_coleta_historico = new ComputadorColetaHistorico();

            $computador_coleta = this->getDoctrine()->getRepository('CacicCommonBundle:ComputadorColeta')->findByIdComputador( $computador->getIdComputador() ) ; //procura pelo IdComputador
            $computador_coleta = empty( $computador_coleta ) ? new ComputadorColeta() : $computador_coleta; // se o computador não existir sera instanciado um novo Computador()

            //persistindo coleta de computador
            $computador_coleta->setIdClass( $classe->getIdClass() );
            $computador_coleta->setTeClassValues( TagValue::getClassValue( $classe->getNmClassName() ) );
            $computador_coleta->setIdComputador( $computador->getIdComputador() );
            $this->getDoctrine()->getManager()->persist( $computador_coleta );
            $this->getDoctrine()->getManager()->flush();

            //persistendo Historico de Coletas
            $computador_coleta_historico->setIdComputador( $computador->getIdComputador() );
            $computador_coleta_historico->setTeClassValues( TagValue::getClassValue( $classe->getNmClassName() ) );
            $computador_coleta_historico->setIdComputador( $computador->getIdComputador() );
            $computador_coleta_historico->setDtHrInclusao( \DateTime('NOW') );
            $this->getDoctrine()->getManager()->persist( $computador_coleta_historico );
            $this->getDoctrine()->getManager()->flush();

        }
    }


    /**
     *  Método responsável por retornar configurações necessarias ao Agente CACIC
     *
     */
    public function getConfigAction(Request $request)
    {

    }

    /**
     *  Método responsável por ************ do Agente CACIC
     *
     */
    public function setPatrimonioAction()
    {

    }

    /**
     *  Método responsável por ************ do Agente CACIC
     *
     */
    public function getPatrimonioAction()
    {

    }

    /**
     *  Método responsável por ************ do Agente CACIC
     *
     */
    public function setTcpIpAction()
    {

    }

    /**
     *  Método responsável por ************ do Agente CACIC
     *
     */
    public function setUsbInfoAction()
    {

    }

    /**
     *  Método responsável por ************ do Agente CACIC
     *
     */
    public function setOfficeScanAction()
    {

    }

    /**
     *  Método responsável por ************ do Agente CACIC
     *
     */
    public function setCompartAction()
    {

    }

    /**
     *  Método responsável por ************ do Agente CACIC
     *
     */
    public function setHardwareAction()
    {

    }

    /**
     *  Método responsável por ************ do Agente CACIC
     *
     */
    public function setMonitoradoAction()
    {

    }

    /**
     *  Método responsável por ************ do Agente CACIC
     *
     */
    public function setSoftwareAction()
    {

    }

    /**
     *  Método responsável por ************ do Agente CACIC
     *
     */
    public function setUnidDiscosAction()
    {

    }
}
