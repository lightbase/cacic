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
// Valores espec�ficos para trabalho com o PyCACIC - 04 de abril de 2008 - Rog�rio Lino - Dataprev/ES
if ($_POST['padding_key'])
	{
	// Valores espec�ficos para trabalho com o PyCACIC - 04 de abril de 2008 - Rog�rio Lino - Dataprev/ES
	$strPaddingKey 	= $_POST['padding_key']; // A vers�o inicial do agente em Python exige esse complemento na chave...
	}

autentica_agente($key,$iv,$v_cs_cipher,$v_cs_compress,$strPaddingKey);

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
	// Verifico se o computador em quest�o j� foi inserido anteriormente, e se n�o foi, insiro.
	$arrSO = inclui_computador_caso_nao_exista(	$te_node_address, 
											  	$id_so_new, 
											  	$te_so, 										
											  	$id_ip_rede, 
											  	$te_ip, 
											  	$te_nome_computador, 
											  	$te_workgroup);																				
	conecta_bd_cacic();


	// =================
	// SOFTWARES B�SICOS
	// =================	
	$query = "SELECT 	count(*) as num_registros
			  FROM 		versoes_softwares
			  WHERE		te_node_address = '" . $te_node_address . "'
						AND id_so = '" . $arrSO['id_so'] . "'";
	$result = mysql_query($query);
	if (mysql_result($result, 0, "num_registros") == 0) 
		{
		$query = "INSERT INTO versoes_softwares(te_node_address, id_so)
				  VALUES ('" . $te_node_address . "', '" . $arrSO['id_so'] . "'  )";
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
			   WHERE 	te_node_address    		 = '" . $te_node_address . "' and
						id_so                	 = '" . $arrSO['id_so'] . "'";
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
					  WHERE 	te_node_address = '".$te_node_address."' AND
								id_so = '".$arrSO['id_so']."'";					                  
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
			$strValues .= "('".$te_node_address."',".$arrSO['id_so'].",".$v_reg['id_software_inventariado'].")";
			}

		if ($strValues <> '')
			{
			$queryINS  = "INSERT INTO softwares_inventariados_estacoes 
					   			  (te_node_address,
								   id_so,
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
					  WHERE 	te_node_address = '".$te_node_address."' AND
							    id_so = '".$arrSO['id_so']."'";					                  
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
			$strValues .= "('".$te_node_address."',".$arrSO['id_so'].",".$v_reg['id_variavel_ambiente'].",'".$arrHashsValores[$v_reg['te_hash']]."')";
			}

		if ($strValues <> '')
			{
			$queryINS = "INSERT INTO variaveis_ambiente_estacoes 
								  (te_node_address,
								   id_so,
								   id_variavel_ambiente,
								   vl_variavel_ambiente)																			   
					  VALUES 	  ".$strValues;					                  
			$resultINS = mysql_query($queryINS);												
			}		
		}
				
	echo '<?xml version="1.0" encoding="iso-8859-1" ?><STATUS>OK</STATUS>';
	}
else
	echo '<?xml version="1.0" encoding="iso-8859-1" ?><STATUS>Chave (TE_NODE_ADDRESS + ID_SO) Inv�lida</STATUS>';			
?>