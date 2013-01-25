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

 Objetivo:
 ---------
 Esse script tem como objetivo enviar ao servidor de suporte remoto na estação as configurações (em XML) que são específicas para a 
 estação em questão. São levados em consideração a rede do agente, sistema operacional e Mac-Address.
*/
require_once('../include/common_top.php');

// Valido a Palavra-Chave e monto a tripa com os nomes e ids dos servidores de autenticação
if ($strTePalavraChave == $arrDadosComputador['te_palavra_chave'])
	{
	if ($_POST['nm_usuario_cli'] && $_POST['te_senha_cli'])
		{
		$nm_usuario_cli 		= DeCrypt($_POST['nm_usuario_cli'],$v_cs_cipher,$v_cs_compress,$strPaddingKey); 					
		$te_senha_cli	  		= DeCrypt($_POST['te_senha_cli'],$v_cs_cipher,$v_cs_compress,$strPaddingKey); 			

		// Autentico o usuário, verificando nome, senha e local
		$arrUsuario = getValores('usuarios','id_usuario,
								  			 nm_usuario_completo,
											 id_local,
											 id_grupo_usuarios,
											 te_locais_secundarios,
											 id_servidor_autenticacao,
											 id_usuario_ldap,
											 PASSWORD("'.$te_senha_cli.'") as te_senha_cli,
											 te_senha,
											 te_emails_contato','"'.$nm_usuario_cli.'" IN (nm_usuario_acesso,id_usuario_ldap)');
		if ($arrUsuario[0]['id_usuario']<>'') // O usuário existe na Base CACIC
			{	
			// **************************************************************************************************************
			// ** VERIFICAR EXISTÊNCIA DO USUÁRIO NA BASE DO CACIC COM O NOME FORNECIDO (tanto para acesso CACIC quanto LDAP)
			// ** 		Se Existe
			// **			Verificar se o usuário está associado a um Servidor de Autenticação
			// ** 				Se Estiver Associado
			// **					Verificar se usuário e senha fazem BIND no Servidor de Autenticação
			// **						Se Fazem BIND
			// **							Sincronizar senhas LDAP x CACIC
			// **							Retornar USUÁRIO AUTENTICADO
			// **						Se Não Fazem BIND
			// **							Retornar USUÁRIO NÃO AUTENTICADO
			// **				Se Não Estiver Associado
			// **					Verificar se senha confere com Base CACIC
			// **						Se Confere
			// **							Retornar USUÁRIO AUTENTICADO
			// **						Se Não Confere
			// **							Retornar USUÁRIO NÃO AUTENTICADO
			// ** 		Se Não Existe
 			// **			Retornar USUÁRIO NÃO AUTENTICADO			
			// ***************************************************************************************************************
			
			//
			$nm_usuario_completo = '';
			
			if ($arrUsuario[0]['id_servidor_autenticacao'] <> 0)
				{
				$arrAutenticaLDAP = AutenticaLDAP($arrUsuario[0]['id_servidor_autenticacao'], $arrUsuario[0]['id_usuario_ldap'],$te_senha_cli);				
				$nm_usuario_completo = $arrAutenticaLDAP['nm_nome_completo'];
				}
			elseif (trim($arrUsuario[0]['te_senha']) == trim($arrUsuario[0]['te_senha_cli']))
				$nm_usuario_completo = $arrUsuario[0]['nm_usuario_completo'];				

			if ($nm_usuario_completo <> '')				
				{
				$boolIdLocal = stripos2(trim($arrUsuario[0]['te_locais_secundarios']),$arrDadosRede['id_local'],false);
	
				// Caso o usuario tenha como local primario o local do computador ou
				// Caso o usuario seja do nivel "Administracao" ou
				// Caso o usuario tenha como local secundario o local do computador.
				if ($arrUsuario[0]['id_local'] == $arrDadosRede['id_local'] ||$arrUsuario[0]['id_grupo_usuarios'] == '2' || $boolIdLocal)
					{								
					$id_sessao	  			   = DeCrypt($_POST['id_sessao'],$v_cs_cipher,$v_cs_compress,$strPaddingKey); 							
					$id_usuario_cli 	   	   = $arrUsuario[0]['id_usuario'];
					$te_motivo_conexao 		   = DeCrypt($_POST['te_motivo_conexao'],$v_cs_cipher,$v_cs_compress,$strPaddingKey); 																			
					$te_documento_referencial  = DeCrypt($_POST['te_documento_referencial'],$v_cs_cipher,$v_cs_compress,$strPaddingKey);
	
					$dt_hr_autenticacao	 	   = date('Y-m-d H:i:s');	
					//GravaTESTES('AuthClient: dt_hr_autenticacao => '.$dt_hr_autenticacao); 																		
					$dt_hr_inicio_sessao	   = date('d-m-Y') . ' às ' . date('H:i') . 'h';
					//GravaTESTES('AuthClient: dt_hr_inicio_sessao => '.$dt_hr_inicio_sessao); 																		
					// Identifico o SO da máquina visitante
					$arrIdSO = getValores('so','id_so','trim(te_so) = "'.trim($te_so_cli).'"');
					
					if ($arrIdSO[0]['id_so'] == '')
						{
						conecta_bd_cacic();
	
						// Insiro a informação na tabela de Sistemas Operacionais incrementando o Identificador Externo
						$queryINS  = 'INSERT 
									  INTO 		so(te_desc_so,sg_so,te_so) 
									  VALUES    ("S.O. a Cadastrar","Sigla a Cadastrar","'.$pStrTeSOnew.'")';

						$resultINS = mysql_query($queryINS);		
						$arrIdSO = getValores('so','id_so','trim(te_so) = "'.trim($te_so_cli).'"');						
						}
						
					$query_SESSAO = "INSERT INTO srcacic_conexoes 
												(id_sessao,
												 id_usuario_cli,
												 te_documento_referencial,
												 te_motivo_conexao,
												 dt_hr_ultimo_contato,
												 dt_hr_inicio_conexao,
												 id_so_cli)											
									VALUES ("  . $id_sessao 				. ", 
											"  . $id_usuario_cli 		. ",
											'" . $te_documento_referencial . "',
											'" . $te_motivo_conexao . "',										
											'" . $dt_hr_autenticacao		. "',
											'" . $dt_hr_autenticacao		. "',
											 " . $arrIdSO[0]['id_so']			.")";								
					$result_SESSAO = mysql_query($query_SESSAO);
					
					$query_CONEXAO = "SELECT 	id_conexao
									  FROM		srcacic_conexoes 
									  WHERE		id_sessao = ". $id_sessao			. " AND
												id_usuario_cli = ".$id_usuario_cli	. " AND
												dt_hr_inicio_conexao = '".$dt_hr_autenticacao	. "'";								
					$result_CONEXAO = mysql_query($query_CONEXAO);
					$row_CONEXAO	= mysql_fetch_array($result_CONEXAO);
						
					$strXML_Values .= '<ID_USUARIO_CLI>'		. EnCrypt(trim($arrUsuario[0]['id_usuario'])	,$v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey)	. '</ID_USUARIO_CLI>';		
					$strXML_Values .= '<NM_USUARIO_COMPLETO>'	. EnCrypt($nm_usuario_completo				,$v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey)	. '</NM_USUARIO_COMPLETO>';								
					$strXML_Values .= '<DT_HR_INICIO_SESSAO>'	. EnCrypt($dt_hr_inicio_sessao			   	,$v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey)	. '</DT_HR_INICIO_SESSAO>';												
					$strXML_Values .= '<ID_CONEXAO>'			. EnCrypt($row_CONEXAO['id_conexao']	   	,$v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey)	. '</ID_CONEXAO>';																
					$strXML_Values .= '<STATUS>' 				. EnCrypt('S'								,$v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey) 	. '</STATUS>';							
			
					if ($arrUsuario[0]['te_emails_contato'] <> '')
						{
						// Envio e-mail informando da abertura de sessão
						$corpo_mail = "Prezado usuário(a) ".$nm_usuario_completo.",\n\n
										informamos que foi realizada autenticação de acesso para Suporte Remoto Seguro à estação '".$arrDadosComputador['te_nome_computador']."' (IP: ".$arrDadosComputador['te_ip_computador'].") através do Sistema CACIC em ".$dt_hr_inicio_sessao . " a partir de seu usuário '".$nm_usuario_cli.".'\n\n\n\n
										_______________________________________________________________________
									CACIC - Configurador Automático e Coletor de Informações Computacionais\n
									srCACIC - Módulo para Suporte Remoto Seguro do Sistema CACIC\n
									Desenvolvido pela Dataprev - Unidade Regional Espírito Santo";
	
						// Manda mail para os administradores.
						mail($arrUsuario[0]['te_emails_contato'], "Sistema CACIC - Módulo srCACIC - Autenticação para Suporte Remoto Seguro", "$corpo_mail", "From: cacic@{$_SERVER['SERVER_NAME']}");
						}										
					}
				else
					$strXML_Values .= '<STATUS>'.EnCrypt('Usuário Sem Permissão de Suporte Remoto Nesta SubRede',$v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey).'</STATUS>';	
				}
			else
				$strXML_Values .= '<STATUS>'.EnCrypt('Usuário Não Autenticado',$v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey).'</STATUS>';			}
		else
			$strXML_Values .= '<STATUS>'.EnCrypt('Usuário Não Autenticado',$v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey).'</STATUS>';
		}
	}
else
	$strXML_Values .= '<STATUS>'.EnCrypt('Palavra Chave Incorreta!',$v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey).'</STATUS>';
	
require_once('../include/common_bottom.php');
?>