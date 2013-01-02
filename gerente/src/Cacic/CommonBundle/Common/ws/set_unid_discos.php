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
// Perdido? http://www.zend.com/zend/art/parsing.php
// http://www.zend.com/zend/tut/tutbarlach.php

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
	// Tiro os escapes que o post automaticamente coloca.
	//$xmlSource=stripslashes(DeCrypt($key,$iv,$_POST['unidades'],$v_cs_cipher,$v_cs_compress));
	//$strTripaDados=stripslashes(DeCrypt($key,$iv,$_POST['unidades'],$v_cs_cipher,$v_cs_compress));	
	$strTripaDados = DeCrypt($key,$iv,$_POST['UnidadesDiscos'],$v_cs_cipher,$v_cs_compress,$strPaddingKey);		
	
	
	//First we define a number of variables to store the data from each element
	/*
	$te_node_address='';
	$id_so='';
	*/
	/* COMENTADO A PARTIR DAQUI POR OCASIAO DA RETIRADA DO TRATAMENTO POR XML - 25/01/2008 - Anderson Peterle	
	$te_letra='';
	$id_tipo_unid_disco='';
	$cs_sist_arq='';
	$nu_serial='';
	$nu_capacidade='';
	$nu_espaco_livre='';
	$te_unc='';
	
	
	$elementoAtual='';	//holds the name of the current element
	$registro=array(); 	//array to hold all the data
	//$elementos=array('te_node_address', 'id_so', 'te_letra', 'cs_sist_arq', 'nu_serial', 'nu_capacidade', 'nu_espaco_livre', 'te_unc', 'id_tipo_unid_disco');
	$elementos=array('te_letra', 'cs_sist_arq', 'nu_serial', 'nu_capacidade', 'nu_espaco_livre', 'te_unc', 'id_tipo_unid_disco');
	// Importante: lembre-se de que o �ltimo elemento deve ser comum �s duas plataformas (9X e NT). O documento
	// XML enviado difere entre essas duas plataformas. M�quinas NT like n�o enviam 'cs_tipo_permissao', 'in_senha_leitura' e nem 'in_senha_escrita'.
	
	/*	The start Element Handler	
	This is where we store the element name, currently being parsed, in $elementoAtual.
	the character data handler uses  this to identify the element.
	This is also where we get the attribute, if any. */
	/* COMENTADO A PARTIR DAQUI POR OCASIAO DA RETIRADA DO TRATAMENTO POR XML - 25/01/2008 - Anderson Peterle	
	function startElement($parser,$name,$attr)
		{
		$GLOBALS['elementoAtual']=$name;	
		}
	
	
	/*	The character data Handler
		Depending on what the elementoAtual is, the handler assigns the value to the appropriate variable */
	/* COMENTADO A PARTIR DAQUI POR OCASIAO DA RETIRADA DO TRATAMENTO POR XML - 25/01/2008 - Anderson Peterle		
	function characterData($parser, $data) 
		{
		foreach($GLOBALS['elementos'] as $elemento)
			{
			if ($GLOBALS["elementoAtual"] == $elemento) 
				{
				$GLOBALS[$elemento] .= addslashes($data);		
				}  // Esse addslashes resolve os problemas da strings como "c:\".
			}
		}
	
	
	function endElement($parser,$name)
		{
		/*If the element being parsed is a 'in_senha_escrita' it means that the
		parser has completed parsing. We can then store the data in our array $registro[ ]   */
		//  if(strcmp($name,'te_node_address')==0) { $GLOBALS['te_node_address'] = 
	/* COMENTADO A PARTIR DAQUI POR OCASIAO DA RETIRADA DO TRATAMENTO POR XML - 25/01/2008 - Anderson Peterle	
	  	if(strcmp($name,'id_tipo_unid_disco')==0) 
			{
			foreach($GLOBALS['elementos'] as $elemento)
				{
				$temp[$elemento]=$GLOBALS[$elemento];							
				}
			$GLOBALS['registro'][]=$temp;
	   		}				
	
		  /*After parsing a movie we reset the rest of the globals.*/
	/* COMENTADO A PARTIR DAQUI POR OCASIAO DA RETIRADA DO TRATAMENTO POR XML - 25/01/2008 - Anderson Peterle		  
		  if(strcmp($name,'unidade')==0)
		  		{
	  //        $GLOBALS['te_node_address']=""; Aten��o, n�o posso limpar essa vari�vel.
	  //        $GLOBALS['id_so']=""; Aten��o, n�o posso limpar essa vari�vel.
				$GLOBALS['te_letra']="";
				$GLOBALS['cs_sist_arq']="";
				$GLOBALS['nu_serial']="";
				$GLOBALS['nu_capacidade']="";
				$GLOBALS['nu_espaco_livre']="";
				$GLOBALS['te_unc']="";
				$GLOBALS['id_tipo_unid_disco']="";
		  		}
		}
	
	function parseFile()
		{
		global $xmlSource,$registro;
	
		/*Creating the xml parser*/
	/* COMENTADO A PARTIR DAQUI POR OCASIAO DA RETIRADA DO TRATAMENTO POR XML - 25/01/2008 - Anderson Peterle		
		$xml_parser=xml_parser_create();
		
		/*Register the handlers*/
	/* COMENTADO A PARTIR DAQUI POR OCASIAO DA RETIRADA DO TRATAMENTO POR XML - 25/01/2008 - Anderson Peterle		
		xml_set_element_handler($xml_parser,"startElement","endElement");
		xml_set_character_data_handler($xml_parser,"characterData");
		
		/*Disables case-folding. Needed for this example*/
	/* COMENTADO A PARTIR DAQUI POR OCASIAO DA RETIRADA DO TRATAMENTO POR XML - 25/01/2008 - Anderson Peterle		
		xml_parser_set_option($xml_parser,XML_OPTION_CASE_FOLDING,false);
		
		if(!xml_parse($xml_parser,$xmlSource,true))
			die(sprintf("Problema com XML => Linha: %d Coluna: %d ", xml_get_current_line_number($xml_parser), xml_get_current_column_number($xml_parser)) . ' Mensagem: '.xml_error_string(xml_get_error_code($xmlparser)));
	
		xml_parser_free($xml_parser);
		return $registro;
		}
	*/
	conecta_bd_cacic();
	//$result=parseFile();
	
	// Deleto todos os compartilhamentos desse computador, antes de inserir os atualizados.
	$query = "	DELETE 	FROM unidades_disco 
				WHERE 	te_node_address = '" . $te_node_address . "'
				AND 	id_so = '" . $arrSO['id_so'] . "'";
	mysql_query($query);
	
	// Agora insiro.
	//foreach($result as $arr)
	$arrUnidadesDiscos = explode('<REG>',$strTripaDados);

	$strValues = '';	
	// Agora insiro todos os compartilhamentos.
	for ($intIndice=0; $intIndice < count($arrUnidadesDiscos); $intIndice++)
		{
		/*
		ATEN��O: O agente envia as informa��es serializadas com o seguinte conte�do:
        te_letra			+ '<FIELD>' +
		id_tipo_unid_disco	+ '<FIELD>' +
		cs_sist_arq			+ '<FIELD>' +
		nu_serial			+ '<FIELD>' +
		nu_capacidade		+ '<FIELD>' +
		nu_espaco_livre		+ '<FIELD>' +
		te_unc				
		*/											  
		$arrCamposUnidadesDiscos = explode('<FIELD>',$arrUnidadesDiscos[$intIndice]);
		$strValues .= ($strValues <> ''?',':'');
		$strValues .= '( "'.$te_node_address.'",'.
					  	'"'.$arrSO['id_so'].'",'.
						'"'.$arrCamposUnidadesDiscos[0].'",'.
						'"'.$arrCamposUnidadesDiscos[1].'",'.
						'"'.$arrCamposUnidadesDiscos[2].'",'.
						'"'.$arrCamposUnidadesDiscos[3].'",'.
						'"'.$arrCamposUnidadesDiscos[4].'",'.
						'"'.$arrCamposUnidadesDiscos[5].'",'.
						'"'.$arrCamposUnidadesDiscos[6].'")';
						
		}
	if ($strValues <> '')
		{
		$query = "INSERT INTO unidades_disco 
							(te_node_address, 
							id_so,
							te_letra,
							id_tipo_unid_disco, 
							cs_sist_arq, 
							nu_serial, 
							nu_capacidade, 
							nu_espaco_livre,
							te_unc) 
					VALUES " . $strValues;
		mysql_query($query);
		}
	
	echo '<?xml version="1.0" encoding="iso-8859-1" ?><STATUS>OK</STATUS>';
	}
else
	echo '<?xml version="1.0" encoding="iso-8859-1" ?><STATUS>Chave (TE_NODE_ADDRESS + ID_SO) Inv�lida</STATUS>';						
?>
