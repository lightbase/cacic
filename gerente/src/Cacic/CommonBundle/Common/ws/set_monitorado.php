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

autentica_agente($key,$iv,$v_cs_cipher,$v_cs_compress, $strPaddingKey);

$te_node_address 			= DeCrypt($key,$iv,$_POST['te_node_address']	 ,$v_cs_cipher,$v_cs_compress,$strPaddingKey); 
$id_so_new         			= DeCrypt($key,$iv,$_POST['id_so']				 ,$v_cs_cipher,$v_cs_compress,$strPaddingKey); 
$te_so           			= DeCrypt($key,$iv,$_POST['te_so']				 ,$v_cs_cipher,$v_cs_compress,$strPaddingKey); 
$te_ip           			= DeCrypt($key,$iv,$_POST['te_ip']				 ,$v_cs_cipher,$v_cs_compress,$strPaddingKey); 
$id_ip_rede     			= DeCrypt($key,$iv,$_POST['id_ip_rede']			 ,$v_cs_cipher,$v_cs_compress,$strPaddingKey);
$te_nome_computador			= DeCrypt($key,$iv,$_POST['te_nome_computador']	 ,$v_cs_cipher,$v_cs_compress,$strPaddingKey); 
$te_workgroup 				= DeCrypt($key,$iv,$_POST['te_workgroup']		 ,$v_cs_cipher,$v_cs_compress,$strPaddingKey); 
$v_tripa_monitorados 		= DeCrypt($key,$iv,$_POST['te_tripa_monitorados'],$v_cs_cipher,$v_cs_compress,$strPaddingKey);


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

	$v_tripa_nao_achados = ''; //Conter� os registros n�o encontrados na base
	$v_conta_tripa_monitorados = 0;
	
	if ($v_tripa_monitorados<>'')
		{	
		
		$query_mon = "SELECT id_aplicativo
					  FROM   aplicativos_monitorados
					  WHERE  id_so = ".$arrSO['id_so']." AND te_node_address = '".$te_node_address."'";		  
				  
		conecta_bd_cacic();			  	
		$result_mon = mysql_query($query_mon);
	
		//Buscar todos os registros deste computador em aplicativos_monitorados.
		//Montar vetor com as informa��es acima.
		//Atualizar os registros existentes e acrescentar os inexistentes.
		$v_array_monitorados = explode("#",$v_tripa_monitorados);
		$v_tripa_codigos_pesquisados = '';
		while ($v_reg_mon = mysql_fetch_array($result_mon))
			{
			if ($v_tripa_codigos_pesquisados <> '') $v_tripa_codigos_pesquisados .= '#';		
			$v_tripa_codigos_pesquisados .= $v_reg_mon['id_aplicativo']; 
			
			for ($v1=0;$v1 < count($v_array_monitorados);$v1 ++)
				{				
				$v_array_itens_monitorados =	explode(",",$v_array_monitorados[$v1]);
				if ($v_array_itens_monitorados[0]==$v_reg_mon['id_aplicativo'])
					{
					$query = "UPDATE aplicativos_monitorados 
									 SET 
									 te_licenca        = '".$v_array_itens_monitorados[1]."',							 
									 cs_instalado      = '".($v_array_itens_monitorados[2]==''?'N':$v_array_itens_monitorados[2])."',  								 
									 te_versao         = '".$v_array_itens_monitorados[3]."',
									 te_ver_engine     = '".$v_array_itens_monitorados[4]."',
									 te_ver_pattern    = '".$v_array_itens_monitorados[5]."'
							  WHERE  te_node_address   = '".$te_node_address."'
									 AND id_so         = '".$arrSO['id_so']."' 
									 AND id_aplicativo = ".$v_reg_mon['id_aplicativo'];
	
					conecta_bd_cacic();			  				
					$result = mysql_query($query);
					$v1 = count($v_array_monitorados);
					}
				}
			}	
	
		$v_array_codigos_pesquisados = explode('#',str_replace(' ','',$v_tripa_codigos_pesquisados));
		$v_tripa_codigos_monitorados = '';
		for ($v_moni=0;$v_moni < count($v_array_monitorados);$v_moni++)
			{
			$v_array_monitorados_tmp = explode(',',$v_array_monitorados[$v_moni]);
			if ($v_tripa_codigos_monitorados <> '') $v_tripa_codigos_monitorados .= '#';		
			$v_tripa_codigos_monitorados .= $v_array_monitorados_tmp[0]; 			
			}
				
		$v_array_codigos_monitorados = explode('#',str_replace(' ','',$v_tripa_codigos_monitorados));
		$v_array_diferentes = array_diff($v_array_codigos_monitorados,$v_array_codigos_pesquisados);
	
		$v_tripa_diferentes = implode('#',$v_array_diferentes);
		$v_array_diferentes = explode('#',str_replace(' ','',$v_tripa_diferentes));
	
		for ($v_dife=0;$v_dife < count($v_array_diferentes);$v_dife++)
			{		
			for ($v_moni=0;$v_moni < count($v_array_monitorados);$v_moni++)
				{
				$v_array_itens_para_insercao =	explode(',',$v_array_monitorados[$v_moni]);						
				if ($v_array_itens_para_insercao[0]==$v_array_diferentes[$v_dife])
					{
					$query = "INSERT INTO 	aplicativos_monitorados 
											(te_node_address,
											 id_so,
											 id_aplicativo,
											 te_licenca,								 
											 cs_instalado,
											 te_versao,
											 te_ver_engine,
											 te_ver_pattern)											
						  VALUES 		('".$te_node_address."', 
										 '".$arrSO['id_so']."',".
										 $v_array_itens_para_insercao[0].",'".
										 $v_array_itens_para_insercao[1]."','".
										 $v_array_itens_para_insercao[2]."','".
										 $v_array_itens_para_insercao[3]."','".
										 $v_array_itens_para_insercao[4]."','".
										 $v_array_itens_para_insercao[5]."')";
	
					$result = mysql_query($query);			
					$v_moni = count($v_array_monitorados);
					}
				}
			}
		}
	
	echo '<?xml version="1.0" encoding="iso-8859-1" ?><STATUS>OK</STATUS>';
	}
else
	echo '<?xml version="1.0" encoding="iso-8859-1" ?><STATUS>Chave (TE_NODE_ADDRESS + ID_SO) Inv�lida</STATUS>';		
?>