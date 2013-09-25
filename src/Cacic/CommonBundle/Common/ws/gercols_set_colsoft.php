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

conecta_bd_cacic();


// =================
// SOFTWARES B�SICOS
// =================	
$query = "SELECT 	count(*) as num_registros
		  FROM 		versoes_softwares
		  WHERE		id_computador " . $arrDadosComputador['id_computador'];
$result = mysql_query($query);
if (mysql_result($result, 0, "num_registros") == 0) 
	{
	$query = "INSERT INTO versoes_softwares(id_computador)
			  VALUES (" . $arrDadosComputador['id_computador']. ")";
	$result = mysql_query($query);
	} 

$query = "UPDATE 	versoes_softwares 
		  SET		te_versao_bde            = '" . DeCrypt($key,$iv,$_POST['te_versao_bde']			,$v_cs_cipher,$v_cs_compress,$strPaddingKey) . "', 
					te_versao_dao            = '" . DeCrypt($key,$iv,$_POST['te_versao_dao']			,$v_cs_cipher,$v_cs_compress,$strPaddingKey) . "', 
					te_versao_ado            = '" . DeCrypt($key,$iv,$_POST['te_versao_ado']			,$v_cs_cipher,$v_cs_compress,$strPaddingKey) . "', 
					te_versao_odbc           = '" . DeCrypt($key,$iv,$_POST['te_versao_odbc']			,$v_cs_cipher,$v_cs_compress,$strPaddingKey) . "', 
					te_versao_directx        = '" . DeCrypt($key,$iv,$_POST['te_versao_directx']		,$v_cs_cipher,$v_cs_compress,$strPaddingKey) . "', 
					te_versao_acrobat_reader = '" . DeCrypt($key,$iv,$_POST['te_versao_acrobat_reader']	,$v_cs_cipher,$v_cs_compress,$strPaddingKey) . "', 
					te_versao_ie             = '" . DeCrypt($key,$iv,$_POST['te_versao_ie']				,$v_cs_cipher,$v_cs_compress,$strPaddingKey) . "', 
					te_versao_mozilla        = '" . DeCrypt($key,$iv,$_POST['te_versao_mozilla']		,$v_cs_cipher,$v_cs_compress,$strPaddingKey) . "', 
					te_versao_jre            = '" . DeCrypt($key,$iv,$_POST['te_versao_jre']			,$v_cs_cipher,$v_cs_compress,$strPaddingKey) . "' 
		   WHERE 	id_computador			 = "  . $arrDadosComputador['id_computador'];
$result = mysql_query($query);

// =========================================================
// SOFTWARES INVENTARIADOS (Registrados no Windows Registry)
// =========================================================
$te_inventario_softwares = str_replace("&quot;","'",DeCrypt($key,$iv,$_POST['te_inventario_softwares'],$v_cs_cipher,$v_cs_compress,$strPaddingKey));
$te_inventario_softwares = str_replace("&apos;","^",$te_inventario_softwares);
// Aspas simples e acento cincunflexo inseridos pelo agente coletor

if ($te_inventario_softwares <> '')
	{
	$queryDEL  = "DELETE FROM softwares_inventariados_estacoes 
				  WHERE 	id_computador = " . $arrDadosComputador['id_computador'];
	$resultDEL = mysql_query($queryDEL);									
	
	$v_array_te_inventario_softwares = explode('#',$te_inventario_softwares);	
	$strTripaHash = '';
	$arrHashs  = array();
	// Crio uma tripa contendo os HASHS separados por v�rgulas
	// Crio um array com chaves HASH e valores <Nomes dos Softwares Inventariados>
	for ($intIndice=0; $intIndice < count($v_array_te_inventario_softwares); $intIndice ++)
		if ($v_array_te_inventario_softwares[$intIndice] <> '')
			{
			$strHash = hash('md5',$v_array_te_inventario_softwares[$intIndice]);
			$strTripaHASH .= ($strTripaHASH <> ''?',':'');
			$strTripaHASH .= "'".$strHash."'";
			$arrHashs[$strHash] = $v_array_te_inventario_softwares[$intIndice];
			}

	// Consulto no banco a exist�ncia dos softwares inventariados
	$querySEL  = "SELECT	*
				  FROM 		softwares_inventariados
				  WHERE		te_hash in (".$strTripaHASH.")";
	$resultSEL = mysql_query($querySEL );

	// Retiro os S.I. j� existentes do array para montar query de inser��o em S.I.
	while ($v_reg = mysql_fetch_array($resultSEL))
		unset($arrHashs[$v_reg['te_hash']]); 
		
	$strValues  = '';			
	while (list($Chave, $Valor) = each($arrHashs)) 
		{
		$strValues .= ($strValues <> ''?',':'');
		$strValues .= "('".$Valor."','".$Chave."')";			
		}

	// Insiro em S.I. somente os registros inexistentes (que sobraram no array)
	if ($strValues <> '')
		{			
		$queryINS  = "INSERT INTO softwares_inventariados
							  (nm_software_inventariado,
							   te_hash)											
					  VALUES 	  ".$strValues;					                  
		$resultINS = mysql_query($queryINS);															
		}

	// Consulto novamente o banco, agora para montar inser��o em S.I.E.
	$querySEL  = "SELECT	*
				  FROM 		softwares_inventariados
				  WHERE		te_hash in (".$strTripaHASH.")";
	$resultSEL = mysql_query($querySEL);

	// Monto os valores para inser��o em S.I.E.
	$strValues = '';
	while ($v_reg = mysql_fetch_array($resultSEL))
		{
		$strValues .= ($strValues <> ''?',':'');
		$strValues .= "(".$arrDadosComputador['id_computador'].",".$v_reg['id_software_inventariado'].")";
		}

	if ($strValues <> '')
		{
		$queryINS  = "INSERT INTO softwares_inventariados_estacoes 
							  (id_computador,
							   id_software_inventariado)											
					  VALUES 	  ".$strValues;					                  
		$resultINS = mysql_query($queryINS);												
		}
	}


