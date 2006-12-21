<? session_start();

// Verifica qual a versão do PHP
$PHPVERSAO = "Versao pela funcao" . phpversion();

if ($PHPVERSAO > 4)
{
  echo "<br>Este script funciona apenas com a versao PHP maior que 4";
  echo "<br>A versão $PHPVERSAO está atualmente instalada";
  echo "<br> Saindo do script...";
  exit (1);
}

if($_POST['submit']) {
	$_SESSION["list2"] = $_POST['list2'];
	$_SESSION["list4"] = $_POST['list4'];
	$_SESSION["list6"] = $_POST['list6'];
	$_SESSION["list8"] = $_POST['list8'];	
	$_SESSION["cs_situacao"] = $_POST["cs_situacao"];
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Relat&oacute;rio de coletas e verifica&ccedil;&atilde;o das estações ativas</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" type="text/JavaScript">
<!--
function MM_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
}
//-->
</script>
<link rel="stylesheet"   type="text/css" href="../../include/cacic.css">
<style type="text/css">
       TR {font-size:10pt ; font-family: Verdana, Arial}
</style>
</head>

<body bgcolor="#FFFFFF" topmargin="5">
<table border="0" align="left" cellpadding="0" cellspacing="0" bordercolor="#999999">
  <tr bgcolor="#E1E1E1"> 
    <td rowspan="5" bgcolor="#FFFFFF"><img src="../../imgs/cacic_logo.png" width="50" height="50"></td>
	
    <td bgcolor="#FFFFFF">&nbsp;</td>
  </tr>
  <tr bgcolor="#E1E1E1"> 
    <td nowrap bgcolor="#FFFFFF"><font color="#333333" size="4" face="Verdana, Arial, Helvetica, sans-serif"><strong>CACIC 
      - Relat&oacute;rio de coletas e verifica&ccedil;&atilde;o das estações ativas</strong></font></td>
  </tr>
  <tr> 
    <td height="1" bgcolor="#333333"></td>
  </tr>
  <tr> 
    <td><p align="left"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Gerado 
        em <? echo date("d/m/Y à\s H:i\h"); ?></font></p></td>
  </tr>
</table>
<br>
<br>
<br>
<br>
<? 
require_once('../../include/library.php');
conecta_bd_cacic();

$redes_selecionadas = '';
if($_SESSION["cs_situacao"] == 'S') {
	// Aqui pego todas as redes selecionadas e faço uma query p/ condição de redes
	$redes_selecionadas = "'" . $_SESSION["list2"][0] . "'";
	for( $i = 1; $i < count($_SESSION["list2"] ); $i++ ) {
		$redes_selecionadas = $redes_selecionadas . ",'" . $_SESSION["list2"][$i] . "'";
	}
	$query_redes = 'AND id_ip_rede IN ('. $redes_selecionadas .')';
}

// Aqui pego todos os SO selecionados
$so_selecionados = "'" . $_SESSION["list4"][0] . "'";
for( $i = 1; $i < count($_SESSION["list4"] ); $i++ ) {
	$so_selecionados = $so_selecionados . ",'" . $_SESSION["list4"][$i] . "'";
}

// Aqui pego todas as configurações de hardware que deseja exibir
for( $i = 0; $i < count($_SESSION["list6"] ); $i++ ) {
	$campos_software = $campos_software . $_SESSION["list6"][$i];
}

function PING($IPNUMBER){
  // Executa o comando externo ping para verificar se o micro esta ativo.
  // Este script utiliza componentes das versões 4 ou supoerior.

  // Verifica qual o sistema operacional para selecionar o comando correto
  $SO = $_ENV["OS"];

  // Converte para minusculo
  $SO = strtolower($SO);

  // Verifica se o SO é windows, se verdade vai retornar true
  $SO = strpos($SO, "windows");
  if ($SO === FALSE)
  {
        // Linux utilizamos
		$CMD = "ping -w 1 -c 1 $IPNUMBER";
  }else{
     	// windows utilizamos as opções w (tempo espera) e n (numero de ecos)
        $CMD = "ping -w 1 -n 1 $IPNUMBER";
  }
  // Executa o ping
  exec($CMD,$output,$retorno);

  // Se o ping retorna 0 significa que a estação está ativa.
  if ($retorno == 0)
  {
	  echo "<img src=../../imgs/arvore/tree_computer_green.gif border=0 title=\"PING OK, click aqui para testar novamente\">";
  }else{
	  echo "<img src=../../imgs/arvore/tree_computer_red.gif border=0 title=\"PING NAO RESPONDE, click aqui para testar novamente\">";
  }
}

