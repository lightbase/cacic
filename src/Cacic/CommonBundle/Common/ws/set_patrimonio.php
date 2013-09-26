<? 
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

// Defini��o do n�vel de compress�o (Default = 9 => m�ximo)
//$v_compress_level = 9;
$v_compress_level = 0;  // Mantido em 0(zero) para desabilitar a Compress�o/Decompress�o 
						// H� necessidade de testes para An�lise de Viabilidade T�cnica 
 
require_once('../include/library.php');

// Essas vari�veis conter�o os indicadores de criptografia e compacta��o
$v_cs_cipher	= (trim($_POST['cs_cipher'])   <> ''?trim($_POST['cs_cipher'])   : '4');
$v_cs_compress	= (trim($_POST['cs_compress']) <> ''?trim($_POST['cs_compress']) : '4');

$strPaddingKey = '';

// O agente PyCACIC envia o valor "padding_key" para preenchimento da palavra chave para decripta��o/encripta��o
if ($_POST['padding_key'])
	{
	// Valores espec�ficos para trabalho com o PyCACIC - 04 de abril de 2008 - Rog�rio Lino - Dataprev/ES
	$strPaddingKey 	= $_POST['padding_key']; // A vers�o inicial do agente em Python exige esse complemento na chave...
	}

autentica_agente($key,$iv,$v_cs_cipher,$v_cs_compress,$strPaddingKey);


$v_dados_rede = getDadosRede();

// Se o envio de informa��es foi feito com dados criptografados... (Vers�es 2.0.2.5+)
$te_node_address 	= DeCrypt($key,$iv,$_POST['te_node_address']	,$v_cs_cipher,$v_cs_compress,$strPaddingKey); 
$id_so_new         	= DeCrypt($key,$iv,$_POST['id_so']				,$v_cs_cipher,$v_cs_compress,$strPaddingKey); 
$te_so           	= DeCrypt($key,$iv,$_POST['te_so']				,$v_cs_cipher,$v_cs_compress,$strPaddingKey); 
$te_nome_computador = DeCrypt($key,$iv,$_POST['te_nome_computador']	,$v_cs_cipher,$v_cs_compress,$strPaddingKey); 
$te_ip              = DeCrypt($key,$iv,$_POST['te_ip']				,$v_cs_cipher,$v_cs_compress,$strPaddingKey); 
$te_nome_host       = DeCrypt($key,$iv,$_POST['te_nome_host']		,$v_cs_cipher,$v_cs_compress,$strPaddingKey); 
$id_ip_rede         = DeCrypt($key,$iv,$_POST['id_ip_rede']			,$v_cs_cipher,$v_cs_compress,$strPaddingKey);
$te_workgroup       = DeCrypt($key,$iv,$_POST['te_workgroup']		,$v_cs_cipher,$v_cs_compress,$strPaddingKey);

/* Todas as vezes em que � feita a recupera��o das configura��es por um agente, � inclu�do 
 o computador deste agente no BD, caso ainda n�o esteja inserido. */
