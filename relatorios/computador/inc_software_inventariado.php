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
if (!$_SESSION['software_inventariado'])
	$_SESSION['software_inventariado'] = false;
if ($exibir == 'software_inventariado')
	{
	$_SESSION['software_inventariado'] = !($_SESSION['software_inventariado']);
	}
else	
	{
	$_SESSION['software_inventariado'] = false;
	}
?>
<table width="94%" border="0" align="center" cellpadding="0" cellspacing="1">
  <tr> 
    <td colspan="5" height="1" bgcolor="#333333"></td>
  </tr>
  <tr bgcolor="#E1E1E1"> 
    <td class="cabecalho_tabela" colspan="5">&nbsp;<a href="computador.php?exibir=software_inventariado&te_node_address=<? echo $_GET['te_node_address']?>&id_so=<? echo $_GET['id_so']?>"> 
      <img src="../../imgs/<? if($_SESSION['software_inventariado'] == true) echo 'menos';
   			 else echo 'mais'; ?>.gif" width="12" height="12" border="0"> Softwares 
      Inventariados</a></td>
  </tr>
  <tr> 
    <td height="1" bgcolor="#333333"></td>
  </tr>
  <?
			if ($_SESSION['software_inventariado'] == true) {
			// EXIBIR INFORMAÇÕES DE SOFTWARES INVENTARIADOS NO COMPUTADOR
				$query = "SELECT 	cs_situacao
						  FROM 		acoes_redes 
						  WHERE 	id_acao = 'cs_coleta_software' AND
						  			id_ip_rede = '".mysql_result($result,0,'id_ip_rede')."'";
				$result_acoes =  mysql_query($query);
				
				if (mysql_result($result_acoes, 0, "cs_situacao") <> 'N') {
					$query = "	SELECT 		DISTINCT si.nm_software_inventariado 
								FROM softwares_inventariados si,
											softwares_inventariados_estacoes sie
							  	WHERE 		sie.te_node_address = '".$_GET['te_node_address']."' AND
											sie.id_so = '". $_GET['id_so'] ."' AND
											sie.id_software_inventariado = si.id_software_inventariado
								ORDER BY	si.nm_software_inventariado";
					$result_software = mysql_query($query);
					$v_achei = 0;
					while ($row = mysql_fetch_array($result_software)) 
					{
					$v_achei = 1;
					?>
  <tr> 
    <td class="descricao">&nbsp;<? echo $row['nm_software_inventariado']; ?></td>
  </tr>
  <?
  echo $linha;
				}
				if (!$v_achei)
				{
					echo '<tr><td> 
							<p>
							<div align="center">
							<br>
							<font font size="2" face="Verdana, Arial, Helvetica, sans-serif" color="#FF0000">
							Não foram coletadas informações de software inventariado referente a esta máquina
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