// Buscas os dias que desejo exibir as estações:
for( $i = 0; $i < count($_SESSION["list6"] ); $i++ ) {

    //Atribui a num_dias os dias selecionados
    $num_dias = $_SESSION["list6"][$i];

    // Verifica se utilizamos = ou >=
    // Atribui cabeçalho as tabelas e as cores.
    switch ($num_dias) {
        case 0:
            $OPERADOR="=";
            $CABECALHO="Hoje: ";
            break;
        case 1:
            $OPERADOR=" = ";
            $CABECALHO="Ontem: ";
            break;
        case 2:
            $OPERADOR=" = ";
            $CABECALHO="Há 2 dias: ";
            break;
        case 3:
            $OPERADOR=" = ";
            $CABECALHO="Há 3 dias: ";
            break;
        case 4:
            $OPERADOR=" = ";
            $CABECALHO="Há 4 dias: ";
            break;
        case 5:
            $OPERADOR=" >= ";
            $CABECALHO="Há mais de 4 dias: ";
            break;
        case 10:
            $OPERADOR=" >= ";
            $CABECALHO="Há mais de 9 dias: ";
            break;
    }

    $query = "SELECT computadores.te_node_address,
                     computadores.te_nome_computador,
                     so.sg_so,
                     computadores.te_ip,
                     dt_hr_ult_acesso,
                     computadores.id_so,
                     te_workgroup,
                     te_dominio_windows
              FROM computadores, so
              WHERE (computadores.id_so = so.id_so)
                     AND dt_hr_ult_acesso is not null
                     AND ((to_days( curdate( ) ) - to_days( dt_hr_ult_acesso ))$OPERADOR $num_dias)
                     AND computadores.id_so IN ($so_selecionados)
                     $query_redes
              ORDER BY dt_hr_ult_acesso DESC";

    $result = mysql_query($query) or die('Erro no select' . mysql_error());

    //Exibe o titulo da tabela
    echo "<table border=\"0\" cellpadding=\"0\" cellspacing=\"1\" width=\"80%\">
          <tr>
              <td bgcolor=\"#333333\" height=\"1\"></td>
          </tr>
          <tr>
              <td class=\"cabecalho_tabela\" bgcolor=\"#e1e1e1\">&nbsp;<b>$CABECALHO</b></td>
          </tr>
          <tr>
              <td bgcolor=\"#333333\" height=\"1\"></td>
          </tr>
          <tr>
              <td height=\"1\"></td>
          </tr>
          </table>";

    //Verifica a quantidade de registros encontrados
    $num_rows = mysql_num_rows($result);

    if ($num_rows == 0){
        echo "<table border=\"0\" cellpadding=\"0\" cellspacing=\"1\" width=\"80%\">
              <tr>
                  <td height=\"1\"></td>
              </tr>
              <tr>
                  <td size=\"2\">Nenhum computador encontrado na Rede ou Sistema Operacional selecionado!</td>
              </tr>
              <tr>
                  <td height=\"1\"></td>
              </tr>
            </table>
            <br>";
    }else{
        //Retorna consulta mysql ao inicio
        mysql_data_seek($result, 0);

        //Retorna todas as linhas da consulta conforme num_dias
        echo '<table  width="80%" cellpadding="2" cellspacing="0" border="1" bordercolor="#999999" bordercolordark="#E1E1E1">
         <tr bgcolor="#E1E1E1" >
          <td nowrap align="left"><font size="1" face="Verdana, Arial">&nbsp;</font></td>
          <td>Grupo</td>
          <td>Computador</td>
          <td>S.O.</td>
          <td>Último Login</td>
          <td>Endereço IP</td>
          <td>Endereço MAC</td>
          <td>Último Acesso</td>
          <td>Ping</td>
         </tr>
          ';

        $cor = 0;
        $num_registro = 1;

        while ($linha = mysql_fetch_array($result))
        {
                $NOME_COMPUTADOR = $linha['te_nome_computador'];
                $GRUPO = $linha['te_workgroup'];
                $SO = $linha['id_so'];
                $U_LOGADO = $linha['te_dominio_windows'];
                $MAC = $linha['te_node_address'];
                $SO_NOME = $linha['sg_so'];
                $SO = $linha['id_so'];
                $IP =  $linha['te_ip'];
                $U_ACESSO = $linha['dt_hr_ult_acesso'];
                echo "<tr ";
	            if ($cor) { echo 'bgcolor="#E1E1E1"'; }
	            echo ">
                         <td>$num_registro</td>
                         <td>$GRUPO</td>
                         <td>
                         <a href=../computador/computador.php?te_node_address=$MAC&id_so=$SO target=_blank>
                         <B>$NOME_COMPUTADOR</B></a>
                         </td>
                         <td>$SO_NOME</td>
                         <td>$U_LOGADO</td>
                         <td>$IP</td>
                         <td>$MAC</td>
                         <td>$U_ACESSO</td>
                         <td align=center>";?>
                           <a href="#" onClick="window.open('../../relatorios/comandos_rede.php?tool=ping&ip=<? echo $IP; ?>','','width=550,height=370');return false">
                           <? PING($IP);
                echo "</a></td></tr>";
                $cor=!$cor;
            	$num_registro++;
        }
        echo "</table><br>";
    }
}
?>

<p align="left"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Relat&oacute;rio
  gerado pelo <strong>CACIC</strong> - Configurador Autom&aacute;tico e Coletor
  de Informa&ccedil;&otilde;es Computacionais</font><br>
  <font size="1" face="Verdana, Arial, Helvetica, sans-serif">Software desenvolvido
  pela Dataprev - Escrit&oacute;rio do Esp&iacute;rito Santo
  <br>Relat&oacute;rio adaptado por Emerson Pellis (epellis at unerj.br)</font>
  </p>
</body>
</html>
