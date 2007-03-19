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
 * verifica se houve login e também as permissões de usuário
 */
if(!isset($_SESSION['id_usuario'])) 
  die('Acesso negado!');
else { // Inserir regras para verificar permissões do usuário!
}
?>
<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<? 
require_once('../include/opcoes_avancadas_combos.js');  
?>
<link rel="stylesheet"   type="text/css" href="../include/cacic.css">
</head>

<body background="../imgs/linha_v.gif"  onLoad="SetaCampo('in_exibe_bandeja');">
<script language="JavaScript" type="text/javascript" src="../include/cacic.js"></script>
<form action="config_agentes_set.php"  method="post" ENCTYPE="multipart/form-data" name="forma">
<table width="90%" border="0" align="center">
  <tr> 
      <td class="cabecalho">Configura&ccedil;&otilde;es 
        dos M&oacute;dulos Agentes</td>
  </tr>
  <tr> 
    <td class="descricao">As op&ccedil;&otilde;es 
      abaixo determinam qual ser&aacute; o comportamento dos agentes oper&aacute;rios 
      do CACIC.</td>
  </tr>
</table>
  <table width="90%" border="0" align="center" cellpadding="0" cellspacing="1">
    <tr> 
      <td class="label">
        <? 
    require_once('../include/library.php');
	// Comentado temporariamente - AntiSpy();
    conecta_bd_cacic();
	$query = "SELECT 	in_exibe_bandeja, 
						in_exibe_erros_criticos, 
						nu_exec_apos, 
						nu_intervalo_exec, 
						te_senha_adm_agente, 
 	         			DATE_FORMAT(dt_hr_coleta_forcada, '%d/%m/%Y às %H:%i') as dt_hr_coleta_forcada, 
						te_enderecos_mac_invalidos, 
						te_janelas_excecao
	          FROM 		configuracoes_locais 
			  WHERE		id_local = ".$_SESSION['id_local']." 
			  			limit 1"; 						 


	$result_configuracoes = mysql_query($query) or die('Ocorreu um erro durante a consulta à tabela de configurações.'); 
	$campos_configuracoes = mysql_fetch_array($result_configuracoes);
