<?php
/**
 * Created by PhpStorm.
 * User: eduardo
 * Date: 20/04/15
 * Time: 12:47
 */

namespace Cacic\WSBundle\Tests\Controller;

use Cacic\WSBundle\Tests\BaseTestCase;
use Cacic\CommonBundle\Entity\RedeVersaoModulo;
use Cacic\CommonBundle\Entity\Rede;

class NeoInstallControllerTest extends BaseTestCase {

    public function setUp() {
        // Carrega dados da classe de cima
        parent::setUp();

        $this->em =$this->container->get('doctrine')->getManager();

        $this->hash = file_get_contents($this->data_dir."instala/hash.json");
        $this->erro = file_get_contents($this->data_dir."instala/erro.json");

        $rede = new Rede();
        $rede->setTeIpRede('10.209.8.0');
        $rede->setTeMascaraRede('255.255.255.255');
        $rede->setTeServCacic('http://localhost');
        $rede->setTeServUpdates('http://localhost');
        $rede->setTePathServUpdates('/msi');
        $rede->setNuLimiteFtp(100);
        $rede->setCsPermitirDesativarSrcacic('S');
        $rede->setDownloadMethod('http');

        //$this->em->persist($rede);


        $elemento = array(
            'nmModulo' => 'cacic-service.exe',
            'teVersaoModulo' => '3.0a1',
            'csTipoSo' => 'windows',
            'teHash' => '79df3561f83ac86eb19e2996b17d5e31',
            'tipo' => 'cacic',
            'tipoSo' => 'windows',
            'filepath' => 'cacic/current/windows/cacic-service.exe'
        );

        $tipo_so = $this->em->getRepository("CacicCommonBundle:TipoSo")->findOneBy(array(
            'tipo' => 'windows'
        ));

        $classe = new RedeVersaoModulo(null, null, null, null, null, $rede);
        $classe->setNmModulo($elemento['nmModulo']);
        $classe->setTeVersaoModulo($elemento['teVersaoModulo']);
        $classe->setDtAtualizacao(new \DateTime());
        $classe->setCsTipoSo($elemento['csTipoSo']);
        $classe->setTeHash($elemento['teHash']);
        $classe->setTipo('cacic');
        $classe->setTipoSo($tipo_so);
        $classe->setFilepath($elemento['filepath']);

        //$this->em->persist($classe);
        //$this->em->flush();

        // Carrega parâmetros globais
        $this->rede = $rede;
        $this->modulo = $classe;
        $this->tipo_so = $tipo_so;

        // Encontra hash do último serviço
        $rede = $this->em->getRepository("CacicCommonBundle:Rede")->findOneBy(array(
            'teIpRede' => '0.0.0.0'
        ));
        $cacic_service = $this->em->getRepository("CacicCommonBundle:RedeVersaoModulo")->getModulo(
            $rede->getIdRede(),
            'cacic-service.exe',
            'windows'
        );
        $this->hash_service = $cacic_service[0]['teHash'];
    }

