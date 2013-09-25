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
	 $itens = explode('A',$pergunta);

	  // O último elemento identificará o tipo de consulta: 
	  // 001 => HARDWARE
	  // 002 => SOFTWARE
	  // 003 => LICENCAS
	  // Ex.:  1A001  (Item "1"  de HARDWARE)
	  //      19A002  (Item "19" de SOFTWARE)
	  //       5A003  (Item "5"  de LICENÇAS)
	  
	  $QuantosRegistros = count($itens) - 1;

  	  $Para   = 0;
	  $select = "SELECT COUNT(*) as quantos"; 	  
	  $where  = "";

//require_once("../include/config.php");
//$conexao = mysql_connect($ip_servidor . ':' . $porta, $usuario_bd, $senha_usuario_bd);
//$bancodedados = mysql_select_db($nome_bd, $conexao);

//$query = "DELETE from testes";
//$result = mysql_query($query);

//$query = "INSERT INTO testes (te_linha,te_ip) VALUES ('pergunta=".$pergunta."',now())";
//$result = mysql_query($query);



      while ($Para < $QuantosRegistros) 
	  	{
	    $critica = $itens[$Para];
		if (trim($critica) != '') 
			{					
			if ($itens[$QuantosRegistros]=='001') // HARDWARE
				{
				switch (true) 
					{
		   			case ($critica == 1):
				      $critica = "CD-ROM";
				      $desc_tipo = "te_cdrom_desc";
					  break;
		   		   	case ($critica == 2):
				      $critica = "CPU";
				      $desc_tipo = "te_cpu_desc";
					  break;
		   		   	case ($critica == 3):
				      $critica = "Descrição BIOS";
				      $desc_tipo = "te_bios_desc";
					  break;
		   		   	case ($critica == 4):
				      $critica = "Descrição RAM";
				      $desc_tipo = "te_mem_ram_desc";
					  break;
		   		   	case ($critica == 5):
				      $critica = "Fabricante Placa Mãe";
				      $desc_tipo = "te_placa_mae_fabricante";
					  break;
		   		   	case ($critica == 6):
				      $critica = "Fabricante da BIOS";
				      $desc_tipo = "te_bios_fabricante";
					  break;
		   		   	case ($critica == 7):
				      $critica = "Fabricante CPU";
				      $desc_tipo = "te_cpu_fabricante";
					  break;
		   		   	case ($critica == 8):
				      $critica = "Memória Placa Vídeo";
				      $desc_tipo = "qt_placa_video_mem";
					  break;
		   		   	case ($critica == 9):
				      $critica = "Memória RAM";
				      $desc_tipo = "qt_mem_ram";
					  break;
		   		   	case ($critica == 10):
				      $critica = "Modem";
				      $desc_tipo = "te_modem_desc";
					  break;
		   		  	case ($critica == 11):
				      $critica = "Mouse";
				      $desc_tipo = "te_mouse_desc";
					  break;
		   		   	case ($critica == 12):
				      $critica = "Placa Mãe";
				      $desc_tipo = "te_placa_mae_desc";
					  break;
		   		   	case ($critica == 13):
				      $critica = "Placa de Rede";
				      $desc_tipo = "te_placa_rede_desc";
					  break;
		   		   	case ($critica == 14):
				      $critica = "Placa de Som";
				      $desc_tipo = "te_placa_som_desc";
					  break;
		   		   	case ($critica == 15):
				      $critica = "Placa de Vídeo";
				      $desc_tipo = "te_placa_video_desc";
					  break;
		   		   	case ($critica == 16):
				      $critica = "Qtd Cores Placa Vídeo";
				      $desc_tipo = "qt_placa_video_cores";
					  break;
		   		   	case ($critica == 17):
				      $critica = "Resolução Placa de Vídeo";
				      $desc_tipo = "te_placa_video_resolucao";
					  break;
		   		   	case ($critica == 18):
				      $critica = "Serial da CPU";
				      $desc_tipo = "te_cpu_serial";
					  break;
		   		   	case ($critica == 19):
				      $critica = "Teclado";
				      $desc_tipo = "te_teclado_desc";
					  break;
					}
				}
			elseif ($itens[$QuantosRegistros]=='002') // SOFTWARE
				{
  		        $desc_tipo = "nm_aplicativo";				
				switch (true) 
					{
		   			case ($critica == 1):
				      $critica = "OpenOffice.org 1.0.3";
					  break;
		   		   	case ($critica == 2):
				      $critica = "OpenOffice.org 1.1.0";
					  break;
		   		   	case ($critica == 3):
				      $critica = "OpenOffice.org 1.1.1a";
					  break;
		   		   	case ($critica == 4):
				      $critica = "OpenOffice.org 1.1.3";
					  break;
		   		   	case ($critica == 5):
				      $critica = "OpenOffice.org.br 1.1.3";
					  break;
		   		   	case ($critica == 6):
				      $critica = "Windows 95";
					  break;
		   		   	case ($critica == 7):
				      $critica = "Windows 95OSR2";
					  break;
		   		   	case ($critica == 8):
				      $critica = "Windows 98";
					  break;
		   		   	case ($critica == 9):
				      $critica = "Windows 98SE";
					  break;
		   		   	case ($critica == 10):
				      $critica = "Windows ME";
					  break;
		   		   	case ($critica == 11):
				      $critica = "Windows NT";
					  break;
		   		   	case ($critica == 12):
				      $critica = "Windows 2000";
					  break;
		   		   	case ($critica == 13):
				      $critica = "Windows XP";
					  break;
		   		   	case ($critica == 14):
				      $critica = "Office 97";
					  break;
		   		   	case ($critica == 15):
				      $critica = "Office 2000";
					  break;
		   		   	case ($critica == 16):
				      $critica = "Office 2003";
					  break;					  
					}
				}
			elseif ($itens[$QuantosRegistros]=='003') // LICENCAS
				{
  		        $desc_tipo = "te_licenca";								
				switch (true) 
					{
		   		   	case ($critica == 1):
				      $critica = "Windows 95";
					  break;
		   		   	case ($critica == 2):
				      $critica = "Windows 95 OSR2";
					  break;
		   		   	case ($critica == 3):
				      $critica = "Windows 98";
					  break;
		   		   	case ($critica == 4):
				      $critica = "Windows 98 SE";
					  break;
		   		   	case ($critica == 5):
				      $critica = "Windows ME";
					  break;
		   		   	case ($critica == 6):
				      $critica = "Windows NT";
					  break;
		   		   	case ($critica == 7):
				      $critica = "Windows 2000";
					  break;
		   		   	case ($critica == 8):
				      $critica = "Windows XP";
					  break;
		   		   	case ($critica == 9):
				      $critica = "Office 97";
					  break;
		   		   	case ($critica == 10):
				      $critica = "Office 2000";
					  break;
		   		   	case ($critica == 11):
				      $critica = "Office 2003";
					  break;					  
					}
				}
				
			if ($itens[$QuantosRegistros]=='001') // HARDWARE
				{
				$from = " FROM computadores "; 													
				}
			elseif ($itens[$QuantosRegistros]=='002') // SOFTWARE
				{
				$from = ' FROM perfis_aplicativos_monitorados perfis, aplicativos_monitorados apls ';
				$where = ' WHERE TRIM(perfis.nm_aplicativo) = "'.trim($critica).'" and perfis.id_aplicativo = apls.id_aplicativo and apls.te_versao <> "?" ';			
				}
			elseif ($itens[$QuantosRegistros]=='003') // LICENCAS
				{
				$from = ' FROM perfis_aplicativos_monitorados perfis, aplicativos_monitorados apls ';
				$where = ' WHERE TRIM(perfis.nm_aplicativo) = "'.trim($critica).'" and perfis.id_aplicativo = apls.id_aplicativo and apls.te_licenca <> "" ';							
				}										
			
			$sql = $select . $from . $where; 
//$query = "INSERT INTO testes (te_linha,te_ip) VALUES ('SQL1=".$sql."',now())";
//$result = mysql_query($query);
			
			$qySistemaOperacional = mysql_query($sql);
			$row = mysql_fetch_array($qySistemaOperacional);
			$TotalRegistro = $row[quantos];

			$sql = $select . "," . $desc_tipo; 
			$sql = $sql . $from . $where;
			$sql = $sql." GROUP BY ".$desc_tipo; 			
			if ($itens[$QuantosRegistros]=='003') // LICENCAS
				{
				$sql = $sql." ORDER BY quantos DESC "; 
				}										
				

//$query = "INSERT INTO testes (te_linha,te_ip) VALUES ('SQL2=".$sql."',now())";
//$result = mysql_query($query);
			
			$qySistemaOperacional = mysql_query($sql);

			$Registros = 0;
			while ($Registros < mysql_num_rows($qySistemaOperacional)) 
				{
				
				 $xml_resposta = $xml_resposta."<linha>";
				 $row = mysql_fetch_array($qySistemaOperacional);
				 $Percent = number_format(($row[quantos]*100)/$TotalRegistro,1,',','.');
				 if (trim($row[$desc_tipo]) == '')
				 	{					
					$xml_resposta = $xml_resposta."<descricao><![CDATA[Não identificado]]></descricao>"; 
					}
				 else
				 	{
					$xml_resposta = $xml_resposta."<descricao><![CDATA[".$row[$desc_tipo]."]]></descricao>"; 
					}
				 $xml_resposta = $xml_resposta."<quantidade>".$row[quantos]."</quantidade>";
				 $xml_resposta = $xml_resposta."<percentual>".$Percent."</percentual>";
				 $xml_resposta = $xml_resposta."<tipo>".$critica."</tipo>";
				 $Registros = $Registros+1;
				 $xml_resposta = $xml_resposta."</linha>";			 
				}
		} // Fecha if diferente de branco	
		
  		$Para = $Para+1;
	  } //Fecha while
//	mysql_close($conexao);	  
?>