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

require_once('../../include/library.php'); 
// Comentado temporariamente - AntiSpy();
if ($_POST['submit_cond'])
	{
	$query_sele_cria = '';
	$value_anterior = '';
	while(list($key, $value) = each($HTTP_POST_VARS))
		{
		if (trim($value)<>'' && trim(strpos($key,'frm_'))<>'') 
			{
			if (trim(strpos($key,'frm_condicao_'))<>'')
				{
				$query_sele_cria .= str_replace('frm_condicao_','',$value);
				}
			else
				{
				if ($value_anterior) $query_sele_cria .= ' and ';
				$query_sele_cria = str_replace('frm_te_valor_condicao',$value,$query_sele_cria);
				}
			
			$value_anterior = $value;
			}
		} 


	$query_sele_cria = (substr($query_sele_cria,-5)==' and '?substr($query_sele_cria,0,strlen($query_sele_cria)-5):$query_sele_cria);
	$query_sele_cria = str_replace('-MENOR-',' < ',$query_sele_cria);
	$query_sele_cria = str_replace('-MAIOR-',' > ',$query_sele_cria);	

    $from 	= ($_SESSION['cs_nivel_administracao']<>1&&$_SESSION['cs_nivel_administracao']<>2?' ,redes c':'');	
    $where 	= ($_SESSION['cs_nivel_administracao']<>1&&$_SESSION['cs_nivel_administracao']<>2?' AND a.id_ip_rede = c.id_ip_rede AND c.id_local='.$_SESSION['id_local']:'');		
	$Query_Pesquisa = 'SELECT 	a.id_so,
								a.te_node_address,
								a.te_nome_computador, 
								a.te_ip, 
								a.te_versao_cacic, 
								a.te_versao_gercols, 
								a.dt_hr_ult_acesso,
								a.dt_hr_inclusao,								
								b.te_desc_so						 
						FROM	computadores a,
								so b '.
								$from . ' 
						WHERE   '.stripslashes($query_sele_cria).' and a.id_so = b.id_so '.
								$where . ' 
						ORDER 	by a.te_nome_computador';

	conecta_bd_cacic();
	$result = mysql_query($Query_Pesquisa) or die('Erro no select');
	
	
	?>
	<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
	<html>
	<head>
	<title>Consulta Computadores</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">	
	<link rel="stylesheet" type="text/css" href="../../include/cacic.css">
	<SCRIPT>
	function Verifica_Check_Cria()
		{
		var v_total_check;
		v_total_check = 0;
		for (i=0;i<window.document.forms.length;i++)
			{
			for (j=0;j<window.document.forms[i].elements.length;j++)
				{
				if (window.document.forms[i].elements[j].type == 'checkbox' && window.document.forms[i].elements[j].checked == true)
					{
					v_total_check ++;
					}
				}
			}		

		for (i=0;i<window.document.forms.length;i++)
			{
			for (j=0;j<window.document.forms[i].elements.length;j++)
				{
				if (window.document.forms[i].elements[j].type == 'submit' && window.document.forms[i].elements[j].name == 'submit_expo')
					{
					if (v_total_check==0) window.document.forms[i].elements[j].disabled = true
					else window.document.forms[i].elements[j].disabled = false;
					}
				}
			}					
		}
	</script>	
	</head>

	<body background="../../imgs/linha_v.gif">
	<script language="JavaScript" type="text/javascript" src="../../include/cacic.js"></script>
	<form name="form1" method="post">
	<table width="95%" border="0" align="center">
	<tr> 
	
	  <td class="cabecalho">Computadores Selecionados</td>
	</tr>
	<tr> 
	
	  <td class="descricao">Rela&ccedil;&atilde;o de computadores selecionados na consulta parametrizada, para cria&ccedil;&atilde;o de relat&oacute;rio de hardware.</td>
	</tr>
	</table>
	<br><br>
  	<table width="90%" align="center"><tr>
    <td><div align="center"> 
	<?          $query_sele_cria = '';
		   $value_anterior = '';


		while(list($key, $value) = each($HTTP_POST_VARS))
                {
                if (trim($value)<>'' && trim(strpos($key,'frm_'))<>'')
                        {
                        if (trim(strpos($key,'frm_condicao_'))<>'')
                                {
                                $query_sele_cria .= str_replace('frm_condicao_','',$value);
                                }
                        else
                                {
                                if ($value_anterior) $query_sele_cria .= ' and ';
                                $query_sele_cria = str_replace('frm_te_valor_condicao',$value,$query_sele_cria);
                                }

                        $value_anterior = $value;
                        }
		 printf("%s = %s\n", $key , $value);
                }
	?>


   	<input name="submit_expo" type="submit" value="  Exportar Selecao" onClick="return Confirma('Confirma EXPORTACAO?');" <? echo ($_SESSION['cs_nivel_administracao']<>1&&$_SESSION['cs_nivel_administracao']<>3?'disabled':'')?>>
   	&nbsp;&nbsp;
   	<input name="submit_nova" type="submit" value="  Nova Selecao  ">	
	</div></td>		
   	</tr></table>
	<br><br>
	<table border="0" align="center" cellpadding="0" cellspacing="0">
  	<tr> 
    <td> <table border="0" cellpadding="0" cellspacing="0" align="center">
          <tr bgcolor="#E1E1E1"> 
            <td align="center"  nowrap>&nbsp;</td>
            <td align="center"  nowrap>&nbsp;</td>
            <td align="center"  nowrap>&nbsp;</td>
	    <td align="center"  nowrap><img src="../../imgs/checked.png" width="22" height="22"></td>
            <td align="center"  nowrap><img src="../../imgs/tree_vertline.gif" width="10" height="18"></td>
            <td align="center"  nowrap class="cabecalho_tabela"><div align="left">Nome 
                da M&aacute;quina</div></td>
            <td nowrap ><img src="../../imgs/tree_vertline.gif" width="10" height="18"></td>
            <td nowrap class="cabecalho_tabela"><div align="center">IP</div></td>
            <td nowrap ><img src="../../imgs/tree_vertline.gif" width="10" height="18"></td>
            <td nowrap class="cabecalho_tabela"><div align="center">Endereço MAC</div></td>
            <td nowrap ><img src="../../imgs/tree_vertline.gif" width="10" height="18"></td>
            <td nowrap class="cabecalho_tabela"><div align="center">S.O.</div></td>
            <td nowrap ><img src="../../imgs/tree_vertline.gif" width="10" height="18"></td>
            <td nowrap class="cabecalho_tabela"><div align="center"> Cacic2</div></td>
            <td nowrap ><img src="../../imgs/tree_vertline.gif" width="10" height="18"></td>
            <td nowrap class="cabecalho_tabela"> GerCols</td>
            <td nowrap class="cabecalho_tabela"><img src="../../imgs/tree_vertline.gif" width="10" height="18"></td>
            <td nowrap class="cabecalho_tabela"><div align="center">&Uacute;lt. 
                Acesso</div></td>
            <td nowrap class="cabecalho_tabela"><img src="../../imgs/tree_vertline.gif" width="10" height="18"></td>
            <td nowrap class="cabecalho_tabela"><div align="center">Inclusão</div></td>
            <td nowrap >&nbsp;</td>
          </tr>
          <tr> 
            <td height="1" bgcolor="#333333" colspan="20"></td>
          </tr>
          <?  
	$Cor = 0;
	$NumRegistro = 1;
	
	while($row = mysql_fetch_array($result)) 
		{		  
	 	?>
          <tr <? if ($Cor) echo 'bgcolor="#E1E1E1"'; ?>> 
            <td nowrap>&nbsp;</td>
            <td nowrap><div align="left"><? echo $NumRegistro; ?></div></td>
            <td nowrap>&nbsp;</td>
	    <td nowrap><input type="checkbox" name="chk_<? echo $row['te_node_address'].'#'. $row['id_so']; ?>" value="1" checked onClick="Verifica_Check_Cria();"></td>
            <td nowrap>&nbsp;</td>
            <td nowrap><div align="left"><a href="computador.php?te_node_address=<? echo $row['te_node_address'];?>&id_so=<? echo $row['id_so'];?>" target="_blank"><? echo $row['te_nome_computador']; ?></a></div></td>
            <td nowrap>&nbsp;</td>
            <td nowrap><div align="left"><a href="computador.php?te_node_address=<? echo $row['te_node_address'];?>&id_so=<? echo $row['id_so'];?>" target="_blank"><? echo $row['te_ip']; ?></a></div></td>
            <td nowrap>&nbsp;</td>
            <td nowrap><div align="left"><a href="computador.php?te_node_address=<? echo $row['te_node_address'];?>&id_so=<? echo $row['id_so'];?>" target="_blank"><? echo $row['te_node_address'];?></a></div></td>
            <td nowrap>&nbsp;</td>
            <td nowrap><div align="center"><a href="computador.php?te_node_address=<? echo $row['te_node_address'];?>&id_so=<? echo $row['id_so'];?>" target="_blank"><? echo $row['te_desc_so']; ?></a></div></td>
            <td nowrap>&nbsp;</td>
            <td nowrap><div align="left"><a href="computador.php?te_node_address=<? echo $row['te_node_address'];?>&id_so=<? echo $row['id_so'];?>" target="_blank"><? echo $row['te_versao_cacic']; ?></a></div></td>
            <td nowrap>&nbsp;</td>
            <td nowrap><div align="left"><a href="computador.php?te_node_address=<? echo $row['te_node_address'];?>&id_so=<? echo $row['id_so'];?>" target="_blank"><? echo $row['te_versao_gercols']; ?></a></div></td>
            <td nowrap>&nbsp;</td>
            <td nowrap><div align="right"><a href="computador.php?te_node_address=<? echo $row['te_node_address'];?>&id_so=<? echo $row['id_so'];?>" target="_blank"><? echo date("d/m/y H:i", strtotime( $row['dt_hr_ult_acesso'] )); ?></a></div></td>
            <td nowrap>&nbsp;</td>
            <td nowrap><div align="right"><a href="computador.php?te_node_address=<? echo $row['te_node_address'];?>&id_so=<? echo $row['id_so'];?>" target="_blank"><? echo date("d/m/y H:i", strtotime( $row['dt_hr_inclusao'] ));   ?></a></div></td>
            <td nowrap>&nbsp;</td>
          </tr>
          <? 
		$Cor=!$Cor;
		$NumRegistro++;
		}		
	if ($NumRegistro == 1)
		{
		?>
          <td colspan="20" align="center" class="label_vermelho">NÃO FORAM ENCONTRADOS 
            REGISTROS!</TD>
          <script language="JavaScript">
		for (i=0;i<window.document.forms.length;i++)
			{
			for (j=0;j<window.document.forms[i].elements.length;j++)
				{
				if (window.document.forms[i].elements[j].name == 'submit_expo')
					{
					window.document.forms[i].elements[j].disabled = true;					
					}
				}
			}		
		</script>
          <?
		}
		?>
        </table></td>
  	</tr>
  	<tr> 
    <td height="1" bgcolor="#333333"></td>
  	</tr>
	</table>

  	<br><br>
  	<table width="90%" align="center"><tr>
    <td><div align="center"> 
   	<input name="submit_expo" type="submit" value="  Exportar Selecao" <? if ($NumRegistro == 1) echo 'disabled'; ?> onClick="return Confirma('Confirma EXPORTACAO?');" <? echo ($_SESSION['cs_nivel_administracao']<>1&&$_SESSION['cs_nivel_administracao']<>3?'disabled':'')?>>		  
   	&nbsp;&nbsp;
   	<input name="submit_nova" type="submit" value="  Nova Selecao  ">	
	</div></td>		
   	</tr></table>
	<p><p><p>  
	</form>
	</html>	
	<?				
	}
