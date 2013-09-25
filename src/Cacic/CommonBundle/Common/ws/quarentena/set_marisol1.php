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
require_once('../include/library.php');
//Maquina Lu
/*autentica_agente($key,$iv,$_POST['cs_cipher']);
$te_node_address 			= DeCrypt($_POST['te_node_address'],$_POST['cs_cipher']); 
$id_so          			= DeCrypt($_POST['id_so']			,$_POST['cs_cipher']); 
*/ 
$te_node_address 			= "00-08-02-3F-CE-B9";
$id_so  					= "8";
$variavel = "1st Page 2000 2.00 Free#3A PDF to Text Batch Converter 2.00#Adobe Download Manager 2.0 (So remoção)#AutoIt v3.1.1#Back it up!#BDE Information Utility#Conference Client Uninstall#Citrix Web Client#Cobian Backup 7#CoGrOO 1.0#coLinux#ConTEXT#Filzip 3.05#Free Download Manager 1.9#hott notes 4#InfoTip Extension v2.0.4.106 (Unicode)(Remove Only)#Disk Array Management Program 2(GUI) 7.20#Intel(R) PROSet II#Windows XP Hotfix - KB834707#Windows XP Hotfix - KB867282#Microsoft Data Access Components KB870669#Windows XP Hotfix - KB873333#Windows XP Hotfix - KB873339#Atualizacao de Seguranca para Windows XP (KB883939)#Windows XP Hotfix - KB885250#Windows XP Hotfix - KB885835#Windows XP Hotfix - KB885836#Windows XP Hotfix - KB886185#Windows XP Hotfix - KB887742#Windows XP Hotfix - KB887797#Windows XP Hotfix - KB888113#Windows XP Hotfix - KB888302#Atualizacao de Seguranca para Windows XP (KB890046)#Windows XP Hotfix - KB890047#Windows XP Hotfix - KB890175#Windows XP Hotfix - KB890859#Windows XP Hotfix - KB891781#Atualizacao de Seguranca para Windows XP (KB893066)#Windows XP Hotfix - KB893086#Atualizacao de Seguranca para Windows XP (KB893756)#Windows Installer 3.1 (KB893803)#Atualizacao para Windows XP (KB894391)#Atualizacao de Seguranca para Windows XP (KB896358)#Atualizacao de Seguranca para Windows XP (KB896422)#Atualizacao de Seguranca para Windows XP (KB896423)#Atualizacao de Seguranca para Windows XP (KB896424)#Atualizacao de Seguranca para Windows XP (KB896428)#Atualizacao de Seguranca para Windows XP (KB896688)#Atualizacao para Windows XP (KB898461)#Atualizacao de Seguranca para Windows XP (KB899587)#Atualizacao de Seguranca para Windows XP (KB899589)#Atualizacao de Seguranca para Windows XP (KB899591)#Atualizacao para Windows XP (KB900485)#Atualizacao de Seguranca para Windows XP (KB900725)#Atualizacao para Windows XP (KB900930)#Atualizacao de Seguranca para Windows XP (KB901017)#Atualizacao de Seguranca para Windows XP (KB901190)#Atualizacao de Seguranca para Windows XP (KB901214)#Atualizacao de Seguranca para Windows XP (KB902400)#Atualizacao de Seguranca para Windows XP (KB904706)#Atualizacao para Windows XP (KB904942)#Atualizacao de Seguranca para Windows XP (KB905414)#Atualizacao de Seguranca para Windows XP (KB905749)#Atualizacao de Seguranca para Windows XP (KB905915)#Atualizacao de Seguranca para Windows XP (KB908519)#Atualizacao para Windows XP (KB908531)#Atualizacao para Windows XP (KB910437)#Atualizacao de Seguranca para Windows XP (KB911562)#Atualizacao de Seguranca para o Windows Media Player (KB911564)#Atualizacao de Seguranca para o Windows Media Player 10 (KB911565)#Atualizacao de Seguranca para Windows XP (KB911567)#Atualizacao de Seguranca para Windows XP (KB911927)#Atualizacao de Seguranca para Windows XP (KB912812)#Atualizacao de Seguranca para Windows XP (KB912919)#Atualizacao de Seguranca para Windows XP (KB913446)#Atualizacao de Seguranca para Windows XP (KB913580)#LeechFTP#Look@LAN 2.50 Build 35#Microsoft .NET Framework 1.1 Hotfix (KB886903)#Microsoft .NET Framework 1.1#Mozilla Firefox (1.5.0.4)#MWSnap 3#NDDigital n-Client#NINO 4.1.9#Novell Client for Windows#NVIDIA Windows 2000/XP Display Drivers#Trend Micro OfficeScan Client#Oracle JInitiator 1.3.1.9#Console do Oracle Web Conferencing#OrphansRemover version 1.8.9.36#Password Safe#PHP Editor#PrimoPDF#Intel(R) PRO Network Connections Drivers#PRTG Traffic Grapher#QuickTime#SciTE4Autoit3 4/13/2006#Skype 2.0#Spybot - Search  Destroy 1.4#Project1#Relativa Manager#Relativa IT-Manager®#Timon Linux#Relativa Manager (C:\Arquivos de programas\Relativa Manager\)#TaskSwitchXP#Vim 7.0 (self-installing)#VideoLAN VLC media player 0.8.4a#WebTrends Professional Suite#Windows Genuine Advantage Validation Tool#Windows Media Format Runtime#Windows Media Player 10#Windows XP Service Pack 2#GTK+ 2.6.4 runtime environment#PrimoPDF#HP OpenView Storage Data Protector A.05.50#Microsoft .NET Framework 1.1 Brazilian Portuguese Language Pack#Google Toolbar for Internet Explorer#
J2SE Runtime Environment 5.0 Update 4#BrOffice.org 2.0#WebFldrs XP#Check Point Session Authentication  NG#AutoIt v2.64#X-Win32#Windows Genuine Advantage v1.3.0254.0#CmdHere Powertoy For Windows XP#Disk Array Management Program 2(GUI) 7.20#Oasys Columbus#Check Point VPN-1 SecureClient NGX R60#UltraVNC v1.0.1#Adobe Reader 7.0.5 - Portugues#Check Point SmartConsole NGX R60#Microsoft .NET Framework 1.1#MSN Messenger 7.5#ActivePerl 5.8.8 Build 817#Disk Array Management Program 2(GUI) 9.51#OpenOffice.org.br 1.1.3#OpenOffice.org.br 1.1.3#testando";
echo $variavel;
$data = date('Y-m-d H:i:s'); 
conecta_bd_cacic();
// Verifico se o computador em questão já foi inserido anteriormente, e se não foi, insiro.
 /*$query = "SELECT count(*) as num_registros
          FROM versoes_softwares
										WHERE te_node_address = '" . $te_node_address . "'
										AND id_so = '" . $id_so . "'";
$result = mysql_query($query);
if (mysql_result($result, 0, "num_registros") == 0) {
					$query = "INSERT INTO versoes_softwares
															(te_node_address, id_so)
															VALUES ('" . $te_node_address . "', '" . $id_so . "'  )";
					$result = mysql_query($query);
} 

$query = "UPDATE versoes_softwares 
 									SET	te_versao_bde            = '" . DeCrypt($_POST['te_versao_bde']			,$_POST['cs_cipher']) . "', 
										te_versao_dao            = '" . DeCrypt($_POST['te_versao_dao']			,$_POST['cs_cipher']) . "', 
										te_versao_ado            = '" . DeCrypt($_POST['te_versao_ado']			,$_POST['cs_cipher']) . "', 
										te_versao_odbc           = '" . DeCrypt($_POST['te_versao_odbc']			,$_POST['cs_cipher']) . "', 
										te_versao_directx        = '" . DeCrypt($_POST['te_versao_directx']		,$_POST['cs_cipher']) . "', 
										te_versao_acrobat_reader = '" . DeCrypt($_POST['te_versao_acrobat_reader']	,$_POST['cs_cipher']) . "', 
										te_versao_ie             = '" . DeCrypt($_POST['te_versao_ie']				,$_POST['cs_cipher']) . "', 
										te_versao_mozilla        = '" . DeCrypt($_POST['te_versao_mozilla']		,$_POST['cs_cipher']) . "', 
										te_versao_jre            = '" . DeCrypt($_POST['te_versao_jre']			,$_POST['cs_cipher']) . "' 
	  							WHERE 	te_node_address    		 = '" . $te_node_address . "' and
										id_so                	 = '" . $id_so . "'";

$result = mysql_query($query);
*/


