<?
 /* 
 Copyright 2000, 2001, 2002, 2003, 2004, 2005 Dataprev - Empresa de Tecnologia e Informações da Previdência Social, Brasil

 Este arquivo é parte do programa CACIC - Configurador Automático e Coletor de Informações Computacionais

 O CACIC é um software livre; você pode redistribui-lo e/ou modifica-lo dentro dos termos da Licença Pública Geral GNU como 
 publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença, ou (na sua opnião) qualquer versão.

 Este programa é distribuido na esperança que possa ser  util, mas SEM NENHUMA GARANTIA; sem uma garantia implicita de 
 ADEQUAÇÂO a qualquer
 MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU para maiores detalhes.

 Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "LICENCA.txt", junto com este programa, se não, 
 escreva para a Fundação do Software
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

if($_POST['submit']) {
	$so             = $_POST['te_desc_so'];
        $placa_video    = $_POST['te_placa_video_desc'];
        $placa_rede     = $_POST['te_placa_rede_desc'];
        $cpu            = $_POST['te_cpu_desc'];
        $placa_mae      = $_POST['te_placa_mae_desc'];
        $modem          = $_POST['te_modem_desc'];
	$palavra_chave  = $_POST['palavra_chave'];
	$memoria        = $_POST['qt_mem_ram'];
	$software       = $_POST['te_software'];
	$software_valor = $_POST['te_software_valor'];
	
        $tem_escolha   = $so . $placa_video . $placa_rede . $cpu . $placa_mae . $modem;
        $descricao     = ""; 
}


require_once('../../include/library.php');
conecta_bd_cacic();

$pesquisa_avancada = true;

if ($pesquisa_avancada) {
  if ($software == '') {
  $query = " SELECT a.nm_rede AS REDE, b.te_desc_so AS Sistema_Operacional, concat(c.te_cpu_desc,'-', c.te_cpu_fabricante) AS CPU, 
	    concat(c.te_placa_mae_desc,'-', c.te_placa_mae_fabricante) AS Placa_Mae, c.qt_mem_ram AS Memoria, 
	    c.te_placa_video_desc AS Placa_Video,
            c.te_placa_rede_desc AS Placa_Rede, c.te_modem_desc AS Modem, c.id_so, c.te_node_address, c.te_nome_computador
            FROM redes a, so b, computadores c
            WHERE a.id_ip_rede = c.id_ip_rede AND b.id_so = c.id_so ";
  } else {
       $query = " SELECT a.nm_rede AS REDE, b.te_desc_so AS Sistema_Operacional, e.nm_software_inventariado AS Software,
             concat(c.te_cpu_desc,'-', c.te_cpu_fabricante) AS CPU, concat(c.te_placa_mae_desc,'-', 
	     c.te_placa_mae_fabricante) AS Placa_Mae, c.qt_mem_ram AS Memoria,  c.te_placa_video_desc AS Placa_Video, 
            c.te_placa_rede_desc AS Placa_Rede, c.te_modem_desc AS Modem, c.id_so, c.te_node_address, c.te_nome_computador
            FROM redes a, so b, computadores c, softwares_inventariados_estacoes d, softwares_inventariados e
            WHERE a.id_ip_rede = c.id_ip_rede AND b.id_so = c.id_so AND c.te_node_address = d.te_node_address AND
	          c.id_so = d.id_so AND d.id_software_inventariado = e.id_software_inventariado and ";
       $query = $query . "e.nm_software_inventariado like '%" . $software_valor . "%' ";    
   }
       	    
  if ($memoria != 0) {
    if ($memoria == "32") {$query = $query . " AND c.qt_mem_ram <= 48";}
    else if ($memoria == "64") {$query = $query . " AND c.qt_mem_ram between 49 and 96";}
    else if ($memoria == "128") {$query = $query . " AND c.qt_mem_ram between 97 and 192";}
    else if ($memoria == "256") {$query = $query . " AND c.qt_mem_ram between 193 and 384";}
    else if ($memoria == "512") {$query = $query . " AND c.qt_mem_ram between 385 and 768";}
    else if ($memoria == "1024") {$query = $query . " AND c.qt_mem_ram >= 769";}   
   $descricao .= "Memoria " . $memoria . " MBytes"; 
  }
  
  if ($tem_escolha != "") { 	    
	$query = $query . " AND (1 = 0 ";
	    }
	   
  if ($so == "S") {
    $query = $query . " or b.te_desc_so like '%" . $palavra_chave . "%' ";
    $descricao .= ", Sistema Operacional";
  }  
	    
  if ($placa_video == "S") {
    $query = $query . " or c.te_placa_video_desc like '%" . $palavra_chave . "%' ";
    $descricao .= ", Placa de Video";
  }
  if ($placa_rede == "S") {
    $query = $query . " or c.te_placa_rede_desc like '%" . $palavra_chave . "%' ";
    $descricao .= ", Placa de Rede";
  }
  if ($cpu == "S") {
    $query = $query . " or c.te_cpu_desc like '%" . $palavra_chave . "%' or c.te_cpu_fabricante like '%" . $palavra_chave . "%'";
    $descricao .= ", CPU (Processador)";
  }
  if ($placa_mae == "S") {
    $query = $query . " or c.te_placa_mae_desc like '%" . $palavra_chave . "%' or c.te_placa_mae_fabricante like '%" . 
                         $palavra_chave . "%'";
    $descricao .= ", Placa Mãe";
  }
  if ($modem == "S") {
    $query = $query . " or c.te_modem_desc like '%" . $palavra_chave . "%' ";
    $descricao .= ", Descricao";
  }

  if ($tem_escolha != "") { 
     $query = $query . ")";
  }
   
     $query = $query . " order by a.nm_rede, b.te_desc_so ";
    
//  echo $query;
//  echo $memoria;
//  echo $tem_escolha;
//  echo  $_POST['te_placa_video_desc'];
//  echo  $_POST['te_placa_rede_desc'];
//  echo  $_POST['te_cpu_desc'];
//  echo  $_POST['te_placa_mae_desc'];
//  echo  $_POST["te_modem_desc"];
//  echo  $palavra_chave;

	$result = mysql_query($query) or die ('Erro no select ou sua sessão expirou!');
	$tipo_historico = ' encontrados';
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title> Maquinas por Rede <? echo $tipo_historico?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body bgcolor="#FFFFFF">
<table border="0" align="default" cellpadding="0" cellspacing="0" bordercolor="#999999">
  <tr bgcolor="#E1E1E1"> 
    <td rowspan="5" bgcolor="#FFFFFF">&nbsp;</td>
    <td rowspan="5" bgcolor="#FFFFFF">&nbsp;</td>
    <td bgcolor="#FFFFFF">&nbsp;</td>
  </tr>
  <tr bgcolor="#E1E1E1"> 
    <td bgcolor="#FFFFFF"><font color="#333333" size="4" face="Verdana, Arial, Helvetica, sans-serif">
        <strong>Máquinas por Rede<? echo $tipo_historico?>
      </strong></font></td>
  </tr>
  <tr> 
    <td height="1" bgcolor="#333333"></td>
  </tr>
  <tr bgcolor="#E1E1E1"> 
    <td bgcolor="#FFFFFF"><font color="#333333" size="3" face="Verdana, Arial, Helvetica, sans-serif">
        <strong> Palavra chave : </strong><? echo $palavra_chave?> </font></td>
  </tr>
  <tr bgcolor="#E1E1E1"> 
    <td bgcolor="#FFFFFF"><font color="#333333" size="3" face="Verdana, Arial, Helvetica, sans-serif">
       <strong>Filtro : </strong><? echo $descricao?> </td>
  </tr>
  
  
  <tr> 
    <td><p>&nbsp;</p></td>
  </tr>
</table>
<p><br>
  <? 
$cor = 0;
$num_registro = 1;
$rede = '';

$fields=mysql_num_fields($result)-3;
if (mysql_num_rows($result) > 0) {
    
	echo '<table cellpadding="1" cellspacing="0" border="1" bordercolor="#999999" bordercolordark="#E1E1E1">
		 <tr bgcolor="#E1E1E1" >
		  <td nowrap align="left"><font size="2" face="Verdana, Arial">&nbsp;</font></td>';
	
	for ($i=0; $i < $fields; $i++) { //Table Header
	   print '<td nowrap align="center"><b><font size="2" face="Verdana, Arial">'. mysql_field_name($result, $i) .'</font><b></td>';
	}
	echo '</tr>';

	while ($row = mysql_fetch_row($result)) { //Table body
          if ($rede != $row[0]) {
                echo '<tr ';
                echo '>';
                echo '<td colspan=10 align="left"><font size="3" face="Verdana, Arial"> Rede -> ' . $row[0] . '</font></td>';
                echo '</tr>';
                $num_registro = 1;
          }
          $rede = $row[0];	

	   echo '<tr ';
		if ($cor) { echo 'bgcolor="#E1E1E1"'; } 
		echo '>';
		echo '<td nowrap align="left"><font size="2" face="Verdana, Arial">' . $num_registro . '</font></td>';
	
		for ($i=0; $i < $fields; $i++) {
		   if ($i == 0) {
		   echo '<td nowrap align="left"><font size="1" face="Verdana, Arial">' . 
		   ' <a href="../computador/computador.php?te_node_address=' . $row[10] . '&id_so=' . $row[9] . 
		   '" target="_blank">' . $row[11] . '</a>&nbsp;</td>';
		   } 
                    else
		   {		
			echo '<td nowrap align="left"><font size="1" face="Verdana, Arial">' . $row[$i] .'&nbsp;</td>'; 
		   }
		}
		$cor=!$cor;
		$num_registro++;
		echo '</tr>';
	}
	echo '</table>';
}
else {
	echo '</table>';
	echo mensagem('Não foi encontrado nenhum registro');
}
