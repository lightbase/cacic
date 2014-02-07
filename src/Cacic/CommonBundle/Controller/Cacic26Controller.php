<?php

namespace Cacic\CommonBundle\Controller;

use Composer\Downloader\ZipDownloader;
use Doctrine\Common\Util\Debug;
use Doctrine\DBAL\Driver\PDOConnection;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use ZipArchive;
use Symfony\Component\Filesystem\Filesystem;


/**
 *
 * @author lightbase
 *
 */
class Cacic26Controller extends Controller
{
    /**
     *
     * Tela de importação de dados do Gerente 2.6
     */
   public function importardadosAction( Request $request )
    {
        $form = $this->createFormBuilder()
            ->add('arquivo', 'file', array('label' => 'Arquivo', 'attr' => array( 'accept' => '.zip' )))
            ->getForm();

        $form->handleRequest( $request );

        if ( $form->isValid() )
        {
            {
                // grava no diretório src/Cacic/CommonBundle/Resources/data
                //$dirMigracao = realpath( dirname(__FILE__) .'/../Resources/data/' );

                //$fileName = 'importacao'.'.zip';
                //$form['arquivo']->getData()->move($dirMigracao, $fileName);

                // Eduardo: 13/01/2014
                // Altera a função de migração
                $result = $this->importaPostgreSQL($form['arquivo']->getData());

                if ( $result ) {
                    $this->get('session')->getFlashBag()->add('success', 'Envio realizado com sucesso!');
                } else {
                    $this->get('session')->getFlashBag()->add('error', 'Migração inválida! Ocorreu uma falha ao importar os arquivos');
                }

            }

            //return $this->redirect( $this->generateUrl( 'cacic_migracao_importador') );
        }

        return $this->render(
            'CacicCommonBundle:Cacic26:migracao.html.twig',
            array( 'form' => $form->createView() )
        );
    }

