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
 */
session_start();
/*
 * verifica se houve login e também regras para outras verificações (ex: permissões do usuário)!
 */
if(!isset($_SESSION['id_usuario'])) 
  die('Acesso negado!');
else { // Inserir regras para outras verificações (ex: permissões do usuário)!
}

?>
<html>
<head>
<title>Cacic - Configurador Autom&aacute;tico e Coletor de Informa&ccedil;&otilde;es Computacionais</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"></head>
<body bgcolor="#F0F0F0" background="file:///K|/prontonewdev/imgs/bkg_padrao.gif">
   <CENTER>
  <p>&nbsp;</p>
  <p>&nbsp;</p>
  <table border="1" cellpadding="0" cellspacing="0" align="center">
    <tr> 
      <td bgcolor="#000064" align="center"> <font color="#ffffff" size="2" face="Verdana, Arial, Helvetica, sans-serif">Cacic</font></td>
    </tr>
    <tr align="center"> 
      <td bgcolor="#c0c0c0" bordercolor="#c0c0c0"> <div align="center"> 
          <table width="75%" border="0" cellpadding="2" cellspacing="2">
            <tr> 
              <td nowrap><div align="center"><font color="#FF0000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Sess&atilde;o 
                  encerrada.</font></div></td>
            </tr>
            <tr> 
              <td nowrap><div align="center"> <B><a href="index.php" target="_parent"><img src="imgs/mais.gif" alt="Clique aqui para logar novamente" width="12" height="12" border="0"></a></B></div></td>
            </tr>
          </table>
        </div></td>
    </tr>
    <tr align="center"> 
      <td align="left" bgcolor="#c0c0c0"><font color="#000032" face="Verdana, Arial, Helvetica, sans-serif" size="2"> 
        <? 
		include 'include/library.php';
		//echo HoraParaRodape(); 
		?>
		</font></td>
    </tr>
  </table>
</CENTER>
</body>
</html>