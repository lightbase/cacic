
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
//<table align="center">
session_start();
/*
 * verifica se houve login e também regras para outras verificações (ex: permissões do usuário)!
 */
if(!isset($_SESSION['id_usuario'])) 
  die('Acesso negado (Access denied)!');
else { // Inserir regras para outras verificações (ex: permissões do usuário)!
}

if (!$forca_coleta_estacao=='OK')
	{
	?>
	<tr><td>&nbsp;</td></tr>
    <tr> 
    <table width="556" border="0" align="center">
    <tr> 
    <td class="label" colspan="2"><u><?=$oTranslator->_('Induzir o envio das informacoes coletadas');?>
    </u><br></td>
    </tr>
    <tr> 
    <td colspan="2" class="descricao"><?=$oTranslator->_('kciq_msg help - Induzir o envio das informacoes coletadas');?></font></td>
    </tr>	
	<?
	}
?>
<tr>
<th>&nbsp;</th>
<td nowrap>&nbsp;</td>
</tr>
<tr> 
<th width="20"><div align="left"> 
<input name="te_node_address" type="hidden" value=" <? echo $_GET['te_node_address']; ?>">				
<input name="id_so" type="hidden" value=" <? echo $_GET['id_so']; ?>">								
<?
if (!$forca_coleta_estacao=='OK')
	{
	?>
    <input name="v_todas_acoes_redes" type="checkbox" onClick="MarcaDesmarcaTodasAcoesRedes(this.checked)"  class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);">				
    <td width="453" nowrap class="label"><div align="left"><em><strong><?=$oTranslator->_('Marca/Desmarca todas as Acoes para todas as Redes abaixo');?></em></div></td>
	<?
	}
?>
</th>				
</tr>
</table>
<br>		  
<table width="485" border="0" align="center" cellpadding="0" cellspacing="0">
<? 
require_once('../include/library.php');
AntiSpy('1,2,3'); // Permitido somente a estes cs_nivel_administracao...
// 1 - Administração
// 2 - Gestão Central
// 3 - Supervisão

conecta_bd_cacic();			
$query = "	SELECT 		ac.id_acao,
						ac.te_descricao_breve
			FROM 		acoes ac
			WHERE		ac.id_acao <> 'cs_auto_update'
			ORDER BY	ac.te_descricao_breve"; 

$id_local = ($_POST['id_local']?$_POST['id_local']:$_SESSION['id_local']);							
$result_acoes = mysql_query($query) or die($oTranslator->_('kciq_msg select on table fail', array('acoes'))."! ".$oTranslator->_('kciq_msg session fail',false,true)."!"); 
$where = ($_SESSION["cs_nivel_administracao"] == 3?"AND ac_re.id_local = ".$id_local:"");	

if ($_SESSION['te_locais_secundarios'])
	{
	$where = str_replace('ac_re.id_local',' (ac_re.id_local',$where);
	$where .= ' OR (ac_re.id_local IN ('.$_SESSION['te_locais_secundarios'].'))) ';
	}

