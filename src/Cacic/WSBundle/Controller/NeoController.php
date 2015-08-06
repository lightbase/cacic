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

use Cacic\CommonBundle\Entity\Computador;
use Cacic\CommonBundle\Entity\LogAcesso;
use Cacic\CommonBundle\Entity\LogUserLogado;

class NeoController extends Controller {


    public function __construct($maxIdleTime = 1800)
    {
        $this->maxIdleTime = $maxIdleTime;
    }

    /**
     * Retorna 200 na página inicial para testar
     *
     * @param Request $request
     * @return JsonResponse
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
     * Login do Agente
     *
     * @param Request $request
     * @return JsonResponse
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
     * Valida a sessão
     *
     * @param Request $request
     * @return JsonResponse
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

    /**
     * Método que registra o Acesso e identifica o computador
     *
     * @param Request $request
     * @return JsonResponse
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

        # TODO: Grava log de acessos de usuario do computador caso não esteja vazio
        if (!empty($dados['computador']['usuario'])){
            $log_usuario = new LogUserLogado();
            $log_usuario->setIdComputador($computador);
            $log_usuario->setData(new \DateTime());
            $log_usuario->setUsuario($dados['computador']['usuario']);
            $em->persist($log_usuario);
        }

        $em->flush();

        $response = new JsonResponse();
        $response->setStatusCode('200');
        return $response;
    }

    /**
     * Retorna configurações de coleta
     *
     * @param Request $request
     * @return JsonResponse
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
        $so_json = $dados['computador']['operatingSystem'];

        // 1 - Ações para o computador
        //$logger->debug("11111111111111111111111111111111 ".print_r($so_json, true));
        $acoes = $em->getRepository('CacicCommonBundle:Acao')->listaAcaoComputador(
            $computador->getIdComputador()
        );
        $logger->debug("Ações encontradas \n".print_r($acoes, true));
        foreach ($acoes as $elm) {
            // Adiciona ações na saída
            $saida['agentcomputer']['actions'][$elm['idAcao']] = true;
        }

        //$logger->debug("1111111111111111111111111111111 \n".print_r($saida, true));

        // 2 - Adiciona módulos da subrede
        $modulos = $em->getRepository('CacicCommonBundle:RedeVersaoModulo')->findBy(array(
            'idRede' => $computador->getIdRede()
        ));

        // Eduardo: 2015-08-06
        // Adiciona URL para download diferente para cada módulo
        $scheme = $computador->getIdRede()->getDownloadMethod();
        if ($scheme == 'http') {
            $server = preg_replace("(^https?://)", "", $computador->getIdRede()->getTeServUpdates());
        } else {
            $server = preg_replace("(^ftps?://)", "", $computador->getIdRede()->getTeServUpdates());
        }
        $url = $computador->getIdRede()->getDownloadMethod() . "://" . $server;
        // Remove trailing slash
        $url = rtrim($url, "/");

        // path removing slash
        $path = $computador->getIdRede()->getTePathServUpdates();
        if (empty($path)) {
            $path = "downloads";
        }
        $url .= "/" . ltrim($path, "/");

        //$logger->debug("Módulos encontrados \n". print_r($modulos, true));
        $mods = array();
        foreach($modulos as $elm) {
            $tipo = $elm->getTipo();
            if (empty($tipo)) {
                $tipo = 'cacic';
            }
            if (empty($mods[$tipo])) {
                $mods[$tipo] = array();
            }

            /*
             * Para agentes 2.8 o tipo de SO é igual ao nome do módulo.
             * Neste caso retornará o JSON vazio para não forçar a atualização.
             * Necessário para atualização de agente 2.8 > 3.1
             */
            $tipoSo = $elm->getTipoSo();
            if (empty($tipoSo)){
                $mods[$tipo] = array();
            }

