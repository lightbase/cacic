<?php

namespace Cacic\CommonBundle\Migrations;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Ijanki\Bundle\FtpBundle\Exception\FtpException;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150213142949 extends AbstractMigration implements ContainerAwareInterface
{
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * Copia todos os módulos para a pasta FTP fixa do update para a versão 2.8.1.23
     *
     * @param Schema $schema
     * @return bool
     */
    public function up(Schema $schema)
    {
        $logger = $this->container->get('logger');
        $em = $this->container->get('doctrine.orm.entity_manager');

        // Mapeia diretórios
        $rootDir = $this->container->get('kernel')->getRootDir();
        $webDir = $rootDir . "/../web/";
        $downloadsDir = $webDir . "downloads/";

        // Seleciona rede padrão
        $rede = $em->getRepository('CacicCommonBundle:Rede')->findOneBy(array(
           'teIpRede' => '0.0.0.0'
        ));

        $modulo = array('cacic280.exe', '2.8.1.23', '6bec84cb246c49e596256d4833e6b301');
        $logger->debug("Copia modulo  via FTP: ".$modulo[0]);

        $strResult = $this->checkAndSend(
            $modulo[0],
            realpath($downloadsDir . 'update28/' . $modulo[0]),
            $rede->getTeServUpdates(),
            'update28',
            $rede->getNmUsuarioLoginServUpdatesGerente(),
            $rede->getTeSenhaLoginServUpdatesGerente(),
            $rede->getNuPortaServUpdates()
        );

        $arrResult = explode('_=_',$strResult);

        if ($arrResult[1] == 'Ok!')
        {
            $logger->debug("Arquivo copiado com sucesso :".$modulo[0]);
        } else {
            $logger->error("Erro no envio dos módulos!\n".$arrResult[1]);
            return false;
        }

        $modulo = array('cacicservice.exe', '2.8.1.23', '3119b4e67d71fcec2700770632974a31');
        $logger->debug("Copia modulo  via FTP: ".$modulo[0]);

        $strResult = $this->checkAndSend(
            $modulo[0],
            realpath($downloadsDir . 'update28/' . $modulo[0]),
            $rede->getTeServUpdates(),
            'update28',
            $rede->getNmUsuarioLoginServUpdatesGerente(),
            $rede->getTeSenhaLoginServUpdatesGerente(),
            $rede->getNuPortaServUpdates()
        );

        $arrResult = explode('_=_',$strResult);

        if ($arrResult[1] == 'Ok!')
        {
            $logger->debug("Arquivo copiado com sucesso :".$modulo[0]);
        } else {
            $logger->error("Erro no envio dos módulos!\n".$arrResult[1]);
            return false;
        }

        $modulo = array('chksis.exe', '2.8.1.23', '748b8265eb0cd80e1854a90fe34df671');
        $logger->debug("Copia modulo  via FTP: ".$modulo[0]);

        $strResult = $this->checkAndSend(
            $modulo[0],
            realpath($downloadsDir . 'update28/' . $modulo[0]),
            $rede->getTeServUpdates(),
            'update28',
            $rede->getNmUsuarioLoginServUpdatesGerente(),
            $rede->getTeSenhaLoginServUpdatesGerente(),
            $rede->getNuPortaServUpdates()
        );

        $arrResult = explode('_=_',$strResult);

        if ($arrResult[1] == 'Ok!')
        {
            $logger->debug("Arquivo copiado com sucesso :".$modulo[0]);
        } else {
            $logger->error("Erro no envio dos módulos!\n".$arrResult[1]);
            return false;
        }

        $modulo = array('gercols.exe', '2.8.1.23', '6e358a7302e8c9b3d0c09fbd9c7a7000');
        $logger->debug("Copia modulo  via FTP: ".$modulo[0]);

        $strResult = $this->checkAndSend(
            $modulo[0],
            realpath($downloadsDir . 'update28/' . $modulo[0]),
            $rede->getTeServUpdates(),
            'update28',
            $rede->getNmUsuarioLoginServUpdatesGerente(),
            $rede->getTeSenhaLoginServUpdatesGerente(),
            $rede->getNuPortaServUpdates()
        );

        $arrResult = explode('_=_',$strResult);

        if ($arrResult[1] == 'Ok!')
        {
            $logger->debug("Arquivo copiado com sucesso: ".$modulo[0]);
        } else {
            $logger->error("Erro no envio dos módulos!\n".$arrResult[1]);
            return false;
        }

        $modulo = array('installcacic.exe', '2.8.1.23', '388c9d020e72f5b62696824cc69077ea');
        $logger->debug("Copia modulo  via FTP: ".$modulo[0]);

        $strResult = $this->checkAndSend(
            $modulo[0],
            realpath($downloadsDir . 'update28/' . $modulo[0]),
            $rede->getTeServUpdates(),
            'update28',
            $rede->getNmUsuarioLoginServUpdatesGerente(),
            $rede->getTeSenhaLoginServUpdatesGerente(),
            $rede->getNuPortaServUpdates()
        );

        $arrResult = explode('_=_',$strResult);

        if ($arrResult[1] == 'Ok!')
        {
            $logger->debug("Arquivo copiado com sucesso :".$modulo[0]);
        } else {
            $logger->error("Erro no envio dos módulos!\n".$arrResult[1]);
            return false;
        }

        $modulo  = array('mapacacic.exe', '2.8.1.23', '3f8a6191fbad092eeb202617288559e9');
        $logger->debug("Copia modulo  via FTP: ".$modulo[0]);

        $strResult = $this->checkAndSend(
            $modulo[0],
            realpath($downloadsDir . 'update28/' . $modulo[0]),
            $rede->getTeServUpdates(),
            'update28',
            $rede->getNmUsuarioLoginServUpdatesGerente(),
            $rede->getTeSenhaLoginServUpdatesGerente(),
            $rede->getNuPortaServUpdates()
        );

        $arrResult = explode('_=_',$strResult);

        if ($arrResult[1] == 'Ok!')
        {
            $logger->debug("Arquivo copiado com sucesso :".$modulo[0]);
        } else {
            $logger->error("Erro no envio dos módulos!\n".$arrResult[1]);
            return false;
        }
    }

