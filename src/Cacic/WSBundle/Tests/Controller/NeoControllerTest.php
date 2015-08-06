<?php
/**
 * Created by PhpStorm.
 * User: gabi
 * Date: 25/07/14
 * Time: 11:47
 */

namespace Cacic\WSBundle\Tests\Controller;

use Cacic\WSBundle\Tests\BaseTestCase;
use Symfony\Component\HttpFoundation\Session;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;



class NeoControllerTest extends BaseTestCase
{

    /**
     * Método que cria dados comuns a todos os testes
     */
    public function setUp() {
        // Load setup from BaseTestCase method
        parent::setUp();

    }

    /**
     * Testa a comunicação SSL
     */
    public function testCommunication()
    {
        $logger = $this->container->get('logger');
        $client = $this->client;
        $client->request(
            'POST',
            '/ws/neo',
            array(),
            array(),
            array(
                'CONTENT_TYPE'  => 'application/json',
                'HTTPS'         => true
            ),
            '{}'
        );


        //$logger->debug("11111111111111111111111111111111111111 ".print_r($client->getResponse()->getStatusCode(), true));

        $this->assertEquals(200,$client->getResponse()->getStatusCode());
    }


    /**
     * test login
     */
    public function testGetLogin()
    {

        $logger = $this->container->get('logger');
        $this->client->request(
            'POST',
            '/ws/neo/getLogin',
            array(),
            array(),
            array(
                'CONTENT_TYPE'  => 'application/json',
                'HTTPS'         => true
            ),
            '{  "user" : "cacic-adm",
                "password": "cacic123"
            }'
        );
        $logger->debug("Dados JSON de login enviados\n".$this->client->getRequest()->getcontent());//user e password

        $response = $this->client->getResponse();
        //$logger->debug("Response:\n".print_r($response,true)); // arrays session e chavecrip
        $data = $response->getContent();
        //$logger->debug("Response data:\n".print_r($data,true)); //session e chavecrip
        // JSON Serialization
        $json = json_decode($data, true);
        $logger->debug("Response json: \n".print_r($json,true)); //session e chavecrip
        $session = $json['session'];
        $chavecrip= $json['chavecrip'];

        $this->assertTrue(is_string($session));

