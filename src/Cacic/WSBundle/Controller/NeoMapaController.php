<?php
/**
 * Created by PhpStorm.
 * User: eduardo
 * Date: 24/07/15
 * Time: 12:17
 */

namespace Cacic\WSBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class NeoMapaController extends NeoController
{
    public function mapaConfigAction(Request $request) {
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

        // Agora pega o patrimônio se estiver habilitado para o computador
        $acao = $em->getRepository("CacicCommonBundle:Acao")->listaAcaoComputador(
            $computador->getIdComputador(),
            'col_patr'
        );

        if (empty($acao)) {
            $logger->debug("MAPA: Patrimonio nao habilitado para a rede");
            // Patrimônio não está habilitado para a rede
            $msg = '{
                "col_patr": false
            }';

            $response = new JsonResponse();
            $response->setStatusCode('200');
            $response->setContent($msg);
            return $response;
        }

        $msg = array();

        // Verifica se o patrimônio deve ser executdo na máquina
        $patr = $em->getRepository("CacicCommonBundle:Computador")->getPatrimonio($computador->getIdComputador());

        if (!empty($patr)) {
            // Como já existe coleta, só executo novamente se houver coleta forçada de patrimonio
            if ($computador->getForcaPatrimonio() != 'S') {
                $logger->debug("MAPA: Patrimonio existe para o computador e a coleta forçada nao esta habilitada");
                // Nao precisa executar
                $msg = '{
                    "col_patr": false
                }';

                $response = new JsonResponse();
                $response->setStatusCode('200');
                $response->setContent($msg);
                return $response;
            }

            // Nesse caso já carrego os dados de patrimônio coletados para devolver ao agente
            $msg['coleta'] = array();

            // O primeiro resultado é a coleta mais atual. Passo como data pro Agente saber
            $msg['data_coleta'] = $patr[0]['dtHrInclusao'];
            foreach ($patr as $coleta) {
                $msg["coleta"][$coleta['nmPropertyName']] = $coleta['teClassPropertyValue'];
            }
        }

        // A partir daqui já estou dizendo que o patrimônio pode ser executado. Não dá mais pra voltar
        $msg['col_patr'] = true;

        // Adiciona servidor de autenticação se houver
        $servidor_autenticacao = $computador->getIdRede()->getIdServidorAutenticacao();
        if (!empty($servidor_autenticacao)) {
            // Só considera se estiver ativo
            $ativo = $servidor_autenticacao->getInAtivo();
            if ($ativo) {
                $msg['ldap'] = array(
                    'base' => $servidor_autenticacao->getNmServidorAutenticacaoDns(),
                    "filter" => array(
                        $servidor_autenticacao->getTeAtributoIdentificador()
                    ),
                    "login" => $servidor_autenticacao->getUsuario(),
                    "pass" => $servidor_autenticacao->getSenha(),
                    "server" => $servidor_autenticacao->getTeIpServidorAutenticacao(),
                    "port" => $servidor_autenticacao->getNuPortaServidorAutenticacao(),
                    "attr" => array(
                        "name" => $servidor_autenticacao->getTeAtributoRetornaNome(),
                        "email" => $servidor_autenticacao->getTeAtributoRetornaEmail()
                    )
                );
            }
        }

        // Adiciona atributos cadastrados na classe do patrimônio para informar ao Agente
        // Pode ser utilizado para alterar a mensagem que ele exibe na tela
        $propriedades = $em->getRepository("CacicCommonBundle:ClassProperty")->getByClassName('Patrimonio');

        $msg['properties'] = array();
        foreach ($propriedades as $elm) {
            $pretty_name = $elm->getPrettyName();
            if (empty($pretty_name)) {
                $msg['properties'][$elm->getNmPropertyName()] = array(
                    'name' => $pretty_name,
                    'description' => $elm->getTePropertyDescription()
                );
            } else {
                $msg['properties'][$elm->getNmPropertyName()] = array(
                    'name' => $elm->getNmPropertyName(),
                    'description' => $elm->getTePropertyDescription()
                );
            }
        }

        // IMPORTANTE: Pega emnsagem do pop-up
        $mensagem = $computador->getIdRede()->getIdLocal()->getConfiguracoes()->get('msg_popup_patrimonio');
        if (empty($mensagem)) {
            // Pega configuração padrão
            $configuracao = $em->getRepository("CacicCommonBundle:ConfiguracaoPadrao")->findOneBy(array(
                'idConfiguracao' => 'msg_popup_patrimonio'
            ));

            if (empty($configuracao)) {
                // Retorna mensagem genérica
                $mensagem = "Preencha os dados do patrimônio";
            } else {
                $mensagem = $configuracao->getVlConfiguracao();
                if (empty($mensagem)) {
                    // Configuração existe e mensagem vazia
                    $mensagem = "Preencha os dados do patrimônio";
                }
            }
        }
        $msg['message'] = $mensagem;

        // Retorna resposta com atributos
        $response = new JsonResponse();
        $response->setContent(json_encode($msg));
        $response->setStatusCode(200);

        return $response;

        // Verifica se todas as propriedades foram enviadas

    }
}