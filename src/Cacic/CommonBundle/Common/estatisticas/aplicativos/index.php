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
 * verifica se houve login e tamb�m regras para outras verifica��es (ex: permiss�es do usu�rio)!
 */
if(!isset($_SESSION['id_usuario'])) 
  die('Acesso negado (Access denied)!');
else { // Inserir regras para outras verifica��es (ex: permiss�es do usu�rio)!
}

require_once('../../include/library.php');
AntiSpy('1,2,3,4'); // Permitido somente a estes cs_nivel_administracao...

conecta_bd_cacic();
$linha = '<tr bgcolor="#e7e7e7"> 
			  <td height="1"></td>
			  <td height="1"></td>
         </tr>';


?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title><?=$oTranslator->_('Estatisticas de sistemas monitorados');?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<? require_once('../../include/selecao_listbox.js');  ?>
<?
// Essa vari�vel � usada pelo arquivo de include selecao_redes_inc.php e inicio_relatorios_inc.php.
$id_acao = 'cs_coleta_monitorado';
$cs_situacao = 'S';

?>
<link rel="stylesheet"   type="text/css" href="../../include/cacic.css">
<script LANGUAGE="JavaScript">
<!-- Begin
function open_window(theURL,winName,features) { 
    window.open(theURL,winName,features);
}
//-->
</script>
</head>
<body bgcolor="#FFFFFF" background="../../imgs/linha_v.gif" leftmargin="2" topmargin="10" marginwidth="0" marginheight="0">
<script language="JavaScript" type="text/javascript" src="../../include/cacic.js"></script>
<br>
<table width="90%" border="0" align="center">
  <tr> 
    <td class="cabecalho"><?=$oTranslator->_('Estatisticas de sistemas monitorados');?></td>
  </tr>
  <tr> 
    <td class="descricao">
      <?=$oTranslator->_('Estatisticas de sistemas monitorados - informe');?>
    </td>
  </tr>
  <tr> 
    <td>
				</td>
  </tr>