else	
	{
	if ($_POST['submit_expo'])
		{
		$query_sele_cria = '';
                $value_anterior = '';


//                while(list($key, $value) = each($HTTP_POST_VARS))
//                {
//                if (trim($value)<>'' && trim(strpos($key,'frm_'))<>'')
//                        {
//                        if (trim(strpos($key,'frm_condicao_'))<>'')
//                                {
//                                $query_sele_cria .= str_replace('frm_condicao_','',$value);
//                                }
//                        else
//                                {
//                                if ($value_anterior) $query_sele_cria .= ' and ';
//                                $query_sele_cria = str_replace('frm_te_valor_condicao',$value,$query_sele_cria);
//                                }
//
//                        $value_anterior = $value;
 //                       }
 //                printf("%s = %s\n", $key , $value);
  //              }
        	
	$v_cs_cria = '';
		conecta_bd_cacic();			
		$where='';
		$num_elementos = count($HTTP_POST_VARS);
		$passo = $num_elementos;
		while(list($key, $value) = each($HTTP_POST_VARS))
			{
			if (strpos($key,'chk_')>-1)
				{
//				if (!$result_tables) $result_tables	= mysql_list_tables($nome_bd); //Retorna a lista de tabelas do CACIC
				
//				mysql_data_seek($result_tables,0);
				$v_arr_cria = explode('#',$key);

//$from       = ($_SESSION['cs_nivel_administracao']<>1&&$_SESSION['cs_nivel_administracao']<>2?' ,redes c':'');
//$where      = ($_SESSION['cs_nivel_administracao']<>1&&$_SESSION['cs_nivel_administracao']<>2?' AND a.id_ip_rede = c.id_ip_rede AND c.id_local='.$_SESSION['id_local']:'');

//				while ($row_cria = mysql_fetch_row($result_tables)) //Percorre as tabelas comandando a exclusão, conforme TE_NODE_ADDRESS e ID_SO				
//					{
				$where='(a.te_node_address = "'. str_replace('chk_','',$v_arr_cria[0]) . '" and b.id_so="'.str_replace('chk_','',$v_arr_cria[1]).'")' . $where;
                                if ( $passo > 2 ) //botao e o ultimo
                                        $where =  ' or ' . $where ;
                                $passo--;


				}
			}
			$file_dir = '/tmp/';
			$nome_arquivo='relatorio_' .$_SESSION['id_usuario']. '_' . time(). '.csv';
            $v_query_cria = 'SELECT b.te_desc_so,
                                    a.te_node_address,
                                    a.te_nome_computador,
                                    a.te_ip,
                                    a.te_versao_cacic,
                                    a.te_versao_gercols,
                                    a.dt_hr_ult_acesso,
                                    a.dt_hr_inclusao
                        INTO OUTFILE \''. $file_dir . $nome_arquivo .
                                    '\' fields terminated by \',\' lines terminated by \'\n\' 
                        FROM computadores a, so b 
                        WHERE '.$where;

	        $cria = @mysql_query($v_query_cria) or die( @mysql_error() . "<br>Erro no SQL: $v_query_cria" );
	        header("Content-type: application/vnd.ms-excel");
            header("Content-disposition: csv" . date("Y-m-d") . ".xls");
            header( "Content-disposition: filename=$nome_arquivo");
            // Cabeçalho do arquivo
            echo 'S.O.,Endereco MAC,Nome da maquina,IP,Cacic2,GerCols,Ultimo acesso,Inclusao'."\n";
	        readfile($file_dir.$nome_arquivo);
	        flush();
			GravaLog('CRIA',$_SERVER['SCRIPT_NAME'],'computadores');
			exit();
		}
	?>	
	<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
	<html>
	<head>
	<title>Cria Computadores</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	
	<link href="../../include/cacic.css" rel="stylesheet" type="text/css">
	<SCRIPT>
	<?isset($nome_arquivo) and printf("window.open('%s');", $nome_arquivo); ?>
	function Preenche_Condicao_VAZIO(p_campo)
		{
		for (i=0;i<window.document.forms.length;i++)
			{
			for (j=0;j<window.document.forms[i].elements.length;j++)
				{
				if (window.document.forms[i].elements[j].name == p_campo)
					{
					window.document.forms[i].elements[j].value = '<VAZIO>';
					window.document.forms[i].elements[j].disabled = true;					
					}
				}
			}		
		}

	function Verifica_Condicoes_Seta_Campo(p_campo)
		{
		for (i=0;i<window.document.forms.length;i++)
			{
			for (j=0;j<window.document.forms[i].elements.length;j++)
				{
				if (window.document.forms[i].elements[j].name == p_campo && window.document.forms[i].elements[j].value == '<VAZIO>')
					{
					window.document.forms[i].elements[j].value = '';
					window.document.forms[i].elements[j].disabled = false;										
					}
				}
			}		
		SetaCampo(p_campo);								
		}

	function Verifica_Selecao(p_campo,p_campo_selecao)
		{
		if (p_campo.value == '')
			{
			for (i=0;i<window.document.forms.length;i++)
				{
				for (j=0;j<window.document.forms[i].elements.length;j++)
					{
					if (window.document.forms[i].elements[j].name == p_campo_selecao)
						{
						window.document.forms[i].elements[j].value = '';
						}
					}
				}		
			}
		}

	
	function Valida_Form_Pesquisa(p_argumento)
		{
		var v_conteudo = '';
		var v_tamanho = 0;
		v_tamanho = p_argumento.length;
		for (i=0;i<window.document.forms.length;i++)
			{
			for (j=0;j<window.document.forms[i].elements.length;j++)
				{
				if (window.document.forms[i].elements[j].name.substring(0,v_tamanho) == p_argumento && 
				    window.document.forms[i].elements[j].value != '')
					{
					v_conteudo = v_conteudo + window.document.forms[i].elements[j].value;
					}
				}
			}

		if (v_conteudo == '')
			{
			alert('E necessao informar ao menos uma condicao para pesquisa!');
			return false;
			}

		return true;
		}			
	</SCRIPT>
	</head>

	<body background="../../imgs/linha_v.gif">
	<script language="JavaScript" type="text/javascript" src="../../include/cacic.js"></script>
	<form name="form1" method="post">
	<table width="90%" align="center" border="0" cellpadding="0" cellspacing="0">
	<tr> 
	
  	<td class="cabecalho">Criar Relatório</td>
	</tr>
	<tr> 
	
  	<td class="descricao">Esta op&ccedil;&atilde;o permite fazer uma pequisa 
	parametrizada das informa&ccedil;&otilde;es armazenadas na base sobre 
	as esta&ccedil;&otilde;es monitoradas. Deve-se tomar muito cuidado com 
	a abrang&ecirc;ncia da condi&ccedil;&atilde;o a ser formulada, devido a demora na consulta.</td>
	</tr>
	</table>
	<br><br>
	<table width="90%" align="center" border="0" cellpadding="0" cellspacing="0"><tr>
    <td colspan="3"><div align="center"> 
   	<input name="submit_cond" type="submit" value="  Selecionar Computadores  " onClick="return Valida_Form_Pesquisa('frm_te_valor_condicao_');">
   	</div></td>
   	</tr></table>

	<br><br>	
	<table width="90%" align="center" border="0" cellpadding="0" cellspacing="0">
	<tr bgcolor="#CCCCCC"> 
  	<td class="destaque">Campo</font></strong></td>
  	<td class="destaque">Condi&ccedil;&atilde;o</font></strong></td>
  	<td class="destaque">Valor para Pesquisa</font></strong></td>
	</tr>
  	<tr> 
    <td colspan="3" height="1" bgcolor="#333333"></td>
  	</tr>
	
	<?
	$cor = 0;
	require_once('../../include/library.php');
	conecta_bd_cacic();	
	$res_fields = mysql_query("SHOW COLUMNS FROM computadores");
	$v_arr_nomes_campos = array();
	while ($row_fields = mysql_fetch_array($res_fields)) 
		{
		$query_desc = 'SELECT 	* 
					   FROM 	descricoes_colunas_computadores 
					   WHERE 	TRIM(nm_campo) = "'. $row_fields[0].'"';
		$res_desc 	= @mysql_query($query_desc);	 //Neste caso, o "@" inibe qualquer mensagem de erro retornada pela função MYSQL_QUERY()
		if (!@$row_desc = mysql_fetch_array($res_desc))
			{
			$query_ins_desc = 'INSERT 
							   INTO 	descricoes_colunas_computadores 
							   SET 		nm_campo = "'. $row_fields[0].'", 
							   			te_descricao_campo="'.$row_fields[0].'", 
										nm_tipo_campo = "datetime"';
			$res_ins_desc 	= @mysql_query($query_ins_desc);
			//GravaLog('INS',$_SERVER['SCRIPT_NAME'],'descricoes_colunas_computadores');
			}
			
		if ($row_desc['cs_condicao_pesquisa']=='S')
			{
			array_push($v_arr_nomes_campos,$row_desc['te_descricao_campo'].'#'.$row_fields[0].'#'.$row_fields[1]);
			}
		}

	sort($v_arr_nomes_campos);
	for ($i=0;$i<count($v_arr_nomes_campos);$i++)
		{
		$v_arr_campo = explode('#',$v_arr_nomes_campos[$i]);
		?>
		<tr <? if ($cor) echo 'bgcolor="#E1E1E1"';?>> 
		<td nowrap><? echo $v_arr_campo[0];?></td>
		<td><select name="frm_condicao_<? echo $v_arr_campo[1]; ?>" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >
		<option value=""></option>
		<?
		if ($v_arr_campo[2] == 'datetime') 
			{
			$v_operacao = "(TO_DAYS(NOW())-TO_DAYS(a.".$v_arr_campo[1].")";
			?>
			<option value="<? echo $v_operacao . ' =       frm_te_valor_condicao)'; ?>" onClick="Verifica_Condicoes_Seta_Campo('<? echo "frm_te_valor_condicao_". $v_arr_campo[1]; ?>');">IGUAL A</option>					
			<option value="<? echo $v_operacao . ' -MAIOR- frm_te_valor_condicao)'; ?>" onClick="Verifica_Condicoes_Seta_Campo('<? echo "frm_te_valor_condicao_". $v_arr_campo[1]; ?>');">MAIOR QUE&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option>					
			<option value="<? echo $v_operacao . ' -MENOR- frm_te_valor_condicao)'; ?>" onClick="Verifica_Condicoes_Seta_Campo('<? echo "frm_te_valor_condicao_". $v_arr_campo[1]; ?>');">MENOR QUE</option>											
			<?
			}
		else
			{
			?>
			<option value="<? echo 'a.'      .$v_arr_campo[1]." =       'frm_te_valor_condicao'"  ;?>" onClick="Verifica_Condicoes_Seta_Campo('<? echo "frm_te_valor_condicao_". $v_arr_campo[1]; ?>');">IGUAL A</option>		
			<option value="<? echo 'a.'      .$v_arr_campo[1]." <>      'frm_te_valor_condicao'"  ;?>" onClick="Verifica_Condicoes_Seta_Campo('<? echo "frm_te_valor_condicao_". $v_arr_campo[1]; ?>');">DIFERENTE DE</option>			
			<option value="<? echo 'a.'      .$v_arr_campo[1]." -MAIOR- 'frm_te_valor_condicao'"  ;?>" onClick="Verifica_Condicoes_Seta_Campo('<? echo "frm_te_valor_condicao_". $v_arr_campo[1]; ?>');">MAIOR QUE</option>
			<option value="<? echo 'a.'      .$v_arr_campo[1]." -MENOR- 'frm_te_valor_condicao'"  ;?>" onClick="Verifica_Condicoes_Seta_Campo('<? echo "frm_te_valor_condicao_". $v_arr_campo[1]; ?>');">MENOR QUE</option>						
			<option value="<? echo 'a.'      .$v_arr_campo[1]." like    '%frm_te_valor_condicao%'";?>" onClick="Verifica_Condicoes_Seta_Campo('<? echo "frm_te_valor_condicao_". $v_arr_campo[1]; ?>');">CONTENHA</option>
			<option value="<? echo 'a.'      .$v_arr_campo[1]." like    'frm_te_valor_condicao%'" ;?>" onClick="Verifica_Condicoes_Seta_Campo('<? echo "frm_te_valor_condicao_". $v_arr_campo[1]; ?>');">INICIE COM</option>
			<option value="<? echo 'a.'      .$v_arr_campo[1]." like    '%frm_te_valor_condicao'" ;?>" onClick="Verifica_Condicoes_Seta_Campo('<? echo "frm_te_valor_condicao_". $v_arr_campo[1]; ?>');">TERMINE COM</option>				
			<option value="<? echo 'TRIM(a.'.$v_arr_campo[1].") = '' and " 					   ;?>" onClick="Preenche_Condicao_VAZIO('<? echo "frm_te_valor_condicao_". $v_arr_campo[1]; ?>');"		 >VAZIO</option>		
			<?
			}
			?>
		</select> </td>
		<td><input name="frm_te_valor_condicao_<? echo $v_arr_campo[1]; ?>" type="text" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);Verifica_Selecao(this,'<? echo "frm_condicao_". $v_arr_campo[1]; ?>');" size="70" maxlength="100"></td>
		</tr>
		<?			
		$cor=!$cor;
		}

	?>
  	<tr> 
    <td colspan="3" height="1" bgcolor="#333333"></td>
  	</tr>
	
	</table>
	<br><br>
	<table width="90%" align="center" border="0" cellpadding="0" cellspacing="0"><tr>
	<td colspan="3"><div align="center"> 
   	<input name="submit_cond" type="submit" value="  Selecionar Computadores  " onClick="return Valida_Form_Pesquisa('frm_te_valor_condicao_');">	
	</div></td>
	</tr></table>

	<p><p><p>  
	</form>
	</html>	
	<?			
	}
	?>
