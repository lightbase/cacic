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
// Esse arquivo � um arquivo de include, usado pelo arquivo computador.php. 
if (!$_SESSION['variavel_ambiente'])
	$_SESSION['variavel_ambiente'] = false;
if ($exibir == 'variavel_ambiente')
	$_SESSION['variavel_ambiente'] = !($_SESSION['variavel_ambiente']);
else
	$_SESSION['variavel_ambiente'] = false;
		
?>
<table width="94%" border="0" align="center" cellpadding="0" cellspacing="1">
  <tr> 
    <td height="1" colspan="4" bgcolor="#333333"></td>
  </tr>
  <tr bgcolor="#E1E1E1"> 
    <td colspan="4" class="cabecalho_tabela">&nbsp;<a href="computador.php?exibir=variavel_ambiente&te_node_address=<? echo $_GET['te_node_address']?>&id_so=<? echo $_GET['id_so']?>"> 
      <img src="../../imgs/<? if($_SESSION['variavel_ambiente'] == true) echo 'menos';
   			 else echo 'mais'; ?>.gif" width="12" height="12" border="0">&nbsp;<?=$oTranslator->_('Variaveis de ambiente');?></a></td>
  	</tr>
  	<tr> 
    <td height="1" colspan="4" bgcolor="#333333"></td>
  	</tr>
  	<?
			if ($_SESSION['variavel_ambiente'] == true) 
				{
			// EXIBIR INFORMA��ES DE VARI�VEIS DE AMBIENTE NO COMPUTADOR
				$query = "SELECT 	cs_situacao
						  FROM 		acoes_redes 
						  WHERE 	id_acao = 'cs_coleta_software' AND
						  			id_ip_rede = '".mysql_result($result,0,'id_ip_rede')."'";
				$result_acoes =  mysql_query($query);
				
				if (@mysql_result($result_acoes, 0, "cs_situacao") <> 'N') 
					{
					$query = "	SELECT 		DISTINCT va.nm_variavel_ambiente, vae.vl_variavel_ambiente
								FROM 		variaveis_ambiente va,
											variaveis_ambiente_estacoes vae
							  	WHERE 		vae.te_node_address = '".$_GET['te_node_address']."' AND
											vae.id_so = '". $_GET['id_so'] ."' AND
											vae.id_variavel_ambiente = va.id_variavel_ambiente
								ORDER BY	va.nm_variavel_ambiente";
					$result_software = mysql_query($query);
					$v_achei = 0;
					$intContaItem =0;
					$strCor = '';

					while ($row = @mysql_fetch_array($result_software)) 
						{
						$strCor = ($strCor==''?$strPreenchimentoPadrao:'');						
						$v_achei = 1;
						$intContaItem ++;
						?>
  						<tr bgcolor="<? echo $strCor;?>">
  						  <td width="2%" class="descricao">
  						    <div align="right"><B><? echo $intContaItem;?></B></div>
  						  </td> 
    					  <td class="descricao">&nbsp;</td>
    					  <td width="63%" align="left" class="descricao">&nbsp;<? echo $row['nm_variavel_ambiente']; ?></td>
    					<td width="35%" class="descricao">&nbsp;<? echo $row['vl_variavel_ambiente']; ?></td>
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
								'.$oTranslator->_('Nao foram coletadas informacoes de variaveis de ambiente referente a esta maquina').'
								</font></div>
								</p>
							  </td></tr>';
						}
					}
				else 
					{
					echo '<tr><td> 
							<div align="center">
							<font font size="2" face="Verdana, Arial, Helvetica, sans-serif" color="#FF0000">
							'.$oTranslator->_('O modulo de coleta de informacoes de software nao foi habilitado pelo Administrador').'
							</font></div>
						  </td></tr>';
					}
				}
		// FIM DA EXIBI��O DE INFORMA��ES DE VARI�VEIS DE AMBIENTE DO COMPUTADOR
		?>
</table>