    /**
     * Testa verificação de hash do Serviço no Gerente
     */
    public function testInstallHash() {
        $logger = $this->container->get('logger');
        $client = $this->client;
        $client->request(
            'POST',
            '/ws/instala/hash',
            array(),
            array(),
            array(
                'CONTENT_TYPE'  => 'application/json',
                'HTTPS'         => true
            ),
            $this->hash
        );

        $logger->debug("Dados JSON do computador enviados \n".$this->client->getRequest()->getcontent());
        $response = $this->client->getResponse();
        $status = $response->getStatusCode();
        $logger->debug("Response status: $status");
        $this->assertEquals(200, $status);

        // Testa se o erro foi inserido com sucesso para o computador
        //$em =$this->container->get('doctrine')->getManager();

        // Encontra hash do último serviço
        $rede = $this->em->getRepository("CacicCommonBundle:Rede")->findOneBy(array(
            'teIpRede' => '0.0.0.0'
        ));
        $cacic_service = $this->em->getRepository("CacicCommonBundle:RedeVersaoModulo")->getModulo(
            $rede->getIdRede(),
            'cacic-service.exe',
            'windows'
        );

        // Verifica se o sistema informou JSON inválido
        $dados = json_decode($response->getContent(), true);
        $this->assertEquals($cacic_service[0]['teHash'], $dados['valor']);

        // Agora testa o módulo com outro hash e verifica se identificou a rede
        $this->em->persist($this->rede);
        $this->em->persist($this->modulo);
        $this->em->flush();

        $computador = json_decode($this->hash, true);
        $rede = $this->em->getRepository("CacicCommonBundle:Rede")->getDadosRedePreColeta(
            $computador['ip_address'],
            $computador['netmask']
        );

        $this->assertEquals($this->rede->getTeIpRede(), $rede->getTeIpRede());

        // Agora verifica se o módulo retornado foi o correto
        $cacic_service = $this->em->getRepository("CacicCommonBundle:RedeVersaoModulo")->getModulo(
            $this->rede->getIdRede(),
            'cacic-service.exe',
            'windows'
        );

        // Envia requisição novamente e testa resposta novamente
        $client->request(
            'POST',
            '/ws/instala/hash',
            array(),
            array(),
            array(
                'CONTENT_TYPE'  => 'application/json',
                'HTTPS'         => true
            ),
            $this->hash
        );

        $logger->debug("Dados JSON do computador enviados \n".$this->client->getRequest()->getcontent());
        $response = $this->client->getResponse();
        $status = $response->getStatusCode();
        $logger->debug("Response status: $status");
        $this->assertEquals(200, $status);

        $dados = json_decode($response->getContent(), true);
        $this->assertEquals($cacic_service[0]['teHash'], $dados['valor']);

        // Remove dados do teste
        $this->em->remove($this->rede);
        $this->em->remove($this->modulo);
        $this->em->flush();

    }

    /**
     * Testa armazenamento de erro de instalação
     */
    public function testInstallErro() {
        $logger = $this->container->get('logger');
        $client = $this->client;
        $client->request(
            'POST',
            '/ws/instala/erro',
            array(),
            array(),
            array(
                'CONTENT_TYPE'  => 'application/json',
                'HTTPS'         => true
            ),
            $this->erro
        );

        $logger->debug("Dados JSON do computador enviados \n".$this->client->getRequest()->getcontent());
        $response = $this->client->getResponse();
        $status = $response->getStatusCode();
        $logger->debug("Response status: $status");
        $this->assertEquals(200, $status);

        // Verifica se o dado foi inserido com sucesso
        $request = $client->getRequest();
        $ip_computador = $request->getClientIp();

        $computador = $this->em->getRepository("CacicCommonBundle:InsucessoInstalacao")->findOneBy(array(
            'teIpComputador' => $ip_computador
        ));
        $this->assertNotEmpty($computador);
    }

