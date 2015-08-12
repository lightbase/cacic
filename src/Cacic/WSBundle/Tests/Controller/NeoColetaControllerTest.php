<?php
/**
 * Created by PhpStorm.
 * User: eduardo
 * Date: 20/04/15
 * Time: 12:01
 */

namespace Cacic\WSBundle\Tests\Controller;
use Cacic\CommonBundle\Entity\Computador;
use Cacic\WSBundle\Tests\BaseTestCase;


class NeoColetaControllerTest extends BaseTestCase {

    public function setUp() {
        // Carrega dados da classe Pai
        parent::setUp();

    }

    /**
     * Teste de inserção das coletas
     */
    public function testColeta() {
        $logger = $this->container->get('logger');
        $this->client->request(
            'POST',
            '/ws/neo/coleta',
            array(),
            array(),
            array(
                'CONTENT_TYPE'  => 'application/json',
                //'HTTPS'         => true
            ),
            $this->coleta
        );
        $logger->debug("Dados JSON do computador enviados para a coleta: \n".$this->client->getRequest()->getcontent());

        $response = $this->client->getResponse();
        $status = $response->getStatusCode();
        $logger->debug("Response status: $status");
        //$logger->debug("JSON da coleta: \n".$response->getContent());

        $this->assertEquals($status, 200);

        // Verifica se o Software Coleta foi inserido
        $em =$this->container->get('doctrine')->getManager();

        $software = $em->getRepository("CacicCommonBundle:Software")->getByName("Messaging account plugin for Jabber/XMPP");
        $this->assertNotEmpty($software);

        $software = $em->getRepository("CacicCommonBundle:Software")->getByName("Software que não existe");
        $this->assertEmpty($software);

        // Testa se identificou que não é notebook
        $so = $em->getRepository("CacicCommonBundle:So")->findOneBy(array(
            'teSo' => 'Windows_NT'
        ));
        $this->assertNotEmpty($so);

        $computador = $em->getRepository("CacicCommonBundle:Computador")->findOneBy(array(
            'teNodeAddress' => '9C:D2:1E:EA:E0:89',
            'idSo' => $so->getIdSo()
        ));
        $this->assertNotEmpty($computador);

        $notebook = $computador->getIsNotebook();
        $this->assertEmpty($notebook);

    }

    /**
     * Teste de coletas e identificação de notebook
     */

    public function testColetaNotebook() {
        $logger = $this->container->get('logger');
        $this->client->request(
            'POST',
            '/ws/neo/coleta',
            array(),
            array(),
            array(
                'CONTENT_TYPE'  => 'application/json',
                //'HTTPS'         => true
            ),
            $this->coleta_notebook
        );
        $logger->debug("Dados JSON do computador enviados para a coleta: \n".$this->client->getRequest()->getcontent());

        $response = $this->client->getResponse();
        $status = $response->getStatusCode();
        $logger->debug("Response status: $status");
        //$logger->debug("JSON da coleta: \n".$response->getContent());

        $this->assertEquals($status, 200);

        // Verifica se o Software Coleta foi inserido
        $em =$this->container->get('doctrine')->getManager();

        $software = $em->getRepository("CacicCommonBundle:Software")->getByName("Messaging account plugin for Jabber/XMPP");
        $this->assertNotEmpty($software);

        $software = $em->getRepository("CacicCommonBundle:Software")->getByName("Software que não existe");
        $this->assertEmpty($software);

        // Testa se identificou que não é notebook
        $so = $em->getRepository("CacicCommonBundle:So")->findOneBy(array(
            'teSo' => 'Windows_NT'
        ));
        $this->assertNotEmpty($so);

        $computador = $em->getRepository("CacicCommonBundle:Computador")->findOneBy(array(
            'teNodeAddress' => '9C:D2:1E:EA:E0:88',
            'idSo' => $so->getIdSo()
        ));
        $this->assertNotEmpty($computador);

        $notebook = $computador->getIsNotebook();
        $this->assertEquals(true, $notebook);

    }

