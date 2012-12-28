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

// Essa vari�vel � usada pelo arquivo de include selecao_redes_inc.php e inicio_relatorios_inc.php.
$id_acao = 'cs_coleta_patrimonio';
require_once('../../include/inicio_relatorios_inc.php'); 
?>
<script>
function montaComboMulti(strComboName, fromImage)
	{
	var countSelecteds = 0;
	var UO_IDS = "";
	var imgName = 'img_'+strComboName;

	imgMenos = new Image();
	imgMenos.src = '../../imgs/menos.gif';
	imgMais = new Image();
	imgMais.src = '../../imgs/mais.gif';	
	
	for (i=0;i<window.document.forms.length;i++)
		for (j=0;j<window.document.forms[i].elements.length;j++)
			if (window.document.forms[i].elements[j].name == strComboName) 
				{
				for (k=0;k<window.document.forms[i].elements[j].options.length;k++)
					if (window.document.forms[i].elements[j].options[k].value != '0' &&					
					    window.document.forms[i].elements[j].options[k].selected == true)
						{
						countSelecteds ++;
						if (UO_IDS != "")
							UO_IDS += ",";
						UO_IDS += window.document.forms[i].elements[j].options[k].value;
						}
						
				if (fromImage) // Se h� algo selecionado ou a chamada partiu da imagem (OnClick)
					{
					if (window.document.forms[i].elements[j].size == 5) // Se j� tiver sido aberto
						{					
						window.document.images[imgName].src = imgMais.src;
						window.document.images[imgName].title = "Clique para selecionar m�ltiplos valores";
						window.document.forms[i].elements[j].multiple = false;						
						window.document.forms[i].elements[j].size = 1;
						}
					else
						{
						window.document.images[imgName].src = imgMenos.src;
						window.document.images[imgName].title = "Clique para selecionar apenas um valor";
						window.document.forms[i].elements[j].multiple = true;
						window.document.forms[i].elements[j].size = 5;
						}
					}
				if (countSelecteds > 0)
					{
					var fieldName = window.document.forms[i].elements[j].name;
					fieldName = 'IDS_'+fieldName;						
					window.document.forms[i].elements[fieldName].value = UO_IDS;
					}
				}
	}

	
</script>
<table width="90%" border="0" align="center">
  <tr> 
    <td class="cabecalho">Relat&oacute;rio de Informa&ccedil;&otilde;es Patrimoniais 
      e Localiza&ccedil;&atilde;o F&iacute;sica</td>
  </tr>
  <tr> 
    <td class="descricao">Este relat&oacute;rio exibe informa&ccedil;&otilde;es 
      de Patrim&ocirc;nio e Localiza&ccedil;&atilde;o F&iacute;sica dos computadores 
      das redes selecionadas. &Eacute; poss&iacute;vel selecionar os sistemas 
      operacionais desejados e tamb&eacute;m determinar quais informa&ccedil;&otilde;es 
      de Patrim&ocirc;nio e Localiza&ccedil;&atilde;o F&iacute;sica ser&atilde;o 
      exibidas no relat&oacute;rio.</td>
  </tr>
  <tr> 
    <td>
<?
require_once('../../include/library.php'); 
?>
				</td>
  </tr>
		