if ($te_node_address <> '')
	{ 
	$arrSO = inclui_computador_caso_nao_exista(	$te_node_address, 
											  	$id_so_new, 
											  	$te_so, 										
											  	$id_ip_rede, 
											  	$te_ip, 
											  	$te_nome_computador, 
											  	$te_workgroup);																				

	// Aten��o: n�o use o count (*) - Com espa�o entre o count e o (*)				
	$query = "SELECT COUNT(*) 
			  FROM patrimonio 
			  WHERE te_node_address = '" . $te_node_address . "'
			  AND id_so = '" . $arrSO['id_so'] . "'";
	conecta_bd_cacic();
	$result = mysql_query($query);
	if (mysql_num_rows($result) > 0) 
		{  // Atualiza��o das informa��es de patrim�nio (e n�o inclus�o). 
	
	   // Agora, verifica se os administradores dever�o ser notificados da altera��o nas informa��es de patrim�nio.
	   $arrConfiguracoesLocais = getValores('configuracoes_locais', 'te_notificar_mudanca_patrim','id_local = '.$v_dados_rede['id_local']);
		if (trim($destinatarios = $arrConfiguracoesLocais['te_notificar_mudanca_patrim']) != '') 
			{
			$query 	= "	SELECT 	te_etiqueta,nm_campo_tab_patrimonio 
						FROM 	patrimonio_config_interface
						WHERE	id_local = ".$v_dados_rede['id_local'];
			$result = mysql_query($query);
			$tripa_nomes_campos = '';
			while($row=mysql_fetch_array($result))
				{
				$tripa_nomes_campos .= $row['nm_campo_tab_patrimonio'].'#'.$row['te_etiqueta'].'#';
				}
	
			$tripa_nomes_campos = substr($tripa_nomes_campos,0,strlen($tripa_nomes_campos)-1);	
			$array_nomes_campos = explode('#',$tripa_nomes_campos);
	
			// Monto um array com as UO N�vel 1a
			$query 	= "SELECT 	UON1a.id_unid_organizacional_nivel1a, 
								UON1a.nm_unid_organizacional_nivel1a
					   FROM 	unid_organizacional_nivel1a UON1a,
					   			unid_organizacional_nivel2  UON2					   
					   WHERE	UON2.id_unid_organizacional_nivel1a = UON1a.id_unid_organizacional_nivel1a AND
					            UON2.id_local = ".$v_dados_rede['id_local'];
								
			$result = mysql_query($query);
			$tripa_nomes_UON1a = '';
			while($row=mysql_fetch_array($result))
				{
				$tripa_nomes_UON1a .= $row['id_unid_organizacional_nivel1a'].'#'.$row['nm_unid_organizacional_nivel1a'].'#';
				}
	
			$tripa_nomes_UON1a = substr($tripa_nomes_UON1a,0,strlen($tripa_nomes_UON1a)-1);	
			$array_nomes_UON1a = explode('#',$tripa_nomes_UON1a);
	
			// Monto um array com as UO N�vel 2 relacionadas �s suas UO N�vel 1a
			/*
			$query 	= "SELECT 	a.id_unid_organizacional_nivel2, 
								a.nm_unid_organizacional_nivel2
					   FROM 	unid_organizacional_nivel2 a, 
								unid_organizacional_nivel1 b,
								locais c
					   WHERE	a.id_unid_organizacional_nivel1 = b.id_unid_organizacional_nivel1 AND
								b.id_local = c.id_local AND
								c.id_local = ".$v_dados_rede['id_local'];
			$result = mysql_query($query);
			*/
			mysql_data_seek($result,0);
			$tripa_nomes_UON2 = '';
			while($row=mysql_fetch_array($result))
				{
				$tripa_nomes_UON2 .= $row['id_unid_organizacional_nivel2'].'#'.$row['nm_unid_organizacional_nivel2'].'#';
				}
	
			$tripa_nomes_UON2 = substr($tripa_nomes_UON2,0,strlen($tripa_nomes_UON2)-1);	
			$array_nomes_UON2 = explode('#',$tripa_nomes_UON2);
	
	
			$query = "SELECT * 
					  FROM patrimonio
					  WHERE te_node_address = '" . $te_node_address . "'
							AND id_so = '" . $arrSO['id_so'] . "'";
	
			$result = mysql_query($query);
	
			// Recupero as �ltimas informa��es de patrim�nio para montar o e-mail.
			$campos = mysql_fetch_array($result);
	
			// Varre todos os campos e verifica quais foram os que sofreram altera��es.
			$cont_aux = 0;
			$campos_alterados = '';
	
			for ($i=0; $i < mysql_num_fields($result); $i++) 
				{
				$nome_campo_tabela = mysql_field_name($result, $i); 											
				$tam_campo_tabela = mysql_field_len($result, $i);
				$posicao_array_nomes_campos = array_search($nome_campo_tabela, $array_nomes_campos);
				$nome_campo_tela = $array_nomes_campos[$posicao_array_nomes_campos + 1];
				if (($campos["$nome_campo_tabela"] != substr(DeCrypt($key,$iv,$_POST[$nome_campo_tabela],$v_cs_cipher,$v_cs_compress,$strPaddingKey), 0, $tam_campo_tabela)) && ($nome_campo_tabela != 'dt_hr_alteracao' && $nome_campo_tabela != 'id_so' && $nome_campo_tabela != 'te_node_address')) 
					{
					if ($nome_campo_tabela != 'id_unid_organizacional_nivel1a' && $nome_campo_tabela != 'id_unid_organizacional_nivel2')
						{
						$valor_anterior = 	$campos["$nome_campo_tabela"];
						$valor_atual	=	DeCrypt($key,$iv,$_POST["$nome_campo_tabela"],$v_cs_cipher,$v_cs_compress,$strPaddingKey);
						}									
					else
						{
						if ($nome_campo_tabela == 'id_unid_organizacional_nivel1a')
							{
							$posicao_array_nomes_UON1 = array_search($campos["$nome_campo_tabela"], $array_nomes_UON1);
							$valor_anterior = $array_nomes_UON1[$posicao_array_nomes_UON1 + 1];
							$posicao_array_nomes_UON1 = array_search(DeCrypt($key,$iv,$_POST["$nome_campo_tabela"],$v_cs_cipher,$v_cs_compress,$strPaddingKey), $array_nomes_UON1);
							$valor_atual = $array_nomes_UON1[$posicao_array_nomes_UON1 + 1];
							}
						else
							{
							$posicao_array_nomes_UON2 = array_search($campos["$nome_campo_tabela"], $array_nomes_UON2);
							$valor_anterior = $array_nomes_UON2[$posicao_array_nomes_UON2 + 1];
							$posicao_array_nomes_UON2 = array_search(DeCrypt($key,$iv,$_POST["$nome_campo_tabela"],$v_cs_cipher,$v_cs_compress,$strPaddingKey), $array_nomes_UON2);
							$valor_atual = $array_nomes_UON2[$posicao_array_nomes_UON2 + 1];
							}
						}
					$campos_alterados =  $campos_alterados . $nome_campo_tela .  ":\n" . str_repeat('-', strlen($nome_campo_tela)) .  "\nValor Anterior.: " . $valor_anterior . "\nNovo Valor.....: " . $valor_atual . "\n\n";
					$cont_aux++;
					}
				}
	
			$query = "SELECT te_nome_computador, id_ip_rede, te_ip 
				  FROM computadores 
				  WHERE te_node_address = '" . $te_node_address . "'
				  AND id_so = '" . $arrSO['id_so'] . "'";
			$result = mysql_query($query);
	
			if ($cont_aux > 0) 
				{ 
				$corpo_mail = "
	Prezado administrador,\n
	Foi identificada uma altera��o nas informa��es de patrim�nio do seguinte computador:\n
	Nome...........: ". mysql_result( $result, 0, "te_nome_computador" ) ."
	Endere�o TCP/IP: ". mysql_result( $result, 0, "te_ip" ) . "
	Rede...........: ". mysql_result( $result, 0, "id_ip_rede" ) ."\n\n
	As informa��es que sofreram altera��es est�o relacionadas abaixo:\n\n" .
	$campos_alterados ."\n\n
	Para visualizar mais informa��es sobre esse computador, acesse o endere�o ".CACIC_PATH."/relatorios/computador/computador.php?id_so=" . $arrSO['id_so'] . "&te_node_address=" . $te_node_address . "\n
	______________________________________________
	CACIC - " . date('d/m/Y H:i') . "h
	Desenvolvido pela Dataprev - Unidade Regional Esp�rito Santo";
	
				// Manda mail para os administradores.
				mail("$destinatarios", "Alteracao de Patrimonio Detectada", "$corpo_mail", "From: cacic@{$_SERVER['SERVER_NAME']}");
				}
			}
		}
	
	
	// Inclui dados patrimoniais.
	$query_verify = "SELECT te_node_address, id_so FROM patrimonio
	                        WHERE te_node_address = '" . $te_node_address . "' AND id_so = '" . $arrSO['id_so'] . "'";
	$result = mysql_query($query_verify);
	
        if(mysql_num_rows($result) <> 0) {
	     $query = "UPDATE patrimonio 
	                  SET id_unid_organizacional_nivel1a = '".
	                      DeCrypt($key,$iv,$_POST['id_unid_organizacional_nivel1a'],$v_cs_cipher,$v_cs_compress,$strPaddingKey). "'".
	                         
	                ", id_unid_organizacional_nivel2 = '".
	                      DeCrypt($key,$iv,$_POST['id_unid_organizacional_nivel2'],$v_cs_cipher,$v_cs_compress,$strPaddingKey). "'".
	                        
	                ", te_localizacao_complementar = '".
	                      DeCrypt($key,$iv,$_POST['te_localizacao_complementar']	,$v_cs_cipher,$v_cs_compress,$strPaddingKey)."'".
	                      
	                ", te_info_patrimonio1 = '".DeCrypt($key,$iv,$_POST['te_info_patrimonio1'],$v_cs_cipher,$v_cs_compress,$strPaddingKey)."'".
	                ", te_info_patrimonio2 = '".DeCrypt($key,$iv,$_POST['te_info_patrimonio2'],$v_cs_cipher,$v_cs_compress,$strPaddingKey)."'".
	                ", te_info_patrimonio3 = '".DeCrypt($key,$iv,$_POST['te_info_patrimonio3'],$v_cs_cipher,$v_cs_compress,$strPaddingKey)."'".
	                ", te_info_patrimonio4 = '".DeCrypt($key,$iv,$_POST['te_info_patrimonio4'],$v_cs_cipher,$v_cs_compress,$strPaddingKey)."'".
	                ", te_info_patrimonio5 = '".DeCrypt($key,$iv,$_POST['te_info_patrimonio5'],$v_cs_cipher,$v_cs_compress,$strPaddingKey)."'".
	                ", te_info_patrimonio6 = '".DeCrypt($key,$iv,$_POST['te_info_patrimonio6'],$v_cs_cipher,$v_cs_compress,$strPaddingKey)."'".
	                  
	              " WHERE te_node_address = '" . $te_node_address . "' AND id_so = '" . $arrSO['id_so'] . "'";
	}
        else {
	     $query = "INSERT INTO patrimonio ( te_node_address, id_so, dt_hr_alteracao,
	                                   id_unid_organizacional_nivel1a,
	                                   id_unid_organizacional_nivel2,
	                                   te_localizacao_complementar,
	                                   te_info_patrimonio1,
	                                   te_info_patrimonio2,
	                                   te_info_patrimonio3,
	                                   te_info_patrimonio4,
	                                   te_info_patrimonio5,
	                                   te_info_patrimonio6 )
			 VALUES ( '" . $te_node_address . "', 
			          '" . $arrSO['id_so'] . "', NOW(),
			          '" . DeCrypt($key,$iv,$_POST['id_unid_organizacional_nivel1a'],$v_cs_cipher,$v_cs_compress,$strPaddingKey) . "',
			          '" . DeCrypt($key,$iv,$_POST['id_unid_organizacional_nivel2']	,$v_cs_cipher,$v_cs_compress,$strPaddingKey) . "', 
			          '" . DeCrypt($key,$iv,$_POST['te_localizacao_complementar']	,$v_cs_cipher,$v_cs_compress,$strPaddingKey) . "', 
			          '" . DeCrypt($key,$iv,$_POST['te_info_patrimonio1']		,$v_cs_cipher,$v_cs_compress,$strPaddingKey) . "', 
			          '" . DeCrypt($key,$iv,$_POST['te_info_patrimonio2']		,$v_cs_cipher,$v_cs_compress,$strPaddingKey) . "', 
			          '" . DeCrypt($key,$iv,$_POST['te_info_patrimonio3']		,$v_cs_cipher,$v_cs_compress,$strPaddingKey) . "', 
			          '" . DeCrypt($key,$iv,$_POST['te_info_patrimonio4']		,$v_cs_cipher,$v_cs_compress,$strPaddingKey) . "',
			          '" . DeCrypt($key,$iv,$_POST['te_info_patrimonio5']		,$v_cs_cipher,$v_cs_compress,$strPaddingKey) . "',
			          '" . DeCrypt($key,$iv,$_POST['te_info_patrimonio6']		,$v_cs_cipher,$v_cs_compress,$strPaddingKey) . "')";
	}
			          
	$result = mysql_query($query);
	echo '<?xml version="1.0" encoding="iso-8859-1" ?><STATUS>OK</STATUS>';
	}
else
	echo '<?xml version="1.0" encoding="iso-8859-1" ?><STATUS>Chave (TE_NODE_ADDRESS + ID_SO) Inv�lida</STATUS>';		
?>
