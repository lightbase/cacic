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
 * verifica se houve login e também regras para outras verificações (ex: permissões do usuário)!
 */
if(!isset($_SESSION['id_usuario'])) 
  die('Acesso negado (Access denied)!');
else { // Inserir regras para outras verificações (ex: permissões do usuário)!
}

require_once('../include/library.php');
//AntiSpy();
$_SESSION['tipo_consulta1']=$_REQUEST['tipo_consulta'];
$_SESSION['string_consulta1']=$_REQUEST['string_consulta'];
$_SESSION['consultar1']=$_REQUEST['consultar'];
if ($_GET['p']=='' && $_POST['consultar'] == '')
	{
	if ($_SESSION['tipo_consulta1']=='')
		{
		$_SESSION['consultar1']='Consultar Primeira Vez...';	
		$_SESSION['tipo_consulta1']='';
		$_SESSION['string_consulta1']='';	
		}
	}	
	
?>
	<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
	<html>
	<head>
<link rel="stylesheet"   type="text/css" href="../include/cacic.css">
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	</head>
	
	<body bgcolor="#FFFFFF" background="../imgs/linha_v.gif">
	
<script language="JavaScript" type="text/javascript" src="../include/cacic.js"></script>
<form action="<? echo $PHP_SELF; ?>" method="post" name="form1">
<table width="90%" border="0" align="center">
  <tr nowrap> 
    <td nowrap class="cabecalho">Navega&ccedil;&atilde;o pelos Computadores das 
      Subredes</td>
  </tr>
  <tr nowrap> 
    <td nowrap>
        <select name="tipo_consulta" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >
          <option value="menulist">Selecione o Filtro desejado</option>
          <option value="nome">Nome do Computador</option>
          <option value="ip">IP do Computador</option>
        </select>
        &nbsp;&nbsp; 
        <input name="string_consulta" type="text"  class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" value="<? if ($_SESSION['string_consulta1']<>'') echo $_SESSION['string_consulta1'];?>">
        &nbsp;&nbsp; </font> 
        <input name="consultar" type="submit" value="   Filtrar   ">
      </td>
  </tr>
  <tr nowrap>
    <td nowrap> 
      <?
	  
	if ($_SESSION['consultar1'] || $_POST['consultar'])
		{
		if($_SESSION['tipo_consulta1'] == 'nome') 
			{
			$where = "computadores.te_nome_computador like '%". $_SESSION['string_consulta1']."%'";
			}
		elseif($_SESSION['tipo_consulta1'] == 'ip') 
			{
			$where = "computadores.te_ip like '%". $_SESSION['string_consulta1']."%'";
			}
		else
			{
			$where = '1';
			}

	$where1 = 	($_SESSION['cs_nivel_administracao']<>1 && $_SESSION['cs_nivel_administracao']<>2?" AND computadores.id_ip_rede = redes.id_ip_rede AND redes.id_local=".$_SESSION['id_local']:'');		


	if ($_SESSION['te_locais_secundarios']<>'' && $where1 <> '')
		{
		// Faço uma inserção de "(" para ajuste da lógica para consulta		
		$where1 = str_replace('redes.id_local=','(redes.id_local=',$where1);
		$where1 .= ' OR redes.id_local in ('.$_SESSION['te_locais_secundarios'].')) ';
		}
		
		
		$query_sel = "SELECT 	IF(TRIM(redes.nm_rede)=''                   OR redes.nm_rede is null,'Rede Desconhecida', redes.nm_rede) as nm_rede,
								IF(TRIM(computadores.te_nome_computador)='' OR computadores.te_nome_computador is null,'Computador Desconhecido',computadores.te_nome_computador) as te_nome_computador,
								IF(TRIM(SUBSTRING_INDEX(computadores.te_workgroup, '@', -1))='' OR computadores.te_workgroup is null,'Grupo de Trabalho Desconhecido',LOWER(SUBSTRING_INDEX(computadores.te_workgroup, '@', -1))) as te_workgroup,
								computadores.id_ip_rede,
								computadores.te_ip,							
								computadores.dt_hr_ult_acesso,
								computadores.te_node_address,
								computadores.id_so,
								compartilhamentos.te_comentario,
								compartilhamentos.nm_compartilhamento,
								redes.id_local,
								sg_local,
								nm_local,
								servidores_autenticacao.nm_servidor_autenticacao
					 FROM 		computadores 
					 			LEFT JOIN redes             ON (computadores.id_ip_rede = redes.id_ip_rede)
					 			LEFT JOIN compartilhamentos ON (computadores.id_so=compartilhamentos.id_so and computadores.te_node_address=compartilhamentos.te_node_address and compartilhamentos.cs_tipo_compart='I')
					 			LEFT JOIN locais            ON (redes.id_local = locais.id_local)								
								LEFT JOIN servidores_autenticacao ON (redes.id_servidor_autenticacao = servidores_autenticacao.id_servidor_autenticacao)
					 WHERE		".$where.$where1."
				  	 GROUP BY 	sg_local,
					 			computadores.id_ip_rede,
					 			te_workgroup,
								computadores.te_ip,
								computadores.te_node_address,
								computadores.id_so
					 ORDER BY   sg_local,
								computadores.id_ip_rede,					 
					 			te_workgroup,
								computadores.te_ip,
								computadores.te_node_address,
								computadores.id_so";					 
		conecta_bd_cacic();					 
		$result_sel = mysql_query($query_sel) or die ('Erro no acesso à tabela "computadores" ou sua sessão expirou!');
		$_SESSION['Tripa'] = '';

		if(mysql_num_rows($result_sel) > 0) 
			{	              			
				$RedeAnt 		= 'zzz';				
				$RedeAntAux 	= 'zzz'; //To care ambiguous names of WorkGroup in two nets ...
				$WorkGroupAnt 	= 'zzz';			
				$LocalAnt = 'zzz';												
				while($row = mysql_fetch_array($result_sel)) 
					{
					if ($LocalAnt <> $row["sg_local"])
						{
						$nm_local = $row["nm_local"];
						if (!$nm_local)
							{
							$nm_local = 'SubRedes Não Cadastradas';
							}
							
						$_SESSION['Tripa'] 	.= '.<b>' . $row["sg_local"] . '</b> (' . $nm_local . ')|'.$row["id_local"].'#';						
						$LocalAnt = $row["sg_local"];
						}
					
					if ($RedeAnt <> $row["id_ip_rede"])
						{
						$nm_rede = $row["nm_rede"];
						if (!$nm_rede)
							{
							$nm_rede = 'SubRede Não Cadastrada';
							}
							
						$_SESSION['Tripa'] 	.= '..' . $row["id_ip_rede"] . ' (' . $nm_rede . ')|'.$row["sg_local"] . ' (' . $row['nm_local'] . ')|'.$row["nm_servidor_autenticacao"].'#';

						
						$RedeAnt = $row["id_ip_rede"];
						}

					$arrGrupo = explode('@',$row["te_workgroup"]);						
					if (count($arrGrupo) > 1)
						$strGrupo = $arrGrupo[1];
					else
						$strGrupo = $row["te_workgroup"];						

					if ($WorkGroupAnt <> $strGrupo || $RedeAntAux <> $RedeAnt)
						{		
						$_SESSION['Tripa'] 		   .= '...' . $strGrupo.'#';												
						$WorkGroupAnt 	= $strGrupo;
						$RedeAntAux 	= $RedeAnt;						
						}
						
						$_SESSION['Tripa'] .= 	'....' . $row["te_ip"] . ' (' . $row["te_nome_computador"] . ')';
				
						$today=date('m-d-Y');	
						$access_day = explode('-',$row["dt_hr_ult_acesso"]);	
						$diference = date_difference(trim(substr($access_day[1],0,2)).'-'.$access_day[2].'-'.$access_day[0],$today);

						// Mostrarei a quantidade de dias e a data de último acesso para a diferença maior que 4 dias
						if     ($diference > 4) 						
							{
							$_SESSION['Tripa'] .= 	'<font size=1><b> '.$diference.'</b> dias => '.substr($row["dt_hr_ult_acesso"],8,2).'/'.substr($row["dt_hr_ult_acesso"],5,2).'/'.substr($row["dt_hr_ult_acesso"],0,4).'</font>';
							$_SESSION['Tripa'] .= 	'|3';	//Red    Computer Icon
							}
						else if($diference > 0 ) 						
							{
							$_SESSION['Tripa'] .= 	'|2';	//Yellow Computer Icon
							}
						else
							{
							$_SESSION['Tripa'] .= 	'|1';	//Green  Computer Icon
							}
						
						$_SESSION['Tripa'] .= 	'|te_node_address='.$row["te_node_address"] . '&id_so=' . $row["id_so"].'|';

						if ($row["nm_compartilhamento"] || $row["te_comentario"]){
							if ($row["nm_compartilhamento"]) $_SESSION['Tripa'] .= 	'Nome: '.$row["nm_compartilhamento"].' ';
							if ($row["te_comentario"]) $_SESSION['Tripa'] .= 	'Comentário: '.$row["te_comentario"];						
						}

						$_SESSION['Tripa'] .= 	'#';	//Register delimiter
				}
			}
			else
			{
				echo mensagem("Não foram encontrados registros na consulta!");	
				//not data found on SELECT.
			}	
			
		}

			  /*********************************************/
			  /*  PHP TreeMenu 1.1                         */
			  /*  Author: Bjorge Dijkstra                  */
			  /*  email : bjorge@gmx.net                   */
			  /*  Placed in Public Domain                  */
			  /*********************************************/
				
			  if(isset($PATH_INFO)) 
				  {
				  $script       =  $PATH_INFO; 
				  } 
			  else 
				  {
				  $script	=  $SCRIPT_NAME;
				  }
			
			  $tree_imgs_path		= "../imgs/arvore/";
			  $img_expand   		= $tree_imgs_path . "tree_expand.gif";
			  $img_collapse 		= $tree_imgs_path . "tree_collapse.gif";

			  $img_line     		= $tree_imgs_path . "tree_vertline.gif";  
			  $img_split			= $tree_imgs_path . "tree_split.gif";
			  $img_end      		= $tree_imgs_path . "tree_end.gif";

			  $img_leaf     		= $tree_imgs_path . "tree_leaf.gif";
			  $img_spc      		= $tree_imgs_path . "tree_space.gif";
			  $img_workgroup		= $tree_imgs_path . "tree_workgroup.gif";  
			  $img_redes    		= $tree_imgs_path . "tree_redes.gif";  			  

			  $generic_imgs_path		= "../imgs/";
			  $img_totals		    = $generic_imgs_path . "totals.gif";  			  	
			  $img_details		    = $generic_imgs_path . "details.gif";  			  				  
			  $img_compart_print    = $generic_imgs_path . "compart_print.gif";  			  			
			  $img_authentication_server = $generic_imgs_path . "authentication_server.gif";  			  				  			  	  			  

			  $img_os_win95 		= "<B>95</B>";  			  			  
			  $img_os_win95_osr2	= "<B>95 OSR2</B>";  			  			  			  			  			  
			  $img_os_win98			= "<B>98</B>";  			  			  
			  $img_os_win98_se		= "<B>98 SE</B>";
			  $img_os_winme			= "<B>ME</B>";
			  $img_os_winnt			= "<B>NT</B>";  			  			  
			  $img_os_win2k			= "<B>2000</B>";
			  $img_os_winxp			= "<B>XP</B>";  			  			  
			  $img_os_linux			= "<B>LINUX</B>";  			  			  
			 
			  /*********************************************/
			  /* read file to $tree array                  
				 tree[x][0] 	-> Tree Level                  
				 tree[x][1] 	-> Name of SubNet/WorkGroup/WorkStation             
				 tree[x][2] 	-> 	1 = Green   OK!
							   		2 = Yellow  Até 5 dias
							   		3 = Red     Over 5 days
				 tree[x][3] 	-> te_node_address + id_so  				 
				 tree[x][4] 	-> Last Item In SubTree        
				 tree[x][5] 	-> Total of nodes        				 
				 tree[x][6] 	-> Net IP        				 				 
				 tree[x][7] 	-> Total win95        				 				 
				 tree[x][8] 	-> Total win95_osr2
				 tree[x][9] 	-> Total win98        				 				 
				 tree[x][10] 	-> Total win98_se        				 				 
				 tree[x][11] 	-> Total winme        				 				 
				 tree[x][12] 	-> Total winnt        				 				 
				 tree[x][13] 	-> Total win2k        				 				 
				 tree[x][14] 	-> Total winxp        				 				 
				 tree[x][15] 	-> Total linux        				 				 
				 tree[x][16] 	-> Shared printer comment
				 tree[x][17] 	-> Localization code
				 tree[x][18] 	-> Localization name to general use
				 tree[x][19] 	-> SubNet name to general use				 				 				 
				 tree[x][20] 	-> WorkGroup name to general use				 				 				 				 
			  /*********************************************/
			  $maxlevel				=	0;
			  $cnt					=	0;			  
			  $cnt_aux_local	=	0;			  
			  $cnt_aux_rede			=	0;			  			  
			  $cnt_aux_grupo		=	0;			  
			  $v_conta_micros   	= 	0;
			  $v_conta_os_win95		=	0;
			  $v_conta_os_win95_osr2=	0;
			  $v_conta_os_win98		=	0;
			  $v_conta_os_win98_se	=	0;
			  $v_conta_os_winme		=	0;
			  $v_conta_os_winnt		=	0;
			  $v_conta_os_winxp		=	0;
			  $v_conta_os_linux		=	0;
		  	  $monta 				= 	0;
			  $id_ip_rede_atual 	= 	'';			  

			  if (strpos($_SERVER['REQUEST_URI'],'relatorios/arvore/index.php?tipo_consulta='))
