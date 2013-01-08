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
else { // Inserir regras para outras verifica��es (ex: permiss�es do usu�rio)!
}

include_once "include/library.php";
AntiSpy('1');
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title></title>
<script language="JavaScript" type="text/javascript" src="include/js/jquery.js"></script>            
<script language="JavaScript" type="text/javascript" src="include/js/cacic.js"></script>    
<script language="JavaScript" type="text/javascript" src="include/js/ajax/admin_queries.js"></script>                    
<meta http-equiv="Content-Type" content="text/html;charset=utf-8" >
<link href="include/css/cacic.css" rel="stylesheet" type="text/css">
<title>Sistema CACIC - Consultas Rápidas de Nível Administrativo</title>
</head>

<body background="imgs/linha_v.gif" onLoad="SetaCampo('frm_te_ip_rede')">
<table width="90%" border="1" align="center">
<tr>
<td colspan="4" nowrap class="cabecalho_rel"><h1 align="center">Sistema CACIC </h1>
<h2 align="center">Consultas Rápidas de Nível Administrativo</h2></td>
</tr>
<tr>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
</tr>
<tr>
<td colspan="4" class="cabecalho_secao"><div align="center">Informações de Sub-Rede</div></td>
</tr>
<tr>
<td nowrap class="label"><div align="right">Endereço IP:</div></td>
<td><label><form><input type="text" name="frm_te_ip_rede" id="frm_te_ip_rede"></form></label></td>
<td nowrap class="label"><div align="right">Máscara:</div></td>
<td nowrap class="dado_med_sem_fundo"><div align="left" name="frm_te_mascara_rede" id="frm_te_mascara_rede"></div></td>
</tr>
<tr>
<td nowrap class="label"><div align="right">Descrição:</div></td>
<td colspan="3" nowrap><div align="left" name="frm_nm_rede" id="frm_nm_rede"></div></td>
</tr>
<tr>
<td nowrap class="label"><div align="right">Local:</div></td>
<td colspan="3" nowrap class="dado_med_sem_fundo"><div align="left" name="frm_nm_local" id="frm_nm_local"></div></td>
</tr>
<tr><td nowrap class="label">&nbsp;</td><td colspan="3" nowrap>&nbsp;</td></tr>

<tr>
<td nowrap class="label"><div align="right">Servidor de Autenticação LDAP:</div></td>
<td nowrap><div align="left" name="frm_nm_servidor_autenticacao" id="frm_nm_servidor_autenticacao"></div></td>
<td nowrap class="label"><div align="right">Servidor de Aplicação:</div></td>
<td nowrap><div align="left" name="frm_te_serv_cacic" id="frm_te_serv_cacic"></div></td>
</tr>

<tr>
<td nowrap class="label">&nbsp;</td>
<td nowrap class="dado_med_sem_fundo">&nbsp;</td>
<td nowrap class="label">&nbsp;</td>
<td nowrap>&nbsp;</td>
</tr>

<tr>
<td nowrap class="label"><div align="right">Servidor de Atualizações:</div></td>
<td nowrap><div align="left" name="frm_te_serv_updates" id="frm_te_serv_updates"></div></td>
<td nowrap class="label"><div align="right">Porta:</div></td>
<td nowrap><div align="left" name="frm_nu_porta_serv_updates" id="frm_nu_porta_serv_updates"></div></td>
</tr>

<tr>
<td nowrap>&nbsp;</td>
<td nowrap class="dado_med_sem_fundo">&nbsp;</td>
<td nowrap class="label"><div align="right">Limite FTP:</div></td>
<td nowrap><div align="left" name="frm_nu_limite_ftp" id="frm_nu_limite_ftp"></div></td>
</tr>

<tr>
<td nowrap>&nbsp;</td>
<td nowrap class="dado_med_sem_fundo">&nbsp;</td>
<td nowrap class="label"><div align="right">Caminho no Servidor de Atualizações:</div></td>
<td nowrap><div align="left" name="frm_te_path_serv_updates" id="frm_te_path_serv_updates"></div></td>
</tr>

<tr>
<td nowrap>&nbsp;</td>
<td nowrap class="dado_med_sem_fundo">&nbsp;</td>
<td nowrap class="label"><div align="right">Usuário FTP (para acesso do Gerente WEB):</div></td>
<td nowrap><div align="left" name="frm_nm_usuario_login_serv_updates_gerente" id="frm_nm_usuario_login_serv_updates_gerente"></div></td>
</tr>

<tr>
<td nowrap>&nbsp;</td>
<td nowrap class="dado_med_sem_fundo">&nbsp;</td>
<td nowrap class="label"><div align="right">Senha:</div></td>
<td nowrap><div align="left" name="frm_te_senha_login_serv_updates_gerente" id="frm_te_senha_login_serv_updates_gerente"></div></td>
</tr>

<tr>
<td nowrap>&nbsp;</td>
<td nowrap class="dado_med_sem_fundo">&nbsp;</td>
<td nowrap class="label"><div align="right">Usuário FTP (para acesso dos Agentes):</div></td>
<td nowrap><div align="left" name="frm_nm_usuario_login_serv_updates" id="frm_nm_usuario_login_serv_updates"></div></td>
</tr>

<tr>
<td nowrap>&nbsp;</td>
<td nowrap class="dado_med_sem_fundo">&nbsp;</td>
<td nowrap class="label"><div align="right">Senha:</div></td>
<td nowrap><div align="left" name="frm_te_senha_login_serv_updates" id="frm_te_senha_login_serv_updates"></div></td>
</tr>
</table>

</p>
</body>
</html>