    public function checkAndSend($pStrNmItem,
                                 $pStrFullItemName,
                                 $pStrTeServer,
                                 $pStrTePathServer,
                                 $pStrNmUsuarioLogin,
                                 $pStrTeSenhaLogin,
                                 $pStrNuPortaServer)
    {
        $logger = $this->container->get('logger');

        // Pega objetos do FTP
        $ftp = $this->container->get('ijanki_ftp');

        $strSendProcess   = 'Nao Enviado!';
        $strProcessStatus = '';
        $strProcessCode	  = '';

        try
        {
            $logger->debug("Enviando módulo $pStrFullItemName para o servidor $pStrTeServer na pasta $pStrTePathServer");


            $conn = $ftp->connect($pStrTeServer, $pStrNuPortaServer);
            // Retorno esperado....: 230 => FTP_USER_LOGGED_IN
            // Retorno NÃO esperado: 530 => FTP_USER_NOT_LOGGED_IN

            # TODO: Acrescentar verificação de sucesso em cada operação
            $result = $ftp->login($pStrNmUsuarioLogin,$pStrTeSenhaLogin);

            // Retorno esperado: 250 => FTP_FILE_ACTION_OK
            // Retorno NÃO esperado: 550 => FTP_PERMISSION_DENIED (ou a pasta não existe!)
            $result = $ftp->chdir($pStrTePathServer);
            $result = $ftp->put($pStrNmItem, $pStrFullItemName, FTP_BINARY);

            $strSendProcess   = 'Enviado com Sucesso!';
            $strProcessStatus = 'Ok!';
        }
        catch (FTPException $e)
        {
            $strSendProcess   = 'Falha no envio!';
            $strProcessStatus = 'ERRO: Problema durante a conexao! (' . $e->getMessage() . ')';
        }

        return $strSendProcess . '_=_' . $strProcessStatus . '_=_' . $strProcessCode;
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
