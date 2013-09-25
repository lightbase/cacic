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
	?>
<link href="cacic.css" rel="stylesheet" type="text/css">

	<tr><td colspan="4">&nbsp;</td></tr>
    <tr> 
	<td>&nbsp;</td>
    <td class="label" colspan="3">
       <?php echo $oTranslator->_('Selecao para coleta de informacoes de sistemas monitorados:');?>
    </td>
    </tr>
    <tr> 
      <td colspan="4" height="1" bgcolor="#333333"></td>
    </tr>
    <tr> 
	<td>&nbsp;</td>
    <td colspan="3" class="descricao">
       <?php echo $oTranslator->_('Essa opcao permite a selecao de coletas de informacoes de sistemas monitorados para essa rede.');?>
    </td>
    </tr>	
 <tr> 
 <td colspan="4" height="1" bgcolor="#CCCCCC"></td>
 </tr>

<br>
<?php 
// Se a chamada veio de ...detalhes_rede.php
$detalhes = strpos($_SERVER['REQUEST_URI'],'detalhes_rede');
if ($detalhes)
	{
	conecta_bd_cacic();			
	$query = "	SELECT 		*
				FROM 		aplicativos_redes
				WHERE		id_rede = ".$_REQUEST['id_rede']; 
	$result_aplicativos_redes = mysql_query($query) or die($oTranslator->_('Ocorreu um erro no acesso a tabela %1 ou sua sessao expirou!',array('aplicativos_redes'))); 
	$v_aplicativos_redes = '';
	while ($row = mysql_fetch_array($result_aplicativos_redes))
		{
		$v_aplicativos_redes .= '--' . $row['id_aplicativo'];
		}
	}
$query = "	SELECT 		*
			FROM 		perfis_aplicativos_monitorados
			ORDER BY	nm_aplicativo"; 						
$result_monitorados = mysql_query($query) or die($oTranslator->_('Ocorreu um erro no acesso a tabela %1 ou sua sessao expirou!',array('perfis_aplicativos_monitorados'))); 
$total_registros = count($result_monitorados);
$seq = 0;
while ($row = mysql_fetch_array($result_monitorados))
	{ 
	if (!strpos($row['nm_aplicativo'], "#DESATIVADO#")>0) 
		{
		$seq ++;
		$id_aplicativo = '-'.$row['id_aplicativo'];
		$pos = strpos($v_aplicativos_redes,$id_aplicativo);
		?>
		<tr> 
		<td>&nbsp;</td>
		<td class="opcao_tabela" colspan="3"><div align="left"><?php echo str_pad($seq,($total_registros>999?4:$total_registros>99?3:2),'0',STR_PAD_LEFT); ?>)&nbsp;<input name="id_aplicativo_<?php echo $row['id_aplicativo']; ?>" value="<?php echo $row['id_aplicativo']; ?>" type="checkbox" class="normal" <?php if ($pos || !$detalhes){ echo 'checked';} ?>>
		<?php echo $row['nm_aplicativo'] ; ?></div></td>
		</tr>
		<?php					
		}
	}				
?>