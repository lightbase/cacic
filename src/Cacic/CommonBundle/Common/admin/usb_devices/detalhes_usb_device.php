<?php
 /* 
 Copyright 2000, 2001, 2002, 2003, 2004, 2005 Dataprev - Empresa de Tecnologia e Informaï¿½ï¿½es da Previdï¿½ncia Social, Brasil

 Este arquivo ï¿½ parte do programa CACIC - Configurador Automï¿½tico e Coletor de Informaï¿½ï¿½es Computacionais

 O CACIC ï¿½ um software livre; vocï¿½ pode redistribui-lo e/ou modifica-lo dentro dos termos da Licenï¿½a Pï¿½blica Geral GNU como 
 publicada pela Fundaï¿½ï¿½o do Software Livre (FSF); na versï¿½o 2 da Licenï¿½a, ou (na sua opniï¿½o) qualquer versï¿½o.

 Este programa ï¿½ distribuido na esperanï¿½a que possa ser  util, mas SEM NENHUMA GARANTIA; sem uma garantia implicita de ADEQUAï¿½ï¿½O a qualquer
 MERCADO ou APLICAï¿½ï¿½O EM PARTICULAR. Veja a Licenï¿½a Pï¿½blica Geral GNU para maiores detalhes.

 Vocï¿½ deve ter recebido uma cï¿½pia da Licenï¿½a Pï¿½blica Geral GNU, sob o tï¿½tulo "LICENCA.txt", junto com este programa, se nï¿½o, escreva para a Fundaï¿½ï¿½o do Software
 Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */
session_start();
require_once('../../include/library.php');

AntiSpy('1,2'); // Permitido somente a estes cs_nivel_administracao...

// 1 - Administraï¿½ï¿½o
// 2 - Gestï¿½o Central


conecta_bd_cacic();


if (($_POST['ExcluiUSBDevice'] <> '') && $_SESSION['cs_nivel_administracao']==1) 
	{
	$query = "DELETE 	
			  FROM		usb_devices
			  WHERE 	trim(id_device) = '".$_POST['frm_id_device']."' AND trim(id_vendor)='".$_POST['frm_id_vendor']."'";
	mysql_query($query) or die('Delete falhou ou sua sessï¿½o expirou!');
	GravaLog('DEL',$_SERVER['SCRIPT_NAME'],'usb_devices',$_SESSION["id_usuario"]);		
    header ("Location: ../../include/operacao_ok.php?chamador=../admin/usb_devices/index.php&tempo=1");				
	}
else if (($_POST['GravaAlteracoes'] <> '') && $_SESSION['cs_nivel_administracao']==1) 
	{
			
	$query = "UPDATE 	usb_devices 
			  SET		id_vendor 					= '".$_POST['frm_id_vendor_new'] 	."',
			  			nm_device 					= '".$_POST['frm_nm_device'] 	."',
			  			te_observacao				= '".$_POST['frm_te_observacao']."'			  
			  WHERE 	trim(id_device) 			= '".trim($_POST['frm_id_device'])."' AND
			            trim(id_vendor)				= '".trim($_POST['frm_id_vendor'])."'";
			
	mysql_query($query) or die('Update falhou ou sua sessï¿½o expirou!');
	GravaLog('UPD',$_SERVER['SCRIPT_NAME'],'usb_devices',$_SESSION["id_usuario"]);		
	
    header ("Location: ../../include/operacao_ok.php?chamador=../admin/usb_devices/index.php&tempo=1");				
	}
