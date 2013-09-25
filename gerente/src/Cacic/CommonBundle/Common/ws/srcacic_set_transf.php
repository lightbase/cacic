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
 Esse script tem como objetivo receber as informações referentes às transferências de arquivos efetuadas durante a sessão/conexão de suporte remoto.
*/
require_once('../include/common_top.php');

// Valido a Palavra-Chave e monto a tripa com os nomes e ids dos servidores de autenticação
if ($strTePalavraChave == $arrDadosComputador['te_palavra_chave'])
	{
	conecta_bd_cacic();	

	$query_INSERT = "INSERT INTO srcacic_transfs (	dt_systemtime,
													nu_duracao,
													te_path_origem,
													te_path_destino,
													nm_arquivo,
													nu_tamanho_arquivo,
													cs_status,
													cs_operacao,
													id_conexao)
					 VALUES						  '".DeCrypt($_POST['dt_systemtime']		,$v_cs_cipher,$v_cs_compress,$strPaddingKey)."',
												   ".DeCrypt($_POST['nu_duracao']			,$v_cs_cipher,$v_cs_compress,$strPaddingKey).",
												  '".DeCrypt($_POST['te_path_origem']		,$v_cs_cipher,$v_cs_compress,$strPaddingKey)."',								
												  '".DeCrypt($_POST['te_path_destino']		,$v_cs_cipher,$v_cs_compress,$strPaddingKey)."',																
												  '".DeCrypt($_POST['nm_arquivo']			,$v_cs_cipher,$v_cs_compress,$strPaddingKey)."',																
												   ".DeCrypt($_POST['nu_tamanho_arquivo']	,$v_cs_cipher,$v_cs_compress,$strPaddingKey).",
												  '".DeCrypt($_POST['cs_status']			,$v_cs_cipher,$v_cs_compress,$strPaddingKey)."',																								
												  '".DeCrypt($_POST['cs_operacao']			,$v_cs_cipher,$v_cs_compress,$strPaddingKey)."',"
												    .DeCrypt($_POST['id_conexao']			,$v_cs_cipher,$v_cs_compress,$strPaddingKey); 								;
	$result_INSERT = mysql_query($query_INSERT);
	$strXML_Values .= '<OK>'	. EnCrypt('OK',$v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey)	. '</OK>';		
	$strXML_Values .= '<STATUS>'	. EnCrypt('OK',$v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey)	. '</STATUS>';	
	}
else
	$strXML_Values .= '<STATUS>'.EnCrypt('Palavra-Chave Incorreta!',$v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey).'</STATUS>';

require_once('../include/common_bottom.php');
?>