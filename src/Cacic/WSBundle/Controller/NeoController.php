<?php
/**
 * Created by PhpStorm.
 * User: gabi
 * Date: 25/07/14
 * Time: 12:24
 */

namespace Cacic\WSBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\HttpFoundation\Session\Storage\MetadataBag;


class NeoController extends Controller {

    /**
     * Método que retorna 200 em requisição na raiz
     */


    public function __construct($maxIdleTime = 1800)
    {
        $this->maxIdleTime = $maxIdleTime;
    }

    public function indexAction(Request $request)
    {
        $logger = $this->get('logger');
        //$logger->debug("222222222222222222222222222222222222 ");

        $response = new JsonResponse();


        if ( $request->isMethod('POST') ) {
            $response->setStatusCode(200);
        } else {
            $response->setStatusCode(403);
        }

        return $response;
    }

    /**
     * Faz login do agente
     */
    public function loginAction(Request $request)
    {
        $logger = $this->get('logger');
        $data = $request->getContent();

        // JSON Serialization
        //$usuario = $serializer->deserialize($data, 'Usuario', 'json');
        $usuario = json_decode($data);
        $logger->debug("JSON login received data".print_r($usuario, true));
        $_SERVER['SERVER_ADDR'] = $this->getRequest()->getUri();

        $auth = $this->forward('CacicCommonBundle:Security:login', array(
            'username' => $usuario->user,
            'password' => $usuario->senha,
        ));

        $session = $request->getSession();

        $auth->setContent(json_encode(array(
            'session' => $session->getId()
        )));

        return $auth;
    }

    /**
     * Controller só para testar a validação da sessão
     */

    public function checkSessionAction(Request $request)
    {
        $logger = $this->get('logger');
        $data = $request->getContent();
        $response = new JsonResponse();
        $session = $request->getSession();
        if (empty($session)) {
            $response->setStatusCode('401');
        }
        $session_valid = $this->checkSession($session);
        if ($session_valid) {
            $response->setStatusCode('200');
        } else {
            $response->setStatusCode('401');
        }

        return $response;
    }

    public function getTestAction(Request $request)
    {
        //1 - Verificar se computador existe
        $logger = $this->get('logger');
        $status = $request->getContent();
        $em = $this->getDoctrine()->getManager();

        $response = new JsonResponse();
        $dados = json_decode($status);
        $logger->debug("JSON get Test status".print_r($dados, true));

        $so_json = $dados['so'];

        $rede_json = $dados['rede'];
        $rede1 = $rede_json[0];
        $mac_json = $rede1['mac'];
        $rede = $rede1['interface'];

        $so = $em->getRepository('CacicCommonBundle:So')->findOneBy(array('te_so' => $so_json));
        $mac = $em->getRepository('CacicCommonBundle:Computador')->findOneBy(array('te_node_address'=> $mac_json));
        $logger->debug("$so".print_r($so, true));
        $logger->debug("$mac".print_r($mac, true));

        // Regra: MAC e SO são únicos e não podem ser nulos
        $computador = $em->findOneBy( array( 'te_node_address'=> $mac, 'te_so'=> $so->getTeSo()) );
        $data = new \DateTime('NOW'); //armazena data Atual

        //2 - Insere computador que não existe
          if( empty ( $computador ) )
          {
              $computador = new Computador();

              $computador->setTeNodeAddress( $mac );
              $computador->setIdSo( $so );
              $computador->setIdRede( $rede );
              $computador->setDtHrInclusao( $data);
              $computador->setTePalavraChave( $request->get('PHP_AUTH_PW') );

              $em->persist( $computador );

          }

        /*
        $computador->setDtHrUltAcesso( $data );
        $computador->setTeVersaoCacic( $te_versao_cacic );
        $computador->setTeVersaoGercols( $te_versao_gercols );
        $computador->setTeUltimoLogin( TagValueHelper::getValueFromTags( 'UserName' ,$computer_system ) );
        $computador->setTeIpComputador( TagValueHelper::getValueFromTags( 'IPAddress' ,$network_adapter ) );
        $computador->setNmComputador( TagValueHelper::getValueFromTags( 'Caption' ,$computer_system ));
        $this->getEntityManager()->persist( $computador );

        $acoes = $this->getEntityManager()->getRepository('CacicCommonBundle:Acao')->findAll();
        */

        // 2.1 - Se existir, atualiza hora de inclusão
         else
          {
              $update = $em->getRepository('CacicCommonBundle:Computador')->findBy(aresponserray('te_node_address'=> $mac, 'te_so'=> $so->getTeSo()));

              $update->setDtHrInclusao($data);

              //Atualiza hora de inclusão
              $em->persist($update);
              $em->flush();


          }

        // 3 - Grava no log de acesso
        //Só adiciona se o último registro foi em data diferente da de hoje

        $data_acesso = new \DateTime();
        $hoje = $data_acesso->format('Y-m-d');

        $ultimo_acesso = $em->getRepository('CacicCommonBundle:LogAcesso')->ultimoAcesso( $computador->getIdComputador() );
        if (empty($ultimo_acesso)) {
            // Se for o primeiro registro grava o acesso do computador
            $logger->debug("Último acesso não encontrado. Registrando acesso para o computador $computador em $hoje");

            $log_acesso = new LogAcesso();
            $log_acesso->setIdComputador($computador);
            $log_acesso->setData($data_acesso);

            // Grava o log
            $em->persist($log_acesso);
            $em->flush();

        } else {
            $dt_ultimo_acesso = $ultimo_acesso->getData()->format('Y-m-d');

            // Adiciona se a data de útimo acesso for diferente do dia de hoje
            if ($hoje != $dt_ultimo_acesso) {
                $logger->debug("Inserindo novo registro de acesso para o computador $computador em $hoje");

                $log_acesso = new LogAcesso();
                $log_acesso->setIdComputador($computador);
                $log_acesso->setData($data_acesso);

                // Grava o log
                $em->persist($log_acesso);
                $em->flush();
            }
        }

        // 4 - Retorna chave de criptografia


        $response->setStatusCode('200');
        return $response;


    }

    /**
     * Função para validar a sessão
     */
    public function checkSession(Session $session) {
        $session->getMetadataBag()->getCreated();
        $session->getMetadataBag()->getLastUsed();

        if(time() - $session->getMetadataBag()->getLastUsed() > $this->maxIdleTime) {
            $session->invalidate();
            throw new SessionExpired(); // direciona para a página de sessão expirada
            return false;
        }
        else{
            return true;
        }
    }


} 