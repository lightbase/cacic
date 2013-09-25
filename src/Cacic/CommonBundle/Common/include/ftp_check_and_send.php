<?php
/*
 Copyright 2000, 2001, 2002, 2003, 2004, 2005 Dataprev - Empresa de Tecnologia e Informações da Previdência Social, Brasil

 Este arquivo é parte do programa CACIC - Configurador Automático e Coletor de Informações Computacionais

 O CACIC é um software livre; você pode redistribui-lo e/ou modifica-lo dentro dos termos da Licença Pública Geral GNU como
 publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença, ou (na sua opnião) qualquer versão.

 Este programa é distribuido na esperança que possa ser  util, mas SEM NENHUMA GARANTIA; sem uma garantia implicita de ADEQUAÇÂO a qualquer
 MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU para maiores detalhes.

 Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "LICENCA.txt", junto com este programa, se não, escreva para a Fundação do Software
 Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */
require_once('library.php');
AntiSpy('1,2,3'); // Permitido somente a estes cs_nivel_administracao...

function checkAndSend($pStrNmItem,
					  $pStrFullItemName,
					  $pStrTeServer,
					  $pStrTePathServer,					  
					  $pStrNmUsuarioLogin,
					  $pStrTeSenhaLogin,
					  $pStrNuPortaServer)
    {
	require_once('class.ftp.php');						
	$strSendProcess   = 'Nao Enviado!';		
	$strProcessStatus = '';
	$strProcessCode	  = '';
	try 
		{
		$FTP =& new FTP();
	
		if ($FTP->connect($pStrTeServer)) 
			{
			// Retorno esperado....: 230 => FTP_USER_LOGGED_IN
			// Retorno NÃO esperado: 530 => FTP_USER_NOT_LOGGED_IN
			if ($FTP->login($pStrNmUsuarioLogin,$pStrTeSenhaLogin)) 
				{
				// Retorno esperado: 250 => FTP_FILE_ACTION_OK
				// Retorno NÃO esperado: 550 => FTP_PERMISSION_DENIED (ou a pasta não existe!)
				$FTP->chdir($pStrTePathServer);				

				$intFtpPutResult = $FTP->getLastResult();				
				$strProcessCode  = $intFtpPutResult;				
				if ($intFtpPutResult == 250)
					{		
					// Retorno esperado....: 226 => FTP_FILE_TRANSFER_OK				
					// Retorno NÃO esperado: 550 => FTP_PERMISSION_DENIED
					
					$FTP->put($pStrNmItem,$pStrFullItemName);					
					$intFtpPutResult = $FTP->getLastResult();
					$strProcessCode  = $intFtpPutResult;									
					if ($intFtpPutResult == 226)
						{
						$strSendProcess   = 'Enviado com Sucesso!';
						$strProcessStatus = 'Ok!';														
						}
					else
						$strProcessStatus = 'ERRO: Problema no Envio!';		
					}
				else	
					$strProcessStatus = 'ERRO: Pasta "' . $pStrTePathServer . '" inacessivel!';					
				} 
			else
				{
				$strProcessStatus = 'Falha no Login com usuario "' . $pStrNmUsuarioLogin . '"!';
				$strProcessCode   = $FTP->getLastResult();
				}

			$FTP->disconnect();
			} 
		else 
			{
			$strProcessStatus = 'ERRO: Impossivel conectar o servidor "' . $pStrTeServer . '"!';			
			$strProcessCode   = $FTP->getLastResult();			
			}
		} 
	catch (FTPException $e)
		{
		$strProcessStatus = 'ERRO: Problema durante a conexao! (' . $e->getMessage() . ')';
		$strProcessCode   = $FTP->getLastResult();		
		}

	return $strSendProcess . '_=_' . $strProcessStatus . '_=_' . $strProcessCode;		
	}
	?>