    /**
     * Testa inserção de nova coleta com os mesmos softwares
     */
    public function testSoftwareDuplicado() {
        // 1 - Primeiro computador
        $logger = $this->container->get('logger');
        $this->client->request(
            'POST',
            '/ws/neo/coleta',
            array(),
            array(),
            array(
                'CONTENT_TYPE'  => 'application/json',
                //'HTTPS'         => true
            ),
            $this->coleta
        );
        $logger->debug("Dados JSON do computador enviados para a coleta: \n".$this->client->getRequest()->getcontent());

        $response = $this->client->getResponse();
        $status = $response->getStatusCode();
        $logger->debug("Response status: $status");
        //$logger->debug("JSON da coleta: \n".$response->getContent());

        $this->assertEquals($status, 200);

        // 2 - Segundo Computador
        $logger = $this->container->get('logger');
        $this->client->request(
            'POST',
            '/ws/neo/coleta',
            array(),
            array(),
            array(
                'CONTENT_TYPE'  => 'application/json',
                //'HTTPS'         => true
            ),
            $this->coleta_notebook
        );
        $logger->debug("Dados JSON do computador enviados para a coleta: \n".$this->client->getRequest()->getcontent());

        $response = $this->client->getResponse();
        $status = $response->getStatusCode();
        $logger->debug("Response status: $status");
        //$logger->debug("JSON da coleta: \n".$response->getContent());

        $this->assertEquals($status, 200);

        // Verifica se o Software Coleta foi inserido
        $em =$this->container->get('doctrine')->getManager();

        // Verifica que dois computadores foram coletas
        $computadores = $em->getRepository("CacicCommonBundle:Computador")->findAll();
        $this->assertCount(2, $computadores, "Não foram encontrados exatamente dois computadores");

        // Checa se o notebook foi identificado
        $result = false;
        foreach ($computadores as $elm) {
            if ($elm->getIsNotebook() == true) {
                $result = true;
            }
        }
        $this->assertEquals(true, $result);

        // Busca software pelo nome
        $software = $em->getRepository("CacicCommonBundle:Software")->findBy(array(
            'nmSoftware' => 'Messaging account plugin for AIM'
        ));

        $this->assertCount(1, $software, "O software foi duplicado");

        // Altera o valor do nome da descrição do software e garante que não foi alterado
        $coleta = json_decode($this->coleta, true);
        $coleta['computador']['software']['account-plugin-aim']['description'] = "Descrição alterada";

        // Envia coleta
        $this->client->request(
            'POST',
            '/ws/neo/coleta',
            array(),
            array(),
            array(
                'CONTENT_TYPE'  => 'application/json',
                //'HTTPS'         => true
            ),
            json_encode($coleta, true)
        );
        $logger->debug("Dados JSON do computador enviados para a coleta: \n".$this->client->getRequest()->getcontent());

        $response = $this->client->getResponse();
        $status = $response->getStatusCode();
        $logger->debug("Response status: $status");

        // Encontra software no computador
        $so = $em->getRepository("CacicCommonBundle:So")->findOneBy(array(
            'teSo' => 'Windows_NT'
        ));
        $this->assertNotEmpty($so);

        $computador = $em->getRepository("CacicCommonBundle:Computador")->findOneBy(array(
            'teNodeAddress' => '9C:D2:1E:EA:E0:89',
            'idSo' => $so
        ));
        $this->assertNotEmpty($computador);

        $idClass = $em->getRepository("CacicCommonBundle:Classe")->findOneBy(array(
            'nmClassName' => 'SoftwareList'
        ));

        $classProperty = $em->getRepository("CacicCommonBundle:ClassProperty")->findOneBy(array(
            'nmPropertyName' => 'account-plugin-aim',
            'idClass' => $idClass
        ));
        $this->assertNotEmpty($classProperty, "Software não encontrado como propriedade");

        $softwareObject = $em->getRepository('CacicCommonBundle:Software')->findOneBy(array(
            'idClassProperty' => $classProperty
        ));

        $propSoftware = $em->getRepository("CacicCommonBundle:PropriedadeSoftware")->findOneBy(array(
            'computador' => $computador,
            'classProperty' => $classProperty,
            'software' => $softwareObject
        ));
        $this->assertNotEmpty($propSoftware, "Coleta de software não encontrada no computador");
    }

