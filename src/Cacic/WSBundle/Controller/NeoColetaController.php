<?php
/**
 * Created by PhpStorm.
 * User: eduardo
 * Date: 20/04/15
 * Time: 11:40
 */

namespace Cacic\WSBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\Session;
use Cacic\CommonBundle\Entity\ClassProperty;
use Cacic\CommonBundle\Entity\Software;
use Cacic\CommonBundle\Entity\ComputadorColeta;
use Cacic\CommonBundle\Entity\ComputadorColetaHistorico;
use Cacic\CommonBundle\Entity\PropriedadeSoftware;
use Doctrine\ORM\ORMException;
use Doctrine\DBAL\DBALException;
use Doctrine\ORM\ORMInvalidArgumentException;

class NeoColetaController extends NeoController {

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

        $classObject = $em->getRepository('CacicCommonBundle:Classe')->findByName($classe);

        $logger->debug("COLETA: Coletando classe $classe");

        // Adiciona isNotebook
        if ($classe == 'IsNotebook') {
            $logger->debug("Valor do isNotebook: ".print_r($valor, true));

            $computador->setIsNotebook($valor['Value']);

            $em->persist( $computador );
            $em->flush();

            return;
        }

        if (empty($classObject)) {
            $logger->debug("COLETA: Classe não cadastrada: $classe");
            return;
        }

        // Trata classe multivalorada
        if (!empty($valor[0])) {
            // Nesse caso a classe é multivalorada. Considero somente a primeira ocorrência
            $logger->debug("COLETA: Classe $classe multivalorada. Retornando somente primeiro elemento.");
            $valor = $valor[0];
        }

        // Eduardo: 2015-02-05
        // Verifica se o JSON com propriedades é válido
        $propriedades_array = @array_keys($valor);
        if (empty($propriedades_array)) {
            $logger->error("COLETA: erro na coleta da classe $classe. String retornada quando deveria ser um objeto JSON: ".print_r($valor, true));
            return;
        }

        foreach ($propriedades_array as $propriedade) {
            // Necessário pegar o EM novamente se estiver dando erro
            $em = $this->getDoctrine()->getManager();

            if (is_array($valor[$propriedade])) {
                $logger->debug("COLETA: Atributo $propriedade multivalorado não implementado na coleta");
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
                    $em->flush();
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

                // Persiste os objetos dependentes para evitar erro no ORM
                $em->persist($computador);
                $em->persist($classObject);

            } catch(ORMException $e){
               // Reopen Entity Manager
               if (!$em->isOpen()) {

                   // reset the EM and all alias
                   $container = $this->container;
                   $container->set('doctrine.orm.entity_manager', null);
                   $container->set('doctrine.orm.default_entity_manager', null);
               }

                $logger->error("COLETA: Erro na inserçao de dados da propriedade $propriedade.");
                $logger->debug($e);
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
            $logger->error("COLETA: Classe SoftwareList não cadastrada");
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

            // FIX: alteração para igualar os campos nome do software e descrição
            $idSoftware = $software;
            if (array_key_exists('description', $valor)) {
                $software = $valor['description'];
            }

            // Verifica se software ja esta cadastrado
            $softwareObject = $em->getRepository('CacicCommonBundle:Software')->getByName($software);
            if (empty($softwareObject)) {
                // Se nao existir, cria
                $softwareObject = new Software();
                $softwareObject->setNmSoftware($software);

                // Se não der o flush aqui, as consultas de baixo não encontram o objeto
                $em->persist($softwareObject);
                $em->flush();
            }

            // Recupera classProperty para o software
            $classProperty = $em->getRepository('CacicCommonBundle:ClassProperty')->findOneBy(array(
                'idClass' => $classObject->getIdClass(),
                'nmPropertyName' => $idSoftware
            ));
            if (empty($classProperty)) {

                $classProperty = new ClassProperty();
                $classProperty->setTePropertyDescription("Software detectado: $software");
                $classProperty->setNmPropertyName($idSoftware);
                $classProperty->setIdClass($classObject);

                // Se não der o flush aqui, as consultas de baixo não encontram o objeto
                $em->persist($classProperty);
                $em->flush();
            }

            // Adiciona software ao computador
            $propSoftware = $em->getRepository('CacicCommonBundle:PropriedadeSoftware')->findOneBy(array(
                'classProperty' => $classProperty,
                'software' => $softwareObject,
                'computador' => $computador
            ));
            if (empty($propSoftware)) {
                $logger->info("COLETA: Cadastrando software não encontrado $software");

                $propSoftware = new PropriedadeSoftware();
                $propSoftware->setComputador($computador);
                $propSoftware->setSoftware($softwareObject);
                $propSoftware->setClassProperty($classProperty);

                // Adiciona software na coleta
                $softwareObject->addColetado($propSoftware);
            }

            // Encontra coleta já feita para o Computador
            $computadorColeta = $em->getRepository('CacicCommonBundle:ComputadorColeta')->findOneBy(array(
                'computador' => $computador,
                'classProperty' => $classProperty
            ));
            if(empty($computadorColeta)) {
                $logger->debug("COLETA: Registrando nova coleta para o software $software no computador ".$computador->getIdComputador());
                $computadorColeta = new ComputadorColeta();
                $computadorColeta->setComputador($computador);
                $computadorColeta->setClassProperty($classProperty);
            }

            // Atualiza valores
            $computadorColeta->setComputador( $computador );

            // Atualiza valores do Software
            $softwareObject->setNmSoftware($software);
            if (array_key_exists('description', $valor)) {
                $softwareObject->setTeDescricaoSoftware($valor['description']);
                $propSoftware->setDisplayName($valor['description']);
            }
            if (array_key_exists('name', $valor)) {
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

            // Armazena no banco o objeto
            $computadorColeta->setClassProperty($classProperty);
            $computadorColeta->setTeClassPropertyValue($software);
            $computadorColeta->setIdClass($classObject);
            $computadorColeta->setDtHrInclusao( new \DateTime() );

            // Persiste os objetos 
            $em->persist($propSoftware);
            $em->persist($softwareObject);
            $em->persist( $computadorColeta );

            // Persistencia de Historico
            $computadorColetaHistorico = new ComputadorColetaHistorico();
            $computadorColetaHistorico->setComputadorColeta( $computadorColeta );
            $computadorColetaHistorico->setComputador( $computador );
            $computadorColetaHistorico->setClassProperty( $classProperty);
            $computadorColetaHistorico->setTeClassPropertyValue($software);
            $computadorColetaHistorico->setDtHrInclusao( new \DateTime() );
            $em->persist( $computadorColetaHistorico );

            // Tem que adicionar isso aqui ou o Doctrine vai duplicar o software
            $em->flush();

        } catch(ORMException $e){
            // Reopen Entity Manager
            if (!$em->isOpen()) {

                // reset the EM and all alias
                $container = $this->container;
                $container->set('doctrine.orm.entity_manager', null);
                $container->set('doctrine.orm.default_entity_manager', null);
            }

            $logger->error("COLETA: Erro na inserçao de dados do software $software.");
            $logger->debug($e);
        } catch(DBALException $e){
            // Reopen Entity Manager
            if (!$em->isOpen()) {

                // reset the EM and all alias
                $container = $this->container;
                $container->set('doctrine.orm.entity_manager', null);
                $container->set('doctrine.orm.default_entity_manager', null);
            }

            $logger->error("COLETA: Erro impossível de software repetido para $software.");
            $logger->debug($e);
        }
    }
}