            // Adiciona somente o módulo que estiver com o tipo de SO cadastrado
            elseif ($so_json['tipo'] == $elm->getTipoSo()->getTipo() ) {
                // URL do módulo
                $url_modulo = rtrim($url, "/") . "/$tipo/" . $elm->getTeVersaoModulo() . "/" . $so_json['tipo'] . "/" . $elm->getNmModulo();

                // Adiciona módulos e hashes
                array_push($mods[$tipo], array(
                    'nome' => $elm->getNmModulo(),
                    'hash' => $elm->getTeHash(),
                    'versao' => $elm->getTeVersaoModulo(),
                    'url' => $url_modulo
                ));
            }

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
            "tipo" => $computador->getIdRede()->getDownloadMethod(),
            "url" => $computador->getIdRede()->getTeServUpdates(),
            "usuario" => $computador->getIdRede()->getNmUsuarioLoginServUpdates(),
            "senha" => $computador->getIdRede()->getTeSenhaLoginServUpdates()
        );

        // 4.1 - Configuração de método de Download
        if ($computador->getIdRede()->getDownloadMethod() == 'http') {
            // Eduardo: 2015-07-30
            // Resolve bug no envio do path quando o agente está em um subdiretório
            //$base_url = $request->getBaseUrl();
            //$base_url = preg_replace('/\/app.*.php/', "", $base_url);
            $base_url = "";
            $saida['agentcomputer']['metodoDownload']['path'] = $base_url . '/downloads/cacic/current/' . $so_json['tipo'];
        } else {
            $saida['agentcomputer']['metodoDownload']['path'] = $computador->getIdRede()->getTePathServUpdates();
        }

        // 5 - Força coleta
        $saida['agentcomputer']['configuracoes']['nu_forca_coleta'] = $computador->getForcaColeta();

        // 6 - Configurações do local
        //$configuracao_local = $computador->getIdRede()->getIdLocal()->getConfiguracoes();
        $configuracao_padrao = $em->getRepository('CacicCommonBundle:ConfiguracaoPadrao')->findAll();
        foreach ($configuracao_padrao as $configuracao) {
            //$logger->debug("5555555555555555555555555555555555555 ".$configuracao->getIdConfiguracao()->getIdConfiguracao() . " | " . $configuracao->getVlConfiguracao());
            $saida['agentcomputer']['configuracoes'][$configuracao->getIdConfiguracao()] = $configuracao->getVlConfiguracao();
        }

        //$logger->debug("Dados das configurações \n". print_r($saida, true));
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

        // Eduardo: 2015-02-05
        // Verifica o caso da rede aparecer vazia
        $rede1 = @$rede_json[0];
        if (empty($rede1)) {
            $logger->error("COMPUTADOR: erro na identificação da rede. JSON sem informações de rede válidas. IP do computador: ".$request->getClientIp());
            $logger->error(print_r($dados, true));

	        return null;
        }

        // Eduardo: 2015-05-18
        // Verifica se o nome do SO existe e não é vazio
        if (empty($so_json)) {
            $logger->error("COMPUTADOR: SO não identificado! JSON não enviou o SO");
            $logger->error(print_r($so_json, true));

            return null;
        }

        $nomeOs = @$so_json['nomeOs'];
        if (empty($nomeOs)) {
            $logger->error("COMPUTADOR: SO vazio! So com nome vazio enviado.");
            $logger->error(print_r($so_json, true));

            return null;
        }

        $te_node_address = $rede1['mac'];
        $ip_computador = $rede1['ipv4'];
        $netmask = $rede1['netmask_ipv4'];
        $usuario = $dados['computador']['usuario'];
        $nmComputador = $dados['computador']['nmComputador'];
        $versaoAgente = $dados['computador']['versaoAgente'];

        // Atualiza versão do GerCols se existir
        if (array_key_exists('versaoGercols', $dados['computador'])) {
            $versaoGercols = $dados['computador']['versaoGercols'];
        } else {
            $versaoGercols = null;
        }

        // TESTES: Se IP for vazio, tenta pegar da conexão
        if (empty($ip_computador) || $ip_computador == '127.0.0.1') {
            $ip_computador = $request->getClientIp();
        }

        // Pega rede e SO
        $rede = $em->getRepository('CacicCommonBundle:Rede')->getDadosRedePreColeta( $ip_computador, $netmask );
        $so = $em->getRepository('CacicCommonBundle:So')->createIfNotExist($nomeOs);
        $tipo = $so->getTipo();
        if (array_key_exists('tipo', $so_json)) {
            if (!empty($so_json['tipo'])) {
                $tipo_so = $em->getRepository('CacicCommonBundle:TipoSo')->createIfNotExist($so_json['tipo']);
            } else {
                // Se não encontrar o tipo considera Windows como padrão
                $tipo_so = $em->getRepository("CacicCommonBundle:TipoSo")->findOneBy(array(
                    'tipo' => 'windows'
                ));
            }
            $so->setTipo($tipo_so);
            $em->persist($so);
        } elseif (empty($tipo)) {
            // Considera Windows por padrão
            $tipo_so = $em->getRepository("CacicCommonBundle:TipoSo")->findOneBy(array(
                'tipo' => 'windows'
            ));
            $so->setTipo($tipo_so);
            $em->persist($so);
        }

        // Regra: MAC e SO são únicos e não podem ser nulos
        // Se SO ou MAC forem vazios, tenta atualizar forçadamente
        if (empty($te_node_address) || empty($so)) {
            $logger->error("COMPUTADOR: Erro na identificação do computador. IP = $ip_computador Máscara = $netmask. MAC = $te_node_address. SO =" . $nomeOs);

            return null;
        }

        $computador = $em->getRepository('CacicCommonBundle:Computador')->findOneBy(array(
            'teNodeAddress'=> $te_node_address,
            'idSo' => $so
        ));

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
            $computador->setTeVersaoCacic($versaoAgente);
            $computador->setTeVersaoGercols($versaoGercols);
            $computador->setNmComputador($nmComputador);
            $computador->setAtivo(true);

            if (!empty($usuario) OR $usuario != "0"){
                $computador->setTeUltimoLogin($usuario);
            }

            $em->persist( $computador );

        }

        // 2.1 - Se existir, atualiza hora de inclusão
        else
        {
            $logger->debug("Atualizando hora de último acesso do computador para MAC = $te_node_address e SO = ".$so_json['nomeOs']);

            $computador->setIdSo( $so );
            $computador->setIdRede( $rede );
            $computador->setDtHrUltAcesso($data);
            $computador->setTeIpComputador($ip_computador);
            $computador->setTeUltimoLogin($usuario);
            $computador->setTeVersaoCacic($versaoAgente);
            $computador->setTeVersaoGercols($versaoGercols);
            $computador->setNmComputador($nmComputador);
            $computador->setAtivo(true);

            //Atualiza hora de inclusão
            $em->persist($computador);

        }

        $em->flush();

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
        $versaoAgente = $dados['computador']['versaoAgente'];

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

        if (empty($computador)){
            // Só vai retornar erro se não tiver nenhum computador cadastrado
            $logger->error("Nenhum computador cadastrado!!! Erro no getUpdate");
            // Retorna erro se o JSON for inválido
            $error_msg = '{
                "message": "Ainda não há computadores cadastrados",
                "codigo": 3
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

}
