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
require_once('../include/common_top.php');

// Para agilizar o processo de verifica��o da vers�o...
$strMapaCacicHash	= DeCrypt($key,$iv,$_POST['MAPACACIC.EXE_HASH']	  ,$v_cs_cipher,$v_cs_compress,$strPaddingKey); 

// Diferenciar entre opera��o de Autentica��o e de Acesso
$te_operacao		= DeCrypt($key,$iv,$_POST['te_operacao']	  ,$v_cs_cipher,$v_cs_compress,$strPaddingKey); 

$boolVersaoCorreta   = true;

if (file_exists(CACIC_PATH . CACIC_PATH_RELATIVO_DOWNLOADS . 'versions_and_hashes.ini'))
	{
	$arrVersionsAndHashes = parse_ini_file(CACIC_PATH . CACIC_PATH_RELATIVO_DOWNLOADS . 'versions_and_hashes.ini');

	if ($arrVersionsAndHashes['mapacacic.exe_HASH'] <> $strMapaCacicHash)
		{
		$strXML_Values	 	.= '<MAPACACIC.EXE_HASH>'.EnCrypt($key,$iv,$arrVersionsAndHashes['mapacacic.exe_HASH'],$v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey).'</MAPACACIC.EXE_HASH>';	
		$boolVersaoCorreta   = false;
		}
	}

if ($boolVersaoCorreta && $te_operacao == 'Autentication')
	{
	// Autentica��o do agente MapaCacic.exe:
	autentica_agente($key,$iv,$v_cs_cipher,$v_cs_compress,$strPaddingKey);
	
	$strCsMapaCACIC = trim(DeCrypt($key,$iv,$_POST['cs_MapaCacic'],$v_cs_cipher,$v_cs_compress,$strPaddingKey));

	// Essa condi��o testa se a chamada trouxe o valor de cs_mapa-cacic, enviado por MapaCacic.exe
	if ($strCsMapaCACIC =='S')
		{	
		// Foi retirada a obrigatoriedade de N�vel "T�cnico" em Outubro/2009
		$arrDadosComputador = getDadosComputador(DeCrypt($key,$iv,$_POST['te_node_address']		,$v_cs_cipher,$v_cs_compress,$strPaddingKey),
												 DeCrypt($key,$iv,$_POST['te_so']		   		,$v_cs_cipher,$v_cs_compress,$strPaddingKey),
												 DeCrypt($key,$iv,$_POST['te_ip_computador']	,$v_cs_cipher,$v_cs_compress,$strPaddingKey),
												 DeCrypt($key,$iv,$_POST['te_nome_computador']	,$v_cs_cipher,$v_cs_compress,$strPaddingKey),
												 DeCrypt($key,$iv,$_POST['te_dominio_dns']		,$v_cs_cipher,$v_cs_compress,$strPaddingKey),
												 DeCrypt($key,$iv,$_POST['te_dominio_windows']	,$v_cs_cipher,$v_cs_compress,$strPaddingKey),										 
												 DeCrypt($key,$iv,$_POST['te_user_name']		,$v_cs_cipher,$v_cs_compress,$strPaddingKey),
												 DeCrypt($key,$iv,$_POST['te_workgroup']		,$v_cs_cipher,$v_cs_compress,$strPaddingKey));
										 
		$arrDadosRede 		= getDadosRede($arrDadosComputador['id_rede']);										 
		conecta_bd_cacic();
		$qry_usuario = "SELECT 	a.id_usuario,
								a.nm_usuario_completo,
								a.id_local,
								a.te_locais_secundarios,
								c.sg_local
						FROM 	usuarios a, 
								locais c
						WHERE 	a.nm_usuario_acesso = '". trim(DeCrypt($key,$iv,$_POST['nm_acesso'],$v_cs_cipher,$v_cs_compress,$strPaddingKey)) ."' AND 
								((a.id_local = c.id_local) OR (" . $arrDadosRede['id_local'] . " in (a.te_locais_secundarios))) AND 
								a.te_senha = ";

		// Solu��o tempor�ria, at� total converg�ncia para vers�es 4.0.2 ou maior de MySQL 
		// Anderson Peterle - Dataprev/ES - 04/09/2006
		$v_AUTH_SHA1 	 = " SHA1('". trim(DeCrypt($key,$iv,$_POST['te_senha'],$v_cs_cipher,$v_cs_compress,$strPaddingKey)) ."')";
		$v_AUTH_PASSWORD = " PASSWORD('". trim(DeCrypt($key,$iv,$_POST['te_senha'],$v_cs_cipher,$v_cs_compress,$strPaddingKey)) ."')";	
	
		// Para MySQL 4.0.2 ou maior	
		// Anderson Peterle - Dataprev/ES - 04/09/2006
		$query = $qry_usuario . $v_AUTH_SHA1; 
		$result_qry_usuario = mysql_query($query);
		if (mysql_num_rows($result_qry_usuario)<=0)
			{
			// Para MySQL at� 4.0	
			// Anderson Peterle - Dataprev/ES - 04/09/2006		
			$query = $qry_usuario . $v_AUTH_PASSWORD;
			$result_qry_usuario = mysql_query($query);
			}
		
		if (mysql_num_rows($result_qry_usuario)>0)
			{
			$row = mysql_fetch_array($result_qry_usuario);
			$strXML_Values .= '<ID_USUARIO>'			. EnCrypt($key,$iv,$row['id_usuario']									,$v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey) . '</ID_USUARIO>';								
			$strXML_Values .= '<NM_USUARIO_COMPLETO>'	. EnCrypt($key,$iv,$row['nm_usuario_completo'].' ('.$row['sg_local'].')',$v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey) . '</NM_USUARIO_COMPLETO>';										
			GravaLog('ACE',$_SERVER['SCRIPT_NAME'],'acesso',$row["id_usuario"]);						
			}

		}
	}
	
$strXML_Values .= '<STATUS>' . EnCrypt($key,$iv,'S', $v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey) . '</STATUS>';			
require_once('../include/common_bottom.php');
?>