<?php
session_start();
/*
 * verifica se houve login e tamb�m as permiss�es de usu�rio
 */
if(!isset($_SESSION['id_usuario'])) 
  die('Acesso negado (Access denied)!');
else { // Inserir regras para verificar permiss�es do usu�rio!
}
require_once "../include/library.php";
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>CACIC - Configurador Autom�tico e Coletor de Informa��es Computacionais</title>
</head>
<body  bgcolor="#FFFFFF" text="#000000" topmargin="10" leftmargin="2">
  <tr> 
    <td valign="top" align="center"> 
        <div align="center"> 
          
        <table cellspacing="0" cellpadding="0" border="2" width="90%">
          <tr> 
              
            
          <td bgcolor="#000064"> 
            <table width="100%" border="0" cellpadding="0" cellspacing="0">
              <tr>
                  <td><font face="Arial" color="#ffffff"><strong><small><font face="Verdana, Arial, Helvetica, sans-serif" size="2">&nbsp;
                  <?=$oTranslator->_('Gerado por');?> CACIC - <?=$tool;?>
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