</table>
<form action="rel_patrimonio.php" target="_blank" method="post" ENCTYPE="multipart/form-data" name="forma"   onsubmit="return valida_form()">
  <table width="90%" border="0" align="center" cellpadding="5" cellspacing="1">
  <? 
  /*
  if ($_SESSION['cs_nivel_administracao']==1 || $_SESSION['cs_nivel_administracao']==2)
  	{
	?>
	<tr> 
      <td valign="top">
	  <table width="100%" border="0" cellpadding="0" cellspacing="1">
          <tr> 
            <td>&nbsp;</td>
          </tr>
          <tr> 
            <td class="label">Selecione os locais:</td>
          </tr>
          <tr> 
            <td height="1" bgcolor="#333333"></td>
          </tr>
          <tr> 
            <td height="1"><table border="0" cellpadding="0" cellspacing="0">
                <tr> 
                  <td>&nbsp;&nbsp;</td>
                  <td class="cabecalho_tabela"><div align="left">Dispon&iacute;veis:</div></td>
                  <td>&nbsp;&nbsp;</td>
                  <td width="40">&nbsp;</td>
                  <td nowrap>&nbsp;&nbsp;</td>
                  <td nowrap class="cabecalho_tabela">Selecionados:</td>
                  <td nowrap>&nbsp;&nbsp;</td>
                </tr>
                <tr> 
                  <td>&nbsp;</td>
                  <td> <div align="left"> 
                      <select multiple name="list11[]" size="10"  class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >
                        <?
                        $query = "SELECT 	id_local,
											sg_local
                                  FROM 		locais
                                  ORDER BY	sg_local";
                        $result = mysql_query($query) or die('Erro na consulta � tabela "locais".');
                        while ($row = mysql_fetch_array($result)) 
							{ 
                            echo '<option value=' . $row['id_local'] . '>' . $row["sg_local"] . '</option>';
                        	}
						?>
                      </select>
                      </div></td>
                  <td>&nbsp;</td>
                  <td width="40"> <div align="center"> 
                      <input type="button" value="   &gt;   " onClick="move(this.form.elements['list11[]'],this.form.elements['list12[]'])" name="B132">					  
                      <br>
                      <br>
                      <input type="button" value="   &lt;   " onClick="move(this.form.elements['list12[]'],this.form.elements['list11[]'])" name="B232">					  
                    </div></td>
                  <td>&nbsp;</td>
                  <td><select multiple name="list12[]" size="10" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >
                    </select></td>
                  <td>&nbsp;</td>
                </tr>
              </table></td>
          </tr>
          <tr> 
            <td class="descricao">&nbsp;&nbsp;&nbsp;(Dica: 
              use SHIFT ou CTRL para selecionar m&uacute;ltiplos itens)</td>
          </tr>
        </table></td>
    </tr>
    <tr> 
      <td valign="top">&nbsp;</td>
    </tr>  
	<?
	}
	*/
	?>
	
    <tr> 
      <td valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="1">
          <tr> 
            <td>&nbsp;</td>
          </tr>
          <tr> 
            <td class="label">Selecione 
              as informa&ccedil;&otilde;es que deseja exibir:</td>
          </tr>
          <tr> 
            <td height="1" bgcolor="#333333"></td>
          </tr>
          <tr> 
            <td height="1"><table border="0" cellpadding="0" cellspacing="0">
                <tr> 
                  <td>&nbsp;&nbsp;</td>
                  <td class="cabecalho_tabela"><div align="left">Dispon&iacute;veis:</div></td>
                  <td>&nbsp;&nbsp;</td>
                  <td width="40">&nbsp;</td>
                  <td nowrap>&nbsp;&nbsp;</td>
                  <td nowrap class="cabecalho_tabela">Selecionados:</td>
                  <td nowrap>&nbsp;&nbsp;</td>
                </tr>
                <tr> 
                  <td>&nbsp;</td>
                  <td> <div align="left"> 
                      <select multiple name="list5[]" size="10"  class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >
                        <?
