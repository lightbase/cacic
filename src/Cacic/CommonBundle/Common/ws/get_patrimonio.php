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
 
 Objetivo:
 ---------
 Esse script tem como objetivo enviar aos agentes a nomeclatura dos campos do formulario utilizado
 para coleta de dados patrimoniais dos equipamentos .
 Sylvio Roberto
*/
require_once('../include/library.php');

// Defini��o do n�vel de compress�o (Default = 9 => m�nimo)
//$v_compress_level = 9;
$v_compress_level = 0;  // Mantido em 0(zero) para desabilitar a Compress�o/Decompress�o 
						// H� necessidade de testes para An�lise de Viabilidade T�cnica 

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

autentica_agente($key,$iv,$v_cs_cipher,$v_cs_compress, $strPaddingKey);

$v_dados_rede = getDadosRede();

$te_node_address 	= DeCrypt($key,$iv,$_POST['te_node_address']	,$v_cs_cipher,$v_cs_compress, $strPaddingKey); 
$id_so_new         	= DeCrypt($key,$iv,$_POST['id_so']				,$v_cs_cipher,$v_cs_compress, $strPaddingKey); 
$te_so           	= DeCrypt($key,$iv,$_POST['te_so']				,$v_cs_cipher,$v_cs_compress, $strPaddingKey); 
$id_ip_rede     	= DeCrypt($key,$iv,$_POST['id_ip_rede']			,$v_cs_cipher,$v_cs_compress, $strPaddingKey);
$te_ip 				= DeCrypt($key,$iv,$_POST['te_ip']				,$v_cs_cipher,$v_cs_compress, $strPaddingKey); 
$te_nome_computador	= DeCrypt($key,$iv,$_POST['te_nome_computador']	,$v_cs_cipher,$v_cs_compress, $strPaddingKey); 
$te_workgroup 		= DeCrypt($key,$iv,$_POST['te_workgroup']		,$v_cs_cipher,$v_cs_compress, $strPaddingKey); 

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
	}


/*
Consulta que devolve as Datas das �ltimas Altera��es nas tabelas 
patrimonio_config_interface, unid_organizacional_nivel1 e unid_organizacional_nivel2.
========================================================================= 	
*/
$query = 'SELECT 	dt_hr_alteracao_patrim_interface, 
					dt_hr_alteracao_patrim_uon1, 
					dt_hr_alteracao_patrim_uon2, 
					cs_abre_janela_patr
          FROM 		configuracoes_locais
		  WHERE		id_local = '.$v_dados_rede['id_local'];
conecta_bd_cacic();			  
$result = mysql_query($query);
$campos = mysql_fetch_array($result);				
$retorno_xml='<?xml version="1.0" encoding="iso-8859-1" ?><CONFIGS><STATUS>OK</STATUS><dt_hr_alteracao_patrim_interface>' . EnCrypt($key,$iv,$campos['dt_hr_alteracao_patrim_interface'],$v_cs_cipher,$v_cs_compress,$v_compress_level, $strPaddingKey) .'</dt_hr_alteracao_patrim_interface><dt_hr_alteracao_patrim_uon1>' . EnCrypt($key,$iv,$campos['dt_hr_alteracao_patrim_uon1'],$v_cs_cipher,$v_cs_compress,$v_compress_level, $strPaddingKey) .'</dt_hr_alteracao_patrim_uon1><dt_hr_alteracao_patrim_uon2>' . EnCrypt($key,$iv,$campos['dt_hr_alteracao_patrim_uon2'],$v_cs_cipher,$v_cs_compress,$v_compress_level, $strPaddingKey) .'</dt_hr_alteracao_patrim_uon2><cs_abre_janela_patr>' . EnCrypt($key,$iv,$campos['cs_abre_janela_patr'],$v_cs_cipher,$v_cs_compress,$v_compress_level, $strPaddingKey) .'</cs_abre_janela_patr>';


