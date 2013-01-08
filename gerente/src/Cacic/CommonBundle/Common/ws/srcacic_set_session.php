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

function criaSessao($p_dt_hr_inicio_sessao,
					$p_nm_nome_acesso_autenticacao,
					$p_nm_nome_completo,
					$p_te_email,
					$p_id_computador)
	{				
	if ($p_te_email <> '')
		{
		// Envio e-mail informando da abertura de sessão
		$corpo_mail = "Prezado usuário(a) ".$p_nm_nome_completo.",\n\n
						informamos que foi iniciada uma sessão para Suporte Remoto Seguro através do Sistema CACIC em ".$p_dt_hr_inicio_sessao . "\n\n\n\n
							_______________________________________________________________________
							CACIC - Configurador Automático e Coletor de Informações Computacionais\n
							srCACIC - Módulo para Suporte Remoto Seguro do Sistema CACIC\n
							Desenvolvido pela Dataprev - Unidade Regional Espírito Santo";

		//GravaTESTES('SetSession: Enviando Email...');													
		// Manda mail para os administradores.
		//mail("$p_te_email", "Sistema CACIC - Módulo srCACIC - Abertura de Sessão para Suporte Remoto Seguro", "$corpo_mail", "From: cacic@{$_SERVER['SERVER_NAME']}");
		}			

	conecta_bd_cacic();
	
	$query_SESSAO = "INSERT INTO srcacic_sessoes 
								(	dt_hr_inicio_sessao,
								 	nm_acesso_usuario_srv,
								 	nm_completo_usuario_srv,
								 	te_email_usuario_srv,													 
								 	id_computador,
								 	dt_hr_ultimo_contato)
					  VALUES ('" . $p_dt_hr_inicio_sessao			. "', 
							  '" . $p_nm_nome_acesso_autenticacao	. "',
							  '" . $p_nm_nome_completo 				. "',									
							  '" . $p_te_email 						. "',																					
							   " . $p_id_computador					. ",
							  '" . $p_dt_hr_inicio_sessao			. "')";
//GravaTESTES('Cria Sessao: '.$query_SESSAO);
	$result_SESSAO = mysql_query($query_SESSAO);			
	}
	
	
$strErrorMessage    = '';

