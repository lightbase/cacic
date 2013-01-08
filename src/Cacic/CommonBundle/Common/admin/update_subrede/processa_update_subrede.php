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
session_start();
require_once('../../include/library.php');
AntiSpy('1,2,3'); // Permitido somente a estes cs_nivel_administracao...
/*
Importante -> Execução Prévia:
-----------------------------
1) Criar _SESSION[sessArrVersionsIni] contendo parse_ini_file de versions_and_hashes.ini
2) Se todos os ítens de redes_versoes_modulos para a rede foram marcados
		Deletar todos os registros do id_rede da tabela redes_versoes_modulos
   Senão
   		Deletar apenas aqueles ítens assinalados para "forçar"

Valores na Chamada:
------------------
pIntIdRede: 		Integer		Id da rede a ser tratada
pStrNmItem: 		String 		Nome do ítem a ser enviado
*/
if(isset($_SESSION['sessArrVersionsIni']))		
	{	
	$strSendProcess   = 'Nao Enviado!';		
	$strProcessStatus = '';
	
	$arrDadosRede = getValores('redes r',
							   'r.te_serv_updates,
								r.te_path_serv_updates,
								r.nu_porta_serv_updates,
								r.nm_usuario_login_serv_updates_gerente,
								r.te_senha_login_serv_updates_gerente', 
							   'r.id_rede=' . $_GET['pIntIdRede']);

	// Caso o servidor de updates ainda não tenha sido trabalhado...
	if(!(stripos2($_SESSION['sessStrTripaItensEnviados'],$arrDadosRede['te_serv_updates'].'_'.$arrDadosRede['te_path_serv_updates'].'_'.$_GET['pStrNmItem'].'_',false)))	
		{
		$_SESSION['sessStrTripaItensEnviados'] .= $arrDadosRede['te_serv_updates'].'_'.$arrDadosRede['te_path_serv_updates'].'_'.$_GET['pStrNmItem'] . '_';
		require_once('../../include/ftp_check_and_send.php');	

		$strResult = checkAndSend($_GET['pStrNmItem'],
								  CACIC_PATH . CACIC_PATH_RELATIVO_DOWNLOADS . ($_SESSION['sessArrVersionsIni'][$_GET['pStrNmItem'] . '_PATH']),
								  $arrDadosRede['te_serv_updates'],
								  $arrDadosRede['te_path_serv_updates'],							  
								  $arrDadosRede['nm_usuario_login_serv_updates_gerente'],
								  $arrDadosRede['te_senha_login_serv_updates_gerente'],
								  $arrDadosRede['nu_porta_serv_updates']);
		}
	else
		$strResult = 'Ja Enviado ao Servidor!_=_Ok!_=_Resended';				
		
	$arrResult = explode('_=_',$strResult);	 	
	if ($arrResult[1] == 'Ok!')
		{
		$queryDEL  = 'DELETE FROM redes_versoes_modulos WHERE id_rede = ' . $_GET['pIntIdRede'] . ' AND nm_modulo = "' . $_GET['pStrNmItem'] . '"';
		$resultDEL = mysql_query($queryDEL) or die($oTranslator->_('Falha excluindo item de (%1) ou sua sessao expirou!',array('redes_versoes_modulos'))); 																

		$queryINS  = 'INSERT INTO redes_versoes_modulos (id_rede,
														 nm_modulo,
														 te_versao_modulo,
														 dt_atualizacao,
														 cs_tipo_so,
														 te_hash) VALUES ('  . 
														 $_GET['pIntIdRede'] .  ',
													"' . $_GET['pStrNmItem'] . '",
													"' . $_SESSION['sessArrVersionsIni'][$_GET['pStrNmItem'] . '_VER'] . '",
														 now(),
													"' . (stripos2($_GET['pStrNmItem'],'.exe',false) ? 'MS-Windows' : 'GNU/LINUX') . '",
													"' . $_SESSION['sessArrVersionsIni'][$_GET['pStrNmItem'] . '_HASH'] . '")';
		$resultINS = mysql_query($queryINS) or die($oTranslator->_('Falha inserindo item em (%1) ou sua sessao expirou!',array('redes_versoes_modulos')));									
		}					

	echo $_GET['pIntIdRede'] . '_=_' . $_GET['pStrNmItem'] . '_=_' . $strResult;		
	}
	?>