    /*
     * Função que importa os dadospara o banco de dados PostgreSQL
     * @param $arquivo Arquivo .zip contendo dados gerados pelo extrator
     */
    public function importaPostgreSQL($arquivo) {
        $tmpdir = sys_get_temp_dir();
        $logger = $this->get('logger');
        $importacao = $tmpdir.'/importacao';

        // Tabelas
        $lista_tabelas = array(
            //"acao",
            "servidor_autenticacao",
            "local",
            "rede",
            //"acao_excecao",
            //"acao_rede",
            "so",
            //"acao_so",
            "aplicativo",
            "aplicativo_rede",
            "aquisicao",
            "tipo_licenca",
            "software",
            "aquisicao_item",
            "computador",
            "descricao_coluna_computador",
            "grupo_usuario",
            "insucesso_instalacao",
            "usuario",
            "local_secundario",
            "log",
            "unid_organizacional_nivel1",
            "unid_organizacional_nivel1a",
            "unid_organizacional_nivel2",
            "patrimonio",
            "patrimonio_config_interface",
            "rede_grupo_ftp",
            "rede_versao_modulo",
            "software_estacao",
            "srcacic_chat",
            "srcacic_conexao",
            "srcacic_sessao",
            "srcacic_transf",
            "teste",
            "tipo_software",
            "tipo_uorg",
            "uorg",
            "usb_vendor",
            "usb_device",
            "usb_log"
        );

        // Sequências
        $lista_sequencias = array(
            "aplicativo" => array(
                "id_aplicativo", "aplicativo_id_aplicativo_seq"
            ),
            "aquisicao" => array(
                "id_aquisicao", "aquisicao_id_aquisicao_seq"
            ),
            "computador" => array(
                "id_computador", "computador_id_computador_seq"
            ),
            "grupo_usuario" => array(
                "id_grupo_usuario", "grupo_usuario_id_grupo_usuario_seq"
            ),
            "insucesso_instalacao" => array(
                "id_insucesso_instalacao", "insucesso_instalacao_id_insucesso_instalacao_seq"
            ),
            "local" => array(
                "id_local", "local_id_local_seq"
            ),
            "log" => array(
                "id_log", "log_id_log_seq"
            ),
            "patrimonio" => array(
                "id_patrimonio", "patrimonio_id_patrimonio_seq"
            ),
            "rede_grupo_ftp" => array(
                "id_ftp", "rede_grupo_ftp_id_ftp_seq"
            ),
            "rede" => array(
                "id_rede", "rede_id_rede_seq"
            ),
            "rede_versao_modulo" => array(
                "id_rede_versao_modulo", "rede_versao_modulo_id_rede_versao_modulo_seq"
            ),
            "servidor_autenticacao" => array(
                "id_servidor_autenticacao", "servidor_autenticacao_id_servidor_autenticacao_seq"
            ),
            "so" => array(
                "id_so", "so_id_so_seq"
            ),
            "software" => array(
                "id_software", "software_id_software_seq"
            ),
            "srcacic_action" => array(
                "id_srcacic_action", "srcacic_action_id_srcacic_action_seq"
            ),
            "srcacic_chat" => array(
                "id_srcacic_chat", "srcacic_chat_id_srcacic_chat_seq"
            ),
            "srcacic_conexao" => array(
                "id_srcacic_conexao", "srcacic_conexao_id_srcacic_conexao_seq"
            ),
            "srcacic_sessao" => array(
                "id_srcacic_sessao", "srcacic_sessao_id_srcacic_sessao_seq"
            ),
            "srcacic_transf" => array(
                "id_srcacic_transf", "srcacic_transf_id_srcacic_transf_seq"
            ),
            "teste" => array(
                "id_transacao", "teste_id_transacao_seq"
            ),
            "tipo_licenca" => array(
                "id_tipo_licenca", "tipo_licenca_id_tipo_licenca_seq"
            ),
            "tipo_software" => array(
                "id_tipo_software", "tipo_software_id_tipo_software_seq"
            ),
            "tipo_uorg" => array(
                "id_tipo_uorg", "tipo_uorg_id_tipo_uorg_seq"
            ),
            "unid_organizacional_nivel1" => array(
                "id_unid_organizacional_nivel1", "unid_organizacional_nivel1_id_unid_organizacional_nivel1_seq"
            ),
            "unid_organizacional_nivel1a" => array(
                "id_unid_organizacional_nivel1a", "unid_organizacional_nivel1a_id_unid_organizacional_nivel1a_seq"
            ),
            "unid_organizacional_nivel2" => array(
                "id_unid_organizacional_nivel2", "unid_organizacional_nivel2_id_unid_organizacional_nivel2_seq"
            ),
            "uorg" => array(
                "id_uorg", "uorg_id_uorg_seq"
            ),
            "usb_log" => array(
                "id_usb_log", "usb_log_id_usb_log_seq"
            ),
            "usuario" => array(
                "id_usuario", "usuario_id_usuario_seq"
            )
        );


        // Cria um diretório temporário com todos os dados extraídos
        $zip = new ZipArchive();
        $x = $zip->open($arquivo);

        $logger->info("Extraindo arquivo de importação no diretorio {$importacao}");
        if ($x === TRUE) {
            $zip->extractTo($importacao);
            $zip->close();
            $logger->info('Arquivo.zip extraído com sucesso');
        } else {
            $logger->error("Erro na extração do arquivo {$x}");
            return false;
        }

        // Carrega configurações do banco de dados
        $dbhost = $this->container->getParameter('database_host');
        $db = $this->container->getParameter('database_name');
        $port = $this->container->getParameter('database_port');
        $user = $this->container->getParameter('database_user');
        $pass = $this->container->getParameter('database_password');

        if ($dbhost) {
            $dbcon = new PDOConnection("pgsql:host={$dbhost};dbname={$db};port={$port}", $user, $pass);
        } else {
            $dbcon = new PDOConnection("pgsql:dbname={$db};port={$port}", $user, $pass);
        }

        // Ou funciona tudo ou não funciona nada
        $dbcon->beginTransaction();

        // Primeiro apaga os dados por tabela
        $excluir = array('configuracao_local');
        $excluir = array_reverse(array_merge($lista_tabelas, $excluir));

        foreach ($excluir as $tabela) {
                $logger->info("Apagando a tabela {$tabela}");
                try {
                    // Apaga a tabela e carrega os dados do arquivo CSV
                    $dbcon->exec("DELETE FROM {$tabela} CASCADE");
                } catch (Exception $e) {
                    // Falha, dá um rollback e retorna falso
                    $logger->error("Erro na exclusão \n" . $e->getMessage());
                    $dbcon->rollBack();
                    return false;
                }
        }

        // Agora carrega os dados
        foreach ($lista_tabelas as $tabela) {
            if (file_exists("{$importacao}/{$tabela}.csv")) {
                $logger->info("Importando a tabela {$tabela}");
                try {
                    // Apaga a tabela e carrega os dados do arquivo CSV
                    $dbcon->exec("COPY {$tabela} FROM '{$tmpdir}/importacao/{$tabela}.csv' DELIMITER ';' NULL E'\\\N' CSV ESCAPE '\"'");
                } catch (Exception $e) {
                    // Falha, dá um rollback e retorna falso
                    $logger->error("Erro na importação \n" . $e->getMessage());
                    $dbcon->rollBack();
                    return false;
                }
            }
        }

        // Se tudo funcionou bem, é prciso ajustar o valor das sequências
        foreach ($lista_sequencias as $tabela => $value) {
            // Atualiza as sequencias de auto-increment
            $logger->info("Carregando a sequência para a tabela {$tabela} ....");
            foreach ($dbcon->query("SELECT MAX({$value[0]}) AS $value[0] FROM {$tabela}") as $row) {
                $result = $row["{$value[0]}"];
            }
            if ($result) {
                $logger->info("Ajustando a sequência {$value[1]}  para o valor máximo {$result} ...");
                $dbcon->exec("SELECT setval('{$value[1]}', {$result})");
            } else {
                $logger->error("Valor máximo não encontrado para a tabela {$tabela}, ID {$value[0]} e sequência {$value[1]}");
            }

        }

        // Finaliza a transação
        $dbcon->commit();

        // Armazena as senhas para todos os usuários criados
        $arrUsuarios = $this->getDoctrine()->getRepository( 'CacicCommonBundle:Usuario' )->findAll();
        foreach ($arrUsuarios as $usuario) {
            $logger->info("Corrige a senha do usuario {$usuario->getNmUsuarioCompleto()} .....");
            $encoder = $this->container
                ->get('security.encoder_factory')
                ->getEncoder($usuario)
            ;
            // Guarda a senha criptografada
            $usuario->setTeSenha($encoder->encodePassword($usuario->getTeSenha(), $usuario->getSalt()));
            $this->getDoctrine()->getManager()->persist($usuario);
            $this->getDoctrine()->getManager()->flush();
        }

        // Finaliza a importação e apaga os arquivos
        $fs = new Filesystem();
        $fs->remove($importacao);
        return true;
    }

