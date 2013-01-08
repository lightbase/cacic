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
session_start();
/*
 * verifica se houve login e também regras para outras verificações (ex: permissões do usuário)!
 */
if(!isset($_SESSION['id_usuario'])) 
  die('Acesso negado (Access denied)!');
else { // Inserir regras para outras verificações (ex: permissões do usuário)!
}

?>
<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<?php 
require_once('../include/js/opcoes_avancadas_combos.js');  
?>
<link rel="stylesheet"   type="text/css" href="../include/css/cacic.css">
</head>

<body background="../imgs/linha_v.gif"  onLoad="SetaCampo('in_exibe_bandeja');">
<?php
$frm_id_local = ($_POST['frm_id_local']<>''?$_POST['frm_id_local']:$_SESSION['id_local']);

require_once('../include/library.php');
conecta_bd_cacic();
$where = ' AND loc.id_local ='.$frm_id_local;
if ($_SESSION['te_locais_secundarios'])
	{
	$where = str_replace('loc.id_local',' (loc.id_local',$where);
	$where .= ' OR (loc.id_local IN ('.$_SESSION['te_locais_secundarios'].'))) ';
	}

$queryConfiguracoesLocais = "	SELECT 			loc.id_local,
												loc.sg_local,
												loc.nm_local,
												c_loc.in_exibe_erros_criticos,
												c_loc.in_exibe_bandeja,
												c_loc.nu_exec_apos,
												c_loc.dt_hr_coleta_forcada,																			 												
												c_loc.nu_intervalo_exec,
												c_loc.te_senha_adm_agente,												
												c_loc.te_serv_updates_padrao,												
												c_loc.te_serv_cacic_padrao,																																				
												c_loc.te_enderecos_mac_invalidos,																																																
												c_loc.te_janelas_excecao																																																												
								FROM 			locais loc,
												configuracoes_locais c_loc
								WHERE 			loc.id_local = c_loc.id_local ";
$orderby = ' ORDER BY loc.sg_local';

$resultConfiguracoesLocais = mysql_query($queryConfiguracoesLocais.$where.$orderby) or die('1-'.$oTranslator->_('kciq_msg select on table fail', array('Locais/Configuracoes_Locais'))."! ".$oTranslator->_('kciq_msg session fail',false,true)."!");
$row_configuracoes_locais = mysql_fetch_array($resultConfiguracoesLocais);
if ($_SESSION['cs_nivel_administracao'] == 1 || $_SESSION['cs_nivel_administracao'] == 2 || ($_SESSION['cs_nivel_administracao'] == 3 && $_SESSION['te_locais_secundarios']<>''))
	{	
	?>
	<div id="LayerLocais" style="position:absolute; width:200px; height:115px; z-index:1; left: 0px; top: 0px; visibility:hidden">
	<?php

	$resultConfiguracoesLocais = mysql_query($queryConfiguracoesLocais.$orderby) or die('2-'.$oTranslator->_('kciq_msg select on table fail', array('Locais/Configuracoes_Locais'))."! ".$oTranslator->_('kciq_msg session fail',false,true)."!");

	echo '<select name="SELECTconfiguracoes_locais">';
	while ($rowConfiguracoesLocais = mysql_fetch_array($resultConfiguracoesLocais))
		{
		echo '<option id="'.$rowConfiguracoesLocais['id_local'].'" value="'. $rowConfiguracoesLocais['in_exibe_bandeja'].'#'.
																			 $rowConfiguracoesLocais['in_exibe_erros_criticos'].'#'.
																			 $rowConfiguracoesLocais['te_senha_adm_agente'].'#'.																			 
																			 $rowConfiguracoesLocais['nu_exec_apos'].'#'.
																			 $rowConfiguracoesLocais['nu_intervalo_exec'].'#'.																			 
																			 $rowConfiguracoesLocais['te_enderecos_mac_invalidos'].'#'.																			 
																			 $rowConfiguracoesLocais['te_janelas_excecao'].'#'.																			 
																			 $rowConfiguracoesLocais['te_serv_updates_padrao'].'#'.
																			 $rowConfiguracoesLocais['te_serv_cacic_padrao'].'#'.
																			 $rowConfiguracoesLocais['dt_hr_coleta_forcada'].'">'.$rowConfiguracoesLocais['nm_local'].'</option>';							
		}
	echo '</select>';		
	?>
	</div>
	<?php
	}
	?>

