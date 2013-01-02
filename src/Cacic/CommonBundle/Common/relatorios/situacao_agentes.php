<html>
<head>
<title>A&ccedil;&otilde;es/Configura&ccedil;&otilde;es</title>
<script LANGUAGE="JavaScript">
<!-- Begin
function open_window(theURL) { 
    window.open(theURL,'','width=550,height=450');
}
//-->
</script>
</head>
<body background="../imgs/linha_v.gif">
<table width="90%" border="0" align="center">
  <tr> 
    <td><font color="#FF0000" size="4" face="Verdana, Arial, Helvetica, sans-serif"><strong>Situa&ccedil;&atilde;o 
      dos agentes do CACIC</strong></font></td>
  </tr>
  <tr> 
    <td><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Este relat&oacute;rio 
      consulta todos os computadores de uma rede que est&atilde;o ligados neste 
      momento e identifica se o agente do CACIC est&aacute; ou n&atilde;o ativo 
      nesses computadores. Isso pode ser &uacute;til para identificar os computadores 
      onde o CACIC ainda n&atilde;o foi instalado.</font></td>
  </tr>
  <tr> 
    <td>&nbsp;</td>
  </tr>
  <tr> 
    <td><form name="form1" method="post" action="">
        <table width="0%" border="0" align="center">
          <tr> 
            <td><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Informe 
              a rede que ser&aacute; verificada:</font></td>
          </tr>
          <tr> 
            <td> 
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
 */

  															require_once($_SERVER['DOCUMENT_ROOT'] . 'include/library.php');
															AntiSpy();
		  													conecta_bd_cacic();
																	$query = ' SELECT id_ip_rede, nm_rede
              												  FROM redes
              												 	ORDER BY nm_rede'; 
        									$result = mysql_query($query) or die('Erro na consulta à tabela "redes" ou sua sessão expirou!'); ?>
              <select name="rede" id="rede">
                <?	while ($row = mysql_fetch_array($result)) { 	?>
                <option value="<? echo $row['id_ip_rede']; ?>"><? echo $row["nm_rede"] . '  (' . $row['id_ip_rede'] . ')' ; ?></option>
                <? } ?>
              </select> <font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp; 
              </font></td>
          </tr>
          <tr> 
            <td><div align="center"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"> 
                <input name="submit" type="submit" id="submit4" value="    Verificar Rede    ">
                </font></div></td>
          </tr>
        </table>
      </form></td>
  </tr>
  <tr> 
    <td>&nbsp;</td>
  </tr>
</table>

