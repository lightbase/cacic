<?php /* 
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
    <td class="label" colspan="2"><u><?php echo $oTranslator->_('Induzir o envio das informacoes coletadas');?>
    </u><br></td>
    </tr>
    <tr> 
    <td colspan="2" class="descricao"><?php echo $oTranslator->_('kciq_msg help - Induzir o envio das informacoes coletadas');?></font></td>
    </tr>	
	<?php }
?>
<tr>
<th>&nbsp;</th>
<td nowrap>&nbsp;</td>
</tr>
<tr> 
<th width="20"><div align="left"> 
<input name="id_computador" type="hidden" value=" <?php echo $_GET['id_computador']; ?>">				
<input name="te_node_address" type="hidden" value=" <?php echo $_GET['te_node_address']; ?>">				
<input name="id_so" type="hidden" value=" <?php echo $_GET['id_so']; ?>">								
<?php if (!$forca_coleta_estacao=='OK')
	{
	?>
    <input name="v_todas_acoes_redes" type="checkbox" onClick="MarcaDesmarcaTodasAcoesRedes(this.checked)"  class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);">				
    <td width="453" nowrap class="label"><div align="left"><em><strong><?php echo $oTranslator->_('Marca/Desmarca todas as Acoes para todas as Redes abaixo');?></em></div></td>
	<?php }
?>
</th>				
</tr>
</table>
<br>		  
<table width="485" border="0" align="center" cellpadding="0" cellspacing="0">
<?php 
require_once('../include/library.php');
AntiSpy('1,2,3'); // Permitido somente a estes cs_nivel_administracao...
// 1 - Administração
// 2 - Gestão Central
// 3 - Supervisão

conecta_bd_cacic();			
$queryAcoes = "	SELECT 		ac.id_acao,
							ac.te_descricao_breve
				FROM 		acoes ac
				WHERE		ac.id_acao
				ORDER BY	ac.te_descricao_breve"; 

$id_local = ($_POST['id_local']?$_POST['id_local']:$_SESSION['id_local']);							
$resultAcoes = mysql_query($queryAcoes) or die($oTranslator->_('kciq_msg select on table fail', array('acoes'))."! ".$oTranslator->_('kciq_msg session fail',false,true)."!"); 


//$where = ($_SESSION["cs_nivel_administracao"] == 3?"AND ac_re.id_local = ".$id_local:"");	
$where = " AND ac_re.id_local = ".$id_local;	

if ($_SESSION['te_locais_secundarios'])
	{
	$where = str_replace('ac_re.id_local',' (ac_re.id_local',$where);
	$where .= ' OR (ac_re.id_local IN ('.$_SESSION['te_locais_secundarios'].'))) ';
	}

while ($rowAcoes = mysql_fetch_array($resultAcoes))
	{ 

	if ($forca_coleta_estacao=='OK')
		{

		$queryRedes = "	SELECT 		DATE_FORMAT(ac_re.dt_hr_coleta_forcada, '%d/%m/%y-%H:%i') as dt_hr_coleta_forcada,
											re.te_ip_rede,
											re.id_rede,											
											re.nm_rede,
											ac_re.id_acao,
											ac.te_descricao_breve,
											ac.te_nome_curto_modulo
					FROM 		redes re, 
								acoes_redes ac_re,
								acoes ac,
								computadores comp
					WHERE		re.id_rede = ac_re.id_rede and
								ac_re.id_acao = ac.id_acao and
								ac_re.id_local = re.id_local AND 
								ac_re.id_local = " . $id_local . " AND 
								ac.id_acao = '" . $rowAcoes['id_acao']."' and
								comp.id_computador = ".$_GET['id_computador']." and 
								comp.id_rede = re.id_rede and 
								ac.id_acao <> 'cs_suporte_remoto'
					GROUP BY    re.te_ip_rede
					ORDER BY	re.nm_rede"; 			
		}
	else
		{
		$queryRedes = "	SELECT 		DATE_FORMAT(ac_re.dt_hr_coleta_forcada, '%d/%m/%y-%H:%i') as dt_hr_coleta_forcada,
											re.te_ip_rede,
											re.id_rede,											
											re.nm_rede,
											ac_re.id_acao,
											ac.te_descricao_breve
					FROM 		redes re, 
								acoes_redes ac_re,
								acoes ac
					WHERE		re.id_rede = ac_re.id_rede and
								ac_re.id_acao = ac.id_acao and
								re.id_local = " . $id_local . " AND 
								ac.id_acao = '" . $rowAcoes['id_acao']."' 
					GROUP BY    re.te_ip_rede
					ORDER BY	re.nm_rede"; 
//								ac_re.id_local = re.id_local AND
//								ac_re.id_local = ".$_SESSION['id_local']." AND
//								ac.id_acao = '" . $row['id_acao']."' 
					
		}

$resultRedes = mysql_query($queryRedes) or die($oTranslator->_('kciq_msg select on table fail', array('redes'))."! ".$oTranslator->_('kciq_msg session fail',false,true)."!"); 
	if (!$forca_coleta_estacao=='OK')			
		{
		$arrRedesNames 	 = array();
		$arrRedesDtHrCF  = array();
		$arrRedesId 	 = array();			
		while ($rowRedes = mysql_fetch_array($resultRedes))
			{
			array_push($arrRedesId     , $rowRedes['id_rede']);
			array_push($arrRedesNames  , $rowRedes['te_ip_rede'] . ' - ' . $rowRedes['nm_rede']);	
			array_push($arrRedesDtHrCF , $rowRedes['dt_hr_coleta_forcada']);		
			}
		}
	else
		{
		$rowRedes = mysql_fetch_array($resultRedes);
		}
			
	if (!$forca_coleta_estacao=='OK')			
		{
		?>			
       	<tr> 
        <td colspan="3"><hr></td>
   	  	</tr>
   		<tr> 
       	<td width="39" class="label"><?php echo $oTranslator->_('Acao');?>:</td>
       	<td colspan="3" nowrap class="destaque"><u><?php echo $rowAcoes['te_descricao_breve'];?></u></td>
   		</tr>
		<?php if (count($v_redes) > 1)
			{
			?>
   			<tr> 
       		<td></td>
       		<td class="opcao_tabela"><div align="left"> 
			<input name="<?php echo $row['id_acao']; ?>" type="checkbox" onClick="MarcaDesmarcaTodos(this.form.<?php echo $rowAcoes['id_acao'];?>);">
			</div></td>
       		<td class="destaque" nowrap><div align="left"><?php echo $oTranslator->_('Marca ou Desmarca Acao para as Redes abaixo');?></div></td>
    		</tr>				
			<?php }
	  	for ($i=0;$i<count($arrRedesNames);$i++)
	  		{
			?>
       	  	<tr> 
	   	    <td></td>
          	<td class="opcao" nowrap><div align="left">
			<input name="<?php echo $rowAcoes['id_acao']; ?>" type="checkbox" id="<?php echo $arrRedesId[$i]; ?>" value="<?php echo $arrRedesId[$i]; ?>" class="opcao"  onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >
			<input name="<?php echo $rowAcoes['id_acao']; ?>" type="hidden"   id="<?php echo $arrRedesId[$i]; ?>">
			</div></td>
            <td nowrap class="descricao"><div align="left"><?php echo $arrRedesNames[$i]; ?>
			<?php if ($arrRedesDtHrCF[$i]) 
				{
				echo ' (Forçada em: '.$arrRedesDtHrCF[$i] . ')';
				}
				?>
			</div></td>
	       	</tr>
    	    <?php }
		}
	elseif ($_SESSION["cs_nivel_administracao"] == 1 || 
			$_SESSION["cs_nivel_administracao"] == 2 ||
			$_SESSION["cs_nivel_administracao"] == 3)			
		if ($rowAcoes['id_acao'])
			{
			?>			
    		<tr nowrap> 
       		<td></td>
       		<td class="opcao"><div align="left"> 
			<input name="<?php echo $rowAcoes['id_acao']; ?>" type="checkbox" value="<?php echo $rowAcoes['te_nome_curto_modulo']; ?>"  class="opcao" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >
			</div></td>
            <td nowrap class="descricao"><div align="left"><em><?php echo $rowAcoes['te_descricao_breve'];?></em></div></td>
    	    </tr>								
			<?php }
	}				
?>
</table>
</tr>
</table>