//						$where = ($_SESSION['cs_nivel_administracao']<>1 && $_SESSION['cs_nivel_administracao']<>2?' AND pat.id_local = '.$_SESSION['id_local']:'');						
						$where = ' AND pat.id_local = '.$_SESSION['id_local'];						
						
						// Listo primeiro os campos Entidade e �rg�o
                        $query = "SELECT
								  DISTINCT 	te_etiqueta, 
											nm_campo_tab_patrimonio, 
											in_destacar_duplicidade
                                  FROM 		patrimonio_config_interface pat,
								  			redes
                                  WHERE 	id_etiqueta in ('etiqueta1','etiqueta1a','etiqueta2') AND (nm_campo_tab_patrimonio in ('id_unid_organizacional_nivel1','id_unid_organizacional_nivel1a', 'id_unid_organizacional_nivel2', 'te_localizacao_complementar')
								  			OR in_exibir_etiqueta = 'S') AND
											redes.id_local = pat.id_local ".
											$where. '
								  ORDER BY  te_etiqueta';
								  
                        $result = mysql_query($query) or die('Erro na consulta � tabela "patrimonio_config_interface" ou sua sess�o expirou!');
                        while ($row = mysql_fetch_array($result)) 
							{ 
                            echo '<option value=", patrimonio.' . $row['nm_campo_tab_patrimonio'] . ' as &quot;' . $row['te_etiqueta'] . '&quot;';
							if (trim($row['in_destacar_duplicidade'])<>'' && trim($row['in_destacar_duplicidade'])<>'N') echo '#in_destacar_duplicidade.'.$row['in_destacar_duplicidade'];
							echo '">' . $row["te_etiqueta"] . '</option>';
                        	}
							
						// Listo o restante dos campos de patrim�nio							
                        $query = "SELECT
								  DISTINCT 	te_etiqueta, 
											nm_campo_tab_patrimonio, 
											in_destacar_duplicidade
                                  FROM 		patrimonio_config_interface pat,
								  			redes
                                  WHERE 	id_etiqueta not in ('etiqueta1','etiqueta1a','etiqueta2') AND (nm_campo_tab_patrimonio in ('id_unid_organizacional_nivel1','id_unid_organizacional_nivel1a','id_unid_organizacional_nivel2', 'te_localizacao_complementar')
								  			OR in_exibir_etiqueta = 'S') AND
											redes.id_local = pat.id_local ".
											$where. '
								  ORDER BY  te_etiqueta';								  
								  
                        $result = mysql_query($query) or die('Erro na consulta � tabela "patrimonio_config_interface" ou sua sess�o expirou!');
                        while ($row = mysql_fetch_array($result)) 
							{ 
                            echo '<option value=", patrimonio.' . $row['nm_campo_tab_patrimonio'] . ' as &quot;' . $row['te_etiqueta'] . '&quot;';
							if (trim($row['in_destacar_duplicidade'])<>'' && trim($row['in_destacar_duplicidade'])<>'N') echo '#in_destacar_duplicidade.'.$row['in_destacar_duplicidade'];
							echo '">' . $row["te_etiqueta"] . '</option>';
                        	}
							
						?>
                      </select>
                      </div></td>
                  <td>&nbsp;</td>
                  <td width="40"> <div align="center"> 
				  		<?
                      //<input type="button" value="   &gt;   " onClick="copia(this.form.elements['list5[]'],this.form.elements['list7[]']); move(this.form.elements['list5[]'],this.form.elements['list6[]'])" name="B132">
					  ?>
                      <input type="button" value="   &gt;   " onClick="move(this.form.elements['list5[]'],this.form.elements['list6[]'])" name="B132">					  
                      <br>
                      <br>
					  <?
                      //<input type="button" value="   &lt;   " onClick="exclui(this.form.elements['list6[]'],this.form.elements['list8[]']); exclui(this.form.elements['list6[]'],this.form.elements['list7[]']); move(this.form.elements['list6[]'],this.form.elements['list5[]'])" name="B232">
					  ?>
                      <input type="button" value="   &lt;   " onClick="move(this.form.elements['list6[]'],this.form.elements['list5[]'])" name="B232">					  
                    </div></td>
                  <td>&nbsp;</td>
                  <td><select multiple name="list6[]" size="10" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >
                    </select></td>
                  <td>&nbsp;</td>
                </tr>
              </table></td>
          </tr>
          <tr> 
            <td class="descricao">&nbsp;&nbsp;&nbsp;(Dica: 
              use SHIFT ou CTRL para selecionar m&uacute;ltiplos itens)</td>
          </tr>
        </table></td>
    </tr>
    <tr> 
      <td valign="top">&nbsp;</td>
    </tr>
	<tr><td>

  <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
     <tr> 
     <td class="label" colspan="3"><p>Informe os crit�rios para pesquisa de informa��es patrimoniais:</p></td>
     </tr>
     <tr> 
     <td height="1" bgcolor="#333333" colspan="3"></td>
     </tr>  
 	
        <?  
		$cor = 1;
		//$where = ($_SESSION['cs_nivel_administracao']<>1 && $_SESSION['cs_nivel_administracao']<>2?' AND pat.id_local = '.$_SESSION['id_local']:'');
		$where = ' AND pat.id_local = '.$_SESSION['id_local'];		
	
		// Como acima, listo primeiro os campos Entidade e �rg�o (etiqueta1 e etiqueta2 ou UON1 e UON2)
		$query = "SELECT 
				  DISTINCT	nm_campo_tab_patrimonio,
							te_etiqueta,
							id_etiqueta 
				  FROM 		patrimonio_config_interface pat,
					 		redes 
				  WHERE 	id_etiqueta in ('etiqueta1','etiqueta1a','etiqueta2') AND
							redes.id_local = pat.id_local ".
							$where . "   
				  ORDER BY te_etiqueta";

		$res_fields = mysql_query($query);

		// Caso o usu�rio n�o tenha n�vel administrativo, filtro as UON2 para sele��o das UON1a
		if ($_SESSION['cs_nivel_administracao']<>1 && $_SESSION['cs_nivel_administracao']<>2)
			{
		
			$selectUON1a 	= ' SELECT 	distinct id_unid_organizacional_nivel1a  ';
			$fromUON1a		= ' FROM	unid_organizacional_nivel2 UON2 ';
			$whereUON1a		= ' WHERE 	UON2.id_local = '.$_SESSION['id_local'];
			if ($_SESSION['te_locais_secundarios'] <> '')
				$whereUON1a .= ' OR UON2.id_local in ('.$_SESSION['te_locais_secundarios'].')';
			
			$strTripaUON1a 	= '';
			$queryUON1a		= $selectUON1a . $fromUON1a . $whereUON1a;
//if ($_SERVER['REMOTE_ADDR']=='10.71.0.58')
	//echo $queryUON1a . '<br>';			
			$resUON1a		= mysql_query($queryUON1a);
			
			while ($rowUON1a = mysql_fetch_array($resUON1a))
				{
				$strTripaUON1a .= ($strTripaUON1a <> ''?',':'');
				$strTripaUON1a .= $rowUON1a['id_unid_organizacional_nivel1a'];
				}
				
			}
		
		$idUO = '';
		while ($row_fields = mysql_fetch_array($res_fields)) 
			{
			$idUO = str_replace('etiqueta','',$row_fields['id_etiqueta']);

			?>
			<tr <? if ($cor) echo 'bgcolor="#E1E1E1"';?> class="normal"> 		
			<td nowrap align="left"><? echo $row_fields['te_etiqueta'];?></td>
			<td nowrap align="left">			
			<select name="frm_condicao1_<? echo $row_fields['nm_campo_tab_patrimonio']; ?>" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);">
			<option value=""></option>
			<option value="<? echo 'unid_organizacional_nivel'.$idUO.'__'.$row_fields['nm_campo_tab_patrimonio']." IN      ('frm_te_valor_condicao1')"  ;?>" onClick="Verifica_Condicoes_Seta_Campo('<? echo "frm_te_valor_condicao1_". $row_fields['nm_campo_tab_patrimonio']; ?>');">IGUAL A</option>		
			<option value="<? echo 'unid_organizacional_nivel'.$idUO.'__'.$row_fields['nm_campo_tab_patrimonio']." NOT IN  ('frm_te_valor_condicao1')"  ;?>" onClick="Verifica_Condicoes_Seta_Campo('<? echo "frm_te_valor_condicao1_". $row_fields['nm_campo_tab_patrimonio']; ?>');">DIFERENTE DE</option>			
			</select>

			<?
			$select1  =  ($idUO=='1' ? ' id_unid_organizacional_nivel1 as id,nm_unid_organizacional_nivel1 as nm':($idUO=='1a'?'id_unid_organizacional_nivel1a as id,nm_unid_organizacional_nivel1a as nm':' id_unid_organizacional_nivel2 as id,nm_unid_organizacional_nivel2 as nm'));
			$from1    =  ($idUO=='1' ? ' unid_organizacional_nivel1 UO':($idUO=='1a'?'unid_organizacional_nivel1a UO':'unid_organizacional_nivel2 UO'));
			$where1	  = '';
			if ($_SESSION['cs_nivel_administracao']<>1 && $_SESSION['cs_nivel_administracao']<>2)
				{
				$where1   .=  ($idUO<>'1' && $idUO<>'1a' ? ' WHERE UO.id_local = '.$_SESSION['id_local']:'');	
				$where1   .= ($idUO<>'1' && $idUO<>'1a' && $_SESSION['te_locais_secundarios']<>''?' OR (UO.id_local in ('.$_SESSION['te_locais_secundarios'].')) ':'');									
				$where1   .= ($idUO<>'1' && $idUO<>'1a' && $_SESSION['te_locais_secundarios']<>''?' OR (UO.id_local in ('.$_SESSION['te_locais_secundarios'].')) ':'');												
				$where1	   = ($idUO=='1a'?' WHERE id_unid_organizacional_nivel1a in ('.$strTripaUON1a.') ':$where1);				
				}
			$orderby1 =  ($idUO=='1' ? ' nm_unid_organizacional_nivel1':($idUO=='1a'?'nm_unid_organizacional_nivel1a':'nm_unid_organizacional_nivel2'));
			
			$query1 = "SELECT  $select1
					   FROM    $from1
					   $where1
					   ORDER BY $orderby1";
