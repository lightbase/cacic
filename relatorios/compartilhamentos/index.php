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
 
Este relatorio foi modificado do original index.php da pasta antivirus do CACIC por Emerson Pellis
Com o objetivo de desenvolver um relatorio de pastas compartilhadas.

*/
		// Essa variável é usada pelo arquivo de include selecao_redes_inc.php e inicio_relatorios_inc.php.
	$id_acao = 'cs_coleta_software';
  	require_once('../../include/inicio_relatorios_inc.php'); 
?>
<table width="90%" border="0" align="center">
  <tr> 
    <td class="cabecalho">Relatório de Pastas Compartilhadas</td>
  </tr>
  <tr> 
    <td class="descricao">Este 
      relat&oacute;rio exibe os compartilhamentos dos microcomputadores
      das redes selecionadas.</td>
  </tr>
  <tr> 
    <td>
				</td>
  </tr>
</table>
<form action="rel_compartilhamentos.php" target="_blank" method="post" ENCTYPE="multipart/form-data" name="forma" onSubmit="valida_form()">
  <table width="90%" border="0" align="center" cellpadding="5" cellspacing="1">
    <tr> 
      <td valign="top"> 
        <? /* require_once($_SERVER['DOCUMENT_ROOT'] . '/cacic2/include/selecao_redes_inc.php'); */
		$cs_situacao = 'T';	
		$v_require = '../../include/' .($_SESSION['cs_nivel_administracao']<>1 && $_SESSION['cs_nivel_administracao']<>2?'selecao_redes_inc.php':'selecao_locais_inc.php');
		require_once($v_require);		
		?>
      </td>
    </tr>

    <tr> 
      <td valign="top">&nbsp;</td>
    </tr>
    <tr> 
      <td valign="top"> 
        <?  require_once('../../include/selecao_so_inc.php');		?>
      </td>
    </tr>
    <tr> 
      <td valign="top">&nbsp;</td>
    </tr>
    <tr> 
      <td valign="top"><br> <table width="100%" border="0" cellpadding="0" cellspacing="1">
          <tr> 
            <td height="1" bgcolor="#333333"></td>
          </tr>
          <tr> 
            <td>&nbsp;</td>
          </tr>
          <tr> 
            <td> <div align="center"> 
				<?
                //<input name="submit" type="submit" id="submit" onClick="SelectAll(this.form.elements['list2[]']), SelectAll(this.form.elements['list4[]']), SelectAll(this.form.elements['list6[]']), SelectAll(this.form.elements['list8[]'])" value="     Gerar Relat&oacute;rio     ">
                //<input name="submit" type="submit" id="submit" onClick="SelectAll(this.form.elements['list2[]']), SelectAll(this.form.elements['list4[]']), SelectAll(this.form.elements['list6[]'])" value="     Gerar Relat&oacute;rio     ">
				?>
                <!-- <input name="submit" type="submit" id="submit" onClick="SelectAll(SelectAll(this.form.elements['list4[]']), SelectAll(this.form.elements['list6[]'])" value="     Gerar Relat&oacute;rio     "> -->
                <input name="submit" type="submit" id="submit" onClick="ChecaTodasAsRedes(),<? echo ($_SESSION['cs_nivel_administracao']<>1 && 
																				 $_SESSION['cs_nivel_administracao']<>2?"SelectAll(this.form.elements['list2[]'])":"SelectAll(this.form.elements['list12[]'])")?>, 
																		SelectAll(this.form.elements['list4[]'])" value="     Gerar Relat&oacute;rio     ">
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