    /**
     * Testa erro de Entity Manager closed na inserção de software
     */
    public function testErroEmClosed() {
        $logger = $this->container->get('logger');
        $this->client->request(
            'POST',
            '/ws/neo/coleta',
            array(),
            array(),
            array(
                'CONTENT_TYPE'  => 'application/json',
                //'HTTPS'         => true
            ),
            $this->coleta_erro_orm
        );
        //$logger->debug("Dados JSON do computador enviados para a coleta: \n".$this->client->getRequest()->getcontent());

        $response = $this->client->getResponse();
        $status = $response->getStatusCode();
        $logger->debug("Response status: $status");
        //$logger->debug("JSON da coleta: \n".$response->getContent());

        $this->assertEquals($status, 200);

        // Verifica se os Softwares coletados foram inseridos
        $em =$this->container->get('doctrine')->getManager();

        // Verifica que um computador foi inserido
        $computadores = $em->getRepository("CacicCommonBundle:Computador")->findAll();
        $this->assertEquals(1, sizeof($computadores));

        // VErifica que a coleta de software foi realizada
        $software = $em->getRepository("CacicCommonBundle:Software")->findAll();
        $this->assertGreaterThan(1, sizeof($software));
    }

    /**
     * Testa processamento do envio de coletas diferencial
     */
    public function testModifications() {
        $logger = $this->container->get('logger');

        $this->client->request(
            'POST',
            '/ws/neo/coleta',
            array(),
            array(),
            array(
                'CONTENT_TYPE'  => 'application/json',
                //'HTTPS'         => true
            ),
            $this->coleta_modifications
        );

        $response = $this->client->getResponse();
        $status = $response->getStatusCode();
        $logger->debug("Response status: $status");
        //$logger->debug("JSON da coleta: \n".$response->getContent());

        $this->assertEquals($status, 200);

        // Verifica se o Software Coleta foi inserido
        $em = $this->container->get('doctrine')->getManager();

        // Busca software pelo nome
        $software = $em->getRepository("CacicCommonBundle:Software")->findBy(array(
            'nmSoftware' => 'Messaging account plugin for AIM'
        ));

        $this->assertEquals(1, sizeof($software));

        $classObject = $em->getRepository('CacicCommonBundle:Classe')->findOneBy( array(
            'nmClassName'=> 'SoftwareList'
        ));

        $this->assertNotEmpty($classObject);

        $classProperty = $em->getRepository("CacicCommonBundle:ClassProperty")->findOneBy(array(
            'nmPropertyName' => 'account-plugin-aim',
            'idClass' => $classObject
        ));

        $this->assertNotEmpty($classProperty);

        // Testa se identificou o computador
        $so = $em->getRepository("CacicCommonBundle:So")->findOneBy(array(
            'teSo' => 'Ubuntu 14.04.1 LTS-x86_64'
        ));
        $this->assertNotEmpty($so);

        $computador = $em->getRepository("CacicCommonBundle:Computador")->findOneBy(array(
            'teNodeAddress' => '9C:D2:1E:EA:E0:89',
            'idSo' => $so
        ));
        $this->assertNotEmpty($computador);

        // Agora testa a alteração do hardware
        $classe = $em->getRepository('CacicCommonBundle:Classe')->findOneBy( array(
            'nmClassName'=> 'Win32_BaseBoard'
        ));

        $prop = $em->getRepository("CacicCommonBundle:ClassProperty")->findOneBy(array(
            'nmPropertyName' => 'SerialNumber',
            'idClass' => $classe
        ));

        $this->client->request(
            'POST',
            '/ws/neo/modifications',
            array(),
            array(),
            array(
                'CONTENT_TYPE'  => 'application/json',
                //'HTTPS'         => true
            ),
            $this->modifications
        );

        $response = $this->client->getResponse();
        $status = $response->getStatusCode();
        $logger->debug("Response status: $status");
        //$logger->debug("JSON da coleta: \n".$response->getContent());

        $this->assertEquals($status, 200);

        // Limpa o cache para garantir o resultado
        $em->clear();

        $softwareRemovido = $em->getRepository("CacicCommonBundle:PropriedadeSoftware")->findOneBy(array(
            'classProperty' => $classProperty,
            'software' => $software,
            'computador' => $computador
        ));

        $this->assertFalse($softwareRemovido->getAtivo(), "O software nao foi desativado como esperado");

        // Testa alteração de hardware
        $computadorColeta = $em->getRepository("CacicCommonBundle:ComputadorColeta")->findOneBy(array(
            'computador' => $computador,
            'classProperty' => $prop
        ));

        $this->assertFalse($computadorColeta->getAtivo(), "O hardware não foi desativado como esperado");
    }