/*
Consulta que devolve as configura��es da interface da janela de patrimonio a ser apresentada pelo agente.
=========================================================================
*/
$query = 'SELECT 	id_etiqueta, 
					te_etiqueta, 
					in_exibir_etiqueta, 
					te_help_etiqueta 
		  FROM 		patrimonio_config_interface 
		  WHERE		id_local = '.$v_dados_rede['id_local'].' 
		  ORDER BY id_etiqueta';
conecta_bd_cacic();														
$result = mysql_query($query);

// ARGHHH!!! - Favor n�o me julgar somente por isso!   :)  (Anderson Peterle - Domingo � noitinha, ainda em f�rias!(???))
$bool_1a = false;
$id      = '';
	
$i = 1;
while ($campos = mysql_fetch_array($result))  
	{				
	if ($i == 2 && !$bool_1a)
		{
		$id = '1a';
		$i  = 1;
		$bool_1a = true;
		}
	else
		{
		$id = $i;
		}
	$retorno_xml .= '<te_etiqueta'        . $id . '>'. EnCrypt($key,$iv,$campos["te_etiqueta"],$v_cs_cipher,$v_cs_compress,$v_compress_level, $strPaddingKey)        . '</te_etiqueta'        . $id . '>' . 
	                '<in_exibir_etiqueta' . $id . '>'. EnCrypt($key,$iv,$campos["in_exibir_etiqueta"],$v_cs_cipher,$v_cs_compress,$v_compress_level, $strPaddingKey) . '</in_exibir_etiqueta' . $id . '>' . 
					'<te_help_etiqueta'   . $id . '>'. EnCrypt($key,$iv,$campos["te_help_etiqueta"],$v_cs_cipher,$v_cs_compress,$v_compress_level, $strPaddingKey)   . '</te_help_etiqueta'   . $id . '>';
	$i++ ;
	}

/*
Consulta que devolve os itens das tabelas de U.O. n�veis 1, 1a e 2
==================================================================
*/
$query = '	SELECT 		uo1.id_unid_organizacional_nivel1  	as uo1_id, 
						uo1.nm_unid_organizacional_nivel1 	as uo1_nm,
						uo1a.id_unid_organizacional_nivel1a as uo1a_id, 
		 				uo1a.nm_unid_organizacional_nivel1a as uo1a_nm,
         				uo2.id_unid_organizacional_nivel2   as uo2_id, 
		 				uo2.nm_unid_organizacional_nivel2   as uo2_nm,
						uo2.id_local						as uo2_id_local,
						loc.sg_local 						as loc_sg
         	FROM 		unid_organizacional_nivel1a uo1a, 
						unid_organizacional_nivel1  uo1,
						unid_organizacional_nivel2  uo2,						
						locais loc
	  		WHERE 		uo1.id_unid_organizacional_nivel1   = uo1a.id_unid_organizacional_nivel1 AND
			            uo1a.id_unid_organizacional_nivel1a = uo2.id_unid_organizacional_nivel1a AND
						uo2.id_local = '.$v_dados_rede['id_local'] .' AND 
						uo2.id_local = loc.id_local 
			ORDER BY 	loc_sg,uo1_nm,uo1a_nm,uo2_nm';
conecta_bd_cacic();																					  
$result = mysql_query($query);

$strTripaIdUON1 = '';
$strAux = '';
while ($campos = mysql_fetch_array($result))
	{
	$strAux = '#'.$campos['uo1_id'].'-'.$campos['uo1_nm'].'#';			
	$pos1 = stripos2($strTripaIdUON1,$strAux,false);	
	if (!$pos1)	
		{	
	  	$retorno_xml .= '<IT1><ID1>' . EnCrypt($key,$iv,$campos['uo1_id'],$v_cs_cipher,$v_cs_compress,$v_compress_level, $strPaddingKey) . '</ID1><NM1>' . EnCrypt($key,$iv,$campos['uo1_nm'],$v_cs_cipher,$v_cs_compress,$v_compress_level, $strPaddingKey) . '</NM1></IT1>'; 		
		$strTripaIdUON1 .= '#'.$campos['uo1_id'].'-'.$campos['uo1_nm'].'#';
		}			
	}