//$v_tripa_inventariados = str_replace("&quot;","'",DeCrypt($_POST['te_inventario_softwares'],$_POST['cs_cipher']));
// MARISOL - 20/06/06 - Acrescenta no final o #, caso nao seja o ultimo caracter
$Caracter = substr($variavel,strlen($variavel)-1,strlen($variavel));
if ($Caracter != "#"){
	$variavel = $variavel."#";
}

$v_tripa_inventariados = $variavel;
$v_tripa_inventariados = str_replace("/","",$v_tripa_inventariados);
$v_tripa_inventariados = str_replace("\\","",$v_tripa_inventariados);
$v_tripa_inventariados = str_replace("&apos;","^",$v_tripa_inventariados);

if ($v_tripa_inventariados<>'')
	{
	/*$queryDEL = "DELETE FROM softwares_inventariados_estacoes 
				 WHERE 	te_node_address = '".$te_node_address."' AND
						id_so = '".$id_so."'";					                 
	$result = mysql_query($queryDEL);*/
		
	$v_array_te_inventario_softwares = explode('#',$v_tripa_inventariados);	

	$query_inv = "SELECT *
				  FROM   softwares_inventariados";
	$result_inv = mysql_query($query_inv );
	$v_array_te_softwares_inventariados = array ();
	while ($v_reg_inv = mysql_fetch_array($result_inv))
		{	
		array_push($v_array_te_softwares_inventariados,$v_reg_inv['id_software_inventariado']);
		array_push($v_array_te_softwares_inventariados,trim($v_reg_inv['nm_software_inventariado']));		
		}	
	for ($v1=0; $v1 < count($v_array_te_inventario_softwares)-1; $v1 ++)		
		{
		$v_posicao = array_search(trim($v_array_te_inventario_softwares[$v1]), $v_array_te_softwares_inventariados);
		if ($v_posicao)
			{
			$v_achei = $v_array_te_softwares_inventariados[$v_posicao-1];
			}
		else
			{						
				$query = "INSERT INTO softwares_inventariados 
									  (nm_software_inventariado)											
						  VALUES 	  ('".trim($v_array_te_inventario_softwares[$v1])."')";
				$result = mysql_query($query);
			
			$v_achei = mysql_insert_id()+1;
			}
			// MARISOL (16/06/06) - Verifica se já existe o software na lista. Se não, cadastra.
			$Verificar = $Verificar.$v_achei.","; // Armazena todos os ids de software da máquina
			$query_compara = "Select * from softwares_inventariados_estacoes  where 
							  te_node_address='".$te_node_address."'and id_so='".$id_so."' and id_software_inventariado = '".$v_achei."'";
			$result_compara = mysql_query($query_compara);
			$linhas = mysql_num_rows($result_compara);
			if ($linhas == 0){				  	  
				$nome_instalados .= $v_array_te_inventario_softwares[$v1]."\n";
				$query = "INSERT INTO softwares_inventariados_estacoes 
									  (te_node_address,
									   id_so,
									   id_software_inventariado)											
						  VALUES 	  ('".$te_node_address."',
									   '".$id_so."',
									   '".$v_achei."')";					                  				
				$result = mysql_query($query);									
			 echo "Foi inserido: ".$v_achei." - ";
			 	
				//Historico (1 - Inserido)
				$queryInser = "INSERT INTO historico_softwares_inventariados_estacoes 
								  (te_node_address,
								   id_so,
								   id_software_inventariado,
								   data,
								   ind_acao								   
								   )											
					  VALUES  ('".$te_node_address."',
								   ".$id_so.",
								   ".$v_achei.",
								   '".$data."',
								   1)";					                  				
				echo "<br>Historico: ".$v_achei."<br>";						   
				$result = mysql_query($queryInser);	
			 
			} // MARISOL - fim alteração				
		}		
	}
	// MARISOL (16/06/06) - Deletar itens da tabela softwares_inventariados_estacoes e insere em Historico como (2 - deletado)
	$Verificar = substr($Verificar,0,strlen($Verificar)-1); //  MARISOL (16/06/06) - Variavel com todos os ids de software da máquina.
	
	// MARISOL (16/06/06) - Verifica todos os ids que não estao na lista mas estao na tabela
	$query_comparaVer = "Select * from softwares_inventariados_estacoes where 
							   te_node_address='".$te_node_address."'and id_so='".$id_so."' and 
							   id_software_inventariado Not in (".$Verificar.")";//
	$result_comparaVer = mysql_query($query_comparaVer);
	$linha_ = mysql_num_rows($result_comparaVer);
	//echo "<br>Encontrados ".$linha." para excluir<br>";					   
	
	//  MARISOL (16/06/06) - Armazena todos os registros encontrados
	while ($comparaVer = mysql_fetch_object($result_comparaVer)){
		$Deletar = $Deletar.$comparaVer->id_software_inventariado."-";
	}
	
	// MARISOL (16/06/06) - Prepara para excluir os registros da tabela softwares_inventariados_estacoes e inserir em Historico 
	$ListaDeletar = explode('-',$Deletar);
	for ($Di=0; $Di < count($ListaDeletar)-1; $Di ++){

		$query_inve = "SELECT * FROM softwares_inventariados where id_software_inventariado='".$ListaDeletar[$Di]."'";
		$result_inve = mysql_query($query_inve );	
		$deletado = mysql_fetch_object($result_inve);
		
		$nome_deletado .= $deletado->nm_software_inventariado."\n";
	
		$queryDEL = "DELETE FROM softwares_inventariados_estacoes 
				 	 WHERE 	te_node_address = '".$te_node_address."' AND
					 id_so = '".$id_so."' AND 
					 id_software_inventariado = '".$ListaDeletar[$Di]."'";					                 
		$result = mysql_query($queryDEL);
		
		$queryInser = "INSERT INTO historico_softwares_inventariados_estacoes 
								  (te_node_address,
								   id_so,
								   id_software_inventariado,
								   data,
								   ind_acao								   
								   )											
					  VALUES  ('".$te_node_address."',
								   ".$id_so.",
								   ".$ListaDeletar[$Di].",
								   '".$data."',
								   2)";					                  				
		echo "Deletado: ".$ListaDeletar[$Di];						   
		$result = mysql_query($queryInser);	
		
	}
	// Verifica se ouve mudança e manda e-mail	
	if (($linhas == 0) || ($linha_>0)){		
		//if (trim($destinatarios = get_valor_campo('configuracoes', 'te_notificar_mudanca_hardware')) != '') {
		// Envia e-mail quando ocorre mudança
		//Recupero informações sobre o computador, para montar o cabeçalho do e-mail.
		$query = "SELECT C.id_so,C.te_workgroup,C.te_dominio_windows,C.te_nome_computador, C.te_ip, C.te_ip, S.te_desc_so 
				  FROM computadores C,so S
				  WHERE C.te_node_address = '" . $te_node_address . "'
				  AND C.id_so = '" . $id_so . "'
				  AND S.id_so = C.id_so";
		$result = mysql_query($query);
		// if ($cont_aux > 0) { 
		$corpo_mail = "Prezado administrador. 	  
	Foi identificada uma alteração na configuração de software do seguinte computador:\n
	Nome...............: ". mysql_result( $result, 0, "te_nome_computador" ) ." 
	Endereço TCP/IP....: ". mysql_result( $result, 0, "te_ip" ) . "
	Rede...............: ". mysql_result( $result, 0, "te_ip" ) ."
	Sistema Operacional: ". mysql_result( $result, 0, "te_desc_so" ) ."
	Grupo...........: ". mysql_result( $result, 0, "te_workgroup" ) ." (". mysql_result( $result, 0, "te_dominio_windows" ) ." )\n
	Os Softwares abaixo foram instalados:\n".$nome_instalados."
	Os Softwares abaixo foram desinstalados:\n".$nome_deletado;
					   
		echo $corpo_mail;
		$destinatarios = "ropelato.e@marisol.com.br";
		// Manda mail para os administradores.	
		mail("$destinatarios", "Alteracao de Software Detectada", "$corpo_mail", "From: cacic@{$_SERVER['SERVER_NAME']}");
		//}
	}						   
