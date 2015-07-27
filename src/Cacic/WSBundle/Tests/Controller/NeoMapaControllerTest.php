<?php
/**
 * Created by PhpStorm.
 * User: eduardo
 * Date: 24/07/15
 * Time: 12:08
 */

namespace Cacic\WSBundle\Tests\Controller;

use Cacic\WSBundle\Tests\BaseTestCase;

class NeoMapaControllerTest extends BaseTestCase
{

    public function setUp() {
        // Carrega dados da classe pai
        parent::setUp();

        $this->em = $this->container->get('doctrine')->getManager();
    }

    /**
     * Testa envio de autorização para execução
     */
    public function testGetMapa() {
        $logger = $this->container->get('logger');

        // Primeiro insiro o computador
        $this->client->request(
            'POST',
            '/ws/neo/getTest',
            array(),
            array(),
            array(
                'CONTENT_TYPE'  => 'application/json',
                //'HTTPS'         => true
            ),
            $this->computador
        );

        $response = $this->client->getResponse();
        $status = $response->getStatusCode();
        $logger->debug("Response status: $status");

        $this->assertEquals($status, 200);

        // Carrega computador
        $computador = json_decode($this->computador, true);

        // Adiciona request do getMapa
        $computador['request'] = 'getMapa';

        // Agora envia a requisição
        $this->client->request(
            'POST',
            '/ws/neo/mapa/config',
            array(),
            array(),
            array(
                'CONTENT_TYPE'  => 'application/json',
                //'HTTPS'         => true
            ),
            json_encode($computador, true)
        );

        $logger->debug("Dados JSON do computador enviados para a coleta: \n".$this->client->getRequest()->getcontent());

        $response = $this->client->getResponse();
        $status = $response->getStatusCode();
        $logger->debug("Response status: $status");
        $logger->debug("JSON da coleta: \n".$response->getContent());

        $this->assertEquals(200, $status, "Erro na requisição do patrimônio");

        // Checa se a coleta de patrimônio está ativa
        $acao = $this->em->getRepository("CacicCommonBundle:Acao")->find('col_patr');
        $this->assertNotEmpty($acao->getAtivo(), "Ação de patrimônio não encontrada");
        $this->assertTrue($acao->getAtivo(), "Ação de patrimônio desabilitada");

        // Antes verifico se a coleta de patrimônio está habilitada para a rede do computador
        $computador_obj = $this->em->getRepository("CacicCommonBundle:Computador")->findOneBy(array(
            'teNodeAddress' => $computador['computador']['networkDevices'][0]['mac']
        ));
        $this->assertNotEmpty($computador_obj, "Computador inserido não encontrado");

        // Testes
        $acaoRede = $this->em->getRepository("CacicCommonBundle:AcaoRede")->findOneBy(array(
            'rede' => $computador_obj->getIdRede(),
            'acao' => 'col_patr'
        ));
        $this->assertNotEmpty($acaoRede);

        $acaoExcecao = $this->em->getRepository("CacicCommonBundle:AcaoExcecao")->findOneBy(array(
            'teNodeAddress' => $computador_obj->getTeNodeAddress(),
            'acao' => $acao,
            'rede' => $computador_obj->getIdRede()
        ));
        $this->assertEmpty($acaoExcecao);

        // Busca ações ativas
        $acao_t1 = $this->em->getRepository("CacicCommonBundle:Acao")->findOneBy(array(
            'ativo' => true,
            'idAcao' => 'col_patr'
        ));
        if (empty($acao_t1)) {
            $logger->debug("ACAO: Não retornou verdadeiro (true). Verifica se é vazia");
            $acao_t2 = $this->em->getRepository("CacicCommonBundle:Acao")->findOneBy(array(
                'ativo' => null,
                'idAcao' => 'col_patr'
            ));
            $this->assertNotEmpty($acao_t2, "A ação deveria retornar nula, mas não retorna");
        }

        // Teste de consulta
        $query = $this->em->createQuery(
            "SELECT a.idAcao,
                a.teNomeCurtoModulo
             FROM CacicCommonBundle:Computador comp
             INNER JOIN comp.idRede rede
             INNER JOIN rede.acoes ar
             INNER JOIN CacicCommonBundle:Acao a WITH a.idAcao = ar.acao
             LEFT JOIN CacicCommonBundle:AcaoExcecao e WITH (
                    e.acao = a.idAcao AND e.rede = comp.idRede AND e.teNodeAddress = comp.teNodeAddress
                )
             WHERE comp.idComputador = :idComputador
             AND (a.ativo = TRUE OR a.ativo IS NULL)
             AND e.acao IS NULL
            "
        )->setParameter('idComputador', $computador_obj->getIdComputador());
        $result = $query->getResult();
        $this->assertNotEmpty($result, "Consulta de teste falhou");

        $acoesComp = $this->em->getRepository("CacicCommonBundle:Acao")->listaAcaoComputador(
            $computador_obj->getIdComputador()
        );
        $this->assertGreaterThan(0, sizeof($acoesComp), "Não foram encontrados resultados para o computador");

        $acaoComp = $this->em->getRepository("CacicCommonBundle:Acao")->listaAcaoComputador(
            $computador_obj->getIdComputador(),
            'col_patr'
        );

        $this->assertNotEmpty($acaoComp, "A coleta de patrimônio não foi encontrada como habilitada no banco");


        // Verifica se a coleta de patrimônio está habilitada
        $content = $response->getContent();
        $dados = json_decode($content, true);

        $this->assertEquals(true, $dados['col_patr'], "O módulo de patrimônio deveria estar habilitado, mas não retornou verdadeiro");

        // Verifica se o array de propriedades existe e não é nulo
        $this->assertNotEmpty($dados['propriedades'], "Não foi possível encontrar nenhuma propriedade de patrimônio");

        // Verifica se existe a propriedade IDPatrimonio
        $this->assertNotEmpty($dados['propriedades']['IDPatrimonio']);

        // Não deve existir servidor de autenticação
        $this->assertEmpty($dados['ldap']);
    }


    public function tearDown() {

        // Apaga dados do teste geral
        parent::tearDown();
    }

}