        $this->assertTrue(is_string($chavecrip));

    }

      /**
       *Teste da sessão
       */
     public function testSession() {
        $logger = $this->container->get('logger');
        $this->client->request(
            'POST',
            '/ws/neo/getLogin',
            array(),
            array(),
            array(
                'CONTENT_TYPE'  => 'application/json',
                //'HTTPS'         => true
            ),
            '{  "user" : "cacic-adm",
                "password": "cacic123"
            }'
        );
        $logger->debug("Dados JSON de login enviados \n".$this->client->getRequest()->getcontent());

        $response = $this->client->getResponse();
        $data = $response->getContent();

        // JSON Serialization
        $json = json_decode($data, true);
        $session = $json['session'];

        // Testa a sessão
        $this->client->request(
            'POST',
            '/ws/neo/checkSession',
            array(),
            array(),
            array(
                'CONTENT_TYPE'  => 'application/json',
                //'HTTPS'         => true
            ),
            '{  "session" : '.$session.'
            }'
        );

        $response = $this->client->getResponse();
        $status = $response->getStatusCode();
        $logger->debug("Response status: $status");

        $this->assertEquals($status, 200);
    }

    /**
     * Testa inserção do computador se não existir
     */
    public function testGetTest() {
        $logger = $this->container->get('logger');
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
        $logger->debug("Dados JSON do computador enviados \n".$this->client->getRequest()->getcontent());

        $response = $this->client->getResponse();
        $status = $response->getStatusCode();
        $logger->debug("Response status: $status");

        $this->assertEquals($status, 200);

    }

    /**
     * Testconfig
     */
    public function testConfig() {
        $logger = $this->container->get('logger');
        $this->client->request(
            'POST',
            '/ws/neo/config',
            array(),
            array(),
            array(
                'CONTENT_TYPE'  => 'application/json',
                //'HTTPS'         => true
            ),
            $this->computador
        );
        //$logger->debug("Dados JSON do computador enviados \n".$this->client->getRequest()->getcontent());

        $response = $this->client->getResponse();
        $status = $response->getStatusCode();
        $logger->debug("Response status: $status");
        $this->assertEquals($status, 200);

        $computador_json = json_decode($this->computador, true);
        $resposta = $response->getContent();
        $config = json_decode($resposta, true);

        // 0 - Identifica computador
        $this->assertNotEmpty($config['agentcomputer']);

        // 1 - Verifica se todas as ações foram encontradas
        $this->assertNotEmpty($config['agentcomputer']['actions'], "Não existem ações ativas");
        $this->assertTrue($config['agentcomputer']['actions']['col_soft'], "Coleta de software não encontrada nas ações");
        $this->assertTrue($config['agentcomputer']['actions']['col_hard'], "Coleta de hardware não encontrada nas ações");
        $this->assertTrue($config['agentcomputer']['actions']['col_patr'], "Coleta de patrimônio não encontrada nas ações");

        // 2 - Verifica módulos da subrede
        $this->assertNotEmpty($config['agentcomputer']['modulos'], "Não existem módulos habilitados");
        $this->assertNotEmpty($config['agentcomputer']['modulos']['cacic'], "Não existe nenhum módulo do Cacic habilitado");
        $this->assertCount(2, $config['agentcomputer']['modulos']['cacic'], "Número de módulos cadastrados é diferente de 2");

        // 3 - Verifica classes WMI
        $this->assertCount(17, $config['agentcomputer']['classes'], "Número de classes cadastradas diferente de 4");

        // 4 - Verifica configurações genéricas
        $this->assertEquals('localhost', $config['agentcomputer']['applicationUrl'], "URL da aplicação é diferente");

        // 4.1 - Verifica método de download
        $this->assertEquals('http', $config['agentcomputer']['metodoDownload']['tipo'], "Método de download não é HTTP");
        $this->assertEquals(
            '/downloads/cacic/current/' . $computador_json['computador']['operatingSystem']['tipo'],
            $config['agentcomputer']['metodoDownload']['path'],
            "Path de download inconsistente"
        );

        // 5 - Verifica coleta forçada
        $this->assertEmpty(
            $config['agentcomputer']['configuracoes']['nu_forca_coleta'],
            "Força coleta não deveria existir"
        );

        // 6 -  Verifica configurações do local
        $this->assertNotEmpty(
            $config['agentcomputer']['configuracoes'],
            "O array de configurações está vazio e não deveria"
        );

        $tmpfile = sys_get_temp_dir(). '/getConfig.json';
        file_put_contents($tmpfile, $resposta);
        $logger->debug("getCONFIG: file temp = $tmpfile");

        // Testa parsing de parâmetros importantes no getConfig
        $em =$this->container->get('doctrine')->getManager();

        $so = $em->getRepository("CacicCommonBundle:So")->findOneBy(array(
            'teSo' => 'Windows_NT'
        ));
        $this->assertNotEmpty($so);

        $computador = $em->getRepository("CacicCommonBundle:Computador")->findOneBy(array(
            'teNodeAddress' => '9C:D2:1E:EA:E0:89',
            'idSo' => $so->getIdSo()
        ));
        $this->assertNotEmpty($computador);

        $rede = $computador->getIdRede();

        // Testa inclusão de URL com HTTP
        $rede->setTeServUpdates('http://localhost');

        // Testa inclusão de bara antes do path
        $rede->setTePathServUpdates("/downloads/");

        // Testa força coleta
        $computador->setForcaColeta('S');

        $em->persist($computador);
        $em->persist($rede);
        $em->flush();

        // Envia getConfig de novo e testa parâmetros
        $this->client->request(
            'POST',
            '/ws/neo/config',
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

        $resposta = $response->getContent();
        $config = json_decode($resposta, true);

        // Testa URL gerada
        $this->assertEquals(
            "http://localhost/downloads/cacic/3.0a1/windows/cacic-service.exe",
            $config['agentcomputer']['modulos']['cacic'][0]['url'],
            "URL gerada foi errada: ".$config['agentcomputer']['modulos']['cacic'][0]['url']
        );

        $this->assertEquals(
            "S",
            $config['agentcomputer']['configuracoes']['nu_forca_coleta'],
            "Força coleta deveria ser S"
        );

        $tmpfile = sys_get_temp_dir(). '/getConfig2.json';
        file_put_contents($tmpfile, $resposta);
        $logger->debug("getCONFIG: file temp = $tmpfile");

    }

    /**
     * Essa função vai falhar se não tiver nenhum computador cadastrado
     */
    public function testSemMac() {
        $logger = $this->container->get('logger');
        $this->client->request(
            'POST',
            '/ws/neo/update',
            array(),
            array(),
            array(
                'CONTENT_TYPE'  => 'application/json',
                //'HTTPS'         => true
            ),
            $this->sem_mac
        );
        $logger->debug("Dados JSON do computador enviados sem MAC para o getUpdate \n".$this->client->getRequest()->getcontent());

        $response = $this->client->getResponse();
        $status = $response->getStatusCode();
        $logger->debug("Response status: $status");
        //$logger->debug("JSON do getConfig: \n".$response->getContent());

        $this->assertEquals($status, 500);

    }

    /**
     * Retorna o último computador cadastrado caso seja enviado um sem Mac
     */
    public function testUpdate() {
        $logger = $this->container->get('logger');
        // Primeiro insere um computador válido
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
        $logger->debug("Dados JSON do computador enviados antes do getUpdate \n".$this->client->getRequest()->getcontent());

        $response = $this->client->getResponse();
        $status = $response->getStatusCode();
        $logger->debug("Response status: $status");

        $this->assertEquals($status, 200);

        // Agora tenta inserir um sem MAC
        $this->client->request(
            'POST',
            '/ws/neo/update',
            array(),
            array(),
            array(
                'CONTENT_TYPE'  => 'application/json',
                //'HTTPS'         => true
            ),
            $this->sem_mac
        );
        $logger->debug("Dados JSON do computador enviados sem MAC para o getUpdate \n".$this->client->getRequest()->getcontent());

        $response = $this->client->getResponse();
        $status = $response->getStatusCode();
        $logger->debug("Response status: $status");
        $logger->debug("JSON do getUpdate: \n".$response->getContent());

        $this->assertEquals($status, 200);

    }


    public function  testHttpUpdate() {
        $logger = $this->container->get('logger');
        $this->client->request(
            'POST',
            '/ws/neo/config',
            array(),
            array(),
            array(
                'CONTENT_TYPE'  => 'application/json',
                //'HTTPS'         => true
            ),
            $this->computador
        );
        //$logger->debug("Dados JSON do computador enviados \n".$this->client->getRequest()->getcontent());

        $response = $this->client->getResponse();
        $status = $response->getStatusCode();
        $logger->debug("Response status: $status");
        //$logger->debug("JSON do getConfig: \n".$response->getContent());

        $this->assertEquals($status, 200);

        $status = $response->getContent();
        $dados = json_decode($status, true);

        // Checa se método de download foi informado
        $this->assertEquals(
            $dados['agentcomputer']['metodoDownload']['tipo'],
            'http'
        );

        // Verifica se o hash correto foi enviado
        $result = false;
        foreach($dados['agentcomputer']['modulos']['cacic'] as $modulo) {
            //$logger->debug("99999999999999999999999999999999999 ".print_r($modulo, true));
            if ($modulo['hash'] == '50cf34bf584880fd401619eb367b2c2d') {
                $result = true;
            }
        }
        $this->assertTrue($result);

    }

    public function testUsuarioLogado() {
        $logger = $this->container->get('logger');
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
        //$logger->debug("Dados JSON do computador enviados \n".$this->client->getRequest()->getcontent());

        $response = $this->client->getResponse();
        $status = $response->getStatusCode();
        $logger->debug("Response status: $status");
        //$logger->debug("JSON do getConfig: \n".$response->getContent());

        $this->assertEquals($status, 200);

        // Testa se o usuário Eric foi persistido
        $em =$this->container->get('doctrine')->getManager();
        $results = $em->getRepository('CacicCommonBundle:LogUserLogado')->findBy(array(
            'usuario' => 'Eric Menezes'
        ));

        $this->assertFalse(empty($results));

        $results = $em->getRepository('CacicCommonBundle:LogUserLogado')->findBy(array(
            'usuario' => 'Joãzinho'
        ));

        $this->assertTrue(empty($results));
    }

    /**
     * Testa SO enviado com tipo vazio
     */
    public function testSemSo() {
        $logger = $this->container->get('logger');
        $this->client->request(
            'POST',
            '/ws/neo/getTest',
            array(),
            array(),
            array(
                'CONTENT_TYPE'  => 'application/json',
                //'HTTPS'         => true
            ),
            $this->computador_semso1
        );
        //$logger->debug("Dados JSON do computador enviados \n".$this->client->getRequest()->getcontent());

        $response = $this->client->getResponse();
        $status = $response->getStatusCode();
        $logger->debug("Response status: $status");
        //$logger->debug("JSON do getConfig: \n".$response->getContent());

        // Sem SO deve retornar 500
        $this->assertEquals($status, 500);

        // Testa se o computador foi inserido
        $em =$this->container->get('doctrine')->getManager();

        $computador_json = json_decode($this->computador_semso1, true);
        $computador = $em->getRepository("CacicCommonBundle:Computador")->findOneBy(array(
            'teNodeAddress' => $computador_json['computador']['networkDevices'][0]['mac']
        ));
        $this->assertEmpty($computador);

        // Agora testa SO com nome vazio
        $this->client->request(
            'POST',
            '/ws/neo/getTest',
            array(),
            array(),
            array(
                'CONTENT_TYPE'  => 'application/json',
                //'HTTPS'         => true
            ),
            $this->computador_semso2
        );
        //$logger->debug("Dados JSON do computador enviados \n".$this->client->getRequest()->getcontent());

        $response = $this->client->getResponse();
        $status = $response->getStatusCode();
        $logger->debug("Response status: $status");
        //$logger->debug("JSON do getConfig: \n".$response->getContent());

        // Sem SO deve retornar 500
        $this->assertEquals($status, 500);

        // Testa se o computador foi inserido
        $em =$this->container->get('doctrine')->getManager();

        $computador_json = json_decode($this->computador_semso2, true);
        $computador = $em->getRepository("CacicCommonBundle:Computador")->findOneBy(array(
            'teNodeAddress' => $computador_json['computador']['networkDevices'][0]['mac']
        ));
        $this->assertEmpty($computador);

    }

    public function testTipoSoVazio() {
        $logger = $this->container->get('logger');
        $this->client->request(
            'POST',
            '/ws/neo/getTest',
            array(),
            array(),
            array(
                'CONTENT_TYPE'  => 'application/json',
                //'HTTPS'         => true
            ),
            $this->computador_sem_tipo
        );
        //$logger->debug("Dados JSON do computador enviados \n".$this->client->getRequest()->getcontent());

        $response = $this->client->getResponse();
        $status = $response->getStatusCode();
        $logger->debug("Response status: $status");
        //$logger->debug("JSON do getConfig: \n".$response->getContent());

        // Sem SO deve retornar 200
        $this->assertEquals($status, 200);

        // Testa se o computador foi inserido
        $em =$this->container->get('doctrine')->getManager();

        $computador_json = json_decode($this->computador_sem_tipo, true);
        $computador = $em->getRepository("CacicCommonBundle:Computador")->findOneBy(array(
            'teNodeAddress' => $computador_json['computador']['networkDevices'][0]['mac']
        ));
        $this->assertNotEmpty($computador);

        // Verifica se o nome inserido é o mesmo cadastrado
        $so = $computador->getIdSo();
        $this->assertEquals($computador_json['computador']['operatingSystem']['nomeOs'], $so->getTeSo());

        // Verifica que o tipo padrão windows foi inserido
        $tipo = $so->getTipo()->getTipo();
        $this->assertEquals('windows', $tipo);
    }


    /**
     * Método que apaga todos os dados criados no teste
     */
    public function tearDown() {
        // Executa método de limpeza de todos os casos de teste
        parent::tearDown();

    }

}