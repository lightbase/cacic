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

use Cacic\CommonBundle\Entity\Computador;
use Cacic\CommonBundle\Entity\LogAcesso;
use Cacic\CommonBundle\Entity\So;


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
        $logger->debug("JSON login received data".print_r($usuario, true)); //user e password
        $_SERVER['SERVER_ADDR'] = $this->getRequest()->getUri();

        $auth = $this->forward('CacicCommonBundle:Security:login', array(
            "username" => $usuario->user,
            "password" => $usuario->password,
        ));
        $logger->debug("JSON login received data".print_r($auth, true)); //dados .twig


        $session = $request->getSession();

        //Gera chave criptografada
        $chave = "123456";
        $chavecrip = md5($chave);

        $auth->setContent(json_encode(array(
            'session' => $session->getId(),
            'chavecrip' => $chavecrip
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
        $dados = json_decode($status, true);
        $logger->debug("JSON get Test status".print_r($dados, true));

        $so_json = $dados['so'];

        $rede_json = $dados['rede'];
        $rede1 = $rede_json[0];
        $mac_json = $rede1['mac'];
        $rede = $rede1['interface'];

        $so = $em->getRepository('CacicCommonBundle:So')->createIfNotExist($so_json);
        $computador = $em->getRepository('CacicCommonBundle:Computador')->findOneBy(array(
            'teNodeAddress'=> $mac_json,
            'idSo' => $so
        ));
        $logger->debug("$so".print_r($so, true));
        $logger->debug("$computador".print_r($computador, true));

        // Regra: MAC e SO são únicos e não podem ser nulos
        $data = new \DateTime('NOW'); //armazena data Atual

        //2 - Insere computador que não existe
          if( empty ( $computador ) )
          {
              $computador = new Computador();

              $computador->setTeNodeAddress( $mac_json );
              $computador->setIdSo( $so );
              $computador->setIdRede( $rede );
              $computador->setDtHrInclusao( $data);

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
              $computador->setDtHrInclusao( $data);

              //Atualiza hora de inclusão
              $em->persist($computador);


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

            }
        }

        $em->flush();

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