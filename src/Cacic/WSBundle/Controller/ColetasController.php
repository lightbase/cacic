<?php

namespace Cacic\WSBundle\Controller;

use Cacic\CommonBundle\Entity\Classe;
use Cacic\CommonBundle\Entity\ClassProperty;
use Cacic\CommonBundle\Entity\ClassPropertyType;
use Cacic\CommonBundle\Entity\ComputadorColeta;
use Cacic\CommonBundle\Helper\TagValue;
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
        if ( trim( $request->request('in_chkcacic') )=='chkcacic_GetTest' )
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
        $classes_property_type = new ClassPropertyType();
        $classes_property = new ClassProperty();
        $classe = new Classe();
        $computador_coleta = new ComputadorColeta();

        $computador = $this->getDoctrine()->getRepository('CacicCommonBundle:Computador')->findByTeNodeAddress( $te_node_address ); //pesquiso pelo MacAddress e atribuo o resultado a computador
        $computador = empty( $computador ) ? new Computador() : $this->computador; // se computador estiver vazio instancio um novo Computador() senão permanecerá o mesmo


        $classes_property_coletado = TagValue::getTagsFromValues($coleta); //estraido todas propriedades que foram coletadas pelo Agente_Cacic
        foreach ($classes_property_coletado as $classe_property)
        {
            $classes_property->setNmPropertyName($classes_property);
            $classes_property->setIdClass( $classe->getIdClass() ); //verificar como obter classe e ID provavente será necessário passar somente nome.
            $classes_property_type->setCsType( TagValue::getValueFromTags( $classe_property, $coleta ) ); //passado parametro tag, stringColetada, retorna conteudo coletado na tag passsada por parametro


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
