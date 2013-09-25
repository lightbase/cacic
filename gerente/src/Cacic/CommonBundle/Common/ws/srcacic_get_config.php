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

 Objetivo:
 ---------
 Esse script tem como objetivo enviar ao servidor de suporte remoto na esta��o(srcacic.exe) as configura��es (em XML) que s�o espec�ficas para a 
 esta��o/usu�rio em quest�o. S�o levados em considera��o a rede do agente, sistema operacional e Mac-Address.
 
 Retorno:
 1) <SERVIDORES_AUTENTICACAO>Servidores de Autentica��o cadastrados no Gerente WEB. O servidor de autentica��o referente � subrede da esta��o ser� acrescido de "*".</SERVIDORES_AUTENTICACAO>
 2) <STATUS>Retornar� OK se a palavra chave informada "bater" com a chave armazenada previamente no BD</STATUS>
*/

require_once('../include/common_top.php');

// Valido a Palavra-Chave e monto a tripa com os nomes e ids dos servidores para autentica��o
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