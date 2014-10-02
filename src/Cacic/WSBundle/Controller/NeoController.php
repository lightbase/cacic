<?php
/**
 * Created by PhpStorm.
 * User: gabi
 * Date: 25/07/14
 * Time: 12:24
 */

namespace Cacic\WSBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\BrowserKit\Response;
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


    public function __construct($maxIdleTime = 1800)
    {
        $this->maxIdleTime = $maxIdleTime;
    }

    /**
     * Método que retorna 200 em requisição na raiz
     */
    public function indexAction(Request $request)
    {
        $logger = $this->get('logger');

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
        $em = $this->getDoctrine()->getManager();
        $data = $request->getContent();

        $session = $request->getSession();
        $session->start();

        $chavecrip = '123456';

        $usuario = $this->get('security.context')->getToken()->getUser();
        $logger->debug("Usuario encontrado: ".$usuario->getUserName());

        $auth = new JsonResponse();
        $auth->setContent(json_encode(array(
            'session' => $session->getId(),
            'chavecrip' => $usuario->getApiKey()
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

    /*
     Insere o computador se não existir
    */
    public function getTestAction(Request $request)
    {
        //1 - Verificar se computador existe
        $logger = $this->get('logger');
        $status = $request->getContent();
        $em = $this->getDoctrine()->getManager();

        $logger->debug("JSON getTest:\n".$status);

        $dados = json_decode($status, true);

        if (empty($dados)) {
            $logger->error("JSON INVÁLIDO!!!!!!!!!!!!!!!!!!! Erro no getTest");
            // Retorna erro se o JSON for inválido
            $error_msg = '{
                "message": "JSON Inválido",
                "codigo": 1
            }';


            $response = new JsonResponse();
            $response->setStatusCode('500');
            $response->setContent($error_msg);
            return $response;
        }

        $logger->debug("JSON get Test status \n".print_r(json_decode($status, true), true));

        // Identifica computador
        $computador = $this->getComputador($dados, $request);

        if (empty($computador)) {
            $logger->error("Erro na identificação do computador. Retorna mensagem de erro");

            $error_msg = '{
                "message": "Computador não identificado",
                "codigo": 2
            }';


            $response = new JsonResponse();
            $response->setStatusCode('500');
            $response->setContent($error_msg);
            return $response;
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

        # TODO: Grava log de acessos de usuario do computador

        $em->flush();

        $response = new JsonResponse();
        $response->setStatusCode('200');
        return $response;
    }

    /*
     * ConfigTeste
    */
    public function configAction(Request $request)
    {
        $logger = $this->get('logger');
        $status = $request->getContent();
        $em = $this->getDoctrine()->getManager();
        $dados = json_decode($status, true);

        if (empty($dados)) {
            $logger->error("JSON INVÁLIDO!!!!!!!!!!!!!!!!!!! Erro no getConfig");
            // Retorna erro se o JSON for inválido
            $error_msg = '{
                "message": "JSON Inválido",
                "codigo": 1
            }';


            $response = new JsonResponse();
            $response->setStatusCode('500');
            $response->setContent($error_msg);
            return $response;
        }

        $computador = $this->getComputador($dados, $request);

        if (empty($computador)) {
            // Se não identificar o computador, manda para o getUpdate
            $logger->error("Computador não identificado no getConfig. Necessário executar getUpdate");

            $error_msg = '{
                "message": "Computador não identificado",
                "codigo": 2
            }';


            $response = new JsonResponse();
            $response->setStatusCode('500');
            $response->setContent($error_msg);
            return $response;
        }

        // 0 - Array de saída
        $saida['agentcomputer'] = "";


        // 1 - Ações para o computador
        $acoes = $em->getRepository('CacicCommonBundle:Acao')->listaAcaoComputador(
            $computador->getIdRede()->getIdRede(),
            $computador->getIdSo()->getIdSo(),
            $computador->getTeNodeAddress()
        );
        $logger->debug("Ações encontradas \n".print_r($acoes, true));
        $cols = array();
        foreach ($acoes as $elm) {
            // Adiciona ações na saída
            if (empty($elm['idAcao'])) {
                $saida['agentcomputer']['actions'][$elm['acaoExecao']] = true;
            }
            $saida['agentcomputer']['actions'][$elm['idAcao']] = true;
        }

        //$logger->debug("1111111111111111111111111111111 \n".print_r($saida, true));

        // 2 - Adiciona módulos da subrede
        $modulos = $em->getRepository('CacicCommonBundle:RedeVersaoModulo')->findBy(array('idRede' => $computador->getIdRede()));
        //$logger->debug("Módulos encontrados \n". print_r($modulos, true));
        $mods = array();
        foreach($modulos as $elm) {
            // Adiciona módulos e hashes
            array_push($mods, array(
                'nome' => $elm->getNmModulo(),
                'hash' => $elm->getTeHash()
            ));
        }
        $saida['agentcomputer']['modulos'] = $mods;
        //$logger->debug("2222222222222222222222222222222222222 \n".print_r($saida, true));

        // 3 - Adiciona classes WMI
        $class = $em->getRepository('CacicCommonBundle:Classe')->findAll();
        $classes = array();
        foreach($class as $elm) {
            // Adiciona classes WMI
            array_push($classes, $elm->getNmClassName());
        }
        $saida['agentcomputer']['classes'] = $classes;
        //$logger->debug("33333333333333333333333333333333333333333 \n".print_r($saida, true));


        // 4 - Configurações genéricas
        $saida['agentcomputer']['applicationUrl'] = $computador->getIdRede()->getTeServCacic();
        $saida['agentcomputer']['metodoDownload'] = array(
            "tipo" => "ftp",
            "url" => $computador->getIdRede()->getTeServUpdates(),
            "path" => $computador->getIdRede()->getTePathServUpdates(),
            "usuario" => $computador->getIdRede()->getNmUsuarioLoginServUpdates(),
            "senha" => $computador->getIdRede()->getTeSenhaLoginServUpdates()
        );
        //$logger->debug("4444444444444444444444444444444444444444 \n".print_r($saida, true));

        // 5 - Configurações do local
        $configuracao_local = $computador->getIdRede()->getIdLocal()->getConfiguracoes();
        foreach ($configuracao_local as $configuracao) {
            //$logger->debug("5555555555555555555555555555555555555 ".$configuracao->getIdConfiguracao()->getIdConfiguracao() . " | " . $configuracao->getVlConfiguracao());
            $saida['agentcomputer']['configuracoes'][$configuracao->getIdConfiguracao()->getIdConfiguracao()] = $configuracao->getVlConfiguracao();
        }

        $logger->debug("Dados das configurações \n". print_r($saida, true));
        $resposta = json_encode($saida);

        $response = new JsonResponse();
        $response->setContent($resposta);

        $response->setStatusCode('200');
        return $response;
    }


    /**
     * Função para validar a sessão
     *
     * @param Session $session
     * @return bool
     */
    public function checkSession(Session $session) {
        $logger = $this->get('logger');
        $session->getMetadataBag()->getCreated();
        $session->getMetadataBag()->getLastUsed();

        if(time() - $session->getMetadataBag()->getLastUsed() > $this->maxIdleTime) {
            $session->invalidate();
            $logger->error("Sessão inválida:\n".$session->getId());
            //throw new SessionExpired(); // direciona para a página de sessão expirada

            return false;
        }
        else{
            return true;
        }
    }


    /**
     * Função para identificar o computador
     *
     * @param $dados JSON da requisitção
     * @param Request $request
     * @return Computador|null|object Computador identificado
     */

    public function getComputador($dados, Request $request) {
        $logger = $this->get('logger');
        $em = $this->getDoctrine()->getManager();

        $so_json = $dados['computador']['operatingSystem'];
        $rede_json = $dados['computador']['networkDevices'];
        $rede1 = $rede_json[0];
        $te_node_address = $rede1['mac'];
        $ip_computador = $rede1['ipv4'];
        $netmask = $rede1['netmask_ipv4'];
        $usuario = $dados['computador']['usuario'];
        $nmComputador = $dados['computador']['nmComputador'];
        $versaoAgente = $dados['computador']['versaoAgente'];


        // TESTES: Se IP for vazio, tenta pegar da conexão
        if (empty($ip_computador)) {
            $ip_computador = $request->getClientIp();
        }

        // Pega rede e SO
        $rede = $em->getRepository('CacicCommonBundle:Rede')->getDadosRedePreColeta( $ip_computador, $netmask );
        $so = $em->getRepository('CacicCommonBundle:So')->createIfNotExist($so_json['nomeOs']);

        // Regra: MAC e SO são únicos e não podem ser nulos
        // Se SO ou MAC forem vazios, tenta atualizar forçadamente
        if (empty($te_node_address) || empty($so)) {
            $logger->error("Erro na operação de getConfig. IP = $ip_computador Máscara = $netmask. MAC = $te_node_address. SO =" . $request->get( 'te_so' ));

            return null;
        }

        $computador = $em->getRepository('CacicCommonBundle:Computador')->findOneBy(array(
            'teNodeAddress'=> $te_node_address,
            'idSo' => $so
        ));
        //$logger->debug("$so".print_r($so, true));
        //$logger->debug("$computador".print_r($computador, true));
        //$logger->debug("111111111111111111111111111111111111111111111111");

        $data = new \DateTime('NOW'); //armazena data Atual

        //2 - Insere computador que não existe
        if( empty ( $computador ) )
        {
            $logger->debug("Inserindo novo computador para MAC = $te_node_address e SO = ".$so_json['nomeOs']);

            $computador = new Computador();

            $computador->setTeNodeAddress( $te_node_address );
            $computador->setIdSo( $so );
            $computador->setIdRede( $rede );
            $computador->setDtHrInclusao( $data);
            $computador->setTeIpComputador($ip_computador);
            $computador->setDtHrUltAcesso($data);
            $computador->setTeUltimoLogin($usuario);
            $computador->setTeVersaoCacic($versaoAgente);
            $computador->setNmComputador($nmComputador);

            $em->persist( $computador );

        }

        // 2.1 - Se existir, atualiza hora de inclusão
        else
        {
            $logger->debug("Atualizando hora de último acesso do computador para MAC = $te_node_address e SO = ".$so_json['nomeOs']);

            $computador->setDtHrUltAcesso($data);
            $computador->setTeIpComputador($ip_computador);
            $computador->setTeUltimoLogin($usuario);
            $computador->setTeVersaoCacic($versaoAgente);
            $computador->setNmComputador($nmComputador);

            //Atualiza hora de inclusão
            $em->persist($computador);

        }

        return $computador;

    }

    public function getComputadorSemMac($dados, Request $request) {
        $logger = $this->get('logger');
        $em = $this->getDoctrine()->getManager();

        $so_json = $dados['computador']['operatingSystem'];
        $rede_json = $dados['computador']['networkDevices'];
        $rede1 = $rede_json[0];
        $ip_computador = $rede1['ipv4'];
        $netmask = $rede1['netmask_ipv4'];

        // TESTES: Se IP for vazio, tenta pegar da conexão
        if (empty($ip_computador)) {
            $ip_computador = $request->getClientIp();
        }

        // Pega rede e SO
        $so = $em->getRepository('CacicCommonBundle:So')->createIfNotExist($so_json['nomeOs']);

        // Regra: MAC e SO são únicos e não podem ser nulos
        // Se SO ou MAC forem vazios, tenta atualizar forçadamente
        $computador = $em->getRepository('CacicCommonBundle:Computador')->semMac($ip_computador, $so);
        //$logger->debug("$so".print_r($so, true));
        //$logger->debug("$computador".print_r($computador, true));
        //$logger->debug("111111111111111111111111111111111111111111111111");

        return $computador;

    }

    public function updateAction(Request $request) {
        $logger = $this->get('logger');
        $status = $request->getContent();
        $em = $this->getDoctrine()->getManager();
        $dados = json_decode($status, true);

        if (empty($dados)) {
            $logger->error("JSON INVÁLIDO!!!!!!!!!!!!!!!!!!! Erro no getUpdate");
            // Retorna erro se o JSON for inválido
            $error_msg = '{
                "message": "JSON Inválido",
                "codigo": 1
            }';


            $response = new JsonResponse();
            $response->setStatusCode('500');
            $response->setContent($error_msg);
            return $response;
        }

        $computador = $this->getComputadorSemMac($dados, $request);

        // 0 - Array de saída
        $saida['agentcomputer'] = "";


        // 1 - Ações para o computador
        $acoes = $em->getRepository('CacicCommonBundle:Acao')->listaAcaoComputador(
            $computador->getIdRede()->getIdRede(),
            $computador->getIdSo()->getIdSo(),
            $computador->getTeNodeAddress()
        );
        $logger->debug("Ações encontradas \n".print_r($acoes, true));
        $cols = array();
        foreach ($acoes as $elm) {
            // Adiciona ações na saída
            if (empty($elm['idAcao'])) {
                $saida['agentcomputer']['actions'][$elm['acaoExecao']] = true;
            }
            $saida['agentcomputer']['actions'][$elm['idAcao']] = true;
        }

        //$logger->debug("1111111111111111111111111111111 \n".print_r($saida, true));

        // 2 - Adiciona módulos da subrede
        $modulos = $em->getRepository('CacicCommonBundle:RedeVersaoModulo')->findBy(array('idRede' => $computador->getIdRede()));
        //$logger->debug("Módulos encontrados \n". print_r($modulos, true));
        $mods = array();
        foreach($modulos as $elm) {
            // Adiciona módulos e hashes
            array_push($mods, array(
                'nome' => $elm->getNmModulo(),
                'hash' => $elm->getTeHash()
            ));
        }
        $saida['agentcomputer']['modulos'] = $mods;
        //$logger->debug("2222222222222222222222222222222222222 \n".print_r($saida, true));

        // 3 - Adiciona classes WMI
        $class = $em->getRepository('CacicCommonBundle:Classe')->findAll();
        $classes = array();
        foreach($class as $elm) {
            // Adiciona classes WMI
            array_push($classes, $elm->getNmClassName());
        }
        $saida['agentcomputer']['classes'] = $classes;
        //$logger->debug("33333333333333333333333333333333333333333 \n".print_r($saida, true));


        // 4 - Configurações genéricas
        $saida['agentcomputer']['applicationUrl'] = $computador->getIdRede()->getTeServCacic();
        $saida['agentcomputer']['metodoDownload'] = array(
            "tipo" => "ftp",
            "url" => $computador->getIdRede()->getTeServUpdates(),
            "path" => $computador->getIdRede()->getTePathServUpdates(),
            "usuario" => $computador->getIdRede()->getNmUsuarioLoginServUpdates(),
            "senha" => $computador->getIdRede()->getTeSenhaLoginServUpdates()
        );
        //$logger->debug("4444444444444444444444444444444444444444 \n".print_r($saida, true));

        // 5 - Configurações do local
        $configuracao_local = $computador->getIdRede()->getIdLocal()->getConfiguracoes();
        foreach ($configuracao_local as $configuracao) {
            //$logger->debug("5555555555555555555555555555555555555 ".$configuracao->getIdConfiguracao()->getIdConfiguracao() . " | " . $configuracao->getVlConfiguracao());
            $saida['agentcomputer']['configuracoes'][$configuracao->getIdConfiguracao()->getIdConfiguracao()] = $configuracao->getVlConfiguracao();
        }

        $logger->debug("Dados das configurações \n". print_r($saida, true));
        $resposta = json_encode($saida);

        $response = new JsonResponse();
        $response->setContent($resposta);

        $response->setStatusCode('200');
        return $response;
    }


}