?>
        &nbsp;<br>
        Exibir o &iacute;cone do CACIC na bandeja do Windows (systray):</td>
    </tr>
    <tr> 
      <td height="1" bgcolor="#333333"></td>
    </tr>
    <tr> 
      <td class="opcao"><p><input name="in_exibe_bandeja" type="radio" value="S"  <? if (strtoupper($campos_configuracoes['in_exibe_bandeja']) == 'S') echo 'checked'; ?>  class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >
          Sim<br>
          <input type="radio" name="in_exibe_bandeja" value="N" <? if (strtoupper($campos_configuracoes['in_exibe_bandeja']) == 'N') echo 'checked'; ?>  class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >
          N&atilde;o<br></p></td>
    </tr>
    <tr> 
      <td class="label">&nbsp;&nbsp; <br>
        Exibir erros cr&iacute;ticos aos usu&aacute;rios: </td>
    </tr>
    <tr> 
      <td height="1" bgcolor="#333333"></td>
    </tr>
    <tr> 
      <td class="opcao"><p><input name="in_exibe_erros_criticos" type="radio" value="S"  <? if (strtoupper($campos_configuracoes['in_exibe_erros_criticos']) == 'S') echo 'checked'; ?>  class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >
           Sim<br>
          <input type="radio" name="in_exibe_erros_criticos" value="N" <? if (strtoupper($campos_configuracoes['in_exibe_erros_criticos']) == 'N') echo 'checked'; ?>  class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >
          N&atilde;o<br>
          </p></td>
    </tr>
    <tr> 
      <td class="label">&nbsp;&nbsp; <br>
        Senha usada para configurar e finalizar os agentes: </td>
    </tr>
    <tr> 
      <td height="1" bgcolor="#333333"></td>
    </tr>
    <tr> 
      <td><p>
          <input name="te_senha_adm_agente" type="password"  value="<? echo $campos_configuracoes['te_senha_adm_agente']; ?>" maxlength="30" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >
          <br></p></td>
    </tr>
    <tr> 
      <td class="label">&nbsp;<br>Inicio de execu&ccedil;&atilde;o das a&ccedil;&otilde;es: </td>
    </tr>
    <tr> 
      <td height="1" bgcolor="#333333"></td>
    </tr>
    <tr> 
      <td class="opcao"> <p>
          <input name="nu_exec_apos" type="radio" value="0" <? if ($campos_configuracoes['nu_exec_apos'] == '0') echo 'checked'; ?>  class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >
          Imediatamente ap&oacute;s a inicializa&ccedil;&atilde;o do CACIC<br>
          <input type="radio" name="nu_exec_apos" value="10"  <? if ($campos_configuracoes['nu_exec_apos'] == '10') echo 'checked'; ?>  class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >
          10 minutos ap&oacute;s a inicializa&ccedil;&atilde;o do CACIC<br>
          <input name="nu_exec_apos" type="radio" value="20"  <? if ($campos_configuracoes['nu_exec_apos'] == '20') echo 'checked'; ?>  class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >
          20 minutos ap&oacute;s a inicializa&ccedil;&atilde;o do CACIC<br>
          <input type="radio" name="nu_exec_apos" value="30"  <? if ($campos_configuracoes['nu_exec_apos'] == '30') echo 'checked'; ?>  class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >
          30 minutos ap&oacute;s a inicializa&ccedil;&atilde;o do CACIC<br>
          <input type="radio" name="nu_exec_apos" value="60"  <? if ($campos_configuracoes['nu_exec_apos'] == '60') echo 'checked'; ?>  class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >
          1 hora ap&oacute;s a inicializa&ccedil;&atilde;o do CACIC<br>
          </p></td>
    </tr>
    <tr> 
      <td class="label">&nbsp;<br>Intervalo de execu&ccedil;&atilde;o das a&ccedil;&otilde;es:</td>
    </tr>
    <tr> 
      <td height="1" bgcolor="#333333"></td>
    </tr>
    <tr> 
      <td class="opcao"><p> 
          <input type="radio" name="nu_intervalo_exec" value="2"   <? if ($campos_configuracoes['nu_intervalo_exec'] == '2') echo 'checked'; ?>  class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >
          A cada 2 horas <br>
           
          <input type="radio" name="nu_intervalo_exec" value="4" <? if ($campos_configuracoes['nu_intervalo_exec'] == '4') echo 'checked'; ?>  class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >
          A cada 4 horas <br>
           
          <input type="radio" name="nu_intervalo_exec" value="6"  <? if ($campos_configuracoes['nu_intervalo_exec'] == '6') echo 'checked'; ?> class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >
          A cada 6 horas  <br>
           
          <input type="radio" name="nu_intervalo_exec" value="8"  <? if ($campos_configuracoes['nu_intervalo_exec'] == '8') echo 'checked'; ?> class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >
          A cada 8 horas  <br>
           
          <input type="radio" name="nu_intervalo_exec" value="10"  <? if ($campos_configuracoes['nu_intervalo_exec'] == '10') echo 'checked'; ?>  class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >
          A cada 10 horas  <br>
          </p></td>

    </tr>
    <tr> 
      <td>&nbsp;</td>
    </tr>

	<tr> 	
    <td class="label">Op&ccedil;&otilde;es avan&ccedil;adas:</td>
    </tr>
    <tr> 
    <td height="1" bgcolor="#333333"></td>
    </tr>

    <tr> 
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td class="label"> <p><u>Endere&ccedil;os MAC a desconsiderar</u><br></p></td>
    </tr>
    <tr> 
      <td class="ajuda">Esta op&ccedil;&atilde;o tem por finalidade informar aos agentes coletores de informa&ccedil;&otilde;es 
          de TCP/IP acerca de endere&ccedil;os MAC inv&aacute;lidos, ou seja, 
          os endere&ccedil;os utilizados como padr&otilde;es em protocolos e/ou 
          dispositivos diferentes de TCP/Ethernet. Os coletores considerar&atilde;o 
          apenas os endere&ccedil;os MAC diferentes ou que n&atilde;o contenham 
          as informa&ccedil;&otilde;es aqui cadastradas, podendo ser partes de 
          endere&ccedil;os.</td>
    </tr>
	
    <tr> 
      <td height="1" bgcolor="#333333"></td>
    </tr>
    <tr> 
      <td><p><textarea name="te_enderecos_mac_invalidos" cols="60" id="te_enderecos_mac_invalidos" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" ><? echo $campos_configuracoes['te_enderecos_mac_invalidos']; ?></textarea>
          </p></td>
    </tr>
    <tr> 
      <td class="descricao">Aten&ccedil;&atilde;o: 
        informe os endere&ccedil;os separados por v&iacute;rgulas (&quot;,&quot;). 
        <br>
        Exemplo: &quot;00-53-45-00-00-00,00-00-00-00-00-00,44-45-53-54-00-00,44-45-53-54-00-01,28-41-53&quot;</td>
    </tr>

    <tr> 
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td class="label"> <p><u>Aplicativos (janelas) a evitar</u><br></p>
        </td>
    </tr>
    <tr> 
      <td class="descricao">Esta op&ccedil;&atilde;o tem por finalidade evitar que o Gerente de Coletas seja acionado enquanto 
          tais aplicativos (janelas) estiverem ativos.</td>
    </tr>
	
    <tr> 
      <td height="1" bgcolor="#333333"></td>
    </tr>
    <tr> 
      <td><p><textarea name="te_janelas_excecao" cols="60" id="te_janelas_excecao" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" ><? echo $campos_configuracoes['te_janelas_excecao']; ?></textarea>
          </p></td>
    </tr>
    <tr> 
      <td class="descricao">Aten&ccedil;&atilde;o: 
        informe os nomes separados por v&iacute;rgulas (&quot;,&quot;). <br>
        Exemplo: &quot;HOD, Microsoft Word, Corel Draw, PhotoShop&quot;</td>
    </tr>
	
    <?
	require_once('../include/opcoes_avancadas.php');
	?>
  </table>
  <tr> 
      	<td height="1" bgcolor="#333333"></td>
    	</tr>
    	<tr> 
      	<td>&nbsp;</td>
    	</tr>
    	<tr> 
      	<td><div align="center"> 
<!--<input name="submit" type="submit" value="  Gravar Informa&ccedil;&otilde;es  " onClick="return SelectAll_Forca_Coleta();return Confirma('Confirma Configuração de Agentes?');">-->
	<input name="submit" type="submit" value="  Gravar Informa&ccedil;&otilde;es  " onClick="return SelectAll_Forca_Coleta();return Confirma('Confirma Configuração de Agentes?');" <? echo ($_SESSION['cs_nivel_administracao']<>1&&$_SESSION['cs_nivel_administracao']<>3?'disabled':'')?>>

        </div></td>
    	</tr>
  </table>
</form>
<p>&nbsp;</p>
</body>
</html>
