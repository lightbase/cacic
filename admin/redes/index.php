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
  die('Acesso restrito (Restricted access)!');
else { // Inserir regras para outras verificações (ex: permissões do usuário)!
}

if ($_POST['submit']) 
	{
  	header ("Location: incluir_rede.php");
	}

include_once "../../include/library.php";

AntiSpy('1,2,3'); // Permitido somente a estes cs_nivel_administracao...
// 1 - Administração
// 2 - Gestão Central
// 3 - Supervisão

Conecta_bd_cacic();

// Obtem quantidade de máquinas por rede
function getNetPCs($p_id_ip_rede) {
   $qry_net_pc = "SELECT computadores.id_ip_rede, 
                         count(te_node_address) AS quantidade_pc
                    FROM      computadores 
                    LEFT JOIN redes ON (computadores.id_ip_rede = redes.id_ip_rede AND computadores.te_mascara = redes.te_mascara_rede)
                    WHERE     computadores.id_ip_rede='".$p_id_ip_rede."'
                    GROUP BY  id_ip_rede
                    ORDER BY  computadores.id_ip_rede;";
   $result_net_pc = mysql_query($qry_net_pc);
   $row = mysql_fetch_array($result_net_pc);

   return ($row['quantidade_pc']);
}

$total_geral_pc = 0;

$where = ($_SESSION['cs_nivel_administracao']==1||$_SESSION['cs_nivel_administracao']==2?
			' LEFT JOIN locais ON (locais.id_local = redes.id_local)':
			', locais WHERE redes.id_local = locais.id_local AND redes.id_local='.$_SESSION['id_local']);
			
if ($_SESSION['te_locais_secundarios']<>'' && $where <> '')
	{
	// Faço uma inserção de "(" para ajuste da lógica para consulta
	$where = str_replace('redes.id_local=','(redes.id_local=',$where);
	$where .= ' OR redes.id_local in ('.$_SESSION['te_locais_secundarios'].')) ';
	}

$ordem = ($_GET['cs_ordem']<>''?$_GET['cs_ordem']:'sg_local,nm_rede');
			
$query = 'SELECT 	* 
		  FROM 		redes LEFT JOIN servidores_autenticacao ON redes.id_servidor_autenticacao = servidores_autenticacao.id_servidor_autenticacao '.
		  $where .' 		  			
		  ORDER BY '.$ordem;

$result = mysql_query($query);
$msg = '<div align="center">
		<font color="#c0c0c0" size="1" face="Verdana, Arial, Helvetica, sans-serif">
		'.$oTranslator->_('Clique nas Colunas para Ordenar').'</font><br><br></div>';				
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<link rel="stylesheet"   type="text/css" href="../../include/cacic.css">
<title>Cadastro de Redes</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body background="../../imgs/linha_v.gif">
<script language="JavaScript" type="text/javascript" src="../../include/cacic.js"></script>
<form name="form1" method="post" action="">
<table width="90%" border="0" align="center">
  <tr> 
      <td class="cabecalho"><?=$oTranslator->_('Cadastro de Subredes');?></td>
  </tr>
  <tr> 
      <td class="descricao"><?=$oTranslator->_('ksiq_msg subredes help');?></td>
  </tr>
