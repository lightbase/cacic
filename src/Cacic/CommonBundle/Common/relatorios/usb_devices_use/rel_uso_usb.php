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
session_start();
/*
 * verifica se houve login e também regras para outras verificações (ex: permissões do usuário)!
 */
if(!isset($_SESSION['id_usuario'])) 
  die('Acesso negado (Access denied)!');
else { // Inserir regras para outras verificações (ex: permissões do usuário)!
}

if($_POST['submit']) 
	{
	$_SESSION["list2"] = $_POST['list2'];
	$_SESSION["list4"] = $_POST['list4'];
	$_SESSION["list6"] = $_POST['list6'];
	$_SESSION["list8"] = $_POST['list8'];	
	$_SESSION["list12"] = $_POST['list12'];		
	$_SESSION["cs_situacao"] = $_POST["cs_situacao"];

	// Aqui eu inverto as datas para YYYYMMDD
	$v_elementos = explode("/",$_POST['date_input1']);
	$v_data_ini = $v_elementos[2] .'/'. $v_elementos[1] .'/'. $v_elementos[0];	
 	$_SESSION["data_ini"] = $v_data_ini;
	$v_elementos = explode("/",$_POST['date_input2']);
	$v_data_fim = $v_elementos[2] .'/'. $v_elementos[1] .'/'. $v_elementos[0];
 	$_SESSION["data_fim"] = $v_data_fim;
	}
 
require_once('../../include/library.php');
AntiSpy();
conecta_bd_cacic();

$redes_selecionadas = '';
if ($_SESSION['cs_nivel_administracao']<>1 && $_SESSION['cs_nivel_administracao']<>2)
	{
	//if($_SESSION["cs_situacao"] == 'S') 
		//{
		// Aqui pego todas as redes selecionadas e faço uma query p/ condição de redes
		$redes_selecionadas = "'" . $_SESSION["list2"][0] . "'";
		for( $i = 1; $i < count($_SESSION["list2"] ); $i++ ) 
			$redes_selecionadas = $redes_selecionadas . ",'" . $_SESSION["list2"][$i] . "'";

		$query_redes = 'AND id_ip_rede IN ('. $redes_selecionadas .')';
		//}	
	}
else
	{
	// Aqui pego todos os locais selecionados e faço uma query p/ condição de redes/locais
	$locais_selecionados = "'" . $_SESSION["list12"][0] . "'";
	for( $i = 1; $i < count($_SESSION["list12"] ); $i++ ) 
		$locais_selecionados .= ",'" . $_SESSION["list12"][$i] . "'";

	$query_redes = 'AND comp.id_ip_rede = redes.id_ip_rede AND 
						redes.id_local IN ('. $locais_selecionados .') AND
						redes.id_local = locais.id_local ';
	$select = ' ,sg_local as Local ';	
	$from = ' ,redes,locais ';			
	}

// Aqui pego todos os SO selecionados
$so_selecionados = "'" . $_SESSION["list4"][0] . "'";
for( $i = 1; $i < count($_SESSION["list4"] ); $i++ ) 
	$so_selecionados = $so_selecionados . ",'" . $_SESSION["list4"][$i] . "'";

if ($_GET['orderby']) 
	$orderby = $_GET['orderby'];
else 
	$orderby = '1';

		
   $query =  "SELECT 
   			  distinct 		comp.te_nome_computador,
							comp.id_so, 
							comp.te_node_address " . 
							$select . " 
			  FROM 			usb_logs usblogs, 
			  				computadores comp ".
							$from . " 
			  WHERE 		usblogs.dt_event >= '" . $_SESSION["data_ini"] . "' AND 
							usblogs.dt_event <= '" . $_SESSION["data_fim"] . "' AND 
							comp.te_node_address = usblogs.te_node_address AND 
							comp.id_so = usblogs.id_so ".
							$query_redes. " 
			  ORDER BY 		$orderby ";
//echo $query . '<br>';
	$result = mysql_query($query) or die ('Erro no select ou sua sessão expirou!');