$v_tripa_variaveis_coletadas = DeCrypt($_POST['te_variaveis_ambiente'],$_POST['cs_cipher']);	
while (substr(trim($v_tripa_variaveis_coletadas),0,1)=='=')	
	{
	$v_tripa_variaveis_coletadas = substr(trim($v_tripa_variaveis_coletadas),1);
	}

if ($v_tripa_variaveis_coletadas<>'')
	{
	$queryDEL = "DELETE FROM variaveis_ambiente_estacoes 
				 WHERE 	te_node_address = '".$te_node_address."' AND
						id_so = '".$id_so."'";					                  
	$result = mysql_query($queryDEL);									
	
	$v_array_te_variaveis_coletadas = explode('#',$v_tripa_variaveis_coletadas);	

	$query_var = "SELECT *
				  FROM   variaveis_ambiente";
	$result_var = mysql_query($query_var );

	$v_array_te_variaveis_ambiente_na_base = array ();
	while ($v_reg_var = mysql_fetch_array($result_var))
		{	
		array_push($v_array_te_variaveis_ambiente_na_base,$v_reg_var['id_variavel_ambiente']);
		array_push($v_array_te_variaveis_ambiente_na_base,strtolower(trim($v_reg_var['nm_variavel_ambiente'])));		
		}	
	for ($v1=0; $v1 < count($v_array_te_variaveis_coletadas)-1; $v1 ++)
		{
		$v_array_variavel_ambiente_tmp = explode('=',$v_array_te_variaveis_coletadas[$v1]);
		if (trim($v_array_variavel_ambiente_tmp[0])<>'')
			{			
			$v_posicao = array_search(strtolower(trim($v_array_variavel_ambiente_tmp[0])), $v_array_te_variaveis_ambiente_na_base);
			if ($v_posicao)
				{
				$v_achei = $v_array_te_variaveis_ambiente_na_base[$v_posicao-1];
				}
			else
				{			
				$query = "INSERT INTO variaveis_ambiente 
									  (nm_variavel_ambiente)											
						  VALUES 	  ('".strtolower(trim($v_array_variavel_ambiente_tmp[0]))."')";
				$result = mysql_query($query);

				$v_achei = mysql_insert_id();
				}
				$query = "INSERT INTO variaveis_ambiente_estacoes 
									  (te_node_address,
									   id_so,
									   id_variavel_ambiente,
									   vl_variavel_ambiente)											
						  VALUES 	  ('".$te_node_address."',
						  			   '".$id_so."',
									   '".$v_achei."',
									   '".trim($v_array_variavel_ambiente_tmp[1])."')";					                  
				$result = mysql_query($query);									
			}
		}		
	}

echo "<br>final";
?>