<script language="JavaScript" type="text/javascript" src="../include/js/cacic.js"></script>
<script language="JavaScript" type="text/javascript" src="../include/js/setLocalConfigAgentes.js"></script>
<form action="config_agentes_set.php"  method="post" ENCTYPE="multipart/form-data" name="forma">
<table width="85%" border="0" align="center">
  <tr> 
      <td class="cabecalho"><?php echo $oTranslator->_('Configuracoes dos Modulos Agentes');?></td>
  </tr>
  <tr> 
    <td class="descricao"><?php echo $oTranslator->_('As opcoes abaixo determinam qual sera o comportamento dos agentes do');?> CACIC.</td>
  </tr>
</table>
  <table width="85%" border="0" align="center" cellpadding="0" cellspacing="1">
  	<?php 

	// Será mostrado apenas para os níveis Administração, Gestão Central e Supervisão com acessos a locais secundários.
	if ($_SESSION['cs_nivel_administracao'] == 1 || $_SESSION['cs_nivel_administracao'] == 2 || ($_SESSION['cs_nivel_administracao'] == 3 && $_SESSION['te_locais_secundarios']<>''))
		{
		?>
	    <tr> 
	    <td class="label"><br><?php echo $oTranslator->_('Locais');?>: </td>
    	</tr>  
    	<tr> 
      	<td height="1" bgcolor="#333333"></td>
    	</tr>
    	<tr> 	
		<td>
		<?php
		if ($_SESSION['cs_nivel_administracao'] == 1 || $_SESSION['cs_nivel_administracao'] == 2)
			$where = '';

		$query_locais = "SELECT		loc.id_local,
									loc.nm_local,
									loc.sg_local
					  	FROM		locais loc 
						WHERE 		1 ".
						$where . " 
				  		ORDER BY  	loc.sg_local"; 
		$result_locais = mysql_query($query_locais) or die('3-'.$oTranslator->_('kciq_msg select on table fail', array('locais'))."! ".$oTranslator->_('kciq_msg session fail',false,true)."!"); 

		?>
    	<select size="5" name="SELECTlocais"  class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" onChange="setLocal(this);">	
    	<?php 		
		while ($row_locais = mysql_fetch_array($result_locais))
			{
			echo '<option id="'.$row_locais['id_local'].'" value="'. $row_locais['id_local'].'"';
			if ($row_locais['id_local']==$frm_id_local) 
				echo '  selected="selected"';
			
			echo '>'.$row_locais['sg_local'].' - '.$row_locais['nm_local'].'</option>';					
			}
 		?> 
    	</select>
		</td>
    	</tr>
		<?php
		}
		?>
  
    <tr> 
      <td class="label">

        <?php 

	AntiSpy('1,2,3'); // Permitido somente a estes cs_nivel_administracao...
	// 1 - Administração
	// 2 - Gestão Central
	// 3 - Supervisão

	$query = "SELECT 	in_exibe_bandeja, 
						in_exibe_erros_criticos, 
						nu_exec_apos, 
						nu_intervalo_exec, 
						te_senha_adm_agente, 
 	         			DATE_FORMAT(dt_hr_coleta_forcada, '%d/%m/%Y às %H:%i') as dt_hr_coleta_forcada, 
						te_enderecos_mac_invalidos, 
						te_janelas_excecao
	          FROM 		configuracoes_locais 
			  WHERE		id_local = ".$frm_id_local." 
			  			limit 1"; 						 


	$result_configuracoes = mysql_query($query) or die('4-'.$oTranslator->_('kciq_msg select on table fail', array('configuracoes_locais'))."! ".$oTranslator->_('kciq_msg session fail',false,true)."!"); 
	$campos_configuracoes = mysql_fetch_array($result_configuracoes);