    /**
     * Testa gravação no histórico somente em caso de alteração
     */
    public function testColetaHistorico() {
        $logger = $this->container->get('logger');
        $computador_json = json_decode($this->coleta, true);

        $this->client->request(
            'POST',
            '/ws/neo/coleta',
            array(),
            array(),
            array(
                'CONTENT_TYPE'  => 'application/json',
                //'HTTPS'         => true
            ),
            json_encode($computador_json, true)
        );
        //$logger->debug("Dados JSON do computador enviados para a coleta: \n".$this->client->getRequest()->getcontent());

        $response = $this->client->getResponse();
        $status = $response->getStatusCode();
        $logger->debug("Response status: $status");
        //$logger->debug("JSON da coleta: \n".$response->getContent());

        $this->assertEquals($status, 200);

        // Verifica se o Software Coleta foi inserido
        $em =$this->container->get('doctrine')->getManager();

        // Testa se identificou que não é notebook
        $so = $em->getRepository("CacicCommonBundle:So")->findOneBy(array(
            'teSo' => 'Windows_NT'
        ));
        $this->assertNotEmpty($so);

        $computador = $em->getRepository("CacicCommonBundle:Computador")->findOneBy(array(
            'teNodeAddress' => '9C:D2:1E:EA:E0:89',
            'idSo' => $so->getIdSo()
        ));
        $this->assertNotEmpty($computador);

        // Verifica se a coleta funcionou da forma esperada
        $classe = $em->getRepository("CacicCommonBundle:Classe")->findOneBy(array(
            'nmClassName' => 'Win32_BIOS'
        ));
        $classProperty = $em->getRepository("CacicCommonBundle:ClassProperty")->findOneBy(array(
            'idClass' => $classe,
            'nmPropertyName' => 'vendor'
        ));
        $this->assertNotEmpty($classProperty, "Propriedade que deveria ter sido coletada não foi encontrada");

        // Testa histórico
        $historico = $em->getRepository("CacicCommonBundle:ComputadorColetaHistorico")->findBy(array(
            'classProperty' => $classProperty,
            'computador' => $computador
        ));
        $this->assertCount(1, $historico, "O histórico não foi encontrado para a propriedade vendor da BIOS. Coleta:\n
        ".print_r($computador_json, true));
        $this->assertEquals(
            $computador_json['hardware']['Win32_BIOS']['vendor'],
            $historico[0]->getTeClassPropertyValue(),
            "Valor do histórico diferente do armazenado"
        );


        // Verifica histórico de software
        // Verifica se a coleta funcionou da forma esperada
        $classeSoftware = $em->getRepository("CacicCommonBundle:Classe")->findOneBy(array(
            'nmClassName' => 'SoftwareList'
        ));
        $classPropertySoftware = $em->getRepository("CacicCommonBundle:ClassProperty")->findOneBy(array(
            'idClass' => $classeSoftware,
            'nmPropertyName' => 'account-plugin-aim'
        ));
        $this->assertNotEmpty($classPropertySoftware, "Propriedade que deveria ter sido coletada não foi encontrada: account-plugin-aim");

        $historico_software = $em->getRepository("CacicCommonBundle:ComputadorColetaHistorico")->findBy(array(
            'classProperty' => $classPropertySoftware,
            'computador' => $computador
        ));
        $this->assertCount(1, $historico_software, "O histórico não foi encontrado para a o software account-plugin-aim. Coleta:\n
        ".print_r($computador_json, true));
        $this->assertEquals(
            $computador_json['software']['account-plugin-aim']['description'],
            $historico_software[0]->getTeClassPropertyValue(),
            "Valor do histórico diferente do armazenado"
        );

        // Envia a coleta de novo e verifica se não houve alteração no histórico
        $this->client->request(
            'POST',
            '/ws/neo/coleta',
            array(),
            array(),
            array(
                'CONTENT_TYPE'  => 'application/json',
                //'HTTPS'         => true
            ),
            json_encode($computador_json, true)
        );
        //$logger->debug("Dados JSON do computador enviados para a coleta: \n".$this->client->getRequest()->getcontent());

        $response = $this->client->getResponse();
        $status = $response->getStatusCode();
        $logger->debug("Response status: $status");
        //$logger->debug("JSON da coleta: \n".$response->getContent());

        $this->assertEquals($status, 200);

        // Garante que o histórico não foi alterado
        $historico = $em->getRepository("CacicCommonBundle:ComputadorColetaHistorico")->findBy(array(
            'classProperty' => $classProperty,
            'computador' => $computador
        ));
        $this->assertCount(1, $historico, "O histórico não foi encontrado para a propriedade vendor da BIOS. Coleta:\n
        ".print_r($computador_json, true));
        $this->assertEquals(
            $computador_json['hardware']['Win32_BIOS']['vendor'],
            $historico[0]->getTeClassPropertyValue(),
            "Valor do histórico diferente do armazenado"
        );

        // Agora para software
        $historico_software = $em->getRepository("CacicCommonBundle:ComputadorColetaHistorico")->findBy(array(
            'classProperty' => $classPropertySoftware,
            'computador' => $computador
        ));
        $this->assertCount(1, $historico_software, "O histórico não foi encontrado para a o software account-plugin-aim. Coleta:\n
        ".print_r($computador_json, true));
        $this->assertEquals(
            $computador_json['software']['account-plugin-aim']['description'],
            $historico_software[0]->getTeClassPropertyValue(),
            "Valor do histórico diferente do armazenado para o software"
        );

        // HISTÓRICO: Testa se a alteração de um valor na propriedade gera uma entrada no histórico
        $computador2 = $computador_json;
        $computador2['software']['account-plugin-aim']['description'] = 'Descrição do software alterada';
        $computador2['hardware']['Win32_BIOS']['vendor'] = 'Vendor alterado';

        $this->client->request(
            'POST',
            '/ws/neo/coleta',
            array(),
            array(),
            array(
                'CONTENT_TYPE'  => 'application/json',
                //'HTTPS'         => true
            ),
            json_encode($computador2, true)
        );
        //$logger->debug("Dados JSON do computador enviados para a coleta: \n".$this->client->getRequest()->getcontent());

        $response = $this->client->getResponse();
        $status = $response->getStatusCode();
        //$logger->debug("Response status: $status");
        //$logger->debug("JSON da coleta: \n".$response->getContent());

        $this->assertEquals($status, 200);

        // HARDWARE: Somente deve ser inserido no histórico
        $classProperty2 = $em->getRepository("CacicCommonBundle:ClassProperty")->findBy(array(
            'idClass' => $classe,
            'nmPropertyName' => 'vendor'
        ));
        $this->assertCount(1, $classProperty2, "Foi criada uma nova propriedade para a classe Win32_BIOS e atributo vendor. Não deveria ter criado outra");

        $historico = $em->getRepository("CacicCommonBundle:ComputadorColetaHistorico")->findBy(array(
            'classProperty' => $classProperty2[0],
            'computador' => $computador
        ));
        $this->assertCount(2, $historico, "Não foi encontrado um segundo histórico para a classe Win32_BIOS e atributo vendor. Coleta:\n
        ".print_r($computador2, true));

        foreach($historico as $elm) {
            // Verifica se os valores encontrados está em algum dos fornecidos
            $this->assertContains(
                $elm->getTeClassPropertyValue(),
                array(
                    $computador_json['hardware']['Win32_BIOS']['vendor'],
                    $computador2['hardware']['Win32_BIOS']['vendor'],
                ),
                "O valor encontrado não está presente em nenhuma das coletas: ".$elm->getTeClassPropertyValue()
            );
        }

        $classPropertySoftware2 = $em->getRepository("CacicCommonBundle:ClassProperty")->findBy(array(
            'idClass' => $classeSoftware,
            'nmPropertyName' => 'account-plugin-aim'
        ));
        $this->assertCount(1, $classPropertySoftware2, "Software foi duplicado e não deveria");

        $propSoftware = $em->getRepository("CacicCommonBundle:PropriedadeSoftware")->findBy(array(
            'classProperty' => $classPropertySoftware2,
            'computador' => $computador
        ));
        $this->assertCount(1, $propSoftware, "Propriedade de software foi duplicada mas não deveria");

        $historico_software2 = $em->getRepository("CacicCommonBundle:ComputadorColetaHistorico")->findBy(array(
            'classProperty' => $classPropertySoftware2[0],
            'computador' => $computador
        ));
        $this->assertCount(2, $historico_software2, "O histórico não foi encontrado para a o software account-plugin-aim. Coleta:\n
        ".print_r($computador2, true));

        foreach($historico_software2 as $elm) {
            // Verifica se os valores encontrados está em algum dos fornecidos
            $this->assertContains(
                $elm->getTeClassPropertyValue(),
                array(
                    $computador_json['software']['account-plugin-aim']['description'],
                    $computador2['software']['account-plugin-aim']['description'],
                ),
                "O valor encontrado não está presente em nenhuma das coletas: ".$elm->getTeClassPropertyValue()
            );
        }
    }

    /**
     * Testa criação da notificação a partir do envio de modificações
     */
    public function testModificationsNotifications() {
        $logger = $this->container->get('logger');

        $this->client->request(
            'POST',
            '/ws/neo/coleta',
            array(),
            array(),
            array(
                'CONTENT_TYPE'  => 'application/json',
                //'HTTPS'         => true
            ),
            $this->coleta_modifications
        );

        $response = $this->client->getResponse();
        $status = $response->getStatusCode();
        $logger->debug("Response status: $status");
        //$logger->debug("JSON da coleta: \n".$response->getContent());

        $this->assertEquals($status, 200);

        // Verifica se o Software Coleta foi inserido
        $em = $this->container->get('doctrine')->getManager();

        // Agora envia a modificação
        $this->client->request(
            'POST',
            '/ws/neo/modifications',
            array(),
            array(),
            array(
                'CONTENT_TYPE'  => 'application/json',
                //'HTTPS'         => true
            ),
            $this->modifications
        );

        $response = $this->client->getResponse();
        $status = $response->getStatusCode();
        $logger->debug("Response status: $status");
        //$logger->debug("JSON da coleta: \n".$response->getContent());

        $this->assertEquals($status, 200);

        // Limpa o cache para garantir o resultado
        $em->clear();

        // Testa se identificou o computador
        $so = $em->getRepository("CacicCommonBundle:So")->findOneBy(array(
            'teSo' => 'Ubuntu 14.04.1 LTS-x86_64'
        ));
        $this->assertNotEmpty($so);

        $computador = $em->getRepository("CacicCommonBundle:Computador")->findOneBy(array(
            'teNodeAddress' => '9C:D2:1E:EA:E0:89',
            'idSo' => $so
        ));
        $this->assertNotEmpty($computador);

        // Agora verifica se a notificação foi inserida com sucesso
        $notifications = $em->getRepository("CacicCommonBundle:Notifications")->findBy(array(
            'notificationAcao' => 'DEL',
            'object' => 'Software',
            'idComputador' => $computador
        ));
        $this->assertCount(1, $notifications, "Não foi encontrada a notificação de remoção do software");

        // Valida atributos
        $this->assertNotEmpty($notifications[0]->getBody(), "O campo body está vazio");
        $this->assertNotEmpty($notifications[0]->getFromAddr(), "O campo from está vazio");
        $this->assertNotEmpty($notifications[0]->getSubject(), "O campo subject está vazio");
        $this->assertEquals('DEL', $notifications[0]->getNotificationAcao(), "A ação não está mapeada como exclusão (DEL)");
        $this->assertEquals('Software', $notifications[0]->getObject(), "A ação não está mapeada como objeto Software");

        $notifications = $em->getRepository("CacicCommonBundle:Notifications")->findBy(array(
            'notificationAcao' => 'DEL',
            'object' => 'Hardware',
            'idComputador' => $computador
        ));
        $this->assertCount(1, $notifications, "Não foi encontrada a notificação de remoção do software");

        // Valida atributos
        $this->assertNotEmpty($notifications[0]->getBody(), "O campo body está vazio");
        $this->assertNotEmpty($notifications[0]->getFromAddr(), "O campo from está vazio");
        $this->assertNotEmpty($notifications[0]->getSubject(), "O campo subject está vazio");
        $this->assertEquals('DEL', $notifications[0]->getNotificationAcao(), "A ação não está mapeada como exclusão (DEL)");
        $this->assertEquals('Hardware', $notifications[0]->getObject(), "A ação não está mapeada como objeto Hardware");
    }

    /**
     * Testa coleta de atrbutos multivalorados
     */
    public function testMultivalorados() {
        $logger = $this->container->get('logger');

        $this->client->request(
            'POST',
            '/ws/neo/coleta',
            array(),
            array(),
            array(
                'CONTENT_TYPE'  => 'application/json',
                //'HTTPS'         => true
            ),
            $this->coleta_multivalorado
        );

        $response = $this->client->getResponse();
        $status = $response->getStatusCode();
        $logger->debug("Response status: $status");
        $logger->debug("JSON da coleta: \n".$response->getContent());

        $this->assertEquals($status, 200);

        // Verifica se o Software Coleta foi inserido
        $em = $this->container->get('doctrine')->getManager();

        // Testa se identificou o computador
        $so = $em->getRepository("CacicCommonBundle:So")->findOneBy(array(
            'teSo' => 'Microsoft Windows 7 Professional'
        ));
        $this->assertNotEmpty($so);

        $computador = $em->getRepository("CacicCommonBundle:Computador")->findOneBy(array(
            'teNodeAddress' => '0C:54:A5:42:71:65',
            'idSo' => $so
        ));
        $this->assertNotEmpty($computador);

        // Debuga o objeto do computador
        $serializer = $this->container->get('jms_serializer');
        $jsonContent = $serializer->serialize($computador, 'json');
        $tmpfile = sys_get_temp_dir(). '/computador.json';
        file_put_contents($tmpfile, $jsonContent);

        $this->assertNotEmpty($computador->getHardwares(), "Coletas não encontradas no objeto do computador");

        $found_bios = false;
        $found_usb = true;
        $count_printer = 0;
        $found_comment = false;
        $count_comment = 0;
        foreach ($computador->getHardwares() as $coleta) {
            $classe = $coleta->getClassProperty()->getIdClass()->getNmClassName();

            // 1 - Testa atributo multivalorado (mais de um valor no mesmo atributo)
            if ($classe == 'Win32_BIOS') {
                $property = $coleta->getClassProperty()->getNmPropertyName();
                if ($property == 'BIOSVersion') {
                    $found_bios = true;

                    // Nesse caso deve ser possível decodificar o valor como JSON
                    $values = json_decode($coleta->getTeClassPropertyValue(), true);
                    $this->assertCount(3, $values, "Não foram encontrados três valores para a classe Win32_BIOS e atributo BIOSVersion");
                }
            }

            // 2 - Testa atributo que é um outro atributo
            if ($classe == 'Win32_Printer') {
                // 2.1 - Deve que haver quatro ocorrências para a coleta da impressora no mesmo computador
                $count_printer += 1;

                $property = $coleta->getClassProperty()->getNmPropertyName();
                if ($property == 'Comment') {

                    // Primeiro checa atributo que deveria estar coletado
                    if ($coleta->getTeClassPropertyValue() == 'PDFCreator Printer') {
                        $found_comment = true;
                    }

                    // Agora adiciona contador à quantidade de vezes que asse atributo deveria ter aparecido
                    $count_comment += 1;
                }

            }
        }

        $this->assertTrue($found_bios, "Classe Win32_BIOS e atributo BIOSVersion não encontrados");
        $this->assertTrue($found_usb, "Classe Win32_USBController e atributo BIOSVersion não encontrados");
        $this->assertEquals(4, $count_printer, "Não foram encontradas 4 propriedades para a classe impressora. Valor: $count_printer");
        $this->assertTrue($found_comment, "Propriedade Comment da classe Win32_printer não encontrada");
        $this->assertEquals(4, $count_comment, "A propriedade Comment da classe Win32_Printer não aparece 4 vezes. Valor: $count_comment");

    }

    public function tearDown() {

        // Remove dados da classe pai
        parent::tearDown();
    }
}