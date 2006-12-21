<? 
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
 Esse script tem como objetivo enviar aos agentes a nomeclatura dos campos do formulario utilizado
 para coleta de dados patrimoniais dos equipamentos .
 Sylvio Roberto
*/
require_once('../include/library.php');

// Definição do nível de compressão (Default = 1 => mínimo)
//$v_compress_level = 1;
$v_compress_level = 0;  // Mantido em 0(zero) para desabilitar a Compressão/Decompressão 
						// Há necessidade de testes para Análise de Viabilidade Técnica 

// Essas variáveis conterão os indicadores de criptografia e compactação
$v_cs_cipher	= (trim($_POST['cs_cipher'])   <> ''?trim($_POST['cs_cipher'])   : '4');
$v_cs_compress	= (trim($_POST['cs_compress']) <> ''?trim($_POST['cs_compress']) : '4');

autentica_agente($key,$iv,$v_cs_cipher,$v_cs_compress);

$v_dados_rede = getDadosRede();

$te_node_address 	= DeCrypt($key,$iv,$_POST['te_node_address']	,$v_cs_cipher,$v_cs_compress); 
$id_so           	= DeCrypt($key,$iv,$_POST['id_so']				,$v_cs_cipher,$v_cs_compress); 
$id_ip_rede     	= DeCrypt($key,$iv,$_POST['id_ip_rede']			,$v_cs_cipher,$v_cs_compress);
$te_ip 				= DeCrypt($key,$iv,$_POST['te_ip']				,$v_cs_cipher,$v_cs_compress); 
$te_nome_computador	= DeCrypt($key,$iv,$_POST['te_nome_computador']	,$v_cs_cipher,$v_cs_compress); 
$te_workgroup 		= DeCrypt($key,$iv,$_POST['te_workgroup']		,$v_cs_cipher,$v_cs_compress); 

/* Todas as vezes em que é feita a recuperação das configurações por um agente, é incluído 
 o computador deste agente no BD, caso ainda não esteja inserido. */
if ($te_node_address || $id_so || $te_nome_computador || $te_ip || $te_workgroup || $id_ip_rede <> '')
	{
	inclui_computador_caso_nao_exista(	$te_node_address, 
										$id_so, 
										$id_ip_rede, 
										$te_ip, 
										$te_nome_computador,
										$te_workgroup);
	}


/*
Consulta que devolve as Datas das Últimas Alterações nas tabelas 
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
$retorno_xml='<?xml version="1.0" encoding="iso-8859-1" ?><CONFIGS><STATUS>OK</STATUS><dt_hr_alteracao_patrim_interface>' . EnCrypt($key,$iv,$campos['dt_hr_alteracao_patrim_interface'],$v_cs_cipher,$v_cs_compress,$v_compress_level) .'</dt_hr_alteracao_patrim_interface><dt_hr_alteracao_patrim_uon1>' . EnCrypt($key,$iv,$campos['dt_hr_alteracao_patrim_uon1'],$v_cs_cipher,$v_cs_compress,$v_compress_level) .'</dt_hr_alteracao_patrim_uon1><dt_hr_alteracao_patrim_uon2>' . EnCrypt($key,$iv,$campos['dt_hr_alteracao_patrim_uon2'],$v_cs_cipher,$v_cs_compress,$v_compress_level) .'</dt_hr_alteracao_patrim_uon2><cs_abre_janela_patr>' . EnCrypt($key,$iv,$campos['cs_abre_janela_patr'],$v_cs_cipher,$v_cs_compress,$v_compress_level) .'</cs_abre_janela_patr>';

/*
Consulta que devolve as configurações da interface da janela de patrimonio a ser apresentada pelo agente.
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
	
$i = 1;
while ($campos = mysql_fetch_array($result))  
	{				
	$retorno_xml .= '<te_etiqueta'        . $i . '>'. EnCrypt($key,$iv,$campos["te_etiqueta"],$v_cs_cipher,$v_cs_compress,$v_compress_level)        . '</te_etiqueta'        . $i . '>' . 
	                '<in_exibir_etiqueta' . $i . '>'. EnCrypt($key,$iv,$campos["in_exibir_etiqueta"],$v_cs_cipher,$v_cs_compress,$v_compress_level) . '</in_exibir_etiqueta' . $i . '>' . 
					'<te_help_etiqueta'   . $i . '>'. EnCrypt($key,$iv,$campos["te_help_etiqueta"],$v_cs_cipher,$v_cs_compress,$v_compress_level)   . '</te_help_etiqueta'   . $i . '>';
	$i++ ;
	}


/*
Consulta que devolve os itens da Tabela de Unidade Organizacional Nível 1.
=========================================================================
*/
$query = 'SELECT id_unid_organizacional_nivel1, nm_unid_organizacional_nivel1 
          FROM unid_organizacional_nivel1 ';
conecta_bd_cacic();																					  
$result = mysql_query($query);

