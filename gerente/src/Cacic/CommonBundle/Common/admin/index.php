<?php
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
 * verifica se houve login e tamb�m regras para outras verifica��es (ex: permiss�es do usu�rio)!
 */
if(!isset($_SESSION['id_usuario'])) 
  die('Acesso negado (Access denied)!');
else 
	{ // Inserir regras para outras verifica��es (ex: permiss�es do usu�rio)!
	include_once "../include/library.php";
	AntiSpy('1');
	?>
	<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
	<html>
	<head>
	<title>Sistema CACIC - Consultas Rapidas de Nivel Administrativo</title>
	<script language="JavaScript" type="text/javascript" src="../include/js/jquery.js"></script>            
	<script language="JavaScript" type="text/javascript" src="../include/js/cacic.js"></script>    
	<script language="JavaScript" type="text/javascript" src="../include/js/ajax/admin_queries.js"></script>  
	<meta http-equiv="Content-Type" content="text/html;charset=utf-8" >
	<link href="../include/css/cacic.css" rel="stylesheet" type="text/css">
	</head>
	<form name="form1" method="post" action="">
	<body background="../imgs/linha_v.gif" onLoad="SetaCampo('frm_te_ip_rede')">
	<table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
	<tr>
	<td colspan="6" nowrap bgcolor="#666666" class="cabecalho_rel"><h1 align="center">Sistema CACIC </h1>
	<h2 align="center">Consultas Rapidas de Nivel Administrativo</h2></td>
	</tr>
	
	<tr>
	<td colspan="6" bgcolor="#CCCCCC" class="cabecalho_secao"><div align="center"> Sub-Rede</div></td>
	</tr>
	<tr>
	<td nowrap class="label"><div align="right">Endereco IP:</div></td>
	<td><label><input type="text" name="frm_te_ip_rede" id="frm_te_ip_rede" value=""></label><img name="frm_btPesquisa" id="frm_btPesquisa" src="../imgs/totals.gif" height="25" width="25">	  </td>
	<td colspan="3" nowrap class="label"><div align="right">Mascara:</div></td>
	<td nowrap class="dado_med_sem_fundo"><div align="left"><input name="frm_te_mascara_rede" type="text" id="frm_te_mascara_rede">
	</div></td>
	</tr>
	<tr>
	<td nowrap class="label"><div align="right">Descricao:</div></td>
	<td colspan="5" nowrap><div align="left"><input name="frm_nm_rede" type="text" id="frm_nm_rede" size="100">
	</div></td>
	</tr>
	<tr>
	<td nowrap class="label"><div align="right">Local:</div></td>
	<td colspan="5" nowrap class="NaoGravavel"><div align="left"><input name="frm_nm_local" type="text" id="frm_nm_local" size="100">
	</div></td>
	</tr>
	<tr><td nowrap class="label">&nbsp;</td><td colspan="5" nowrap>&nbsp;</td></tr>
	
	<tr>
	<td nowrap class="label"><div align="right">Servidor  LDAP:</div></td>
	<td nowrap class="NaoGravavel"><div align="left"><input name="frm_nm_servidor_autenticacao" type="text" id="frm_nm_servidor_autenticacao" size="50">
	</div></td>
	<td colspan="3" nowrap class="label"><div align="right">Servidor de Atualizacoes:</div></td>
	<td nowrap class="NaoGravavel"><div align="left">
	  <input name="frm_te_serv_updates" type="text" id="frm_te_serv_updates" size="50">
    </div></td>
	</tr>
	
	<tr>
	  <td nowrap class="label"><div align="right">Servidor de Aplicacao:</div></td>
	  <td nowrap class="NaoGravavel"><div align="left">
	    <input name="frm_te_serv_cacic" type="text" id="frm_te_serv_cacic" size="50">
	    </div></td>
	  <td colspan="3" nowrap class="label"><div align="right">Porta:</div></td>
	  <td nowrap class="NaoGravavel"><div align="left">
	    <input name="frm_nu_porta_serv_updates" type="text" id="frm_nu_porta_serv_updates">
	    </div></td>
	</tr>
	

	<tr>
	<td nowrap>&nbsp;</td>
	<td nowrap class="dado_med_sem_fundo">&nbsp;</td>
	<td colspan="3" nowrap class="label"><div align="right">Limite FTP:</div></td>
	<td nowrap class="NaoGravavel"><div align="left"><input type="text" name="frm_nu_limite_ftp" id="frm_nu_limite_ftp"></div></td>
	</tr>
	
	<tr>
	<td nowrap>&nbsp;</td>
	<td nowrap class="dado_med_sem_fundo">&nbsp;</td>
	<td colspan="3" nowrap class="label"><div align="right">Caminho no Servidor de Atualizacoes:</div></td>
	<td nowrap class="NaoGravavel"><div align="left"><input type="text" name="frm_te_path_serv_updates" id="frm_te_path_serv_updates" size="50"></div></td>
	</tr>
	
	<tr>
	<td nowrap>&nbsp;</td>
	<td nowrap class="dado_med_sem_fundo">&nbsp;</td>
	<td colspan="3" nowrap class="label"><div align="right">Usuario FTP (Gerente):</div></td>
	<td nowrap class="NaoGravavel"><div align="left"><input type="text" name="frm_nm_usuario_login_serv_updates_gerente" id="frm_nm_usuario_login_serv_updates_gerente" size="50"></div></td>
	</tr>
	
	<tr>
	<td nowrap>&nbsp;</td>
	<td nowrap class="dado_med_sem_fundo">&nbsp;</td>
	<td colspan="3" nowrap class="label"><div align="right">Senha:</div></td>
	<td nowrap class="NaoGravavel"><div align="left"><input type="text" name="frm_te_senha_login_serv_updates_gerente" id="frm_te_senha_login_serv_updates_gerente" size="50"></div></td>
	</tr>
	
	<tr>
	<td nowrap>&nbsp;</td>
	<td nowrap class="dado_med_sem_fundo">&nbsp;</td>
	<td colspan="3" nowrap class="label"><div align="right">Usuario FTP (Agentes):</div></td>
	<td nowrap class="NaoGravavel"><div align="left"><input type="text" name="frm_nm_usuario_login_serv_updates" id="frm_nm_usuario_login_serv_updates" size="50"></div></td>
	</tr>
	
	<tr>
	<td nowrap>&nbsp;</td>
	<td nowrap class="dado_med_sem_fundo">&nbsp;</td>
	<td colspan="3" nowrap class="label"><div align="right">Senha:</div></td>
	<td nowrap class="NaoGravavel"><div align="left"><input type="text" name="frm_te_senha_login_serv_updates" id="frm_te_senha_login_serv_updates" size="50"></div></td>
	</tr>
	<tr>
	  <td nowrap>&nbsp;</td>
	  <td nowrap class="dado_med_sem_fundo">&nbsp;</td>
	  <td colspan="3" nowrap class="label">&nbsp;</td>
	  <td nowrap class="NaoGravavel">&nbsp;</td>
	  </tr>
	<tr>
      <td colspan="6" bgcolor="#CCCCCC" class="cabecalho_secao"></td>
	  </tr>
	<tr>
      <td colspan="6" nowrap align="center"><div class="mensagens" name="frm_te_mensagens" id="frm_te_mensagens"></td>
	  </tr>
      
	<tr>
	  <td colspan="6" nowrap><label>
        <div align="center" class="dado_med_sem_fundo" name="frm_te_acoes" id="frm_te_acoes"></div>
	  </label></td>
	  </tr>
	<tr>
	  <td nowrap>&nbsp;</td>
	  <td nowrap class="dado_med_sem_fundo">&nbsp;</td>
	  <td colspan="3" nowrap class="label">&nbsp;</td>
	  <td nowrap class="NaoGravavel">&nbsp;</td>
	  </tr>
	<tr>
      <td colspan="6" bgcolor="#CCCCCC" class="cabecalho_secao"></td>
	  </tr>
	<tr>
      <td colspan="6" nowrap><label>
        <div align="center" class="dado_med_sem_fundo" name="frm_te_modulos" id="frm_te_modulos"></div>
      </label></td>
	  </tr>
	<tr>
	  <td nowrap>&nbsp;</td>
	  <td nowrap class="dado_med_sem_fundo">&nbsp;</td>
	  <td colspan="3" nowrap class="label">&nbsp;</td>
	  <td nowrap class="NaoGravavel">&nbsp;</td>
	  </tr>
	<tr>
      <td colspan="6" bgcolor="#CCCCCC" class="cabecalho_secao"></td>
	  </tr>
	<tr>
      <td colspan="6" nowrap><label>
        <div align="center" class="dado_med_sem_fundo" name="frm_te_estacoes_windows" id="frm_te_estacoes_windows"></div>
      </label></td>
	  </tr>
	<tr>
	  <td nowrap>&nbsp;</td>
	  <td nowrap class="dado_med_sem_fundo">&nbsp;</td>
	  <td colspan="3" nowrap class="label">&nbsp;</td>
	  <td nowrap class="NaoGravavel">&nbsp;</td>
	  </tr>
	<tr>
      <td colspan="6" bgcolor="#CCCCCC" class="cabecalho_secao"></td>
	  </tr>
	<tr>
      <td colspan="6" nowrap><label>
        <div align="center" class="dado_med_sem_fundo" name="frm_te_estacoes_linux" id="frm_te_estacoes_linux"></div>
      </label></td>
	  </tr>
	<tr>
	  <td nowrap>&nbsp;</td>
	  <td nowrap class="dado_med_sem_fundo">&nbsp;</td>
	  <td colspan="3" nowrap class="label">&nbsp;</td>
	  <td nowrap class="NaoGravavel">&nbsp;</td>
	  </tr>
      
	</table>
	
	</p>
	</body>
    </form>
    <script language="javascript">
    function setFade() {
        $("#frm_te_mensagens").fadeOut(500, function () {
            $("#frm_te_mensagens").fadeIn();
        });
        }
     setInterval("setFade();",50);
	</script>                      
	</html>
    <?php
	}
	?>