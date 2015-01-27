<?php
/**
 * Created by PhpStorm.
 * User: gabi
 * Date: 25/07/14
 * Time: 12:24
 */

namespace Cacic\WSBundle\Controller;

use Cacic\CommonBundle\Entity\ClassProperty;
use Cacic\CommonBundle\Entity\PropriedadeSoftwareRepository;
use Proxies\__CG__\Cacic\CommonBundle\Entity\Software;
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
use Cacic\CommonBundle\Entity\ComputadorColeta;
use Cacic\CommonBundle\Entity\ComputadorColetaHistorico;
use Cacic\CommonBundle\Entity\PropriedadeSoftware;
use Cacic\CommonBundle\Entity\LogUserLogado;

use Doctrine\ORM\NonUniqueResultException;


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
        $log_usuario = new LogUserLogado();
        $log_usuario->setIdComputador($computador);
        $log_usuario->setData(new \DateTime());
        $log_usuario->setUsuario($dados['computador']['usuario']);
        $em->persist($log_usuario);

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
        $so_json = $dados['computador']['operatingSystem'];

        // 1 - Ações para o computador
        //$logger->debug("11111111111111111111111111111111 ".print_r($so_json, true));
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
            $tipo = $elm->getTipo();
            if (empty($tipo)) {
                $tipo = 'cacic';
            }
            if (empty($mods[$tipo])) {
                $mods[$tipo] = array();
            }
	    $tipoSo = $elm->getTipoSo();	
	    // Para agentes 2.8 o tipo de SO é igual ao nome do módulo, neste caso retornará o JSON vazio para não forçar a atualização
	    if (empty($tipoSo)) {
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
                // Adiciona módulos e hashes
                array_push($mods[$tipo], array(
                    'nome' => $elm->getNmModulo(),
                    'hash' => $elm->getTeHash()
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
            $base_url = $request->getBaseUrl();
            $base_url = preg_replace('/\/app.*.php/', "", $base_url);
            $saida['agentcomputer']['metodoDownload']['path'] = $base_url . '/downloads/cacic/current/' . $so_json['tipo'];
        } else {
            $saida['agentcomputer']['metodoDownload']['path'] = $computador->getIdRede()->getTePathServUpdates();
        }

        // 5 - Força coleta
        $saida['agentcomputer']['configuracoes']['nu_forca_coleta'] = $computador->getForcaColeta();

        // 6 - Configurações do local
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
        if (array_key_exists('tipo', $so_json)) {
            $tipo_so = $em->getRepository('CacicCommonBundle:TipoSo')->createIfNotExist($so_json['tipo']);
            $so->setTipo($tipo_so);
            $em->persist($so);
        }

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

    public function coletaAction(Request $request) {
        $logger = $this->get('logger');
        $status = $request->getContent();
        $em = $this->getDoctrine()->getManager();
        $dados = json_decode($status, true);

        if (empty($dados)) {
            $logger->error("JSON INVÁLIDO!!!!!!!!!!!!!!!!!!! Erro na COLETA");
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

        //Verifica se a coleta foi forçada
        if ($computador->getForcaColeta() == 'true') {
            $computador->setForcaColeta('false');
            $this->getDoctrine()->getManager()->persist( $computador );
            $this->getDoctrine()->getManager()->flush();
        }

        $result1 = $this->setHardware($dados['hardware'], $computador);
        $result2 = $this->setSoftware($dados['software'], $computador);

        $response = new JsonResponse();
        if ($result1 && $result2) {
            $response->setStatusCode('200');
        } else {
            $response->setStatusCode('500');
        }

        return $response;

    }

    /**
     * Classe que persiste a coleta de hardware
     *
     * @param $hardware
     * @param $computador
     */

    public function setHardware($hardware, $computador) {
        $logger = $this->get('logger');
        $em = $this->getDoctrine()->getManager();

        // Pega todas as propriedades de coleta
        foreach ($hardware as $classe => $valor) {
            $logger->debug("COLETA: Gravando dados da classe $classe");
            $this->setClasse($classe, $valor, $computador);
        }

        return true;
    }

    /**
     * Classe que grava todas as propriedades da classe de coleta
     *
     * @param $classe
     * @param $computador
     */
    public function setClasse($classe, $valor, $computador) {
        $logger = $this->get('logger');
        $em = $this->getDoctrine()->getManager();

        #$classObject = $em->getRepository('CacicCommonBundle:Classe')->findOneBy( array(
        #    'nmClassName'=> $classe
        #));

        $_dql = "SELECT classe
				FROM CacicCommonBundle:Classe classe
				WHERE LOWER(classe.nmClassName) = LOWER(:classe)";

        $classObject = $em->createQuery( $_dql )->setParameter('classe', $classe)->getOneOrNullResult();

        $logger->debug("COLETA: Coletando classe $classe");

        // Adiciona isNotebook
        if ($classe == 'IsNotebook') {
            $logger->debug("Valor do isNotebook: ".print_r($valor, true));
            if ($valor['Value'] == 'true') {
                $computador->setIsNotebook(true);
            }
            return;
        }


        if (empty($classObject)) {
            $logger->error("COLETA: Classe não cadastrada: $classe");
            return;
        }

        // Trata classe multivalorada
        if (!empty($valor[0])) {
            // Nesse caso a classe é multivalorada. Considero somente a primeira ocorrência
            $logger->debug("COLETA: Classe $classe multivalorada. Retornando somente primeiro elemento.");
            $valor = $valor[0];
        }


        foreach (array_keys($valor) as $propriedade) {
            if (is_array($valor[$propriedade])) {
                $logger->error("COLETA: Atributo $propriedade multivalorado não implementado na coleta");
                //$logger->debug("1111111111111111111111111111111111111111 ".print_r($valor, true));
                $valor[$propriedade] = $valor[$propriedade][0];
                //continue;
            }
            $logger->debug("COLETA: Gravando dados da propriedade $propriedade com o valor ".$valor[$propriedade]);

            try {

                // Pega o objeto para gravar
                $classProperty = $em->getRepository('CacicCommonBundle:ClassProperty')->findOneBy( array(
                    'nmPropertyName'=> $propriedade,
                    'idClass' => $classObject
                ));

                if (empty($classProperty)) {
                    $logger->info("COLETA: Cadastrando propriedade não encontrada $propriedade");

                    $classProperty = new ClassProperty();
                    $classProperty->setIdClass($classObject);
                    $classProperty->setNmPropertyName($propriedade);
                    $classProperty->setTePropertyDescription("Propriedade criada automaticamente: $propriedade");

                    $em->persist($classProperty);
                }

                // Garante unicidade das informações de coleta
                $computadorColeta = $em->getRepository('CacicCommonBundle:ComputadorColeta')->findOneBy(array(
                    'computador' => $computador,
                    'classProperty' => $classProperty
                ));
                if (empty($computadorColeta)) {
                    $computadorColeta = new ComputadorColeta();
                }

                // Armazena no banco o objeto
                $computadorColeta->setComputador( $computador );
                $computadorColeta->setClassProperty($classProperty);
                $computadorColeta->setTeClassPropertyValue($valor[$propriedade]);
                $computadorColeta->setIdClass($classObject);
                $computadorColeta->setDtHrInclusao( new \DateTime() );

                // Mando salvar os dados do computador
                $em->persist( $computadorColeta );

                // Persistencia de Historico
                $computadorColetaHistorico = new ComputadorColetaHistorico();
                $computadorColetaHistorico->setComputadorColeta( $computadorColeta );
                $computadorColetaHistorico->setComputador( $computador );
                $computadorColetaHistorico->setClassProperty( $classProperty);
                $computadorColetaHistorico->setTeClassPropertyValue($valor[$propriedade]);
                $computadorColetaHistorico->setDtHrInclusao( new \DateTime() );
                $em->persist( $computadorColetaHistorico );

            } catch(\Doctrine\ORM\ORMException $e){
                $logger->error("COLETA: Erro na inserçao de dados da propriedade $propriedade. \n$e");
            }
        }
        // Grava tudo da propriedade
        $em->flush();
    }

    public function setSoftware($software, $computador) {
        $logger = $this->get('logger');
        $em = $this->getDoctrine()->getManager();


        $classObject = $em->getRepository('CacicCommonBundle:Classe')->findOneBy( array(
            'nmClassName'=> 'SoftwareList'
        ));

        if (empty($classObject)) {
            $logger->error("COLETA: Classe SoftwareList não cadastrada \n$e");
            return false;
        }

        // Pega todas as propriedades de coleta
        $i = 0;
        foreach ($software as $classe => $valor) {
            $logger->debug("COLETA: Gravando dados do software $classe");
            $this->setSoftwareElement($classe, $valor, $computador, $classObject);
            $i = $i + 1;
        }

        /*
         * Grava tudo
         */
        $em->flush();
        $logger->debug("COLETA: Coleta de software finalizada. Total de softwares coletados: $i");

        return true;
    }

    public function setSoftwareElement($software, $valor, $computador, $classObject) {
        $logger = $this->get('logger');
        $em = $this->getDoctrine()->getManager();

        if (empty($software)) {
            $logger->error("COLETA: Erro na coleta de software. Elemento nulo $software");
            return false;
        }

        try {

            // Pega o objeto para gravar
            $propSoftware = $em->getRepository('CacicCommonBundle:PropriedadeSoftware')->softwareByName($software, $computador->getIdComputador());
            if (!empty($propSoftware)) {
                $classProperty = $propSoftware->getClassProperty();
                $softwareObject = $propSoftware->getSoftware();

                // Encontra coleta já feita para o Computador
                $computadorColeta = $em->getRepository('CacicCommonBundle:ComputadorColeta')->findOneBy(array(
                    'computador' => $computador,
                    'classProperty' => $classProperty
                ));
                if(empty($computadorColeta)) {
                    $logger->error("COLETA: Erro na identificação da coleta. O software está cadastrado mas não há ocorrência de coletas no computador");
                    $computadorColeta = new ComputadorColeta();
                    $computador->addHardware( $computadorColeta );
                }
            } else {
                $logger->info("COLETA: Cadastrando software não encontrado $software");

                $computadorColeta = new ComputadorColeta();
                $propSoftware = new PropriedadeSoftware();

                // Verifica se software ja esta cadastrado
                $softwareObject = $em->getRepository('CacicCommonBundle:Software')->findOneBy(array(
                    'nmSoftware' => $software
                ));
                if (empty($softwareObject)) {
                    // Se nao existir, cria
                    $softwareObject = new Software();
                }

                $classProperty = new ClassProperty();

                // Adiciona software na coleta
                $softwareObject->addColetado($propSoftware);
            }

            // Atualiza valores
            $computadorColeta->setComputador( $computador );

            $classProperty->setIdClass($classObject);
            $classProperty->setNmPropertyName($software);
            $classProperty->setTePropertyDescription("Propriedade criada automaticamente: $software");

            $propSoftware->setComputador($computador);
            $propSoftware->setClassProperty($classProperty);
            $propSoftware->setSoftware($softwareObject);

            // Atualiza valores do Software
            $softwareObject->setNmSoftware($software);
            if (array_key_exists('description', $valor)) {
                $softwareObject->setTeDescricaoSoftware($valor['description']);
                $propSoftware->setDisplayName($valor['description']);
            }
            if (array_key_exists('name', $valor)) {
                $softwareObject->setNmSoftware($valor['name']);
                $classProperty->setNmPropertyName($valor['name']);
            }
            if (array_key_exists('url', $valor)) {
                $propSoftware->setUrlInfoAbout($valor['url']);
            }
            if (array_key_exists('version', $valor)) {
                $propSoftware->setDisplayVersion($valor['version']);
            }
            if (array_key_exists('publisher', $valor)) {
                $propSoftware->setPublisher($valor['publisher']);
            }

            $em->persist($classProperty);
            $em->persist($propSoftware);
            $em->persist($softwareObject);


            // Armazena no banco o objeto
            $computadorColeta->setClassProperty($classProperty);
            $computadorColeta->setTeClassPropertyValue($software);
            $computadorColeta->setIdClass($classObject);
            $computadorColeta->setDtHrInclusao( new \DateTime() );

            // Mando salvar os dados do computador
            $computador->addHardware($computadorColeta);
            $em->persist( $computadorColeta );
            $em->persist( $computador );

            // Persistencia de Historico
            $computadorColetaHistorico = new ComputadorColetaHistorico();
            $computadorColetaHistorico->setComputadorColeta( $computadorColeta );
            $computadorColetaHistorico->setComputador( $computador );
            $computadorColetaHistorico->setClassProperty( $classProperty);
            $computadorColetaHistorico->setTeClassPropertyValue($software);
            $computadorColetaHistorico->setDtHrInclusao( new \DateTime() );
            $em->persist( $computadorColetaHistorico );

        } catch(\Doctrine\ORM\ORMException $e){
            $logger->error("COLETA: Erro na inserçao de dados do software $software. \n$e");
        } catch(NonUniqueResultException $e){
            $logger->error("COLETA: Erro impossível de software repetido para $software. \n$e");
        }
    }

}