//while ($campos = mysql_fetch_array($result))
//	{
//  	$retorno_xml .= '<IT1><ID1>' . EnCrypt($key,$iv,$campos['uo1_id'],$v_cs_cipher,$v_cs_compress,$v_compress_level) . '</ID1><NM1>' . EnCrypt($key,$iv,$campos['uo1_nm'],$v_cs_cipher,$v_cs_compress,$v_compress_level) . '</NM1></IT1>'; 
//	}


$strTripaIdUON1a = '';
$strAux = '';

mysql_data_seek($result,0);
$result = mysql_query($query);
while ($campos = mysql_fetch_array($result))
	{
		
	$strAux = '#'.$campos['uo1a_id'].'-'.$campos['uo2_id_local'].'#';		
	$pos1 = stripos2($strTripaIdUON1a,$strAux,false);

	if (!$pos1)	
		{
	  	$retorno_xml .= '<IT1a><ID1>' . EnCrypt($key,$iv,$campos['uo1_id'],$v_cs_cipher,$v_cs_compress,$v_compress_level, $strPaddingKey) . '</ID1><SG_LOC>' . EnCrypt($key,$iv,$campos['loc_sg'],$v_cs_cipher,$v_cs_compress,$v_compress_level, $strPaddingKey) . '</SG_LOC><ID1a>' . EnCrypt($key,$iv,$campos['uo1a_id'],$v_cs_cipher,$v_cs_compress,$v_compress_level, $strPaddingKey) . '</ID1a><NM1a>' . EnCrypt($key,$iv,$campos['uo1a_nm'],$v_cs_cipher,$v_cs_compress,$v_compress_level, $strPaddingKey) . '</NM1a><ID_LOCAL>' . EnCrypt($key,$iv,$campos['uo2_id_local'],$v_cs_cipher,$v_cs_compress,$v_compress_level, $strPaddingKey) . '</ID_LOCAL></IT1a>';
		$strTripaIdUON1a .= '#'.$campos['uo1a_id'].'-'.$campos['uo2_id_local'].'#';
		}		
	}

mysql_data_seek($result,0);
while ($campos = mysql_fetch_array($result))
  	$retorno_xml .= '<IT2><ID1a>' . EnCrypt($key,$iv,$campos['uo1a_id'],$v_cs_cipher,$v_cs_compress,$v_compress_level, $strPaddingKey) . '</ID1a><ID2>' . EnCrypt($key,$iv,$campos['uo2_id'],$v_cs_cipher,$v_cs_compress,$v_compress_level, $strPaddingKey) . '</ID2><NM2>' . EnCrypt($key,$iv,$campos['uo2_nm'],$v_cs_cipher,$v_cs_compress,$v_compress_level, $strPaddingKey) . '</NM2><ID_LOCAL>' . EnCrypt($key,$iv,$campos['uo2_id_local'],$v_cs_cipher,$v_cs_compress,$v_compress_level, $strPaddingKey) . '</ID_LOCAL></IT2>';


// Envio os valores j� existentes no banco, referentes ao ID_SO+TE_NODE_ADDRESS da esta��o chamadora...
$query = '	SELECT 		pat.id_unid_organizacional_nivel1a, 
		 	       		pat.id_unid_organizacional_nivel2,
				   		pat.te_localizacao_complementar,
				   		pat.te_info_patrimonio1,
				   		pat.te_info_patrimonio2,
				   		pat.te_info_patrimonio3,
				   		pat.te_info_patrimonio4,
				   		pat.te_info_patrimonio5,
				   		pat.te_info_patrimonio6,
				   		pat.dt_hr_alteracao,
						uo2.id_local
         	FROM   		patrimonio pat,
						unid_organizacional_nivel2 uo2
	  		WHERE  		id_so= "'.$arrSO['id_so'].'" and te_node_address="'.$te_node_address.'" AND
			            uo2.id_unid_organizacional_nivel2 = pat.id_unid_organizacional_nivel2
			ORDER  BY 	pat.dt_hr_alteracao DESC LIMIT 1';