// =====================
// VARI�VEIS DE AMBIENTE
// =====================
$te_variaveis_ambiente = DeCrypt($key,$iv,$_POST['te_variaveis_ambiente'],$v_cs_cipher,$v_cs_compress,$strPaddingKey);		

while (substr(trim($te_variaveis_ambiente),0,1)=='=')	
	$te_variaveis_ambiente = substr(trim($te_variaveis_ambiente),1);

if ($te_variaveis_ambiente <> '')
	{
	$queryDEL  = "DELETE FROM variaveis_ambiente_estacoes 
				  WHERE 	id_computador = ".$arrDadosComputador['id_computador'];					                  
	$resultDEL = mysql_query($queryDEL);									

	$v_array_te_variaveis_ambiente = explode('#',$te_variaveis_ambiente);			
	$strTripaHash    = '';
	$arrHashsNomes   = array();
	$arrHashsValores  = array();		
	// Crio uma tripa contendo os HASHS separados por v�rgulas
	// Crio um array com chaves HASH e valores <Nomes das Vari�veis de Ambiente>
	for ($intIndice=0; $intIndice < count($v_array_te_variaveis_ambiente); $intIndice ++)
		if ($v_array_te_variaveis_ambiente[$intIndice] <> '')
			{
			$arrVariavel = explode('=',$v_array_te_variaveis_ambiente[$intIndice]);
			$strHash = hash('md5',$arrVariavel[0]);
			$strTripaHASH .= ($strTripaHASH <> ''?',':'');
			$strTripaHASH .= "'".$strHash."'";
			$arrHashsNomes[$strHash]   = $arrVariavel[0]; // Armazeno no array o nome da variavel
			$arrHashsValores[$strHash] = $arrVariavel[1]; // Armazeno no array o valor da variavel				
			}

	// Consulto no banco a exist�ncia dos softwares inventariados
	$querySEL  = "SELECT	*
				  FROM 		variaveis_ambiente
				  WHERE		te_hash in (".$strTripaHASH.")";
	$resultSEL = mysql_query($querySEL);

	// Retiro os S.I. j� existentes do array para montar query de inser��o em S.I.
	while ($v_reg = mysql_fetch_array($resultSEL))
		unset($arrHashsNomes[$v_reg['te_hash']]); 
		
	$strValues  = '';			
	while (list($te_hash, $nm_variavel_ambiente) = each($arrHashsNomes)) 
		{
		$strValues .= ($strValues <> ''?',':'');
		$strValues .= "('".$nm_variavel_ambiente."','".$te_hash."')";			
		}

	// Insiro em V.A. somente os registros inexistentes (que sobraram no array)
	if ($strValues <> '')
		{			
		$queryINS  = "INSERT INTO variaveis_ambiente
							  (nm_variavel_ambiente,
							   te_hash)											
					  VALUES 	  ".$strValues;					                  
		$resultINS = mysql_query($queryINS);															
		}

	// Consulto novamente o banco, agora para montar inser��o em V.A.E.
	$querySEL  = "SELECT	*
				  FROM 		variaveis_ambiente
				  WHERE		te_hash in (".$strTripaHASH.")";
	$resultSEL = mysql_query($querySEL);

	// Monto os valores para inser��o em V.A.E.
	$strValues = '';
	while ($v_reg = mysql_fetch_array($resultSEL))
		{
		$strValues .= ($strValues <> ''?',':'');
		$strValues .= "(".$arrDadosComputador['id_computador'].",".$v_reg['id_variavel_ambiente'].",'".$arrHashsValores[$v_reg['te_hash']]."')";
		}

	if ($strValues <> '')
		{
		$queryINS = "INSERT INTO variaveis_ambiente_estacoes 
							  (id_computador,
							   id_variavel_ambiente,
							   vl_variavel_ambiente)																			   
				  VALUES 	  ".$strValues;					                  
		$resultINS = mysql_query($queryINS);												
		}		
	}

$strXML_Values .= '<STATUS>OK</STATUS>';					
require_once('../include/common_bottom.php');
?>