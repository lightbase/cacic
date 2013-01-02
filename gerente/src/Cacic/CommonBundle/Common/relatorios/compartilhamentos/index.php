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
 
Este relatorio foi modificado do original index.php da pasta antivirus do CACIC por Emerson Pellis
Com o objetivo de desenvolver um relatorio de pastas compartilhadas.

*/
		// Essa vari�vel � usada pelo arquivo de include selecao_redes_inc.php e inicio_relatorios_inc.php.
	$id_acao = 'cs_coleta_software';
  	require_once('../../include/inicio_relatorios_inc.php'); 
?>
<table width="90%" border="0" align="center">
  <tr> 
    <td class="cabecalho"><?=$oTranslator->_('Relatorio de pastas compartilhadas');?></td>
  </tr>
  <tr> 
    <td class="descricao">
      <?=$oTranslator->_('Relatorio que exibe os compartilhamentos nos microcomputadores das redes selecionadas');?>
    </td>
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
                <input name="submit" type="submit" id="submit" onClick="ChecaTodasAsRedes(),<? echo ($_SESSION['cs_nivel_administracao']<>1 && 
																				 $_SESSION['cs_nivel_administracao']<>2?"SelectAll(this.form.elements['list2[]'])":"SelectAll(this.form.elements['list12[]'])")?>, 
																		SelectAll(this.form.elements['list4[]'])" value="<?=$oTranslator->_('Gerar relatorio');?>">
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
