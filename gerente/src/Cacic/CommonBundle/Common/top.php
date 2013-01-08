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
session_start();
define('CACIC',1);
require_once('include/config.php');
require_once('include/define.php');
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<link rel="stylesheet"   type="text/css" href="include/css/cacic.css">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Topo</title></head>
<body bgcolor=<?php echo ($_SESSION['id_default_body_bgcolor']<>''?$_SESSION['id_default_body_bgcolor']:'#EBEBEB'); ?> leftmargin="1" topmargin="0">	
<table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td><table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr> 
          <td width="5"></td>
          <td align="center"><strong><font size="5" face="Verdana, Arial, Helvetica, sans-serif"><img src="imgs/cacic_logo.png" width="50" height="50"></font></strong></td>
          <td><table width="60%" border="0" align="left" cellpadding="0" cellspacing="0">
              <tr>
                <td><img src="imgs/cacic_tit.gif" height="20"></td>
              </tr>
              <tr>
                <td><div align="left"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">v.<?php echo CACIC_VERSION;?></font></div></td>
              </tr>
            </table>
            
          </td>
          <td>
          	<table border="0" align="right" cellpadding="0" cellspacing="0">
              <tr><td><img src="imgs/cacic_ext.gif" align="bottom"></td></tr>
              <tr><td><div id="TopTitle" class="topTitle">
				<?php 
				if ($_SESSION['nm_local'])
					echo $_SESSION['nm_local']; 					
				else 
					{
					require_once('include/library.php');					
					$arrConfiguracoesPadrao = getValores('configuracoes_padrao', 'nm_organizacao'); 
					echo $arrConfiguracoesPadrao['nm_organizacao'];
					}				
				?>
				</div></td></tr>
              <tr><td><div id="TopSubTitle" class="topSubTitle">
				<?php 
				echo $_SESSION['nm_usuario'] . ($_SESSION['te_grupo_usuarios'] ? ' (' . $_SESSION['te_grupo_usuarios'].')' : ''); 					
				?>
				</div></td></tr>
              
            </table>
          </td>
        </tr>
      </table></td>
  </tr>
  <tr>
    <td height="2" background="imgs/linha_h.gif"></td>
  </tr>
</table>
<p> </p>
<p> </p>
</body>
</html>
