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

        $rede = new Rede();
        $rede->setTeIpRede('10.209.8.0');
        $rede->setTeMascaraRede('255.255.255.255');
        $rede->setTeServCacic('http://localhost');
        $rede->setTeServUpdates('http://localhost');
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
    }

    /**
     * Testa erroAgente
     */
    public function testInstalaHash() {
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

    }

    public function tearDown() {

        // Apaga dados da classe de cima
        parent::tearDown();

    }
}