<?
set_time_limit(120);
if($_POST['submit']) {

  /* Testar:
		   A existência do nmap;
					A correta execução do nmap;
					A existência do arquivo de log;  */		
					
			$rede	= explode('.', $_POST['rede']);
			$rede = $rede['0'] . '.' . $rede['1'] .  '.' . $rede['2'] . '.1-254';
					
			$xmlSource='/tmp/nmap_cacic.log';		
   `nmap $rede -p8181 -n -TInsane -oX $xmlSource`;

// Perdido sobre xml? http://www.zend.com/zend/art/parsing.php
// http://www.zend.com/zend/tut/tutbarlach.php

$registros=array();
$registro=array('host', 'state', 'port');
$elementoAtual = '';
$host='';
$state='';
$port='';



function parseFile(){
	global $xmlSource, $registros;

	/*Creating the xml parser*/
	$xml_parser=xml_parser_create();
	
	/*Register the handlers*/
	xml_set_element_handler($xml_parser,"startElement","endElement");
	xml_set_character_data_handler($xml_parser,"characterData");
	
	/*Disables case-folding. Needed for this example*/
	xml_parser_set_option($xml_parser,XML_OPTION_CASE_FOLDING,false);

	/* Open the xml file and pass it to the parser in 4k chunks.  Return 
	 * an error if file cannot be opened.  */ 
 if (!($fp = fopen($xmlSource,"r"))) { 
    die("Não consigo abrir '".$xmlSource."' ou sua sessão expirou!"); 
	}
	
	while (($data = fread($fp,4096))) { 
   if(!xml_parse($xml_parser,$data, feof($fp))) { 
	     die(sprintf("Erro XML na linha %d e coluna %d. (ou sua sessão expirou!) ", xml_get_current_line_number($xml_parser), xml_get_current_column_number($xml_parser)));
   }
	}		

	 xml_parser_free($xml_parser);
	 return $registros;
}




/*	The start Element Handler	
This is where we store the element name, currently being parsed, in $elementoAtual.
the character data handler uses  this to identify the element.
This is also where we get the attribute, if any. */
function startElement($parser,$name,$attr){
  	$GLOBALS['elementoAtual'] = $name;
			if ($name == 'status') 	$GLOBALS['state'] = $attr["state"];
			if ($name == 'address') 	$GLOBALS['host'] = $attr["addr"]; 
			if ($name == 'state') 	$GLOBALS['port'] = $attr['state']; 
}


/*	The character data Handler
	Depending on what the elementoAtual is, the handler assigns the value to the appropriate variable */
function characterData($parser, $data) {
			foreach($GLOBALS['registro'] as $campo){
						if ($GLOBALS['elementoAtual'] == $campo) {	$GLOBALS[$campo] .= addslashes($data);		}  // Esse addslashes resolve os problemas da strings como "c:\".
			}
}


function endElement($parser,$name){
/*If the element being parsed is a 'in_senha_escrita' it means that the
parser has completed parsing. We can then store the data in our array $registro[ ]   */
  if ($name == 'host') {
						foreach ($GLOBALS['registro'] as $campo) {
//						   echo $GLOBALS[$campo]; 
						   $aux[$campo] = $GLOBALS[$campo]; 
						}
						if (trim($aux['state']) == 'up') { 	$GLOBALS['registros'][]=$aux; }
						$GLOBALS['host']='';
						$GLOBALS['state']='';
						$GLOBALS['port']='';
						
			}
}
?> 
<table border="0" align="center" cellpadding="0" cellspacing="1">
  <tr> 
    <td height="1" bgcolor="#333333"></td>
  </tr>
  <tr> 
    <td> <table border="0" cellpadding="3" cellspacing="0" bordercolor="#333333" align="center">
        <tr bgcolor="#E1E1E1"> 
          <td align="center"  nowrap>&nbsp;</td>
          <td align="center"  nowrap>&nbsp;</td>
          <td align="center"  nowrap><div align="left"><strong><font color="#333333" size="2" face="Verdana, Arial, Helvetica, sans-serif"> 
              TCP/IP </font></strong></div></td>
        </tr>
        <? 
$cor = 0;
$num_registro = 1;
$cont_ativado = 0;
$cont_desativado = 0;
		
$result = parseFile();
foreach ($result as $arr) { ?>
        <tr <? if ($cor) { echo 'bgcolor="#E1E1E1"'; } ?>> 
          <td nowrap><div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><? echo $num_registro; ?>&nbsp;</font></div></td>
          <td> 
            <?
									  $arr['port'] = strtolower(trim($arr['port']));
											if ($arr['port'] == 'closed') {
											   $checkbox = '<img src="/cacic2/imgs/unchecked.gif">';
														$alerta =  '<img src="/cacic2/imgs/alerta_vermelho.gif">';
														$cont_desativado++;
											}
								   else if ($arr['port'] == 'open') {
											   $checkbox = '<img src="/cacic2/imgs/checked.gif">';
														$alerta =  '<img src="/cacic2/imgs/alerta_verde.gif">';
														$cont_ativado++;
											}
								   else $checkbox = $arr['port']; 	
										echo $alerta;
										?>
          </td>
          <td><div align="left"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><? echo $arr['host']; ?></font></div></td>
        </tr>
        <? 
		$cor=!$cor;
		$num_registro++;
} ?>
      </table></td>
  </tr>
  <tr> 
    <td height="1" bgcolor="#333333"></td>
  </tr>
  </table>
<p>&nbsp;</p>
<p align="center"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Neste 
  momento, <strong><? echo ($num_registro - 1); ?></strong> computadores ou dispositivos 
  <br>
  de rede (roteadores, switchs, etc) foram identificados <br>
  na rede. Deste total, <strong><? echo $cont_ativado; ?></strong> t&ecirc;m o 
  agente do CACIC instalado<br>
  e ativo e <strong><? echo $cont_desativado; ?> </strong> n&atilde;o t&ecirc;m 
  o CACIC instalado ou ativo.</font></p>
<? } ?>