//if ($_SERVER['REMOTE_ADDR']=='10.71.0.58')
//	echo $query1 . '<br>';					   
			?>
			<td align="left">
			<select name="frm_UO<? echo $idUO .'_' . $row_fields['id_etiqueta']; ?>" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);montaComboMulti('frm_UO<? echo $idUO .'_' . $row_fields['id_etiqueta'];?>',false);">
			<option value=""></option>			
			<?
			$res_fields1 = mysql_query($query1);			
			while ($row_fields1 = mysql_fetch_array($res_fields1)) 			
				{
				?>
				<option value="<? echo $row_fields1['id'];?>" onClick="Verifica_Condicoes_Seta_Campo('<? echo "frm_te_valor_condicao1_". $row_fields1['id']; ?>');"><? echo $row_fields1['nm'];?></option>		
				<?
				}
				?>
			</select>
			<img name="img_frm_UO<? echo  $idUO .'_' . $row_fields['id_etiqueta'];?>" src="../../imgs/mais.gif" onClick="montaComboMulti('frm_UO<? echo  $idUO .'_' . $row_fields['id_etiqueta']; ?>',true)" id="img_frm_UO<? echo  $idUO .'_' . $row_fields['id_etiqueta'];?>" title="Clique para selecionar m�ltiplos valores">						
			<input name="IDS_frm_UO<? echo $idUO .'_' . $row_fields['id_etiqueta']; ?>" type="hidden" value="123456">			
			</td>			
			<?			
