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
		// Essa vari�vel � usada pelo arquivo de include selecao_redes_inc.php e inicio_relatorios_inc.php.
		$id_acao = 'cs_coleta_hardware';
  require_once('../../include/inicio_relatorios_inc.php'); 
?>
<table width="90%" border="0" align="center">
  <tr> 
    <td class="cabecalho">Relat&oacute;rio de Invent&aacute;rio de Softwares Gen&eacute;ricos</td>
  </tr>
</table>
<form action="rel_softwares_generico.php" target="_blank" method="post" ENCTYPE="multipart/form-data" name="forma"   onsubmit="return valida_form()">
  <table width="90%" border="0" align="center">
    <tr> 
      <td valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="10">
          <tr> 
            <td class="label" colspan=2>Selecione os crit&eacute;rios de pesquisa:</td>
          </tr>
          <tr> 
            <td height="1" bgcolor="#333333" colspan=2></td>
          </tr>
          <tr>
		<td>Tipos de software a exibir:</td>
		<td>
		<select name="filtro_ts" id="select_ts" style="width:450px" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);">
		<option value="licenciados">Mostrar somente licenciados</option>
		<option value="todos">Mostrar todos</option>
		<option value="suspeitos">Mostrar somente suspeitos</option>
		</select>
		</td> 
          </tr>
	  <tr>
		<td>Siglas a pesquisar:<font size=-3><b>(Separar por ';')</b></font></td>
		<td>
		<input name="string_siglas" type="text" id="string_siglas2" style="width:450px" value="">
		</td>
	  </tr>
	  <tr>
		<td>Tipo de relat&oacute;rio:</td>
		<td>
		<select name="filtro_tr" id="select_tr" style="width:450px" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);">
		<option value="estacao">Mostrar por esta&ccedil;&atilde;o</option>
		<option value="software">Mostrar por software</option>
		</select>
		</td>
	  </tr>
        </table></td>
    </tr>
    <tr> 
      <td valign="top"> <table width="100%" border="0" cellpadding="0" cellspacing="1">
          <tr> 
            <td height="1" bgcolor="#333333"></td>
          </tr>
          <tr> 
            <td>&nbsp;</td>
          </tr>
          <tr> 
            <td> <div align="center"> 
                <input name="submit" type="submit" value="        Gerar Relat&oacute;rio      " )">
              </div></td>
          </tr>
          <tr> 
            <td>&nbsp;</td>
          </tr>
        </table></td>
    </tr>
  </table>
</form>
</body>
</html>
