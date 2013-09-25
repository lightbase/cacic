<?php
session_start();
/*
 * verifica se houve login e também as permissões de usuário
 */
if(!isset($_SESSION['id_usuario'])) 
  die('Acesso negado (Access denied)!');
else { // Inserir regras para verificar permissões do usuário!
}
require_once "../include/library.php";
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>CACIC - Configurador Automático e Coletor de Informações Computacionais</title>
</head>
<body  bgcolor="#FFFFFF" text="#000000" topmargin="10" leftmargin="2">
  <tr> 
    <td valign="top" align="center"> 
        <div align="center"> 
          
        <table cellspacing="0" cellpadding="0" border="2" width="85%">
          <tr> 
              
            
          <td bgcolor="#000064"> 
            <table width="100%" border="0" cellpadding="0" cellspacing="0">
              <tr>
                  <td><font face="Arial" color="#ffffff"><strong><small><font face="Verdana, Arial, Helvetica, sans-serif" size="2">&nbsp;
                  <?php echo $oTranslator->_('Gerado por');?> CACIC - <?php echo $tool;?>
                    </font></small></strong></font></td>
                <td>
                  <div align="right"><img src="../imgs/close.gif" width="16" height="14" onClick="window.close()"></div>
                </td>
              </tr>
            </table>
              </td>
            </tr>
            <tr align="middle"> 
              <td bordercolor="#c0c0c0" bgcolor="#c0c0c0"> 
                <div align="center">
                <table width="100%" border="0" cellpadding="0" cellspacing="0">
                  <tr>
                     
                  <td bgcolor="#000000">
                      <p><font face="Verdana, Arial, Helvetica, sans-serif" color="#FFFFFF" size="2"> 
                        $ 
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

if ($tool == 'ping')  
	{
		echo 'ping -c 2' . $ip . '<br>';
		$result = nl2br(`ping -c 2 $ip`);
	}
		else if ($tool == 'traceroute')  
	{
		echo 'traceroute ' . $ip . '<br>';
		$result = nl2br(`traceroute $ip`);
	}
		else if ($tool == 'nmap')  
	{
		echo 'nmap ' . $ip . '<br>';
		$result = nl2br(`nmap $ip`);
	}
	echo $result; 
?>
	<br>
	$</font> </td>
</tr>
                </table>
              </div>
              </td>
            </tr>
            <tr align="middle"> 
              
            <td align="left" bgcolor="#c0c0c0"><font color="#000032" size="1" face="Verdana, Arial, Helvetica, sans-serif"><small><small>&nbsp;<?php echo $oTranslator->_('Gerado em') . ' ' . date("d/m/Y - H:i"); ?></small></small></font></td>
            </tr>
          </table>
        </div>
    </td>
  </tr>
<p align="center"><font face="Verdana, Arial, Helvetica, sans-serif" size="2"> 
<br>
      <div align="center">        
  <input type="submit" name="Submit" value="    Fechar    " onClick="window.close()">
      </div>
  </font>
</body>