?>
        &nbsp;<br>
        <?php echo $oTranslator->_('Exibir o icone do CACIC na bandeja (systray)');?>:</td>
    </tr>
    <tr> 
      <td height="1" bgcolor="#333333"></td>
    </tr>
    <tr> 
      <td class="opcao"><p><input name="in_exibe_bandeja" type="radio" value="S"  <?php if (strtoupper($campos_configuracoes['in_exibe_bandeja']) == 'S') echo 'checked'; ?>  class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >
          <?php echo $oTranslator->_('kciq_msg yes');?><br>
          <input type="radio" name="in_exibe_bandeja" value="N" <?php if (strtoupper($campos_configuracoes['in_exibe_bandeja']) == 'N') echo 'checked'; ?>  class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" ><?php echo $oTranslator->_('ksiq_msg not');?><br>	  
	  <input name="frm_id_local" id="frm_id_local" type="hidden" value="<?php echo $frm_id_local; ?>"></p></td>
    </tr>
    <tr> 
      <td class="label">&nbsp;&nbsp; <br>
        <?php echo $oTranslator->_('Exibir erros criticos aos usuarios');?>: </td>
    </tr>
    <tr> 
      <td height="1" bgcolor="#333333"></td>
    </tr>
    <tr> 
      <td class="opcao"><p><input name="in_exibe_erros_criticos" type="radio" value="S"  <?php if (strtoupper($campos_configuracoes['in_exibe_erros_criticos']) == 'S') echo 'checked'; ?>  class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >
           <?php echo $oTranslator->_('kciq_msg yes');?><br>
          <input type="radio" name="in_exibe_erros_criticos" value="N" <?php if (strtoupper($campos_configuracoes['in_exibe_erros_criticos']) == 'N') echo 'checked'; ?>  class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >
          <?php echo $oTranslator->_('kciq_msg no');?><br>
          </p></td>
    </tr>
    <tr> 
      <td class="label">&nbsp;&nbsp; <br>
        <?php echo $oTranslator->_('Senha usada para configurar e finalizar os agentes');?>: </td>
    </tr>
    <tr> 
      <td height="1" bgcolor="#333333"></td>
    </tr>
    <tr> 
      <td><p>
          <input name="te_senha_adm_agente" type="password"  value="<?php echo $campos_configuracoes['te_senha_adm_agente']; ?>" maxlength="30" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >
          <br></p></td>
    </tr>
    <tr> 
      <td class="label">&nbsp;<br><?php echo $oTranslator->_('Inicio de execucao das acoes');?>: </td>
    </tr>
    <tr> 
      <td height="1" bgcolor="#333333"></td>
    </tr>
    <tr> 
      <td class="opcao"> <p>
          <input name="nu_exec_apos" type="radio" value="0" <?php if ($campos_configuracoes['nu_exec_apos'] == '0') echo 'checked'; ?>  class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >
          <?php echo $oTranslator->_('Imediatamente apos a inicializacao do CACIC');?><br>
          <input type="radio" name="nu_exec_apos" value="10"  <?php if ($campos_configuracoes['nu_exec_apos'] == '10') echo 'checked'; ?>  class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >
          <?php echo $oTranslator->_('10 minutos apos a inicializacao do CACIC');?><br>
          <input name="nu_exec_apos" type="radio" value="20"  <?php if ($campos_configuracoes['nu_exec_apos'] == '20') echo 'checked'; ?>  class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >
          <?php echo $oTranslator->_('20 minutos apos a inicializacao do CACIC');?><br>
          <input type="radio" name="nu_exec_apos" value="30"  <?php if ($campos_configuracoes['nu_exec_apos'] == '30') echo 'checked'; ?>  class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >
          <?php echo $oTranslator->_('30 minutos apos a inicializacao do CACIC');?><br>
          <input type="radio" name="nu_exec_apos" value="60"  <?php if ($campos_configuracoes['nu_exec_apos'] == '60') echo 'checked'; ?>  class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >
          <?php echo $oTranslator->_('1 hora apos a inicializacao do CACIC');?><br>
          </p></td>
    </tr>
    <tr> 
      <td class="label">&nbsp;<br><?php echo $oTranslator->_('Intervalo de execucao das acoes');?>:</td>
    </tr>
    <tr> 
      <td height="1" bgcolor="#333333"></td>
    </tr>
    <tr> 
      <td class="opcao"><p> 
          <input type="radio" name="nu_intervalo_exec" value="2"   <?php if ($campos_configuracoes['nu_intervalo_exec'] == '2') echo 'checked'; ?>  class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >
          <?php echo $oTranslator->_('A cada 2 horas');?><br>
           
          <input type="radio" name="nu_intervalo_exec" value="4" <?php if ($campos_configuracoes['nu_intervalo_exec'] == '4') echo 'checked'; ?>  class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >
          <?php echo $oTranslator->_('A cada 4 horas');?><br>
           
          <input type="radio" name="nu_intervalo_exec" value="6"  <?php if ($campos_configuracoes['nu_intervalo_exec'] == '6') echo 'checked'; ?> class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >
          <?php echo $oTranslator->_('A cada 6 horas');?><br>
           
          <input type="radio" name="nu_intervalo_exec" value="8"  <?php if ($campos_configuracoes['nu_intervalo_exec'] == '8') echo 'checked'; ?> class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >
          <?php echo $oTranslator->_('A cada 8 horas');?><br>
           
          <input type="radio" name="nu_intervalo_exec" value="10"  <?php if ($campos_configuracoes['nu_intervalo_exec'] == '10') echo 'checked'; ?>  class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >
          <?php echo $oTranslator->_('A cada 10 horas');?><br>
          </p></td>

    </tr>
    <tr> 
      <td>&nbsp;</td>
    </tr>

	<tr> 	
    <td class="label"><?php echo $oTranslator->_('Opcoes avancadas');?>:</td>
    </tr>
    <tr> 
    <td height="1" bgcolor="#333333"></td>
    </tr>

    <tr> 
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td class="label"> <p><u><?php echo $oTranslator->_('Enderecos MAC a desconsiderar');?></u><br></p></td>
    </tr>
    <tr> 
      <td class="ajuda"><?php echo $oTranslator->_('kciq_msg MAC Address Help');?></td>
    </tr>
	
    <tr> 
      <td height="1" bgcolor="#333333"></td>
    </tr>
    <tr> 
      <td><p><textarea name="te_enderecos_mac_invalidos" cols="60" id="te_enderecos_mac_invalidos" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" ><?php echo $campos_configuracoes['te_enderecos_mac_invalidos']; ?></textarea>
          </p></td>
    </tr>
    <tr> 
      <td class="descricao"><?php echo $oTranslator->_('Atencao: informe os enderecos separados por virgulas');?>(&quot;,&quot;). 
        <br>
        <?php echo $oTranslator->_('Exemplo');?>: &quot;00-53-45-00-00-00,00-00-00-00-00-00,44-45-53-54-00-00,44-45-53-54-00-01,28-41-53&quot;</td>
    </tr>

    <tr> 
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td class="label"> <p><u><?php echo $oTranslator->_('Aplicativos (janelas) a evitar');?></u><br></p>
        </td>
    </tr>
    <tr> 
      <td class="descricao"><?php echo $oTranslator->_('Evita que o Gerente de Coletas seja acionado enquanto tais aplicativos (janelas) estiverem ativos');?>.</td>
    </tr>
	
    <tr> 
      <td height="1" bgcolor="#333333"></td>
    </tr>
    <tr> 
      <td><p><textarea name="te_janelas_excecao" cols="60" id="te_janelas_excecao" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" ><?php echo $campos_configuracoes['te_janelas_excecao']; ?></textarea>
          </p></td>
    </tr>
    <tr> 
      <td class="descricao">
      <?php echo $oTranslator->_('Atencao: informe os nomes separados por virgulas');?>(&quot;,&quot;).<br>
        <?php echo $oTranslator->_('Exemplo');?>: &quot;<?php echo $oTranslator->_('HOD - Microsoft Word - Corel Draw - PhotoShop');?>&quot;</td>
    </tr>
	
    <?php
	require_once('../include/opcoes_avancadas.php');
	?>
  </table>
<script language="javascript">setLocal(document.getElementById('SELECTlocais'));</script>					    
  <tr> 
      	<td height="1" bgcolor="#333333"></td>
    	</tr>
    	<tr> 
      	<td>&nbsp;</td>
    	</tr>
    	<tr> 
      	<td><div align="center"> 
        <input name="submit" type="submit" value="<?php echo $oTranslator->_('Gravar informacoes');?>" onClick="return SelectAll_Forca_Coleta();return Confirma('<?php echo $oTranslator->_('Confirma Configuracao de Agentes?');?>');" <?php echo ($_SESSION['cs_nivel_administracao']<>1&&$_SESSION['cs_nivel_administracao']<>3?'disabled':'')?>>
        </div></td>
    	</tr>
  </table>
</form>
<p>&nbsp;</p>
</body>
</html>
