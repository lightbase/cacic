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
	include_once "../../include/library.php";
	
	AntiSpy();
	
	// ********************************************
	// Consultando Nome de Usu�rio em Base do CACIC
	// ********************************************
		
	Conecta_bd_cacic();
	
	$query 	= "SELECT 	u.id_local,
						u.te_locais_secundarios,
						u.nm_usuario_completo,
						u.id_servidor_autenticacao,
						g.cs_nivel_administracao,
						u.te_emails_contato,
						u.te_telefones_contato,
						u.id_grupo_usuarios					
			   FROM 	usuarios u,
			            grupo_usuarios g
			   WHERE 	u.nm_usuario_acesso = '".trim($_GET['nm_usuario_acesso'])."' AND
			            u.id_grupo_usuarios = g.id_grupo_usuarios";
	$result  = mysql_query($query);
	$retorno = '';
		
	while ($row = mysql_fetch_array($result))	
		$retorno =  $row['nm_usuario_completo'] 		. '_FD_' .  
					$row['te_emails_contato'] 			. '_FD_' .  					
					$row['te_telefones_contato'] 		. '_FD_' .  					
					$row['te_locais_secundarios'] 		. '_FD_' .  					
					$row['cs_nivel_administracao'] 		. '_FD_' .  					
					$row['id_local'] 					. '_FD_' .  		
					$row['id_grupo_usuarios']			. '_FD_' .  		
					'Base CACIC'  						. '_FD_' .  										
					'0';

	
	// **********************************************************************************************
	// Consultando Nome de Usu�rio em Servi�os de Diret�rios cadastrados (Servidores de Autentica��o)
	// **********************************************************************************************
	$query 	= "SELECT 	sa.id_servidor_autenticacao,
						sa.nm_servidor_autenticacao,	
						sa.nm_servidor_autenticacao_dns,							
						sa.te_ip_servidor_autenticacao,
						sa.id_tipo_protocolo,
						sa.nu_porta_servidor_autenticacao,										
						sa.nu_versao_protocolo,
						sa.te_atributo_identificador,
						sa.te_atributo_retorna_nome,
						sa.te_atributo_retorna_email,
						sa.te_atributo_retorna_telefone
			   FROM 	servidores_autenticacao sa
			   WHERE 	sa.in_ativo='S'";
	$result  = mysql_query($query);
	
	while ($row = mysql_fetch_array($result))
		{
		// Comunica��o com o servidor de Autentica��o
		$ldap = ldap_connect($row['nm_servidor_autenticacao_dns'],$row['nu_porta_servidor_autenticacao']);
				
		ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION, $row['nu_versao_protocolo']);			
	
		if (ldap_errno($ldap) == 0) 
			{			
			$searchResults 			= ldap_search($ldap, '','('.$row['te_atributo_identificador'].'='.trim($_GET['nm_usuario_acesso']).')');				
			$ldapResults 			= ldap_get_entries($ldap, $searchResults);				
		
			$nm_nome_completo  		= $ldapResults[0][strtolower($row['te_atributo_retorna_nome'])][0];					
			$te_email 				= $ldapResults[0][strtolower($row['te_atributo_retorna_email'])][0];					
			$te_telefones_contato	= $ldapResults[0][strtolower($row['te_atributo_retorna_telefone'])][0];									

			if ($nm_nome_completo <> '')
				{
				$retorno .= ($retorno ? '_RC_' : '');
				$retorno .= $nm_nome_completo				 . '_FD_' .
							$te_email				 		 . '_FD_' .							
							$te_telefones_contato	 		 . '_FD_' .														
							''								 . '_FD_' .
							''								 . '_FD_' .
							''								 . '_FD_' .							
							''								 . '_FD_' .
							$row['nm_servidor_autenticacao'] . '_FD_' .														
							$row['id_servidor_autenticacao'];
				}
			}
		}
	echo $retorno;	
	}
?>