/* //mudaCombo('<? echo 'frm_UO_'. $row_fields['id_etiqueta']; ?>');			*/
			$cor=!$cor;
			}

		// Agora listo o restante dos campos de patrim�nio.
		$query = "SELECT 
				  DISTINCT	nm_campo_tab_patrimonio,
							te_etiqueta 
				  FROM 		patrimonio_config_interface pat,
					 		redes 
				  WHERE 	id_etiqueta not in ('etiqueta1','etiqueta1a','etiqueta2') AND
							redes.id_local = pat.id_local ".
							$where . "   
				  ORDER BY te_etiqueta";	
		$res_fields = mysql_query($query);

		while ($row_fields = mysql_fetch_array($res_fields)) 
			{
			?>
			<tr <? if ($cor) echo 'bgcolor="#E1E1E1"';?>> 
			<td nowrap><? echo $row_fields['te_etiqueta'];?></td>
			<td><select name="frm_condicao2_<? echo $row_fields['nm_campo_tab_patrimonio']; ?>" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >
			<option value=""></option>
			<option value="<? echo ' AND patrimonio.'     .$row_fields['nm_campo_tab_patrimonio']." =       'frm_te_valor_condicao2'"  ;?>" onClick="Verifica_Condicoes_Seta_Campo('<? echo "frm_te_valor_condicao2_". $row_fields['nm_campo_tab_patrimonio']; ?>');">IGUAL A</option>		
			<option value="<? echo ' AND patrimonio.'     .$row_fields['nm_campo_tab_patrimonio']." <>      'frm_te_valor_condicao2'"  ;?>" onClick="Verifica_Condicoes_Seta_Campo('<? echo "frm_te_valor_condicao2_". $row_fields['nm_campo_tab_patrimonio']; ?>');">DIFERENTE DE</option>			
			<option value="<? echo ' AND patrimonio.'     .$row_fields['nm_campo_tab_patrimonio']." like    '%frm_te_valor_condicao2%'";?>" onClick="Verifica_Condicoes_Seta_Campo('<? echo "frm_te_valor_condicao2_". $row_fields['nm_campo_tab_patrimonio']; ?>');">CONTENHA</option>
			<option value="<? echo ' AND patrimonio.'     .$row_fields['nm_campo_tab_patrimonio']." like    'frm_te_valor_condicao2%'" ;?>" onClick="Verifica_Condicoes_Seta_Campo('<? echo "frm_te_valor_condicao2_". $row_fields['nm_campo_tab_patrimonio']; ?>');">INICIE COM</option>
			<option value="<? echo ' AND patrimonio.'     .$row_fields['nm_campo_tab_patrimonio']." like    '%frm_te_valor_condicao2'" ;?>" onClick="Verifica_Condicoes_Seta_Campo('<? echo "frm_te_valor_condicao2_". $row_fields['nm_campo_tab_patrimonio']; ?>');">TERMINE COM</option>				
			<option value="<? echo ' AND TRIM(patrimonio.'.$row_fields['nm_campo_tab_patrimonio'].") = '' and " 					     ;?>" onClick="Preenche_Condicao_VAZIO('      <? echo "frm_te_valor_condicao2_". $row_fields['nm_campo_tab_patrimonio']; ?>');">VAZIO</option>		
			</select> </td>
			<td><input name="frm_te_valor_condicao2_<? echo $row_fields['nm_campo_tab_patrimonio']; ?>" type="text" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);Verifica_Selecao(this,'<? echo "frm_condicao2_". $row_fields['nm_campo_tab_patrimonio']; ?>');" size="50" maxlength="100"></td>
			</tr>
			<?			
			$cor=!$cor;
			}
			?>
	</table>
	
	</td></tr>
    <tr> 
      <td valign="top">&nbsp;</td>
    </tr>



    <tr> 
      <td valign="top"> 
        <?  $v_require = '../../include/' .($_SESSION['cs_nivel_administracao']<>1 && $_SESSION['cs_nivel_administracao']<>2?'selecao_redes_inc.php':'selecao_locais_inc.php');
		require_once($v_require);		
		?>

      </td>
    </tr>
    <tr> 
      <td valign="top">&nbsp;</td>
    </tr>
    <tr> 
      <td valign="top"> 
        <?  require_once('../../include/selecao_so_inc.php');		?>
      </td>
    </tr>
    <tr> 
      <td valign="top">&nbsp;</td>
    </tr>
    <tr> 
      <td valign="top"><br> <table width="100%" border="0" cellpadding="0" cellspacing="1">
          <tr> 
            <td height="1" bgcolor="#333333"></td>
          </tr>
          <tr> 
            <td>&nbsp;</td>
          </tr>
          <tr> 
            <td> <div align="center"> 
                <input name="submit" type="submit" value="        Gerar Relat&oacute;rio      " onClick="ChecaTodasAsRedes(),<? echo ($_SESSION['cs_nivel_administracao']<>1 && 
																				 						 $_SESSION['cs_nivel_administracao']<>2?"SelectAll(this.form.elements['list2[]'])":"SelectAll(this.form.elements['list12[]'])")?>, 
																										 SelectAll(this.form.elements['list4[]']), 
																										 SelectAll(this.form.elements['list6[]'])">								
              Formato:<select name="formato" size="1">

        <option value="html">HTML</option>
        <option value="ods">Planilha (.ods)</option>
        <option value="pdf">PDF</option>
        <option value="csv">CSV</option>

    </select></div></td>
          </tr>
          <tr> 
            <td>&nbsp;</td>
          </tr>
        </table></td>
    </tr>
  </table>
</form>

</body>
</html>