    public function importarscriptAction(){
        function getconfig() {
            // fixme: do it the synfony way
            $yfile = "../app/config/parameters.yml";
            $parameters = file_get_contents($yfile);
            $linhas = explode("\n", $parameters);
            foreach ($linhas as $linha) {
                $dado = explode(": ", $linha);
                $config[] = $dado[1];
            }
            return $config;
        }


        // Conexão com o banco
        $config = @getconfig();
        $server = $config[2];
        $port = $config[3];
        $db = $config[4];
        $user = $config[5];
        $pass = $config[6];

        error_log("11111111111111111111111111111111" . $config . " | " . $server . " | " . $db . " | " . $user . " | " . $pass);

	if ($server != 'null') {
	        $dbcon = new PDOConnection("pgsql:host={$server};dbname={$db};port={$port}", $user, $pass);

	} else {
        	$dbcon = new PDOConnection("pgsql:dbname={$db};port={$port}", $user, $pass);


	}


        function importar($dbcon, $tmpdir) {

            // Cria a query padrão de inclusão de dados
            $lista_tabelas = array(
                "acao",
                "servidor_autenticacao",
                "local",
                "rede",
                "acao_excecao",
                "acao_rede",
                "so",
                "acao_so",
                "aplicativo",
                "aplicativo_rede",
                "aquisicao",
                "tipo_licenca",
                "software",
                "aquisicao_item",
                "computador",
                "descricao_coluna_computador",
                "grupo_usuario",
                "insucesso_instalacao",
                "usuario",
                "local_secundario",
                "log",
                "unid_organizacional_nivel1",
                "unid_organizacional_nivel1a",
                "unid_organizacional_nivel2",
                "patrimonio",
                "patrimonio_config_interface",
                "rede_grupo_ftp",
                "rede_versao_modulo",
                "software_estacao",
                "srcacic_chat",
                "srcacic_conexao",
                "srcacic_sessao",
                "srcacic_transf",
                "teste",
                "tipo_software",
                "tipo_uorg",
                "uorg",
                "usb_vendor",
                "usb_device",
                "usb_log"
            );

            echo "Limpando dados anteriores... ";
            foreach ($lista_tabelas as $tabela) {
                // Limpa as tabelas antes
                $dbcon -> exec("truncate {$tabela} cascade");
            }
            echo "feito.<br>";

            foreach ($lista_tabelas as $tabela){
                    if (file_exists("{$tmpdir}/importacao/{$tabela}.csv")) {
                    echo "Importando ".$tabela."...";
                     // Copia do arquivo para a base
                    $dbcon -> exec("COPY {$tabela} FROM '{$tmpdir}/importacao/{$tabela}.csv' WITH DELIMITER AS ';' NULL AS '\N' ESCAPE '\"' ENCODING 'ISO-8859-1' CSV");
                    echo " feito.<br>";
                }
            }
        }

        function atualizar_seq($dbcon){
            $lista_sequencias = array(
                "aplicativo_id_aplicativo_seq",
                "aquisicao_id_aquisicao_seq",
                "class_property_id_class_property_seq",
                "class_property_type_id_class_property_type_seq",
                "classe_id_class_seq",
                "collect_def_class_id_collect_def_class_seq",
                "computador_coleta_historico_id_computador_coleta_historico_seq",
                "computador_coleta_id_computador_coleta_seq",
                "computador_id_computador_seq",
                "grupo_usuario_id_grupo_usuario_seq",
                "insucesso_instalacao_id_insucesso_instalacao_seq",
                "local_id_local_seq",
                "log_id_log_seq",
                "patrimonio_id_patrimonio_seq",
                "rede_grupo_ftp_id_ftp_seq",
                "rede_id_rede_seq",
                "rede_versao_modulo_id_rede_versao_modulo_seq",
                "servidor_autenticacao_id_servidor_autenticacao_seq",
                "so_id_so_seq",
                "software_id_software_seq",
                "srcacic_action_id_srcacic_action_seq",
                "srcacic_chat_id_srcacic_chat_seq",
                "srcacic_conexao_id_srcacic_conexao_seq",
                "srcacic_sessao_id_srcacic_sessao_seq",
                "srcacic_transf_id_srcacic_transf_seq",
                "teste_id_transacao_seq",
                "tipo_licenca_id_tipo_licenca_seq",
                "tipo_software_id_tipo_software_seq",
                "tipo_uorg_id_tipo_uorg_seq",
                "unid_organizacional_nivel1_id_unid_organizacional_nivel1_seq",
                "unid_organizacional_nivel1a_id_unid_organizacional_nivel1a_seq",
                "unid_organizacional_nivel2_id_unid_organizacional_nivel2_seq",
                "uorg_id_uorg_seq",
                "usb_log_id_usb_log_seq",
                "usuario_id_usuario_seq"
            );

            foreach ($lista_sequencias as $tabela) {
                // Atualiza as sequencias de auto-increment
                $dbcon->exec("SELECT nextval('{$tabela}')");
            }
        }

// Execuções

        echo "Iniciando importação...<br>";

        $zipfile = "../src/Cacic/CommonBundle/Resources/data/importacao.zip";
        $tmpdir = sys_get_temp_dir();

// Extrai os arquivos necessarios para a importação
        $zip = new ZipArchive();
        $x = $zip->open($zipfile);
        echo "extraindo arquivo...";
        if ($x === TRUE) {
            $zip->extractTo($tmpdir.'/importacao');
            $zip->close();
            echo " feito.<br>";
        } else {
            die("<br>Erro na extração do arquivo: {$x}");
        }

// Importa os dados para o postgres
        $dbcon->exec("begin");
        importar($dbcon, $tmpdir);
        $dbcon->exec("end");
        atualizar_seq($dbcon);

// Deleta os arquivos
        foreach (glob($tmpdir.'/importacao/*') as $filename) {
            unlink($filename);
        }
        rmdir($tmpdir."/importacao");

// Fecha conexão com o banco
        $dbcon = null;
        return $this->redirect( $this->generateUrl( 'cacic_migracao_cacic26') );
    }

}