conecta_bd_cacic();																					  
$result = mysql_query($query);
if (count($result)>0)
	{
	$valores = mysql_fetch_array($result);
	$retorno_xml .= '<ID_UON1a>'	.EnCrypt($key,$iv,$valores['id_unid_organizacional_nivel1a'],$v_cs_cipher,$v_cs_compress,$v_compress_level, $strPaddingKey).'</ID_UON1a>';		
	$retorno_xml .= '<ID_UON2>'		.EnCrypt($key,$iv,$valores['id_unid_organizacional_nivel2']	,$v_cs_cipher,$v_cs_compress,$v_compress_level, $strPaddingKey).'</ID_UON2>';		
	$retorno_xml .= '<ID_LOCAL>'	.EnCrypt($key,$iv,$valores['id_local']						,$v_cs_cipher,$v_cs_compress,$v_compress_level, $strPaddingKey).'</ID_LOCAL>';				
	$retorno_xml .= '<TE_LOC_COMPL>'.EnCrypt($key,$iv,$valores['te_localizacao_complementar']	,$v_cs_cipher,$v_cs_compress,$v_compress_level, $strPaddingKey).'</TE_LOC_COMPL>';			
	$retorno_xml .= '<TE_INFO1>'	.EnCrypt($key,$iv,$valores['te_info_patrimonio1']			,$v_cs_cipher,$v_cs_compress,$v_compress_level, $strPaddingKey).'</TE_INFO1>';				
	$retorno_xml .= '<TE_INFO2>'	.EnCrypt($key,$iv,$valores['te_info_patrimonio2']			,$v_cs_cipher,$v_cs_compress,$v_compress_level, $strPaddingKey).'</TE_INFO2>';				
	$retorno_xml .= '<TE_INFO3>'	.EnCrypt($key,$iv,$valores['te_info_patrimonio3']			,$v_cs_cipher,$v_cs_compress,$v_compress_level, $strPaddingKey).'</TE_INFO3>';				
	$retorno_xml .= '<TE_INFO4>'	.EnCrypt($key,$iv,$valores['te_info_patrimonio4']			,$v_cs_cipher,$v_cs_compress,$v_compress_level, $strPaddingKey).'</TE_INFO4>';				
	$retorno_xml .= '<TE_INFO5>'	.EnCrypt($key,$iv,$valores['te_info_patrimonio5']			,$v_cs_cipher,$v_cs_compress,$v_compress_level, $strPaddingKey).'</TE_INFO5>';				
	$retorno_xml .= '<TE_INFO6>'	.EnCrypt($key,$iv,$valores['te_info_patrimonio6']			,$v_cs_cipher,$v_cs_compress,$v_compress_level, $strPaddingKey).'</TE_INFO6>';									
	}

// --------------- Retorno de Classificador de CRIPTOGRAFIA --------------- //
if ($v_cs_cipher <> '1') $v_cs_cipher --;

// Comente/Descomente a linha abaixo para habilitar/desabilitar a criptografia de informa��es trafegadas 
//$v_cs_cipher = '0'; 

$retorno_xml .= '<cs_cipher>'.$v_cs_cipher.'</cs_cipher>';		
// ----------------------------------------------------------------------- //


// --------------- Retorno de Classificador de COMPRESS�O --------------- //
$pos = strpos($_SERVER['HTTP_ACCEPT_ENCODING'], "deflate");
if ($pos <> -1 && $v_cs_compress <>'1') $v_cs_compress -= 1;

// Caso o n�vel de compress�o sera setado para 0(zero) o indicador deve retornar 0(zero)
if ($v_compress_level == '0') $v_cs_compress = '0';

// Comente/Descomente a linha abaixo para habilitar/desabilitar a compacta��o de informa��es trafegadas 
//$v_cs_compress = '0'; 
$retorno_xml .= '<cs_compress>'.$v_cs_compress.'</cs_compress>';
// ---------------------------------------------------------------------- //

$retorno_xml .= '</CONFIGS>';

echo $retorno_xml;
?>