</table>
<br><table border="0" align="center" cellpadding="0" cellspacing="1">
  <tr> 
    <td><div align="center">

          <input name="submit" type="submit" id="submit" value="<?=$oTranslator->_('Incluir Nova Subrede');?>" <? echo ($_SESSION['cs_nivel_administracao']<>1 && $_SESSION['cs_nivel_administracao']<>3?'disabled':'')?>>

        
      </div></td>
  </tr>
  <tr> 
    <td height="10">&nbsp;</td>
  </tr>
  <tr> 
    <td height="10"><? echo $msg;?></td>
  </tr>

  <tr> 
    <td height="1" bgcolor="#333333"></td>
  </tr>
  <tr> 
    <td> <table border="0" cellpadding="2" cellspacing="0" bordercolor="#333333" align="center">
       <tr> 
          <th colspan="10" align="left"><?=$oTranslator->_('Redes cadastradas');?></th>
       </tr>
          <tr bgcolor="#E1E1E1"> 
            <td align="center"  nowrap>&nbsp;</td>
            <td align="center"  nowrap>&nbsp;</td>
            <td align="center"  nowrap>&nbsp;</td>
            <td align="center"  nowrap class="cabecalho_tabela"><div align="left"><a href="index.php?cs_ordem=id_ip_rede">Endere&ccedil;o/M&aacute;scara</a></div></td>
            <td nowrap >&nbsp;</td>
			<td nowrap  class="cabecalho_tabela"><div align="left"><a href="index.php?cs_ordem=nm_rede"><?=$oTranslator->_('Subrede');?></a></div></td>            
            <td nowrap >&nbsp;</td>            
			<td nowrap  class="cabecalho_tabela"><div align="left"><a href="index.php?cs_ordem=nm_servidor_autenticacao">Servidor de Autenticação</a></div></td>
            <td nowrap >&nbsp;</td>
            <td align="center"  nowrap class="cabecalho_tabela"><div align="left"><a href="index.php?cs_ordem=sg_local,nm_rede"><?=$oTranslator->_('Local');?></a></div></td>
            <td nowrap class="cabecalho_tabela"><div align="center"><?=$oTranslator->_('Total de Maquinas');?></div></td>
          </tr>
  	<tr> 
    <td height="1" bgcolor="#333333" colspan="11"></td>
  	</tr>
		  
          <?  
if(@mysql_num_rows($result)==0) 
	{
	$msg = '<div align="center">
			<font color="red" size="1" face="Verdana, Arial, Helvetica, sans-serif">
				'.$oTranslator->_('Nenhuma rede cadastrada ou sua sessao expirou!').'</font><br><br></div>';				
	}
else 
	{
	$Cor = 0;
	$NumRegistro = 1;
	
	while($row = mysql_fetch_array($result)) 
		{		  
	 	?>
          <tr <? if ($Cor) { echo 'bgcolor="#E1E1E1"'; } ?>> 
            <td nowrap>&nbsp;</td>
            <td nowrap class="opcao_tabela"><div align="left"><? echo $NumRegistro; ?></div></td>
            <td nowrap>&nbsp;</td>
            <td nowrap class="opcao_tabela"><div align="left"><a href="detalhes_rede.php?id_ip_rede=<? echo $row['id_ip_rede'];?>&id_local=<? echo $row['id_local'];?>"><? echo $row['id_ip_rede'].'/'.$row['te_mascara_rede']; ?></a></div></td>
            <td nowrap>&nbsp;</td>
            <td nowrap class="opcao_tabela"><div align="left"><a href="detalhes_rede.php?id_ip_rede=<? echo $row['id_ip_rede'];?>&id_local=<? echo $row['id_local'];?>"><? echo $row['nm_rede']; ?></a></div></td>
            <td nowrap>&nbsp;</td>
            <td nowrap><a href="detalhes_rede.php?id_ip_rede=<? echo $row['id_ip_rede'];?>&id_local=<? echo $row['id_local'];?>"><? echo $row['nm_servidor_autenticacao']; ?></a></td>
            <td nowrap>&nbsp;</td>
            <td nowrap class="opcao_tabela"><div align="left"><a href="detalhes_rede.php?id_ip_rede=<? echo $row['id_ip_rede'];?>&id_local=<? echo $row['id_local'];?>"><? echo $row['sg_local']; ?></a></div></td>
            <td nowrap align="right">
                <a href="../../relatorios/navegacao.php" title="<?=$oTranslator->_('Navegar nas redes detectadas pelos agentes nas estacoes');?>">
                   <?php $total_ip_rede = getNetPCs($row['id_ip_rede']); $total_geral_pc += $total_ip_rede; echo $total_ip_rede;?>
                </a>
            </td>
           </tr>
            <? 
		$Cor=!$Cor;
		$NumRegistro++;
		}
	}
	?>
        
    </table></td>
  	</tr>
  	<tr> 
    <td height="1" bgcolor="#333333"></td>
  </tr>
  <tr> 
    <td height="10">&nbsp;</td>
  </tr>
  <tr> 
    <td height="10"><? echo $msg;?></td>
  </tr>
  <tr> 
    <td><div align="center">

          <input name="submit" type="submit" id="submit" value="<?=$oTranslator->_('Incluir Nova Subrede');?>" <? echo ($_SESSION['cs_nivel_administracao']<>1 && $_SESSION['cs_nivel_administracao']<>3?'disabled':'')?>>

        
      </div></td>
  </tr>
</table>
</form>
<p>&nbsp;</p>
</body>
</html>