    /**
     * Testa download da última versão do serviço para a subrede
     */
    public function testDownloadService() {
        $logger = $this->container->get('logger');
        $client = $this->client;
        $client->request(
            'POST',
            '/ws/instala/download/service/'.$this->hash_service,
            array(),
            array(),
            array(
                'CONTENT_TYPE'  => 'application/json',
                'HTTPS'         => true
            ),
            '{}'
        );

        $logger->debug("Dados JSON do computador enviados \n".$this->client->getRequest()->getcontent());
        $response = $this->client->getResponse();
        $status = $response->getStatusCode();
        $logger->debug("Response status: $status");
        $this->assertEquals(200, $status);

        // Verifica que foi fornecida uma URL para o serviço
        $dados = json_decode($response->getContent(), true);

        // Pega servidor de updates para a rede
        $rede = $this->em->getRepository("CacicCommonBundle:Rede")->findOneBy(array(
            'teIpRede' => '0.0.0.0'
        ));
        $modulo = $this->em->getRepository("CacicCommonBundle:RedeVersaoModulo")->findOneBy(array(
            'nmModulo' => 'cacic-service.exe',
            'teHash' => $this->hash_service,
            'idRede' => $rede->getIdRede(),
            'tipo' => 'cacic'
        ));
        $url = $rede->getTeServUpdates()
            . "/downloads/"
            . $modulo->getFilepath();

        $this->assertEquals($url, $dados['valor']);

        // Testa envio do IP e máscara
        $client = $this->client;
        $client->request(
            'POST',
            '/ws/instala/download/service/'.$this->hash_service,
            array(),
            array(),
            array(
                'CONTENT_TYPE'  => 'application/json',
                'HTTPS'         => true
            ),
            $this->hash
        );

        $response = $this->client->getResponse();
        $status = $response->getStatusCode();
        $logger->debug("Response status: $status");
        $this->assertEquals(200, $status);

        // Verifica que foi fornecida uma URL para o serviço
        $dados = json_decode($response->getContent(), true);

        // Pega servidor de updates para a rede
        $rede = $this->em->getRepository("CacicCommonBundle:Rede")->findOneBy(array(
            'teIpRede' => '0.0.0.0'
        ));
        $modulo = $this->em->getRepository("CacicCommonBundle:RedeVersaoModulo")->findOneBy(array(
            'nmModulo' => 'cacic-service.exe',
            'teHash' => $this->hash_service,
            'idRede' => $rede->getIdRede(),
            'tipo' => 'cacic'
        ));
        $url = $rede->getTeServUpdates()
            . "/downloads/"
            . $modulo->getFilepath();
        $this->assertEquals($url, $dados['valor']);

        // Agora testa o módulo com outro hash e verifica se identificou a rede
        $this->em->persist($this->rede);
        $this->em->persist($this->modulo);
        $this->em->flush();

        // Testa envio do IP e máscara
        $client = $this->client;
        $client->request(
            'POST',
            '/ws/instala/download/service/'.$this->modulo->getTeHash(),
            array(),
            array(),
            array(
                'CONTENT_TYPE'  => 'application/json',
                'HTTPS'         => true
            ),
            $this->hash
        );

        $response = $this->client->getResponse();
        $status = $response->getStatusCode();
        $logger->debug("Response status: $status");
        $this->assertEquals(200, $status);

        $dados = json_decode($response->getContent(), true);

        $computador = json_decode($this->hash, true);
        $rede = $this->em->getRepository("CacicCommonBundle:Rede")->getDadosRedePreColeta(
            $computador['ip_address'],
            $computador['netmask']
        );
        $modulo = $this->em->getRepository("CacicCommonBundle:RedeVersaoModulo")->findOneBy(array(
            'nmModulo' => 'cacic-service.exe',
            'teHash' => $this->modulo->getTeHash(),
            'idRede' => $rede->getIdRede(),
            'tipo' => 'cacic'
        ));
        $url = $rede->getTeServUpdates()
            . "/downloads/"
            . $modulo->getFilepath();

        $this->assertEquals($url, $dados['valor']);

        // Remove dados do teste
        $this->em->remove($this->rede);
        $this->em->remove($this->modulo);
        $this->em->flush();

    }

    /**
     * Teste que deve retornar sempre a URL para a última versão do MSI disponível
     */
    public function testDownloadMsi() {
        $logger = $this->container->get('logger');
        $client = $this->client;
        $client->request(
            'POST',
            '/ws/instala/download/msi',
            array(),
            array(),
            array(
                'CONTENT_TYPE'  => 'application/json',
                'HTTPS'         => true
            ),
            '{}'
        );

        $logger->debug("Dados JSON do computador enviados \n".$this->client->getRequest()->getcontent());
        $response = $this->client->getResponse();
        $status = $response->getStatusCode();
        $logger->debug("Response status: $status");
        $this->assertEquals(200, $status);

        // Testa retorno da URL
        $dados = json_decode($response->getContent(), true);

        // Pega servidor de updates para a rede
        $rede = $this->em->getRepository("CacicCommonBundle:Rede")->findOneBy(array(
            'teIpRede' => '0.0.0.0'
        ));
        $url = $rede->getTeServUpdates()
            . "/Cacic.msi";

        $logger->debug("URL download: $url");

        $this->assertEquals($url, $dados['valor']);

        // Agora testa o módulo com outro hash e verifica se identificou a rede
        $this->em->persist($this->rede);
        $this->em->persist($this->modulo);
        $this->em->flush();

        $client->request(
            'POST',
            '/ws/instala/download/msi',
            array(),
            array(),
            array(
                'CONTENT_TYPE'  => 'application/json',
                'HTTPS'         => true
            ),
            $this->hash
        );

        $response = $this->client->getResponse();
        $status = $response->getStatusCode();
        $this->assertEquals(200, $status);

        // Testa retorno da URL
        $dados = json_decode($response->getContent(), true);

        // Pega servidor de updates para a rede
        $url = $this->rede->getTeServUpdates()
            . "/"
            . "/msi"
            . "/"
            . "Cacic.msi";

        $logger->debug("URL download: $url");

        $this->assertEquals($url, $dados['valor']);

        // Remove dados do teste
        $this->em->remove($this->rede);
        $this->em->flush();
    }

    public function tearDown() {

        // Apaga dados da classe de cima
        parent::tearDown();

    }
}