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
session_start();
/*
 * verifica se houve login e tamb�m as permiss�es de usu�rio
 */
if(!isset($_SESSION['id_usuario'])) 
  die('Acesso negado (Access denied)!');
else { // Inserir regras para verificar permiss�es do usu�rio!
}
require_once('../include/library.php');
$where = '';
if ($_GET['v_id_ip_gerente'])
	{
	$where = ' WHERE re.id_ip_gerente = ' . $_GET['v_id_ip_gerente'];
	}

$query_GERENTES= "	SELECT 	ge.id_ip_gerente,
							ge.nm_gerente,
							ge.nu_porta_repositorio, 
							ge.nm_usuario_login_repositorio,
							ge.te_senha_login_repositorio,
							gvm.nm_modulo,
							gvm.te_versao_modulo
				FROM		gerentes ge LEFT JOIN gerentes_versoes_modulos gvm ON (ge.id_ip_gerente = gvm.id_ip_gerente) " .
				$where .
				' ORDER BY ge.nm_gerente';
			
conecta_bd_cacic();					
$result_GERENTES = mysql_query($query_GERENTES);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
  <head>
	<title><?=$oTranslator->_('Atualizacao de Base de Dados');?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  </head>
  <body background="file:///h|/cacic2sg/imgs/linha_v.gif">
	<table width="90%" border="0" align="center">
	  <tr> 
	  	<td>
	  		<font color="#FF0000" size="4" face="Verdana, Arial, Helvetica, sans-serif">
	  			<b><?=$oTranslator->_('Atualizacao de Base de Dados');?></b>
	  		</font>
	  	</td>
	  </tr>
	  <tr>
		<td>
			<font size="2" face="Verdana, Arial, Helvetica, sans-serif">
				<?=$oTranslator->_('Modulo para atualizacao das informacoes coletadas dos modulos gerentes descentralizados');?>.
			</font>
		</td>
	  </tr>
	</table>
	<table width="90%" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#666666">
	<tr bordercolor="#000000" bgcolor="#CCCCCC">
	 <td valign="center">
		<p align="left"><font color="#333333" size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>
				<?=$oTranslator->_('IP Gerente');?>.
		  </strong></font> </p>
	 </td>
	 <td valign="center">
	    <p align="left"><font color="#333333" size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;&nbsp;&nbsp;</font>
		</p>
		</td>
		
		<td valign="center">
		<p align="left"><font color="#333333" size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>
				<?=$oTranslator->_('Descricao');?>.
		</strong></font> 
      </p>
</td>		
<td valign="center">
<p align="left"><font color="#333333" size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;&nbsp;&nbsp;</font>
</p>
</td>
	
<td valign="center">
<p align="left"><font color="#333333" size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>
<?=$oTranslator->_('Status');?>.
</strong></font>
</p>
</td>		

