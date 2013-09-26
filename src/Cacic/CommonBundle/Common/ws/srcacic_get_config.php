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
 Esse script tem como objetivo enviar ao servidor de suporte remoto na estação(srcacic.exe) as configurações (em XML) que são específicas para a 
 estação/usuário em questão. São levados em consideração a rede do agente, sistema operacional e Mac-Address.
 
 Retorno:
 1) <SERVIDORES_AUTENTICACAO>Servidores de Autenticação cadastrados no Gerente WEB. O servidor de autenticação referente à subrede da estação será acrescido de "*".</SERVIDORES_AUTENTICACAO>
 2) <STATUS>Retornará OK se a palavra chave informada "bater" com a chave armazenada previamente no BD</STATUS>
*/

require_once('../include/common_top.php');

// Valido a Palavra-Chave e monto a tripa com os nomes e ids dos servidores para autenticação
if ($strTePalavraChave == $arrDadosComputador['te_palavra_chave'])
	{	
	conecta_bd_cacic();	
	$query_SEL	= 'SELECT		id_servidor_autenticacao,
								nm_servidor_autenticacao  
				   FROM			servidores_autenticacao
				   WHERE		in_ativo = "S" AND
				   			    id_servidor_autenticacao = '.$arrDadosRede['id_servidor_autenticacao'].' 
				   ORDER BY		nm_servidor_autenticacao';
	$result_SEL = mysql_query($query_SEL);
	
	$strTripaServidores = '';
	while ($row_SEL = mysql_fetch_array($result_SEL))
		$strTripaServidores .= $row_SEL['id_servidor_autenticacao'].';'.$row_SEL['nm_servidor_autenticacao'].($row_SEL['id_servidor_autenticacao']==$strIdServidor?'*':'').';';

	if ($strTripaServidores == '')
		$strTripaServidores = '0;0';	

	$strXML_Values .= '<SERVIDORES_AUTENTICACAO>'.EnCrypt($strTripaServidores  ,$v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey).'</SERVIDORES_AUTENTICACAO>';
	$strXML_Values .= '<STATUS>'				 .EnCrypt('OK'					,$v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey).'</STATUS>';
	}
else
	$strXML_Values .= '<STATUS>'.EnCrypt('Palavra-Chave Incorreta!',$v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey).'</STATUS>';	
?>