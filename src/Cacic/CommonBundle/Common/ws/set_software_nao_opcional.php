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
// Essa coleta n�o � opcional, ou seja, o administrador n�o tem como desabilit�-la.
// Por isso foi necess�rio cri�-la de forma independente do script set_software.php.

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
$te_ip           	= DeCrypt($key,$iv,$_POST['te_ip']				,$v_cs_cipher,$v_cs_compress,$strPaddingKey); 
$te_nome_computador = DeCrypt($key,$iv,$_POST['te_nome_computador']	,$v_cs_cipher,$v_cs_compress,$strPaddingKey); 
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
	conecta_bd_cacic();
	
	$query = "	UPDATE 	computadores 
				SET		te_versao_cacic   = '" . DeCrypt($key,$iv,$_POST['te_versao_cacic'],$v_cs_cipher,$v_cs_compress,$strPaddingKey) . "'  
				WHERE 	te_node_address = '" . $te_node_address . "' and
						id_so           = '" . $arrSO['id_so'] . "'";
	$result = mysql_query($query);
	
	echo '<?xml version="1.0" encoding="iso-8859-1" ?><STATUS>OK</STATUS>';
	}
else
	echo '<?xml version="1.0" encoding="iso-8859-1" ?><STATUS>Chave (TE_NODE_ADDRESS + ID_SO) Inv�lida</STATUS>';				

?>