</tr>
<?	
$v_tripa_repositorios = '';
while ($row = mysql_fetch_array($result_GERENTES))
	{
	if ($v_cor_zebra == '#FFFFFF') $v_cor_zebra = '#EEEEEE'; else $v_cor_zebra = '#FFFFFF';
	?>
	<tr> 
			<td valign="center" bgcolor="<? echo $v_cor_zebra;?>"  style="height: 25">
	<p align="left"><font color="#333333" size="2" face="Verdana, Arial, Helvetica, sans-serif"><?
	echo $row['id_ip_gerente'];?></font>
	</p>
	</td>
	<td valign="center" bgcolor="<? echo $v_cor_zebra;?>">
	<p align="left"><font color="#333333" size="2" face="Verdana, Arial, Helvetica, sans-serif">
	&nbsp;</font>
	</p>
	</td>
		
	<td valign="center" bgcolor="<? echo $v_cor_zebra;?>" nowrap>
	<p align="left"><font color="#333333" size="2" face="Verdana, Arial, Helvetica, sans-serif"><?
	echo $row['nm_gerente'];?></font>
	</p>
	</td>
	<td valign="center" bgcolor="<? echo $v_cor_zebra; ?>">
	<p align="left"><font color="#333333" size="2" face="Verdana, Arial, Helvetica, sans-serif">
	&nbsp;</font>
	</p>
	</td>
	<td valign="center" bgcolor="<? echo $v_cor_zebra;?>" nowrap>
	<p align="left">
	<?

	$v_conta_objetos_enviados 			= 0;
	$v_conta_objetos_nao_enviados 		= 0;			
	$v_conta_objetos_atualizados 		= 0;
	$v_conta_objetos_nao_atualizados 	= 0;			
	$v_array_objetos_enviados 			= array();			
	$v_array_objetos_nao_enviados 		= array();						
	$v_array_objetos_atualizados 		= array();
	$v_array_objetos_nao_atualizados	= array();
	$v_efetua_conexao_ftp 				= 0;						
		if (trim($row['nu_porta_repositorio'] .
				 $row['nm_usuario_login_repositorio'] .
				 $row['te_senha_login_repositorio']) != '')
			{	
				
			$v_conexao_ftp = conecta_ftp($row['id_ip_gerente'],
										 $row['nm_usuario_login_repositorio'],
										 $row['te_senha_login_repositorio'],
										 $row['nu_porta_repositorio']
										);
	
			if ($v_conexao_ftp)
				{
				$v_efetua_conexao_ftp = 1;
					
				// obtem a lista de arquivos para v_te_path_serv_updates
				$v_arquivos_FTP = ftp_rawlist($v_conexao_ftp, '.');
				$v_nomes_arquivos_FTP = array();
				$v_versoes_arquivos_FTP = array();				
				for ($cnt_arquivos_FTP = 0; $cnt_arquivos_FTP < count($v_arquivos_FTP); $cnt_arquivos_FTP++)
					{	
					while (strpos($v_arquivos_FTP[$cnt_arquivos_FTP],'  ') > 0) 
						{
						// Elimina incidencia de espacos duplicados
						$v_arquivos_FTP[$cnt_arquivos_FTP] = str_replace('  ',' ',$v_arquivos_FTP[$cnt_arquivos_FTP]);			
						}									
					$v_array_arquivos_FTP = explode(' ',$v_arquivos_FTP[$cnt_arquivos_FTP]);
					$v_data_hora_altera_FTP = ftp_mdtm($v_conexao_ftp,$v_array_arquivos_FTP[count($v_array_arquivos_FTP)-1]);
					$v_data_hora_altera_FTP = date("YmdHi", $v_data_hora_altera_FTP);
					array_push($v_nomes_arquivos_FTP, $v_array_arquivos_FTP[count($v_array_arquivos_FTP)-1]);
					array_push($v_versoes_arquivos_FTP, $v_data_hora_altera_FTP);										
					}

				for ($cnt_nomes_arquivos_REP = 0; $cnt_nomes_arquivos_REP < count($v_nomes_arquivos_REP); $cnt_nomes_arquivos_REP++) 
					{	
					$v_achei = 0;
					for ($cnt_nomes_arquivos_FTP = 0; $cnt_nomes_arquivos_FTP < count($v_nomes_arquivos_FTP); $cnt_nomes_arquivos_FTP++)
						{
						if ($v_nomes_arquivos_FTP[$cnt_nomes_arquivos_FTP] == $v_nomes_arquivos_REP[$cnt_nomes_arquivos_REP])
							{
							$v_achei = 1;
							if ($v_versoes_arquivos_FTP[$cnt_nomes_arquivos_FTP] < $v_versoes_arquivos_REP[$cnt_nomes_arquivos_REP])
								{
								@ftp_chdir($v_conexao_ftp,$v_nomes_arquivos_FTP[$cnt_nomes_arquivos_FTP]);
								@ftp_delete($v_conexao_ftp,$v_nomes_arquivos_FTP[$cnt_nomes_arquivos_FTP]);
								if (@ftp_put($v_conexao_ftp,
											$v_nomes_arquivos_FTP[$cnt_nomes_arquivos_FTP],
											$_SERVER['DOCUMENT_ROOT'] . '/cacic2sg/repositorio/' . $v_nomes_arquivos_REP[$cnt_nomes_arquivos_REP],
											FTP_BINARY))
									{
									array_push($v_array_objetos_atualizados, $v_nomes_arquivos_REP[$cnt_nomes_arquivos_REP]);
									atualiza_ger_ver_mod($row['id_ip_gerente'],$v_nomes_arquivos_REP[$cnt_nomes_arquivos_REP],$v_versoes_arquivos_REP[$cnt_nomes_arquivos_REP]);
									$v_conta_objetos_atualizados ++;											
									}
								else
									{
									array_push($v_array_objetos_nao_atualizados, $v_nomes_arquivos_REP[$cnt_nomes_arquivos_REP]);											
									$v_conta_objetos_nao_atualizados ++;
									}	
								}
							$cnt_nomes_arquivos_FTP = count($v_nomes_arquivos_FTP);
							}										
						}
		
					if ($v_achei == 0)
						{
						if (@ftp_put($v_conexao_ftp,
									$v_nomes_arquivos_REP[$cnt_nomes_arquivos_REP],
									$_SERVER['DOCUMENT_ROOT'] . '/cacic2sg/repositorio/' . $v_nomes_arquivos_REP[$cnt_nomes_arquivos_REP],
									FTP_BINARY))
							{
							array_push($v_array_objetos_enviados, $v_nomes_arquivos_REP[$cnt_nomes_arquivos_REP]);
							atualiza_ger_ver_mod($row['id_ip_gerente'],$v_nomes_arquivos_REP[$cnt_nomes_arquivos_REP],$v_versoes_arquivos_REP[$cnt_nomes_arquivos_REP]);
							$v_conta_objetos_enviados ++;											
							}
						else
							{
							array_push($v_array_objetos_nao_enviados, $v_nomes_arquivos_REP[$cnt_nomes_arquivos_REP]);
							$v_conta_objetos_nao_enviados ++;
							$v_achei = 0;
							}									
						}												
					}	
					ftp_quit($v_conexao_ftp);							
				}
			}
			if ($v_efetua_conexao_ftp)
				{							
				?>
				<font color="#000099" size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>
				<?=$oTranslator->_('Verificacao efetuada');?>!</strong></font>
				<?																	
							
				if ($v_conta_objetos_atualizados)
					{
					for ($cnt_objetos = 0; $cnt_objetos < $v_conta_objetos_atualizados; $cnt_objetos++) 					
						{
						?>
						<tr> 
							<td valign="center" bgcolor="<? echo $v_cor_zebra;?>">
							<p align="left"><font color="#333333" size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;</font>
							</p>
							</td>
							<td valign="center" bgcolor="<? echo $v_cor_zebra; ?>">
							<p align="left"><font color="#333333" size="2" face="Verdana, Arial, Helvetica, sans-serif">
							&nbsp;</font>
							</p>
							</td>
						
							<td valign="center" bgcolor="<? echo $v_cor_zebra;?>">
							<p align="left"><font color="#333333" size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;</font>
							</p>
							</td>
							<td valign="center" bgcolor="<? echo $v_cor_zebra; ?>">
							<p align="left"><font color="#333333" size="2" face="Verdana, Arial, Helvetica, sans-serif">
							&nbsp;</font>
							</p>
							</td>
							<td valign="center" bgcolor="<? echo $v_cor_zebra; ?>">
							<p align="left"><font color="#000099" size="2" face="Verdana, Arial, Helvetica, sans-serif">
							<?=$oTranslator->_('Atualizando');?>
							<? echo $v_array_objetos_atualizados[$cnt_objetos];?>...</font>
							<?
						}						
					}
				if ($v_conta_objetos_nao_atualizados)
					{
					for ($cnt_objetos = 0; $cnt_objetos < $v_conta_objetos_nao_atualizados; $cnt_objetos++) 					
						{
						?>
						<tr> 
								<td valign="center" bgcolor="<? echo $v_cor_zebra; ?>">
								<p align="left"><font color="#333333" size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;</font>
								</p>
								</td>
								<td valign="center" bgcolor="<? echo $v_cor_zebra; ?>">
								<p align="left"><font color="#333333" size="2" face="Verdana, Arial, Helvetica, sans-serif">
								&nbsp;</font>
								</p>
								</td>				
								<td valign="center" bgcolor="<? echo $v_cor_zebra; ?>">
								<p align="left"><font color="#333333" size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;</font>
								</p>
								</td>
								<td valign="center" bgcolor="<? echo $v_cor_zebra; ?>">
								<p align="left"><font color="#333333" size="2" face="Verdana, Arial, Helvetica, sans-serif">
								&nbsp;</font>
								</p>
								</td>
								<td valign="center" bgcolor="<? echo $v_cor_zebra; ?>">
								<p align="left"><font color="#FF0000" size="2" face="Verdana, Arial, Helvetica, sans-serif">
							    <?=$oTranslator->_('Nao Atualizado');?>: 
								<? echo $v_array_objetos_nao_atualizados[$cnt_objetos];?>!</font>
								<?
						}						
					}
				if ($v_conta_objetos_enviados)
					{
					for ($cnt_objetos = 0; $cnt_objetos < $v_conta_objetos_enviados; $cnt_objetos++) 					
						{
						?>
						<tr> 
								<td valign="center" bgcolor="<? echo $v_cor_zebra; ?>">
								<p align="left"><font color="#333333" size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;</font>
								</p>
								</td>
								<td valign="center" bgcolor="<? echo $v_cor_zebra; ?>">
								<p align="left"><font color="#333333" size="2" face="Verdana, Arial, Helvetica, sans-serif">
								&nbsp;</font>
								</p>
								</td>
					
								<td valign="center" bgcolor="<? echo $v_cor_zebra; ?>">
								<p align="left"><font color="#333333" size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;</font>
								</p>
								</td>
								<td valign="center" bgcolor="<? echo $v_cor_zebra; ?>">
								<p align="left"><font color="#333333" size="2" face="Verdana, Arial, Helvetica, sans-serif">
								&nbsp;</font>
								</p>
								</td>
								<td valign="center" bgcolor="<? echo $v_cor_zebra; ?>">
								<p align="left"><font color="#000099" size="2" face="Verdana, Arial, Helvetica, sans-serif">
								<?=$oTranslator->_('Enviando');?>
								<? echo $v_array_objetos_enviados[$cnt_objetos];?>...</font>
								<?
						}						
					}
				if ($v_conta_objetos_nao_enviados)
					{
					for ($cnt_objetos = 0; $cnt_objetos < $v_conta_objetos_nao_enviados; $cnt_objetos++) 					
						{
						?>
						<tr> 
								<td valign="center" bgcolor="<? echo $v_cor_zebra; ?>">
								<p align="left"><font color="#333333" size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;</font>
								</p>
								</td>
								<td valign="center" bgcolor="<? echo $v_cor_zebra; ?>">
								<p align="left"><font color="#333333" size="2" face="Verdana, Arial, Helvetica, sans-serif">
								&nbsp;</font>
								</p>
								</td>
		
								<td valign="center" bgcolor="<? echo $v_cor_zebra; ?>">
								<p align="left"><font color="#333333" size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;</font>
								</p>
								</td>
								<td valign="center" bgcolor="<? echo $v_cor_zebra; ?>">
								<p align="left"><font color="#333333" size="2" face="Verdana, Arial, Helvetica, sans-serif">
								&nbsp;</font>
								</p>
								</td>
								<td valign="center" bgcolor="<? echo $v_cor_zebra; ?>">
								<p align="left"><font color="#FF0000" size="2" face="Verdana, Arial, Helvetica, sans-serif">
								<?=$oTranslator->_('Nao Enviado');?>
								<? echo $v_array_objetos_nao_enviados[$cnt_objetos];?>!</font>
								<?
						}						
					}										
				}									
			else {
			
				if (trim($row['nu_porta_repositorio'])			== 	'' || 
				 	trim($row['nm_usuario_login_repositorio']) 	== 	'' ||
				 	trim($row['te_senha_login_repositorio'])	==	'')
					{
					?>
					<font color="#FF0000" size="2" face="Verdana, Arial, Helvetica, sans-serif"><a href="file:///h|/cacic2sg/admin/gerentes/detalhes_gerente.php?id_ip_gerente=<? echo $row['id_ip_gerente']; ?>" style="color: red"><strong>
					<?=$oTranslator->_('FTP nao configurado');?>!</strong></a></font>
					<?
					}
				else
					{
					?>
					<font color="#FF0000" size="2" face="Verdana, Arial, Helvetica, sans-serif"><a href="file:///h|/cacic2sg/admin/gerentes/detalhes_gerente.php?id_ip_gerente=<? echo $row['id_ip_gerente']; ?>" style="color: red"><strong>
					<?=$oTranslator->_('Servidor OffLine');?>!</strong></a></font>
					<?
					}
			}
	}			
?>
				</p>
			</td>
		</tr>
	  </table>	
	</body>
</html>