//			  if(substr($_SERVER['REQUEST_URI'],0,50) == '/cacic2/relatorios/arvore/index.php?tipo_consulta=')
			  	{
				$monta = 1;
				}
				
			  $Arvore = explode('#',$_SESSION['Tripa']);  	

			  $TamanhoArvore = count($Arvore)-2;

			  while ($cnt <= $TamanhoArvore)
			 	{								
				$tree[$cnt][0]	=	strspn($Arvore[$cnt],".");			  
				$tmp=rtrim(substr($Arvore[$cnt],$tree[$cnt][0]));
				$node=explode("|",$tmp); 

				$tree[$cnt][1]	= 	$node[0]	;	// Item identification
				$tree[$cnt][2]	= 	''			;	// Reserved for Work Station data
				$tree[$cnt][3]	= 	''			;	// Reserved for Work Station data								
				$tree[$cnt][4]	= 	1			;  	// 1=Last item in subtree	
				$tree[$cnt][5]	= 	0			;  	// Total of Nodes					
				$tree[$cnt][6]	= 	''			; 	// Net IP													
				$tree[$cnt][7]	= 	0			;	// Total Win95									
				$tree[$cnt][8]	= 	0			;  	// Total Win95_OSR2
				$tree[$cnt][9]	= 	0			;  	// Total Win98
				$tree[$cnt][10]	= 	0			; 	// Total Win98_SE									
				$tree[$cnt][11]	= 	0			; 	// Total WinME									
				$tree[$cnt][12]	= 	0			; 	// Total WinNT
				$tree[$cnt][13]	= 	0			; 	// Total Win2K
				$tree[$cnt][14]	= 	0			; 	// Total WinXP									
				$tree[$cnt][15]	=	0			; 	// Total Linux																	
				$tree[$cnt][16]	=	$node[3]	; 	// Shared printer comment
				$tree[$cnt][17]	=	''			; 	// Localization code
				$tree[$cnt][18]	=	''			; 	// Localization name to general use
				$tree[$cnt][19]	=	''			; 	// SubNet name to general use
				$tree[$cnt][20]	=	''			; 	// WorkGroup name to general use
				$tree[$cnt][21]	=	''			; 	// Authentication Server Name
				
								
				if ($tree[$cnt][0] > $maxlevel) $maxlevel=$tree[$cnt][0];    
				if ($monta && ($tree[$cnt][0]==2 || $tree[$cnt][0]==3)) // SubNet or Work Group
					{
					$_GET['p'] .= $cnt.'|';
					$ultimo = $cnt;
					}

				//--------------------
			  	//Nodes classification
				//--------------------
				
				// if Localization...
				if ($tree[$cnt][0]==1) 
					{
					$v_id_local 		= $node[1];
					$v_sg_local 		= substr($tree[$cnt][1],0,strpos($tree[$cnt][1], " ")); 
					$v_LocalizationName		= $tree[$cnt][1];
					$cnt_aux_local	= $cnt;
					}	

				$tree[$cnt][17] = $v_id_local;								
				$tree[$cnt][18] = $v_LocalizationName;												
					
				// if SubNet...								
				if ($tree[$cnt][0]==2) 
					{	
					$id_ip_rede_atual 	= substr($tree[$cnt][1],0,strpos($tree[$cnt][1], " ")); 
					$cnt_aux_rede 		= 	$cnt;
					$v_SubNetName		= 	$tree[$cnt][1];
					$tree[$cnt][19]		=	$v_SubNetName;	
					$tree[$cnt][21]		=	$node[2];								
					}				
					
				$tree[$cnt][6]=$id_ip_rede_atual;														
				
				// if Work Group...
				if ($tree[$cnt][0]==3) 
					{			
					$cnt_aux_grupo 	= 	$cnt;
					$tree[$cnt][19]	=	$v_SubNetName;					
					$tree[$cnt][20]	=	$tree[$cnt][1];					
					}				

				// if Work Station...
				if ($tree[$cnt][0]==4) 
					{
					$tree[$cnt][2]=$node[1];
					$tree[$cnt][3]=$node[2];					
								
					$tree[$cnt_aux_local][5] 	++;
					$tree[$cnt_aux_rede][5] 	++;					
					$tree[$cnt_aux_grupo][5] 	++;
					$v_conta_micros 			++;
	
					$v_id_so=trim(substr($tree[$cnt][3],strpos($tree[$cnt][3], 'id_so=')+6,2));						
					if ($v_id_so=='1') // win95
						{
						$tree[$cnt][7] 				++;
						$tree[$cnt_aux_grupo][7] 	++;						
						$tree[$cnt_aux_rede][7] 	++;
						$tree[$cnt_aux_local][7] 	++;																		
						$v_conta_os_win95 			++;						
						}
					elseif ($v_id_so=='2') // win95_osr2
						{
						$tree[$cnt][8] 				++;						
						$tree[$cnt_aux_grupo][8] 	++;						
						$tree[$cnt_aux_rede][8] 	++;												
						$tree[$cnt_aux_local][8] 	++;						
						$v_conta_os_win95_osr2		++;												
						}
					elseif ($v_id_so=='3') // win98
						{
						$tree[$cnt][9] 				++;						
						$tree[$cnt_aux_grupo][9] 	++;						
						$tree[$cnt_aux_rede][9] 	++;
						$tree[$cnt_aux_local][9] 	++;																								
						$v_conta_os_win98 			++;												
						}
					elseif ($v_id_so=='4') // win98_se
						{
						$tree[$cnt][10] 			++;						
						$tree[$cnt_aux_grupo][10] 	++;						
						$tree[$cnt_aux_rede][10] 	++;												
						$tree[$cnt_aux_local][10] 	++;												
						$v_conta_os_win98_se		++;												
						}
					elseif ($v_id_so=='5') // winme
						{
						$tree[$cnt][11]				++;												
						$tree[$cnt_aux_grupo][11] 	++;						
						$tree[$cnt_aux_rede][11] 	++;												
						$tree[$cnt_aux_local][11] 	++;												
						$v_conta_os_winme 			++;												
						}
					elseif ($v_id_so=='6') // winnt
						{
						$tree[$cnt][12]				++;												
						$tree[$cnt_aux_grupo][12] 	++;						
						$tree[$cnt_aux_rede][12] 	++;
						$tree[$cnt_aux_local][12] 	++;																														
						$v_conta_os_winnt 			++;												
						}
					elseif ($v_id_so=='7') // win2k
						{
						$tree[$cnt][13]				++;												
						$tree[$cnt_aux_grupo][13] 	++;						
						$tree[$cnt_aux_rede][13] 	++;												
						$tree[$cnt_aux_local][13] 	++;																		
						$v_conta_os_win2k 			++;												
						}
					elseif ($v_id_so=='8') // winxp
						{
						$tree[$cnt][14]				++;												
						$tree[$cnt_aux_grupo][14] 	++;						
						$tree[$cnt_aux_rede][14] 	++;												
						$tree[$cnt_aux_local][14] 	++;																		
						$v_conta_os_winxp 			++;												
						}
					elseif ($v_id_so=='9') // linux
						{
						$tree[$cnt][15]				++;						
						$tree[$cnt_aux_grupo][15] 	++;						
						$tree[$cnt_aux_rede][15] 	++;												
						$tree[$cnt_aux_local][15] 	++;																		
						$v_conta_os_linux 			++;												
						}					
					}																		
				//------------------------------------------------------------------------------------
				
				$cnt++;
			  	}
				
			  if ($monta)
					{
					$_GET['p'] .= ($cnt-1).'#'.$ultimo;
					}
			  
			  for ($i=0; $i<count($tree); $i++) 
			  {
				 $expand[$i]=0;
				 $visible[$i]=0;
				 $levels[$i]=0;
			  }
			  //  Get Node numbers to expand	 
			  if ($_GET['p']!="") 
			  	{
				$explevels = explode("|",$_GET['p']);
				}

			  $i=0;
			  while($i<count($explevels))			  
			  {  
				$expand[$explevels[$i]]=1;	
				$i++;
			  }
			
			  //  Find last nodes of subtrees         
			  $lastlevel=$maxlevel;
			  for ($i=count($tree)-1; $i>=0; $i--)
			  {
				 if ( $tree[$i][0] < $lastlevel )
				 {
				   for ($j=$tree[$i][0]+1; $j <= $maxlevel; $j++)
				   {
					  $levels[$j]=0;
				   }
				 }
				 if ( $levels[$tree[$i][0]]==0 )
				 	{
				    $levels[$tree[$i][0]]=1;
				    $tree[$i][4]=1;	   
				 	}
				 else
				    $tree[$i][4]=0;	   
				 $lastlevel=$tree[$i][0];  
			  }
			 		  
			  // Determine visible nodes                  
 			  // all root nodes are always visible

			  for ($i=0; $i < count($tree); $i++) 
				{
				if ($tree[$i][0]==1)
					{
					 $visible[$i]=1;
					}
				}
			  for ($i=0; $i < count($explevels); $i++)
			  {
				$n=$explevels[$i];
				if ( ($visible[$n]==1) && ($expand[$n]==1) )
				{
				   $j=$n+1;
				   while ( $tree[$j][0] > $tree[$n][0] )
				   {
					 if ($tree[$j][0]==$tree[$n][0]+1) $visible[$j]=1;     
					 $j++;
				   }
				}
			  }
			  			  
			  //  Output nicely formatted tree             		  
			  for ($i=0; $i<$maxlevel; $i++) $levels[$i]=1;
			
			  $maxlevel++;
			  if (count($tree) > 0)	
			  	{
//      			<div align="justify"></div>				
			  	?>
      			<table cellspacing=0 cellpadding=0 border=0 cols="<? echo ($maxlevel+4); ?>" align='left' width="100">
			  	<?				
				}
				
			  for ($i=0; $i<$maxlevel; $i++)
			  	{ 
				?>
			  	<tr nowrap></tr>
				<?
			  	}
			  if (count($tree) > 0)	
			  	{
				echo "<tr nowrap>";
				echo "<img src=\"".$img_redes."\"><font size='1' face='Verdana'><u>Total Geral de Estações Monitoradas:</u></font><font size='2' face='Verdana'> <strong>".$v_conta_micros."</strong></font>";
				
				$nu_totais_estacoes = 	$v_conta_os_win95		."_".
										$v_conta_os_win95_osr2	."_".
										$v_conta_os_win98		."_".
										$v_conta_os_win98_se	."_".
										$v_conta_os_winme		."_".
										$v_conta_os_winnt		."_".
										$v_conta_os_win2k		."_".
										$v_conta_os_winxp		."_".
										$v_conta_os_linux;
				?>
				<a href="#" onClick="MyWindow=window.open('totais_estacoes_rede.php?nu_totais_estacoes=<? echo $nu_totais_estacoes;?>', 'newWindow','toolbar=no,location=no,scrollbars=yes,menubar=no');
				MyWindow.document.close()"><img src="<? echo $img_totals;?>" border=no width=16 height=16 Title="Totais de Estações por Sistema Operacional"></a>				
				<?
						
				echo "</tr>";				
				}
			  $cnt=0;
			  while ($cnt<count($tree))
			  	{			  
				if ($visible[$cnt])
				  {
				  // start new row                        
				  echo "<tr nowrap>";
				  
				  // vertical lines from higher levels    
				  $i=0;
				  //while ($i<($tree[$cnt][0]-1)) 
				  while ($i<($tree[$cnt][0]-1)) 				  
				  	{	  
					echo "<td nowrap><div align='left'><font size='2' face='Verdana, Arial, Helvetica, sans-serif'><a name='$cnt'></a><img src='";
					if ($levels[$i]==1)
						{
						echo $img_line;
						}
					else
						{
						echo $img_spc;			
						}
					echo "'></div></td>";			
					$i++;
				  	}
				  
				  // corner at end of subtree or t-split  
				  if ($tree[$cnt][4]==1) 	  
				  	{
					echo "<td nowrap><div align='left'><img src=\"".$img_end."\"></div></td>";
					$levels[$tree[$cnt][0]-1]=0;
				  	}
				  else
				  	{
					echo "<td nowrap><div align='left'><img src=\"".$img_split."\"></div></td>";                  
					$levels[$tree[$cnt][0]-1]=1;    
				  	} 
				  
				  // Node (with subtree) or Leaf (no subtree) 
				  if ($tree[$cnt+1][0]>$tree[$cnt][0])
				  	{
					// Create expand/collapse parameters    
					$i=0;
					$params="?p=";
					while($i<count($expand))
						{
						if ( ($expand[$i]==1) && ($cnt!=$i) || ($expand[$i]==0 && $cnt==$i))
					  		{
							$params=$params.$i;
							$params=$params."|";
					  		}
					  	$i++;
						}

					if ($_SESSION['tipo_consulta1']<>'')
						{
						$params=$params."&tipo_consulta=".$_SESSION['tipo_consulta1']."&string_consulta=".$_SESSION['string_consulta1'];
						}

					if ($expand[$cnt]==0)
						{
						if ($tree[$cnt][0]==3) //Group
							{
							echo "<td><div align='left'><a href=\"".$script.$params."#$cnt\" class='a".$cnt."'><img src=\"".$img_expand."\" border=no></a></div></td>";
							}
						else
							{
							echo "<td nowrap><div align='left'><a href=\"".$script.$params."#$cnt\" class='a".$cnt."'><img src=\"".$img_expand."\" border=no></a></div></td>";
							}
						}
					else
						{
						echo "<td nowrap><div align='left'><a href=\"".$script.$params."#$cnt\" class='a".$cnt."'><img src=\"".$img_collapse."\" border=no></a></div></td>";
						}
				  	}
				  else
				  	{

					// Tree Leaf             
					if ($tree[$cnt][2]=='3')
						{
						$img_leaf = $tree_imgs_path . "tree_computer_red.gif";
						}
					elseif ($tree[$cnt][2]=='2')
						{
						$img_leaf = $tree_imgs_path . "tree_computer_yellow.gif";			
						}
					else
						{
						$img_leaf = $tree_imgs_path . "tree_computer_green.gif";			
						}

					$v_id_so=trim(substr($tree[$cnt][3],strpos($tree[$cnt][3], 'id_so=')+6,2));						
                                        $qry_sg_so = 'select sg_so from so where id_so='.$v_id_so;
		                        conecta_bd_cacic();
		                        $result_qry_sg_so = mysql_query($qry_sg_so) or die ('Erro no acesso à tabela "so" ou sua sessão expirou! '.mysql_error());
                                        $sg_so = mysql_fetch_array($result_qry_sg_so);
		                        $img_os = $sg_so['sg_so'];
					echo '<td nowrap><div align="left"><a href=computador/computador.php?'.$tree[$cnt][3]." target='_blank'><img src=\"".$img_leaf."\" border=no>".$img_os."</div></td>";
				  	}
				  
				  
				  // output item text                     
				  echo "<td colspan=".(($maxlevel-$tree[$cnt][0])*2)." width=750 valign='middle' nowrap><div align='left'><font size='2' face='Verdana, Arial, Helvetica, sans-serif'>";				  
			
				  if ($tree[$cnt][2]=="")
					{
					if ($tree[$cnt][0]==3) //Group
						{
						echo "<a href=\"".$script.$params."#$cnt\"><img src=\"".$img_workgroup."\" border=no>";
						}			

					echo "<a href=\"".$script.$params."#$cnt\">".$tree[$cnt][1].' <font size="1" face="Verdana, Arial, Helvetica, sans-serif">['.$tree[$cnt][5].'] ';
					
					echo "</font><font size='2' face='Verdana, Arial, Helvetica, sans-serif'>";
					if ($tree[$cnt][0]==1 || $tree[$cnt][0]==2 || $tree[$cnt][0]==3) // Localization, SubNet or WorkGroup
						{
						$nu_totais_estacoes 	= 	$tree[$cnt][7]	."_".
													$tree[$cnt][8]	."_".
													$tree[$cnt][9]	."_".
													$tree[$cnt][10]	."_".
													$tree[$cnt][11]	."_".
													$tree[$cnt][12]	."_".
													$tree[$cnt][13]	."_".
													$tree[$cnt][14]	."_".
													$tree[$cnt][15];


						$v_path = 'totais_estacoes_rede.php?nm_local='	.$tree[$cnt][18].
														  '&nm_subnet='			.$tree[$cnt][19].
														  '&nm_workgroup='		.$tree[$cnt][20].
														  '&nu_totais_estacoes='.$nu_totais_estacoes;

						?>
						<a href="#" onClick="MyWindow=window.open('<? echo $v_path; ?>', 'JANELA','toolbar=no,location=no,scrollbars=yes,menubar=no');
						MyWindow.document.close()"><img src="<? echo $img_totals;?>" border=no width=16 height=16 Title="Totais de Estações por Sistema Operacional"></a>				
						<?
						}

					// Atenção: foi necessário usar a condição "id_grupo_usuarios" abaixo devido ao "cs_nivel_administracao" == 0
					if ($tree[$cnt][0]==2 && ($_SESSION["cs_nivel_administracao"] <> 0 || $_SESSION["id_grupo_usuarios"] == 7))
						{
						if ($_SESSION["cs_nivel_administracao"] <> 0 || $_SESSION["id_grupo_usuarios"] == 7)
							echo "<a href=../admin/redes/detalhes_rede.php?id_ip_rede=".$tree[$cnt][6]."&id_local=".$tree[$cnt][17]." target='_blank'><img src=\"".$img_details."\" border=no width=16 height=16 Title='Detalhes da SubRede'></a>";						
							
						if ($tree[$cnt][21])
							echo "<img src=\"".$img_authentication_server."\" border=no width=16 height=16 Title='Sub-Rede Associada ao Servidor de Autenticação \"".$tree[$cnt][21]."\"'>";												
						}
						
					}
				  else
					{
				  	echo '<a href=computador/computador.php?'.$tree[$cnt][3]." target='_blank'>".$tree[$cnt][1]."</a>";
					if (trim($tree[$cnt][16])<>'')
						{
				  		echo '&nbsp;<img src="'. $img_compart_print. '" border=no width=17 height=17 Title="'.$tree[$cnt][16].'">';
						}					
					}
				  echo "</div></td></tr>";                
				}
				$cnt++;
			}
			
?>
</table>
</td>
</tr>
</table>	
</form>
</body>
</html>
