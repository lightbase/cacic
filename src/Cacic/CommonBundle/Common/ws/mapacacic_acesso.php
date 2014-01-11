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
		// Autenticação do agente MapaCacic.exe:
		autentica_agente($strPaddingKey);
		
		// Essa condição testa se a chamada trouxe o valor de cs_mapa-cacic, enviado por MapaCacic.exe
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
									
			// Solução temporária, até total convergência para versões 4.0.2 ou maior de MySQL 
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
				// Para MySQL até 4.0	
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