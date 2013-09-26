<?php
 /* 
 Copyright 2000, 2001, 2002, 2003, 2004, 2005 Dataprev - Empresa de Tecnologia e Informa��es da Previd�ncia Social, Brasil

 Este arquivo � parte do programa CACIC - Configurador Autom�tico e Coletor de Informa��es Computacionais

 O CACIC � um software livre; voc� pode redistribui-lo e/ou modifica-lo dentro dos termos da Licen�a P�blica Geral GNU como 
 publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a, ou (na sua opni�o) qualquer vers�o.

 Este programa � distribuido na esperan�a que possa ser  util, mas SEM NENHUMA GARANTIA; sem uma garantia implicita de ADEQUA��O a qualquer
 MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU para maiores detalhes.

 Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "LICENCA.txt", junto com este programa, se n�o, escreva para a Funda��o do Software
 Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */
session_start();
/*
 * verifica se houve login e tamb�m regras para outras verifica��es (ex: permiss�es do usu�rio)!
 */
if(!isset($_SESSION['id_usuario'])) 
  	die('Acesso negado (Access denied)!');
else 
	{ // Inserir regras para outras verifica��es (ex: permiss�es do usu�rio)!
	include_once "../include/library.php";
	
	AntiSpy('1'); // Somente ao n�vel Administra��o
	
	// ******************************************************
	// Consultando Sobre Determinada SubRede na Base do CACIC
	// ******************************************************		
	Conecta_bd_cacic();
	
	$queryDadosRede 		= 	"SELECT 	red.te_mascara_rede,
											red.nm_rede,
											loc.nm_local,
											sau.nm_servidor_autenticacao,
											red.te_serv_cacic,
											red.te_serv_updates,						
											red.nu_porta_serv_updates,												
											red.nu_limite_ftp,
											red.te_path_serv_updates,																		
											red.nm_usuario_login_serv_updates_gerente,
											red.te_senha_login_serv_updates_gerente,						
											red.nm_usuario_login_serv_updates,
											red.te_senha_login_serv_updates,
											red.id_rede	
						   		FROM 		redes  					red,
						            		locais 					loc,
											servidores_autenticacao sau
						   		WHERE 		red.te_ip_rede 				 = '".trim($_GET['te_ip_rede'])."' AND
						            		loc.id_local   				 = red.id_local AND 
											sau.id_servidor_autenticacao = red.id_servidor_autenticacao";
	$resultDadosRede  	= mysql_query($queryDadosRede);
	$rowDadosRede 		= mysql_fetch_array($resultDadosRede);	
		
	$queryAcoes 			=  "SELECT 		aco.id_acao,
											DATE_FORMAT(aco.dt_hr_coleta_forcada,'%d-%m-%Y %H:%i:%s') as dt_hr_coleta_forcada
						   		FROM 		acoes_redes				aco
						   		WHERE 		aco.id_rede 				= ".$rowDadosRede['id_rede']." 
						   		ORDER BY	aco.dt_hr_coleta_forcada DESC, aco.id_acao";
	$resultAcoes  			= mysql_query($queryAcoes);

	$strAcoes				= '';
	while ($rowAcoes = mysql_fetch_array($resultAcoes))
		{
		$strAcoes .= ($strAcoes ? '_ACR_' : '');
		$strAcoes .= $rowAcoes['id_acao'] 				. '_ACF_' . 
		   			 ($rowAcoes['dt_hr_coleta_forcada'] ? $rowAcoes['dt_hr_coleta_forcada'] : 'NADA');
		}

	$queryModulos 			=  "SELECT 		nm_modulo,
											te_versao_modulo,
											DATE_FORMAT(dt_atualizacao,'%d-%m-%Y %H:%i') as dt_atualizacao,
											te_hash
						   		FROM 		redes_versoes_modulos
						   		WHERE 		id_rede		 		 = ".$rowDadosRede['id_rede']." AND 
											nm_modulo <> 'versions_and_hashes.ini'  
								ORDER BY	dt_atualizacao DESC, nm_modulo";
								
	$resultModulos  		= mysql_query($queryModulos);

	$strModulos				= '';
	while ($rowModulos = mysql_fetch_array($resultModulos))
		{
		$strModulos .= 	($strModulos ? '_MOR_' : '');
		$strModulos .= 	$rowModulos['nm_modulo'] 		. '_MOF_' . 
						$rowModulos['te_versao_modulo'] . '_MOF_' . 
						$rowModulos['dt_atualizacao'] 	. '_MOF_' . 
						$rowModulos['te_hash'];
		}

	$queryEstacoes 			=  "SELECT 		com.te_node_address,
											sos.te_desc_so,
											com.te_nome_computador,
											com.te_dominio_windows,
											com.te_dominio_dns,
											com.te_ip_computador,
											DATE_FORMAT(com.dt_hr_ult_acesso,'%d-%m-%Y %H:%i') as dt_hr_ult_acesso,
											sos.in_mswindows
						   		FROM 		computadores 			com,
											so						sos
						   		WHERE 		com.id_rede		 		= ".$rowDadosRede['id_rede']." AND 
											com.id_so				= sos.id_so  
								ORDER BY	com.dt_hr_ult_acesso DESC";
	$resultEstacoes  		= mysql_query($queryEstacoes);

	$strEstacoesWindows			= '';
	$strEstacoesLinux			= '';	
	$strEstacoes				= '';
	while ($rowEstacoes = mysql_fetch_array($resultEstacoes))
		{

		$strEstacoes  = $rowEstacoes['te_nome_computador'] 	. '_ESF_' . 
						$rowEstacoes['te_ip_computador']	. '_ESF_' . 
						$rowEstacoes['te_desc_so'] 			. '_ESF_' . 						
						$rowEstacoes['te_node_address']		. '_ESF_' .  	
						$rowEstacoes['te_dominio_windows'] 	. '_ESF_' . 
						$rowEstacoes['te_dominio_dns']		. '_ESF_' .
						$rowEstacoes['dt_hr_ult_acesso'];
		if ($rowEstacoes['in_mswindows'] == 'S')
			{
			$strEstacoesWindows .= ($strEstacoesWindows ? '_ESR_' : '');			
			$strEstacoesWindows .= $strEstacoes;			
			}
		else
			{
			$strEstacoesLinux 	.= ($strEstacoesLinux ? '_ESR_' : '');			
			$strEstacoesLinux   .= $strEstacoes;						
			}
		}
	
	$retorno = '';
		

	
	$retorno =  $rowDados['te_mascara_rede']						. '_FD_' .
				$rowDados['nm_rede']								. '_FD_' .
				$rowDados['nm_local']								. '_FD_' .
				$rowDados['nm_servidor_autenticacao']				. '_FD_' .
				$rowDados['te_serv_cacic']							. '_FD_' .
				$rowDados['te_serv_updates']						. '_FD_' .
				$rowDados['nu_porta_serv_updates']					. '_FD_' .
				$rowDados['nu_limite_ftp']							. '_FD_' .
				$rowDados['te_path_serv_updates']					. '_FD_' .
				$rowDados['nm_usuario_login_serv_updates_gerente']	. '_FD_' .
				$rowDados['te_senha_login_serv_updates_gerente']	. '_FD_' .
				$rowDados['nm_usuario_login_serv_updates']			. '_FD_' .
				$rowDados['te_senha_login_serv_updates']			. '_FD_' .
				$strAcoes											. '_FD_' .
				$strModulos											. '_FD_' .
				$strEstacoesWindows									. '_FD_' .
				$strEstacoesLinux;
	
	echo $retorno;	
	}
?>