else 
	{
	$queryDEVICE = "SELECT 	d.id_vendor,
							d.id_device,
						    d.nm_device,
							d.te_observacao,
							v.id_vendor,
							v.nm_vendor 
			  	    FROM 	usb_devices d,
							usb_vendors v
			  		WHERE   trim(d.id_device) = '".trim($_GET['id_device'])."' AND trim(d.id_vendor) = '".trim($_GET['id_vendor'])."' AND v.id_vendor = d.id_vendor";
	$resultDEVICE = mysql_query($queryDEVICE) or die ('Erro no acesso Ã  tabela Dispositivos_USB ou sua sessÃ£o expirou!');
	$rowDEVICE = mysql_fetch_array($resultDEVICE);
	
	$row = mysql_fetch_array($result);
	?>
    <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
    <html>
    <head>
    <link rel="stylesheet"   type="text/css" href="../../include/css/cacic.css">
    <title>Detalhes de Dispositivo USB</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <SCRIPT LANGUAGE="JavaScript">
    
    function valida_form() 
        {
    
        if ( document.form.frm_id_vendor.value == "" ) 
            {	
            alert("O Identificador do Fabricante ï¿½ obrigatï¿½rio.");
            document.form.frm_id_vendor.focus();
            return false;
            }		
        else if ( document.form.frm_id_device.value == "" ) 
            {	
            alert("O Identificador do Dispositivo USB ï¿½ obrigatï¿½rio.");
            document.form.frm_id_device.focus();
            return false;
            }
        else if ( document.form.frm_nm_device.value == "" ) 
            {	
            alert("O Nome do Dispositivo USB ï¿½ obrigatï¿½rio.");
            document.form.frm_nm_device.focus();
            return false;
            }
        return true;	
        }
    </script>
    <style type="text/css">
<!--
.style2 {	font-size: 9px;
	color: #000099;
}
-->
    </style>
    </head>
    
    <body background="../../imgs/linha_v.gif" onLoad="SetaCampo('frm_nm_vendor');">
    <script language="JavaScript" type="text/javascript" src="../../include/js/cacic.js"></script>
    <table width="85%" border="0" align="center">
    <tr> 
    <td class="cabecalho">Detalhes do Dispositivo "<?php echo $rowDEVICE['id_device'] . ' - '.$rowDEVICE['nm_device'];?>"</td>
    </tr>
    <tr> 
    <td class="descricao">As informações referem-se a um dispositivo USB e são usadas pelo módulo de detecção nas estações de trabalho.</td>
    </tr>
    </table>
    <form action="detalhes_usb_device.php"  method="post" ENCTYPE="multipart/form-data" name="form">
      <table width="85%" border="0" align="center" cellpadding="0" cellspacing="0">
        <tr> 
          <td class="label" colspan="3"><br>
            Identificador do Fabricante:</td>
        </tr>
        <tr> 
          <td height="1" bgcolor="#333333" colspan="3"></td>
        </tr>
        <tr> 
          <td class="label_peq_sem_fundo" colspan="3"> <select name="frm_id_vendor_new" id="frm_id_vendor_new" class="normal" > 
                    <?php
                    $query = "SELECT 	id_vendor,
										nm_vendor 
			  				  FROM 		usb_vendors v
							  WHERE     NOT v.nm_vendor LIKE '%Desconhecido%'
							  ORDER BY  v.nm_vendor";
					$result = mysql_query($query) or die ('1-Select falhou ou sua sessï¿½o expirou!');
                    echo "<option value=''>--> Selecione <--</option>";                					
                    while ($rowVendor = @mysql_fetch_array($result))
                        echo "<option value='".$rowVendor['id_vendor']."' ".($rowVendor['id_vendor']==$rowDEVICE['id_vendor']?'selected':'').">".$rowVendor['id_vendor'].' - '.$rowVendor['nm_vendor']."</option>";                
                    ?>
                    </select></td>

        </tr>
    <tr><td colspan="3">&nbsp;</td></tr>
    <tr><td colspan="3">&nbsp;</td></tr>
        <tr> 
          <td class="label"><br>
            Identificador do Dispositivo:</td>
          <td nowrap class="label" colspan="2"><br>
          Nome do Dispositivo:</td>
        </tr>
        <tr> 
          <td height="1" bgcolor="#333333" colspan="3"></td>
        </tr>
        <tr> 
          <td class="label_peq_sem_fundo"> <input name="frm_id_vendor" type="hidden" id="frm_id_vendor" value="<?php echo $_GET['id_vendor'];?>" >
          <input name="frm_id_device" type="text" size="10" maxlength="5" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" id="frm_id_device" readonly  value="<?php echo $rowDEVICE['id_device'];?>" >
          &nbsp;&nbsp;</td>
          <td class="label_peq_sem_fundo" colspan="2"><input name="frm_nm_device" type="text" size="50" maxlength="100" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" id="frm_nm_device"  value="<?php echo $rowDEVICE['nm_device'];?>" ></td>
        </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td colspan="3">&nbsp;</td>
    </tr>
    
    <tr>
      <td colspan="2" class="label"><div align="left"><br>
        Observa&ccedil;&otilde;es:</div></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td colspan="3"><span class="label_peq_sem_fundo">
        <label>
          <textarea name="frm_te_observacao" id="frm_te_observacao" cols="100" rows="3"  value="<?php echo $rowDEVICE['te_observacao'];?>" ></textarea>
        </label>
      </span></td>
    </tr>
      </table>
      
      <p align="center"> 
        <input name="GravaAlteracoes" type="submit" value="  Gravar Informa&ccedil;&otilde;es  " onClick="return Confirma('Confirma Alteraï¿½ï¿½o de Dispositivo USB?');">
        <br><br><br>
      </p>

    <?php
	$dataFinal   = new DateTime; 
	$dataInicial = new DateTime; 
        $dataInicial->modify( '-7 days');         

   $query =  "SELECT 
   			  distinct 		c.te_nome_computador,
			  				c.id_computador,
							c.te_ip_computador, 
							c.te_node_address, 
							c.id_so,                                                         
							c.id_rede,
							l.sg_local as Local ,
							u.dt_event,
							u.cs_event,
							r.nm_rede,
							r.te_ip_rede							
			  FROM 			usb_logs u, 
			  				computadores c ,
							redes r,
							locais l 
			  WHERE 		u.dt_event >= '" . $dataInicial->format('Ymd').'000000' . "' AND 
							u.dt_event <= '" . $dataFinal->format('Ymd').'235959' . "' AND 
							c.id_computador = u.id_computador AND 
							c.id_rede = r.id_rede AND 
							r.id_local = l.id_local AND trim(id_vendor)='".trim($_GET['id_vendor'])."' AND trim(id_device)='".trim($_GET['id_device'])."'  
			  ORDER BY 		u.dt_event DESC ";

	$result = mysql_query($query) or die ('Erro no select ou sua sessï¿½o expirou!');

if (mysql_num_rows($result) > 0)
	{
	$cor = 0;
	$num_registro = 1;
	
?>
<table border="0" align="center" cellpadding="0" cellspacing="1">
<tr>    
    <td nowrap bgcolor="#FFFFFF"><div align="center"><font color="#333333" size="" face="Verdana, Arial, Helvetica, sans-serif"><strong>Utilização do Dispositivo nos Últimos 7 dias</strong></font></div></td>    
</tr>
  <tr> 
    <td height="1" bgcolor="#333333"></td>
  </tr>
  <tr> 
    <td> <table border="0" cellpadding="2" cellspacing="0" bordercolor="#333333" align="center">
        <tr bgcolor="#E1E1E1"> 
          <td align="center"  nowrap>&nbsp;</td>
          <td align="center"  nowrap><div align="left"><strong></strong></div></td>
          <td align="center"  nowrap>&nbsp;</td>
          <td align="center"  nowrap bgcolor="#E1E1E1" class="cabecalho_tabela"><div align="left">Nome da M&aacute;quina</div></td>
          <td nowrap >&nbsp;</td>
          <td nowrap class="cabecalho_tabela"><div align="center">IP</div></td>
          <td nowrap >&nbsp;</td>
          <td nowrap class="cabecalho_tabela"><div align="center">Rede</div></td>
          <td nowrap >&nbsp;</td>
          <td nowrap class="cabecalho_tabela"><div align="left">Local</div></td>
          <td nowrap >&nbsp;</td>
          <td nowrap class="cabecalho_tabela"><div align="center">Data/Hora</div></td>
          <td nowrap >&nbsp;</td>
          <td nowrap class="cabecalho_tabela"><div align="center">Evento</div></td>
          <td nowrap >&nbsp;</td>
        </tr>
        <tr> 
        <td height="1" colspan="15" bgcolor="#333333"></td>
        </tr>
        <?php  
	$Cor = 0;
	$NumRegistro = 1;
	
	while($row = mysql_fetch_array($result)) 
		{
		  
	 ?>
        <tr <?php if ($Cor) { echo 'bgcolor="#E1E1E1"'; } ?>> 
          <td nowrap>&nbsp;</td>
          <td nowrap class="opcao_tabela"><div align="left"><?php echo $NumRegistro; ?></div></td>
          <td nowrap>&nbsp;</td>
          <td nowrap class="opcao_tabela"><div align="left"><a href="../../relatorios/computador/computador.php?id_computador=<?php echo $row['id_computador'];?>" target="_blank"><?php echo $row['te_nome_computador']; ?></a></div></td>
          <td nowrap>&nbsp;</td>
          <td nowrap class="opcao_tabela"><div align="center"><a href="../../relatorios/computador/computador.php?id_computador=<?php echo $row['id_computador'];?>" target="_blank"><?php echo $row['te_ip_computador']; ?></a></div></td>
          <td nowrap>&nbsp;</td>
          <td nowrap class="opcao_tabela"><div align="center"><a href="../../relatorios/computador/computador.php?id_computador=<?php echo $row['id_computador'];?>" target="_blank"><?php echo $row['te_ip_rede']; ?></a></div></td>
          <td nowrap>&nbsp;</td>
          <td nowrap class="opcao_tabela"><div align="left"><a href="../../relatorios/computador/computador.php?id_computador=<?php echo $row['id_computador'];?>" target="_blank"><?php echo $row['Local']; ?></a></div></td>
          <td nowrap>&nbsp;</td>
          <td nowrap class="opcao_tabela"><div align="center"><a href="../../relatorios/computador/computador.php?id_computador=<?php echo $row['id_computador'];?>" target="_blank"><?php echo substr($row['dt_event'],6,2).'/'.substr($row['dt_event'],4,2).'/'.substr($row['dt_event'],0,4).' às '.substr($row['dt_event'],8,2).':'.substr($row['dt_event'],10,2); ?></a></div></td>
          <td nowrap>&nbsp;</td>
          <td nowrap class="opcao_tabela"><div align="center"><a href="../../relatorios/computador/computador.php?id_computador=<?php echo $row['id_computador'];?>" target="_blank"><?php echo ($row['cs_event']=='I'?'Inserção':'Remoção'); ?></a></div></td>
          <td nowrap>&nbsp;</td>
          <?php 
	$Cor=!$Cor;
	$NumRegistro++;
	}
        ?>
        <tr> 
        <td height="1" colspan="15" bgcolor="#333333"></td>
        </tr>
          
      </table>
	</form>		              
	</body>
</html>
    <?php
    }
	}
?>