if (mysql_num_rows($result) > 0)
	{
	?>
    <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
    <html>
    <head>
    <title>Relat&oacute;rio de Utiliza&ccedil;&atilde;o de Dispositivos USB</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <script language="JavaScript" type="text/JavaScript">
    <!--
    function MM_openBrWindow(theURL,winName,features) 
        {
        window.open(theURL,winName,features); //v2.0
        }
    //-->
    </script>
    </head>
    
    <body bgcolor="#FFFFFF" topmargin="5">
    <table border="0" align="left" cellpadding="0" cellspacing="0" bordercolor="#999999">
      <tr bgcolor="#E1E1E1"> 
        <td rowspan="5" bgcolor="#FFFFFF"><img src="../../imgs/cacic_logo.png" width="50" height="50"></td>
        <td rowspan="5" bgcolor="#FFFFFF">&nbsp;</td>
        <td bgcolor="#FFFFFF">&nbsp;</td>
      </tr>
      <tr bgcolor="#E1E1E1"> 
        <td nowrap bgcolor="#FFFFFF"><div align="center"><font color="#333333" size="4" face="Verdana, Arial, Helvetica, sans-serif"><strong>CACIC 
        - Relat&oacute;rio de Utiliza&ccedil;&atilde;o de Dispositivos USB</strong></font></div></td>
      </tr>
      <tr> 
        <td height="1" bgcolor="#333333"></td>
      </tr>
      <tr> 
        <td><p><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Gerado 
            em <? echo date("d/m/Y à\s H:i"); ?></font></p></td>
      </tr>
    </table>
    <br>
    <br>
    <br>
    <br>
    
    <?
	$cor = 0;
	$num_registro = 1;
	
?>
<table border="0" align="center" cellpadding="0" cellspacing="1">
  <tr> 
    <td align="center" nowrap></td>
  </tr>
  <tr> 
    <td height="1" bgcolor="#333333"></td>
  </tr>
  <tr> 
    <td> <table border="0" cellpadding="2" cellspacing="0" bordercolor="#333333" align="center">
        <tr bgcolor="#E1E1E1"> 
          <td align="center"  nowrap>&nbsp;</td>
          <td align="center"  nowrap><div align="left"><strong></strong></div></td>
          <td align="center"  nowrap>&nbsp;</td>
          <td align="center"  nowrap bgcolor="#E1E1E1" class="cabecalho_tabela"><div align="left">Nome da M&aacute;quina</div></td>
          <td nowrap >&nbsp;</td>
          <td nowrap class="cabecalho_tabela"><div align="right">IP</div></td>
          <td nowrap >&nbsp;</td>
          <td nowrap class="cabecalho_tabela"><div align="right">Sub-Rede</div></td>
          <td nowrap >&nbsp;</td>
          <td nowrap class="cabecalho_tabela"><div align="right">Local</div></td>
          <td nowrap >&nbsp;</td>
          <td nowrap class="cabecalho_tabela"><div align="right">Data/Hora</div></td>
          <td nowrap >&nbsp;</td>
          <td nowrap class="cabecalho_tabela"><div align="right">Dispositivo</div></td>
          <td nowrap >&nbsp;</td>
          <td nowrap class="cabecalho_tabela"><div align="right">Evento</div></td>
          <td nowrap >&nbsp;</td>
        </tr>
        <?  
	$Cor = 0;
	$NumRegistro = 1;
	
	while($row = mysql_fetch_array($result)) {
		  
	 ?>
        <tr <? if ($Cor) { echo 'bgcolor="#E1E1E1"'; } ?>> 
          <td nowrap>&nbsp;</td>
          <td nowrap class="opcao_tabela"><div align="left"><? echo $NumRegistro; ?></div></td>
          <td nowrap>&nbsp;</td>
          <td nowrap class="opcao_tabela"><div align="left"><a href="../../relatorios/computador/computador.php?te_node_address=<? echo $row['te_node_address'];?>&id_so=<? echo $row['id_so'];?>" target="_blank"><? echo $row['te_nome_computador']; ?></a></div></td>
          <td nowrap>&nbsp;</td>
          <td nowrap class="opcao_tabela"><div align="right"><a href="../../relatorios/computador/computador.php?te_node_address=<? echo $row['te_node_address'];?>&id_so=<? echo $row['id_so'];?>" target="_blank"><? echo $row['te_nome_computador']; ?></a></div></td>
          <td nowrap>&nbsp;</td>
          <td nowrap class="opcao_tabela"><div align="right"><a href="../../relatorios/computador/computador.php?te_node_address=<? echo $row['te_node_address'];?>&id_so=<? echo $row['id_so'];?>" target="_blank"><? echo $row['te_nome_computador']; ?></a></div></td>
          <td nowrap>&nbsp;</td>
          <td nowrap class="opcao_tabela"><div align="right"><a href="../../relatorios/computador/computador.php?te_node_address=<? echo $row['te_node_address'];?>&id_so=<? echo $row['id_so'];?>" target="_blank"><? echo $row['te_nome_computador']; ?></a></div></td>
          <td nowrap>&nbsp;</td>
          <td nowrap class="opcao_tabela"><div align="right"><a href="../../relatorios/computador/computador.php?te_node_address=<? echo $row['te_node_address'];?>&id_so=<? echo $row['id_so'];?>" target="_blank"><? echo $row['te_nome_computador']; ?></a></div></td>
          <td nowrap>&nbsp;</td>
          <td nowrap class="opcao_tabela"><div align="right"><a href="../../relatorios/computador/computador.php?te_node_address=<? echo $row['te_node_address'];?>&id_so=<? echo $row['id_so'];?>" target="_blank"><? echo $row['te_nome_computador']; ?></a></div></td>
          <td nowrap>&nbsp;</td>
          <td nowrap class="opcao_tabela"><div align="right"><a href="../../relatorios/computador/computador.php?te_node_address=<? echo $row['te_node_address'];?>&id_so=<? echo $row['id_so'];?>" target="_blank"><? echo $row['te_nome_computador']; ?></a></div></td>
          <td nowrap>&nbsp;</td>
          <? 
	$Cor=!$Cor;
	$NumRegistro++;
	}
	
?>
      </table>
	</p>
	<p></p>
	<p align="left"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Relat&oacute;rio 
	  gerado pelo <strong>CACIC</strong> - Configurador Autom&aacute;tico e Coletor 
	  de Informa&ccedil;&otilde;es Computacionais</font><br>
	  <font size="1" face="Verdana, Arial, Helvetica, sans-serif">Software desenvolvido 
	  pela Dataprev - Unidade Regional Esp&iacute;rito Santo</font></p>
	</body>
	</html>    
    <?		
	}
else
	{
    header ("Location: ../../include/nenhum_registro_encontrado.php?chamador=../relatorios/usb_devices_use/index.php&tempo=1");						
	}
?>