// Valido a Palavra-Chave e monto a tripa com os nomes e ids dos servidores de autenticação
if ($strTePalavraChave == $arrDadosComputador['te_palavra_chave'])
	{
	$id_sessao	= DeCrypt($key,$iv,$_POST['id_sessao'],$v_cs_cipher,$v_cs_compress,$strPaddingKey); 	
	conecta_bd_cacic();	
	if (!$id_sessao)
		{			
		// Identificador para Autenticação no Servidor de Autenticação
		$id_servidor_autenticacao	  	= DeCrypt($key,$iv,$_POST['id_servidor_autenticacao']		,$v_cs_cipher,$v_cs_compress,$strPaddingKey); 
		$nm_nome_acesso_autenticacao  	= DeCrypt($key,$iv,$_POST['nm_nome_acesso_autenticacao']	,$v_cs_cipher,$v_cs_compress,$strPaddingKey); 
		$te_senha_acesso_autenticacao 	= DeCrypt($key,$iv,$_POST['te_senha_acesso_autenticacao']	,$v_cs_cipher,$v_cs_compress,$strPaddingKey); 
				
		$nm_nome_completo  				= '';					
		$dt_hr_inicio_sessao			= date('Y-m-d H:i:s');								
		if ($id_servidor_autenticacao == '0')
			{
			$nm_nome_completo = $nm_nome_acesso_autenticacao . ' (Não Autenticado)';									
			criaSessao($dt_hr_inicio_sessao,
					   $nm_nome_acesso_autenticacao,
					   $nm_nome_completo,
					   $te_email,
					   $arrDadosComputador['id_computador']);			
			}
		else
			{	
			$arrAutenticaLDAP = AutenticaLDAP($id_servidor_autenticacao, $nm_nome_acesso_autenticacao, $te_senha_acesso_autenticacao);
				
			if ($arrAutenticaLDAP['nm_nome_completo'] <> '')
				{
				$nm_nome_completo = $arrAutenticaLDAP['nm_nome_completo'];
				criaSessao($dt_hr_inicio_sessao,
						   $nm_nome_acesso_autenticacao,
						   $nm_nome_completo,
						   $arrAutenticaLDAP['te_email'],
						   $arrDadosComputador['id_computador']);
				}
			}

		if ($nm_nome_completo <> '')
			{
			$arrSessoes = getValores('srcacic_sessoes','id_sessao','dt_hr_inicio_sessao="'.$dt_hr_inicio_sessao.'" AND
																	id_computador='.$arrDadosComputador['id_computador']);

			$strXML_Values .= '<NM_COMPLETO>'.EnCrypt($key,$iv,$nm_nome_completo,$v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey).'</NM_COMPLETO>';						
			$strXML_Values .= '<ID_SESSAO>'.EnCrypt($key,$iv,$arrSessoes['id_sessao'],$v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey).'</ID_SESSAO>';
			}			
		else
			$strErrorMessage = 'U.N.A.'; // Usuário Não Autenticado
		}
	else
		{			
		$query_SESSAO = "UPDATE srcacic_sessoes 
						 SET	dt_hr_ultimo_contato = '".date('Y-m-d H:i:s')."'
						 WHERE  id_sessao = ".$id_sessao;												
		$result_SESSAO = mysql_query($query_SESSAO);			

		conecta_bd_cacic();			
		if ($_POST['te_mensagem']<>'')
			{
			$id_conexao 	= DeCrypt($key,$iv,$_POST['id_conexao']		,$v_cs_cipher,$v_cs_compress,$strPaddingKey); 								
			$te_mensagem   	= DeCrypt($key,$iv,$_POST['te_mensagem']	,$v_cs_cipher,$v_cs_compress,$strPaddingKey); 														
			$cs_origem   	= DeCrypt($key,$iv,$_POST['cs_origem']		,$v_cs_cipher,$v_cs_compress,$strPaddingKey); 																	
			
			$query_CHAT = "INSERT INTO srcacic_chats(id_conexao,dt_hr_mensagem,te_mensagem,cs_origem)
			 					  VALUES (".$id_conexao.",'".date('Y-m-d H:i:s')."','".$te_mensagem."','".$cs_origem."')";
			$result_CHAT = mysql_query($query_CHAT);			
			}
		else
			{
			// Verifico se o registro de conexao eh valido
			// Neste caso, testo se o valor que chega refere-se a "0" criptografado com uma determinada chave...
			if ($_POST['id_conexao'] <> 'pibWRa7Dc7gciUJjHEB4Ww==')
				{
				$arr_id_usuario_cli = explode('[REG]',DeCrypt($key,$iv,$_POST['id_usuario_cli']	,$v_cs_cipher,$v_cs_compress,$strPaddingKey));
				$arr_id_conexao		= explode('[REG]',DeCrypt($key,$iv,$_POST['id_conexao']		,$v_cs_cipher,$v_cs_compress,$strPaddingKey));		
				$arr_te_so_cli 	   	= explode('[REG]',DeCrypt($key,$iv,$_POST['te_so_cli']	 	,$v_cs_cipher,$v_cs_compress,$strPaddingKey));
				
				for ($i=0; $i < count($arr_id_usuario_cli); $i++)
					{
					$te_so_cli 		= $arr_te_so_cli[$i]; 								
					$id_conexao		= $arr_id_conexao[$i]; 											
					$arr_id_so_cli	= getValores('so','id_so','trim(te_so)="'.trim($te_so_cli).'"');

					/*
					E se o tecnico utilizar um notebook externo ao parque computacional da corporacao?
					Nao insiro a maquina do visitante...
					*/
					$query_SESSAO = "UPDATE srcacic_conexoes 
									 SET	dt_hr_ultimo_contato = '".date('Y-m-d H:i:s')."'							 		
									 WHERE  id_sessao  = ".$id_sessao ." and
											id_conexao = ".$id_conexao;
					$result_SESSAO = mysql_query($query_SESSAO);					
					}
				}
			}
		
		$strXML_Values .= '<OK>' 	 . EnCrypt($key,$iv,'OK', $v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey) . '</OK>';					
		$strXML_Values .= '<STATUS>' . EnCrypt($key,$iv,'S' , $v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey) . '</STATUS>';							
		}
	}
else
	$strXML_Values .= '<STATUS>'.EnCrypt($key,$iv,'Palavra-Chave Incorreta!',$v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey).'</STATUS>';	
	
require_once('../include/common_bottom.php');
?>