while ($row = mysql_fetch_array($result_acoes))
	{ 

	if ($forca_coleta_estacao=='OK')
		{

		$query = "	SELECT 		DATE_FORMAT(ac_re.dt_hr_coleta_forcada, '%d/%m/%y-%H:%i') as dt_hr_coleta_forcada,
											re.id_ip_rede,
											re.nm_rede,
											ac_re.id_acao,
											ac.te_descricao_breve,
											ac.te_nome_curto_modulo
					FROM 		redes re, 
								acoes_redes ac_re,
								acoes ac,
								computadores comp
					WHERE		re.id_ip_rede = ac_re.id_ip_rede and
								ac_re.id_acao = ac.id_acao and
								ac_re.id_local = re.id_local ".
								$where . " AND
								ac.id_acao = '" . $row['id_acao']."' and
								comp.te_node_address = '".$te_node_address."' and
								comp.id_so = '".$id_so."' and
								comp.id_ip_rede = re.id_ip_rede 													 													 
					GROUP BY    re.id_ip_rede
					ORDER BY	re.nm_rede"; 			
		}
	else
		{
		$query = "	SELECT 		DATE_FORMAT(ac_re.dt_hr_coleta_forcada, '%d/%m/%y-%H:%i') as dt_hr_coleta_forcada,
											re.id_ip_rede,
											re.nm_rede,
											ac_re.id_acao,
											ac.te_descricao_breve
					FROM 		redes re, 
								acoes_redes ac_re,
								acoes ac
					WHERE		re.id_ip_rede = ac_re.id_ip_rede and
								ac_re.id_acao = ac.id_acao and
								ac_re.id_local = re.id_local ".
								$where . " AND
								ac.id_acao = '" . $row['id_acao']."' 
					GROUP BY    re.id_ip_rede
					ORDER BY	re.nm_rede"; 
		}

	$result_redes = mysql_query($query) or die($oTranslator->_('kciq_msg select on table fail', array('redes'))."! ".$oTranslator->_('kciq_msg session fail',false,true)."!"); 
	if (!$forca_coleta_estacao=='OK')			
		{
		$v_redes       	 = array();
		$v_redes_dthr_cf = array();
		$v_redes_ip 	 = array();			
		while ($row_redes = mysql_fetch_array($result_redes))
			{
			array_push($v_redes_ip     , $row_redes['id_ip_rede']);
			array_push($v_redes        , $row_redes['id_ip_rede'] . ' - ' . $row_redes['nm_rede']);	
			array_push($v_redes_dthr_cf, $row_redes['dt_hr_coleta_forcada']);		
			}
		}
	else
		{
		$row = mysql_fetch_array($result_redes);
		}
			
	if (!$forca_coleta_estacao=='OK')			
		{
		?>			
       	<tr> 
        <td colspan="3"><hr></td>
   	  	</tr>
   		<tr> 
       	<td width="39" class="label"><?=$oTranslator->_('Ação');?>:</td>
       	<td colspan="3" nowrap class="destaque"><u><? echo $row['te_descricao_breve'];?></u></td>
   		</tr>
		<?
		if (count($v_redes) > 1)
			{
			?>
   			<tr> 
       		<td></td>
       		<td class="opcao_tabela"><div align="left"> 
			<input name="<? echo $row['id_acao']; ?>" type="checkbox" onClick="MarcaDesmarcaTodos(this.form.<? echo $row['id_acao'];?>);">
			</div></td>
       		<td class="destaque" nowrap><div align="left"><?=$oTranslator->_('Marca ou Desmarca Acao para as Redes abaixo');?></div></td>
    		</tr>				
			<?
			}
	  	for ($i=0;$i<count($v_redes);$i++)
	  		{
			?>
       	  	<tr> 
	   	    <td></td>
          	<td class="opcao" nowrap><div align="left">
			<input name="<? echo $row['id_acao']; ?>" type="checkbox" id="<? echo $v_redes_ip[$i]; ?>" value="<? echo $v_redes_ip[$i]; ?>" class="opcao"  onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >
			<input name="<? echo $row['id_acao']; ?>" type="hidden"   id="<? echo $v_redes_ip[$i]; ?>">
			</div></td>
            <td nowrap class="descricao"><div align="left"><? echo $v_redes[$i]; ?>
			<?
			if ($v_redes_dthr_cf[$i]) 
				{
				echo ' (Forçada em: '.$v_redes_dthr_cf[$i] . ')';
				}
				?>
			</div></td>
	       	</tr>
    	    <?
			}
		}
	elseif ($_SESSION["cs_nivel_administracao"] == 1 || 
			$_SESSION["cs_nivel_administracao"] == 2 ||
			$_SESSION["cs_nivel_administracao"] == 3)			
		if ($row['id_acao'])
			{
			?>			
    		<tr nowrap> 
       		<td></td>
       		<td class="opcao"><div align="left"> 
			<input name="<? echo $row['id_acao']; ?>" type="checkbox" value="<? echo $row['te_nome_curto_modulo']; ?>"  class="opcao" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >
			</div></td>
            <td nowrap class="descricao"><div align="left"><em><? echo $row['te_descricao_breve'];?></em></div></td>
    	    </tr>								
			<?
			}
	}				
?>
</table>
</tr>
</table>
