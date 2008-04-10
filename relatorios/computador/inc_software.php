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
// Esse arquivo é um arquivo de include, usado pelo arquivo computador.php. 
if (!$_SESSION['software'])
	$_SESSION['software'] = false;
if ($exibir == 'software')
	{
	$_SESSION['software'] = !($_SESSION['software']);
	}
else
	{
	$_SESSION['software'] = false;
	}
$strCor = '';  
$strCor = ($strCor==''?'#CCCCFF':'');						  

?>
<table width="94%" border="0" align="center" cellpadding="0" cellspacing="1">
  <tr> 
    <td colspan="5" height="1" bgcolor="#333333"></td>
  </tr>
  <tr bgcolor="#E1E1E1"> 
    <td colspan="5" class="cabecalho_tabela">&nbsp;<a href="computador.php?exibir=software&te_node_address=<? echo $_GET['te_node_address']?>&id_so=<? echo $_GET['id_so']?>"> 
      <img src="../../imgs/<? if($_SESSION['software'] == true) echo 'menos';
   			 else echo 'mais'; ?>.gif" width="12" height="12" border="0"> Vers&otilde;es 
      de Softwares B&aacute;sicos</a></td>
  </tr>
  <tr> 
    <td colspan="5" height="1" bgcolor="#333333"></td>
  </tr>
  <?
			if ($_SESSION['software'] == true) {
			// EXIBIR INFORMAÇÕES DE SOFTWARE DO COMPUTADOR
				$query = "SELECT 	cs_situacao
						  FROM 		acoes_redes 
						  WHERE 	id_acao = 'cs_coleta_software' AND
						  			id_ip_rede = '".mysql_result($result,0,'id_ip_rede')."'";
				$result_acoes =  mysql_query($query);
				
				if (mysql_result($result_acoes, 0, "cs_situacao") <> 'N') {
					$query = "SELECT * FROM versoes_softwares
							  WHERE te_node_address = '".$_GET['te_node_address']."' AND 
							  		id_so = '". $_GET['id_so'] ."'";
					$result_software = mysql_query($query);
					if(mysql_num_rows($result_software) > 0) {
		?>
  <tr bgcolor="<? echo $strCor;?>"> 
    <td>&nbsp;</td>
    <td class="opcao_tabela">Sistema Operacional:</td>
    <td class="dado"><? echo mysql_result($result, 0, "te_desc_so"); ?></td>
    <td class="opcao_tabela">Vers&atilde;o do DirectX:</td>
    <td class="dado"><? echo mysql_result($result_software, 0, "te_versao_directx"); ?></td>
  </tr>
  <? echo $linha;
  $strCor = ($strCor==''?'#CCCCFF':'');						  
  ?> 

  <tr bgcolor="<? echo $strCor;?>"> 
    <td>&nbsp;</td>
    <td class="opcao_tabela">Vers&atilde;o do Internet Explorer:</td>
    <td class="dado"><? echo mysql_result($result_software, 0, "te_versao_ie"); ?></td>
    <td class="opcao_tabela">Vers&atilde;o do ODBC:</td>
    <td class="dado"><? echo mysql_result($result_software, 0, "te_versao_odbc"); ?></td>
  </tr>
  <? echo $linha;
  $strCor = ($strCor==''?'#CCCCFF':'');						  
  ?> 

  <tr bgcolor="<? echo $strCor;?>"> 
    <td>&nbsp;</td>
    <td class="opcao_tabela">Vers&atilde;o do Mozilla:</td>
    <td class="dado"><div align="left"><? echo mysql_result($result_software, 0, "te_versao_mozilla"); ?></div></td>
    <td class="opcao_tabela">Vers&atilde;o do DAO:</td>
    <td class="dado"><? echo mysql_result($result_software, 0, "te_versao_dao"); ?></td>
  </tr>
  <? echo $linha;
  $strCor = ($strCor==''?'#CCCCFF':'');						  
  ?> 

  <tr bgcolor="<? echo $strCor;?>"> 
    <td>&nbsp;</td>
    <td class="opcao_tabela">Vers&atilde;o do Acrobat Reader:</td>
    <td class="dado"><? echo mysql_result($result_software, 0, "te_versao_acrobat_reader"); ?></td>
    <td class="opcao_tabela">Vers&atilde;o do ADO:</td>
    <td class="dado"><? echo mysql_result($result_software, 0, "te_versao_ado"); ?></td>
  </tr>
  <? echo $linha;
  $strCor = ($strCor==''?'#CCCCFF':'');						  
  ?> 

  <tr bgcolor="<? echo $strCor;?>"> 
    <td>&nbsp;</td>
    <td class="opcao_tabela">Vers&atilde;o do Java Runtime (JVM):</td>
    <td class="dado"><? echo mysql_result($result_software, 0, "te_versao_jre"); ?></td>
    <td>Vers&atilde;o do BDE:</td>
    <td class="dado"><? echo mysql_result($result_software, 0, "te_versao_bde"); ?></td>
  </tr>
  <? echo $linha;
  $strCor = ($strCor==''?'#CCCCFF':'');						  
  ?> 

  <tr> 
    <td>&nbsp;</td>
    <td colspan="4">&nbsp; </td>
  </tr>
  <?
				}
				else {
					echo '<tr><td> 
							<p>
							<div align="center">
							<br>
							<font font size="2" face="Verdana, Arial, Helvetica, sans-serif" color="#FF0000">
							Não foram coletadas informações de software referente a esta máquina
							</font></div>
							</p>
						  </td></tr>';
				}
			}
			else {
				echo '<tr><td> 
						<div align="center">
						<font font size="2" face="Verdana, Arial, Helvetica, sans-serif" color="#FF0000">
						O módulo de Coleta de Informações de Software não foi habilitado pelo Administrador do CACIC.
						</font></div>
					  </td></tr>';
			}
		}
		// FIM DA EXIBIÇÃO DE INFORMAÇÕES DE SOFTWARE DO COMPUTADOR
		?>
</table>
