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
require_once('include/config.php');
require_once('include/library.php');
?>
<html>
<head>
<title>Configurar Gerente</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<?php
require_once('include/js/selecao_listbox.js');  
?>
<link rel="stylesheet"   type="text/css" href="include/css/cacic.css">
<style type="text/css">
<!--
.style2 {font-size: 9px}
.style6 {color: #000099}
-->
</style>
</head>

<body background="imgs/linha_v.gif" onLoad="SetaCampo('te_notificar_mudanca_hardware');">
<form action="admin/config_gerais_set.php"  method="post" ENCTYPE="multipart/form-data" name="forma" onSubmit="return valida_form();return valida_notificacao_hardware();">
<table width="85%" border="0" align="center">
    <tr> 
    <td class="cabecalho">Sistema CACIC - Documentos e Programas Avulsos</td>
    </tr>
    <tr> 
    <td class="descricao">Os �tens  abaixo referem-se a um kit b�sico de documenta��o e programas para <strong>Instala��o de Agentes Principais do Sistema em MS-Windows ou GNU/Linux</strong>, <strong>Coleta de Dados Patrimoniais atrav�s de M�dulo Avulso</strong> e <strong>Cliente Espec�fico</strong> para uso com o m�dulo <strong>srCACIC - Suporte Remoto Seguro</strong>. A opera��o de "download" se d� clicando-se sobre os referidos objetos. </td>
    </tr>
</table>
    
<br><br>

<table width="80%" border="0" align="center" cellpadding="0" cellspacing="1">
    <tr><td bgcolor="#CCCCCC" class="label" colspan="2"></td></tr>
    <tr><td height="1" bgcolor="#333333" colspan="2"></td></tr>
    <tr><td bgcolor="#CCCCCC" class="label" colspan="2"><br><a href="tribo/docs/Cacic-Manual-Instalacao-Utilizacao-v2601.pdf" target="_self">Manual para Instala��o e Utiliza��o - vers�o 2.6.0-Beta-1<span class="style6"> (2.4MB)</span></a></td></tr>    
    <tr><td height="17" colspan="2"></td></tr>
    <tr><td height="1" bgcolor="#333333" colspan="2"></td></tr>
    <?php
    if(file_exists(CACIC_PATH . CACIC_PATH_RELATIVO_DOWNLOADS . 'versions_and_hashes.ini') and is_readable(CACIC_PATH . CACIC_PATH_RELATIVO_DOWNLOADS . 'versions_and_hashes.ini'))		
        $arrVersionsIni	= parse_ini_file(CACIC_PATH . CACIC_PATH_RELATIVO_DOWNLOADS . 'versions_and_hashes.ini');	
    ?>		
    <tr><td class="label" colspan="2"><br><a href="<?php echo CACIC_PATH_RELATIVO_DOWNLOADS . 'installcacic.exe';?>">InstallCACIC -  Instalador para plataformas MS-Windows</a></td></tr>
    <tr><td class="descricao" width="20" align="right">Versao:</td><td class="cabecalho_tabela" align="left"><?php echo $arrVersionsIni['installcacic.exe_VER'];?></td></tr>    
    <tr><td class="descricao" 			 align="right">Tamanho:</td><td class="cabecalho_tabela"> <?php echo $arrVersionsIni['installcacic.exe_SIZE'];?>KB</td></tr>    
    <tr><td class="descricao" 			 align="right">Hash:</td><td class="cabecalho_tabela"> <?php echo $arrVersionsIni['installcacic.exe_HASH'];?></td></tr>            
    <tr><td class="descricao" 			 align="right">Data:</td><td class="cabecalho_tabela"> <?php echo $arrVersionsIni['installcacic.exe_DATE'];?></td></tr>                

    <tr><td height="17" colspan="2"></td></tr>
    <tr><td height="1" bgcolor="#333333" colspan="2"></td></tr>
    
    <tr><td height="17" colspan="2"></td></tr>
    <tr><td class="label" colspan="2"><a href="<?php echo CACIC_PATH_RELATIVO_DOWNLOADS . 'mapacacic.exe';?>">MapaCACIC - M�dulo Avulso para Coleta de Informa��es Patrimoniais</a></td></tr>
    <tr><td class="descricao" align="right">Versao:</td><td class="cabecalho_tabela"> <?php echo $arrVersionsIni['mapacacic.exe_VER'];?></td></tr>    
    <tr><td class="descricao" align="right">Tamanho:</td><td class="cabecalho_tabela"> <?php echo $arrVersionsIni['mapacacic.exe_SIZE'];?>KB</td></tr>    
    <tr><td class="descricao" align="right">Hash:</td><td class="cabecalho_tabela"> <?php echo $arrVersionsIni['mapacacic.exe_HASH'];?></td></tr>            
    <tr><td class="descricao" align="right">Data:</td><td class="cabecalho_tabela"> <?php echo $arrVersionsIni['mapacacic.exe_DATE'];?></td></tr>                

    <tr><td height="17" colspan="2"></td></tr>
    <tr><td height="1" bgcolor="#333333" colspan="2"></td></tr>

    <tr><td class="label" colspan="2"><br><a href="<?php echo CACIC_PATH_RELATIVO_DOWNLOADS . 'srcaciccli.exe';?>">srCACICcli -  Cliente Espec�fico para Suporte Remoto Seguro do Sistema CACIC</a></td></tr>
    <tr><td class="descricao" align="right">Versao:</td><td class="cabecalho_tabela"> <?php echo $arrVersionsIni['srcaciccli.exe_VER'];?></td></tr>    
    <tr><td class="descricao" align="right">Tamanho:</td><td class="cabecalho_tabela"> <?php echo $arrVersionsIni['srcaciccli.exe_SIZE'];?>KB</td></tr>    
    <tr><td class="descricao" align="right">Hash:</td><td class="cabecalho_tabela"> <?php echo $arrVersionsIni['srcaciccli.exe_HASH'];?></td></tr>            
    <tr><td class="descricao" align="right">Data:</td><td class="cabecalho_tabela"> <?php echo $arrVersionsIni['srcaciccli.exe_DATE'];?></td></tr>                
    
    <tr><td height="17" colspan="2"></td></tr>
    <tr><td height="1" bgcolor="#333333" colspan="2"></td></tr>

    <?php

    if (file_exists(CACIC_PATH_RELATIVO_DOWNLOADS . $arrVersionsIni['PyCACIC_Debian'].'_Debian.deb'))
        {
        ?>
        <tr> 
        <td colspan="2">
        <tr> 
        <td class="label"><a href="<?php echo CACIC_PATH . CACIC_PATH_RELATIVO_DOWNLOADS . $arrVersionsIni['PyCACIC_Debian'].'_Debian.deb'; ?>"><?php echo $arrVersionsIni['PyCACIC'];?>_Debian.deb">pyCACIC (DEBIAN) - Instalador em plataforma GNU/Linux - Distros Debian <span class="style2"><span class="style6">(vers�o <?php echo $arrVersionsIni['PyCACIC'] . ' ~ ' .  $arrVersionsIni['PyCACIC_SIZE'];?>KB)</span></span></a> </td>
        </tr>
        <tr> 
        <td height="17"> </td>
        </tr>

        <tr> 
        <td height="1" bgcolor="#333333"></td>
        </tr>
        <tr> 
        <td> </td>
        </tr>
        <?php
        }

    if (file_exists(CACIC_PATH_RELATIVO_DOWNLOADS . $arrVersionsIni['PyCACIC_Debian'].'_Debian.rpm'))
        {
        ?>
        <tr> 
        <td height="17"> </td>
        </tr>
        
        <tr> 
        <td>
        <tr> 
        <td class="label"><a href="<?php echo CACIC_PATH . CACIC_PATH_RELATIVO_DOWNLOADS . $arrVersionsIni['PyCACIC_RedHat'].'_RedHat.rpm';?>_RedHat.rpm">pyCACIC (RedHat) - Instalador plataforma GNU/Linux - Distros RedHat <span class="style2"><span class="style6">(vers�o <?php echo $arrVersionsIni['PyCACIC_RedHat'] . ' ~ ' . $arrVersionsIni['PyCACIC_RedHat_SIZE'];?>KB)</span></span></a> </td>
        </tr>
        <tr> 
        <td height="17"> </td>
        </tr>

        <tr> 
        <td height="1" bgcolor="#333333"></td>
        </tr>

        <tr> 
        <td> </td>
        </tr>
        <?php
        }

    if (file_exists(CACIC_PATH . CACIC_PATH_RELATIVO_DOWNLOADS . $arrVersionsIni['PyCACIC_Generic'].'_Generic.tar.gz'))
        {
        ?>
        <tr> 
        <td height="17"> </td>
        </tr>

        <tr> 
        <td>
        <tr> 
        <td class="label"><a href="<?php echo CACIC_PATH . CACIC_PATH_RELATIVO_DOWNLOADS . $arrVersionsIni['PyCACIC_Generic'];?>_Generic.tar.gz">pyCACIC (Generic) - Instalador em plataforma GNU/Linux - Distros Gen�ricas <span class="style2"><span class="style6">(vers�o <?php echo $arrVersionsIni['PyCACIC_RedHat'] . ' ~ ' . $arrVersionsIni['PyCACIC_RedHat_SIZE'];?>KB)</span></span></a> </td>
        </tr>
        <tr> 
        <td height="17"> </td>
        </tr>

        <tr> 
        <td height="1" bgcolor="#333333"></td>
        </tr>

        <tr> 
        <td> </td>
        </tr>
        <?php
        }
    	?>
    <tr> 
    <td colspan="2"> </td>
    </tr>
</table>
</form>		  
<p> </p>
</body>
</html>
