<?php 
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
    <td colspan="4" class="cabecalho_tabela">&nbsp;<a href="computador.php?exibir=variavel_ambiente&id_computador=<?php echo $_GET['id_computador']?>"> 
      <img src="../../imgs/<?php if($_SESSION['variavel_ambiente'] == true) echo 'menos';
   			 else echo 'mais'; ?>.gif" width="12" height="12" border="0">&nbsp;<?php echo $oTranslator->_('Variaveis de ambiente');?></a></td>
  	</tr>
  	<tr> 
    <td height="1" colspan="4" bgcolor="#333333"></td>
  	</tr>
  	<?php if ($_SESSION['variavel_ambiente'] == true) 
				{
			// EXIBIR INFORMAÇÕES DE VARIÁVEIS DE AMBIENTE NO COMPUTADOR
				$query = "SELECT 	cs_situacao
						  FROM 		acoes_redes 
						  WHERE 	id_acao = 'cs_coleta_software' AND
						  			id_rede = '".mysql_result($result,0,'id_rede')."'";
				$result_acoes =  mysql_query($query);
				
				if (@mysql_result($result_acoes, 0, "cs_situacao") <> 'N') 
					{
					$query = "	SELECT 		DISTINCT va.nm_variavel_ambiente, vae.vl_variavel_ambiente
								FROM 		variaveis_ambiente va,
											variaveis_ambiente_estacoes vae
							  	WHERE 		vae.id_computador = ". $_GET['id_computador'] ." AND
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
  						<tr bgcolor="<?php echo $strCor;?>">
  						  <td width="2%" class="descricao">
  						    <div align="right"><B><?php echo $intContaItem;?></B></div>
  						  </td> 
    					  <td class="descricao">&nbsp;</td>
    					  <td width="63%" align="left" class="descricao">&nbsp;<?php echo $row['nm_variavel_ambiente']; ?></td>
    					<td width="35%" class="descricao">&nbsp;<?php echo $row['vl_variavel_ambiente']; ?></td>
  						</tr>
  						<?php echo $linha;
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
		// FIM DA EXIBIÇÃO DE INFORMAÇÕES DE VARIÁVEIS DE AMBIENTE DO COMPUTADOR
		?>
</table>