</table>
<form action="../aplicativos/aplicativos.php" target="_blank" method="post" ENCTYPE="multipart/form-data" name="forma"   onsubmit="return valida_form()">
<table width="90%" border="0" align="center" cellpadding="0">
              <tr>
            <td class="label">
              <?=$oTranslator->_('Saida Detalhada:');?>            </td>
          </tr>
          <tr> 
            <td height="1" bgcolor="#333333"></td>
          </tr>
    <tr>
      <td valign="top">
        <tr>
          <td><label>
            <input type="radio" name="rdCsSaidaDetalhada" value="S" id="rdCsSaidaDetalhada_0">
            Sim</label>&nbsp;&nbsp;
          <label>
            <input type="radio" name="rdCsSaidaDetalhada" value="N" id="rdCsSaidaDetalhada_1" checked>
            N�o</label></td>
        </tr>

      </td>
    </tr>

  <tr> 
      
    <td valign="top">&nbsp;</td>
    </tr>
    <tr> 
      <td valign="top"><table width="100%" border="0" align="center" cellpadding="0" cellspacing="1">
          <tr>
            <td class="label">
              <?=$oTranslator->_('Selecione os sistemas monitorados que deseja exibir:');?>            </td>
          </tr>
          <tr> 
            <td height="1" bgcolor="#333333"></td>
          </tr>
          <tr> 
            <td height="1"><table width="50%" border="0" align="left" cellpadding="0" cellspacing="0">
                <tr> 
                  <td>&nbsp;&nbsp;</td>
                  <td class="cabecalho_tabela"><div align="left"><?=$oTranslator->_('Disponiveis:');?></div></td>
                  <td>&nbsp;&nbsp;</td>
                  <td width="40">&nbsp;</td>
                  <td nowrap>&nbsp;&nbsp;</td>
                  <td nowrap class="cabecalho_tabela"><?=$oTranslator->_('Selecionados:');?></td>
                  <td nowrap>&nbsp;&nbsp;</td>
                </tr>
                <tr> 
                  <td>&nbsp;</td>
                  <td> <div align="left"> 
                      <select multiple name="list5[]" size="10" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >
                        <? 	$where_usuario_comum = '';	
							if ($_SESSION["cs_nivel_administracao"] == 0)
								{
								$where_usuario_comum = ' AND in_disponibiliza_info_usuario_comum="S" ';
								}
							$query = "SELECT id_aplicativo, nm_aplicativo
									  FROM perfis_aplicativos_monitorados
									  WHERE (nm_aplicativo NOT LIKE '%#DESATIVADO#%' and (trim(cs_car_inst_w9x) <> '0' or
									  		trim(cs_car_ver_w9x)  <> '0' or
									  		trim(cs_car_inst_wnt) <> '0' or											
									  		trim(cs_car_ver_wnt)  <> '0' or
									  		trim(cs_ide_licenca)  <> '0')) ".$where_usuario_comum." 
									  ORDER BY nm_aplicativo";

						$result_aplicativos_selecionados = mysql_query($query) or die($oTranslator->_('Ocorreu um erro no acesso a tabela %1 ou sua sessao expirou!',array('descricao_hardware')));
						/* Agora monto os itens do combo de hardwares selecionadas. */ 
       while($campos_aplicativos_selecionados=mysql_fetch_array($result_aplicativos_selecionados)) 	
	   		{
			   echo '<option value=' . $campos_aplicativos_selecionados['id_aplicativo'] . '>' . capa_string($campos_aplicativos_selecionados['nm_aplicativo'],40)  . '</option>';
			}  ?>
                      </select>
                      </div></td>
                  <td>&nbsp;</td>
                  <td width="40"> <div align="center"> 
                      <input type="button" value="   &gt;   " onClick="move(this.form.elements['list5[]'],this.form.elements['list9[]'])" name="B132">
                      <br>
                      <br>
                      <input type="button" value="   &lt;   " onClick="move(this.form.elements['list9[]'],this.form.elements['list5[]'])" name="B232">
                    </div></td>
                  <td>&nbsp;</td>
                  <td><select multiple name="list9[]" size="10" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >
                    </select></td>
                  <td>&nbsp;</td>
                </tr>
              </table></td>
          </tr>
          <tr> 
            <td class="ajuda">
              (<?=$oTranslator->_('Dica: use SHIFT or CTRL para selecionar multiplos itens');?>)            </td>
          </tr>
        </table></td>
    </tr>
    <tr> 
      <td valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="1">
        <tr> 
          <td height="1" bgcolor="#333333"></td>
        </tr>
      </table></td>
    </tr>
    <tr> 
      <td valign="top">&nbsp;</td>
    </tr>	
    <tr> 
      <td valign="top"> 
        <?  $v_require = '../../include/' .($_SESSION['cs_nivel_administracao']<>1 && $_SESSION['cs_nivel_administracao']<>2?'selecao_redes_inc.php':'selecao_locais_inc.php');
		require_once($v_require);		
		?>      </td>
    </tr>
    <tr> 
      <td valign="top">&nbsp;</td>
    </tr>
    <tr> 
      <td valign="top"> 
        <?  require_once('../../include/selecao_so_inc.php');		?>      </td>
    </tr>
    <tr>
      <td valign="top">&nbsp;</td>
    </tr>

    <tr> 
      <td valign="top"> <br> <br> <table width="100%" border="0" cellpadding="0" cellspacing="1">
          <tr> 
            <td height="1" bgcolor="#333333"></td>
          </tr>
          <tr> 
            <td>&nbsp;</td>
          </tr>
          <tr> 
            <td> <div align="center"> 
				<?
                //<input name="submit" type="submit" value="        Gerar Relat&oacute;rio      " onClick="SelectAll(this.form.elements['list2[]']), SelectAll(this.form.elements['list4[]']), SelectAll(this.form.elements['list9[]']), SelectAll(this.form.elements['list8[]'])">
				?>
                <input name="submit" type="submit" value="<?=$oTranslator->_('Gerar relatorio');?>" onClick="<? echo ($_SESSION['cs_nivel_administracao']<>1 && $_SESSION['cs_nivel_administracao']<>2?"SelectAll(this.form.elements['list2[]'])":"SelectAll(this.form.elements['list12[]'])")?>, SelectAll(this.form.elements['list4[]']), SelectAll(this.form.elements['list9[]']),ChecaTodasAsRedes()">				
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