while ($campos = mysql_fetch_array($result))
	{
  	$retorno_xml .= '<IT1><ID1>' . EnCrypt($key,$iv,$campos['id_unid_organizacional_nivel1'],$v_cs_cipher,$v_cs_compress,$v_compress_level) . '</ID1><NM1>' . EnCrypt($key,$iv,$campos['nm_unid_organizacional_nivel1'],$v_cs_cipher,$v_cs_compress,$v_compress_level) . '</NM1></IT1>'; 
	}

/*
Consulta que devolve os itens da Tabela de Unidade Organizacional Nível 2.
=========================================================================
*/
$query = '	SELECT 		uo1.id_unid_organizacional_nivel1 as uo1_id, 
		 				uo1.nm_unid_organizacional_nivel1 as uo1_nm,
         				uo2.id_unid_organizacional_nivel2 as uo2_id, 
		 				uo2.nm_unid_organizacional_nivel2 as uo2_nm
         	FROM 		unid_organizacional_nivel1 uo1, 
						unid_organizacional_nivel2 uo2
	  		WHERE 		uo1.id_unid_organizacional_nivel1 = uo2.id_unid_organizacional_nivel1 AND
						uo2.id_local = '.$v_dados_rede['id_local'].'
			ORDER BY 	uo1_nm,uo2_nm';

conecta_bd_cacic();																					  
$result = mysql_query($query);
while ($campos = mysql_fetch_array($result))
	{
  	$retorno_xml .= '<IT2><ID1>' . EnCrypt($key,$iv,$campos['uo1_id'],$v_cs_cipher,$v_cs_compress,$v_compress_level) . '</ID1><ID2>' . EnCrypt($key,$iv,$campos['uo2_id'],$v_cs_cipher,$v_cs_compress,$v_compress_level) . '</ID2><NM2>' . EnCrypt($key,$iv,$campos['uo2_nm'],$v_cs_cipher,$v_cs_compress,$v_compress_level) . '</NM2></IT2>'; 	
	}

// Envio os valores já existentes no banco, referentes ao ID_SO+TE_NODE_ADDRESS da estação chamadora...
$query = '	SELECT id_unid_organizacional_nivel1, 
		 	       id_unid_organizacional_nivel2,
				   te_localizacao_complementar,
				   te_info_patrimonio1,
				   te_info_patrimonio2,
				   te_info_patrimonio3,
				   te_info_patrimonio4,
				   te_info_patrimonio5,
				   te_info_patrimonio6,
				   dt_hr_alteracao
         	FROM   patrimonio
	  		WHERE  id_so= "'.$id_so.'" and te_node_address="'.$te_node_address.'"
			ORDER  BY dt_hr_alteracao DESC LIMIT 1';
conecta_bd_cacic();																					  
$result = mysql_query($query);
if (count($result)>0)
	{
	$valores = mysql_fetch_array($result);
	$retorno_xml .= '<ID_UON1>'		.EnCrypt($key,$iv,$valores['id_unid_organizacional_nivel1']	,$v_cs_cipher,$v_cs_compress,$v_compress_level).'</ID_UON1>';		
	$retorno_xml .= '<ID_UON2>'		.EnCrypt($key,$iv,$valores['id_unid_organizacional_nivel2']	,$v_cs_cipher,$v_cs_compress,$v_compress_level).'</ID_UON2>';		
	$retorno_xml .= '<TE_LOC_COMPL>'.EnCrypt($key,$iv,$valores['te_localizacao_complementar']	,$v_cs_cipher,$v_cs_compress,$v_compress_level).'</TE_LOC_COMPL>';			
	$retorno_xml .= '<TE_INFO1>'	.EnCrypt($key,$iv,$valores['te_info_patrimonio1']			,$v_cs_cipher,$v_cs_compress,$v_compress_level).'</TE_INFO1>';				
	$retorno_xml .= '<TE_INFO2>'	.EnCrypt($key,$iv,$valores['te_info_patrimonio2']			,$v_cs_cipher,$v_cs_compress,$v_compress_level).'</TE_INFO2>';				
	$retorno_xml .= '<TE_INFO3>'	.EnCrypt($key,$iv,$valores['te_info_patrimonio3']			,$v_cs_cipher,$v_cs_compress,$v_compress_level).'</TE_INFO3>';				
	$retorno_xml .= '<TE_INFO4>'	.EnCrypt($key,$iv,$valores['te_info_patrimonio4']			,$v_cs_cipher,$v_cs_compress,$v_compress_level).'</TE_INFO4>';				
	$retorno_xml .= '<TE_INFO5>'	.EnCrypt($key,$iv,$valores['te_info_patrimonio5']			,$v_cs_cipher,$v_cs_compress,$v_compress_level).'</TE_INFO5>';				
	$retorno_xml .= '<TE_INFO6>'	.EnCrypt($key,$iv,$valores['te_info_patrimonio6']			,$v_cs_cipher,$v_cs_compress,$v_compress_level).'</TE_INFO6>';									
	}

$retorno_xml .= '</CONFIGS>';

echo $retorno_xml;
?>
