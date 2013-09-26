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

if (trim(DeCrypt($_POST['ModuleProgramName'],$v_cs_cipher,$v_cs_compress,$strPaddingKey)) == 'mapacacic.exe')
	{
	if ($_POST['te_operacao'] == 'CheckVersion')
		{
		if (file_exists(CACIC_PATH . CACIC_PATH_RELATIVO_DOWNLOADS . 'versions_and_hashes.ini'))
			{
			$arrVersionsAndHashes = parse_ini_file(CACIC_PATH . CACIC_PATH_RELATIVO_DOWNLOADS . 'versions_and_hashes.ini');

			if ($arrVersionsAndHashes['mapacacic.exe_HASH'] <> $_POST['MAPACACIC_EXE_HASH'])
				$strXML_Values	 	.= '[MAPACACIC.EXE_HASH]'.$arrVersionsAndHashes['mapacacic.exe_HASH'].'[/MAPACACIC.EXE_HASH]';	
			}
		}			
	elseif ($_POST['te_operacao'] == 'Autentication')
		{
		// Autentica��o do agente MapaCacic.exe:
		autentica_agente($strPaddingKey);
		
		// Essa condi��o testa se a chamada trouxe o valor de cs_mapa-cacic, enviado por MapaCacic.exe
		if (trim(DeCrypt($_POST['ModuleProgramName'],$v_cs_cipher,$v_cs_compress,$strPaddingKey)) == 'mapacacic.exe')
			{								
			$strSelect 	= "	a.id_usuario,
							a.nm_usuario_completo,
							a.id_local,
							a.te_locais_secundarios,
							c.sg_local";		 
							
			$strFrom	= "	usuarios a, 
							locais c";
						                             						  
			$strWhere 	= "	(a.id_local = c.id_local OR ('," . $arrDadosRede[0]['id_local'] . ",' in (CONCAT(',',a.te_locais_secundarios,',')))) AND 
							a.nm_usuario_acesso = '". trim(DeCrypt($_POST['nm_acesso'],$v_cs_cipher,$v_cs_compress,$strPaddingKey)) ."' AND 							
							a.te_senha = ";
									
			// Solu��o tempor�ria, at� total converg�ncia para vers�es 4.0.2 ou maior de MySQL 
			// Anderson Peterle - Dataprev/ES - 04/09/2006
			$v_AUTH_SHA1 	 = " SHA1('". trim(DeCrypt($_POST['te_senha'],$v_cs_cipher,$v_cs_compress,$strPaddingKey)) ."')";
			$v_AUTH_PASSWORD = " PASSWORD('". trim(DeCrypt($_POST['te_senha'],$v_cs_cipher,$v_cs_compress,$strPaddingKey)) ."')";	
		
			// Para MySQL 4.0.2 ou maior	
			// Anderson Peterle - Dataprev/ES - 04/09/2006
			$arrUsuario = getArrFromSelect( $strFrom,
											$strSelect,
											$strWhere . $v_AUTH_SHA1);

			if ($arrUsuario[0]['id_usuario'] == '')
				{
				// Para MySQL at� 4.0	
				// Anderson Peterle - Dataprev/ES - 04/09/2006		
				$arrUsuario = getArrFromSelect( $strFrom,
												$strSelect,												
												$strWhere . $v_AUTH_PASSWORD);
				}
			
			if ($arrUsuario[0]['id_usuario'] <> '')
				{
				$strXML_Values .= '[ID_USUARIO]'			. EnCrypt($arrUsuario[0]['id_usuario'],$v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey) . '[/ID_USUARIO]';								
				$strXML_Values .= '[NM_USUARIO_COMPLETO]'	. EnCrypt($arrUsuario[0]['nm_usuario_completo'].' ('.$arrUsuario[0]['sg_local'].')',$v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey) . '[/NM_USUARIO_COMPLETO]';										
				GravaLog('ACE',$_SERVER['SCRIPT_NAME'],'acesso',$arrUsuario[0]["id_usuario"]);						
				}
			}
		}
	}	
require_once('../include/common_bottom.php');
?>