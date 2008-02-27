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

		// Essa variável é usada pelo arquivo de include selecao_redes_inc.php e inicio_relatorios_inc.php.
		$id_acao = 'cs_coleta_hardware';
  require_once('../../include/inicio_relatorios_inc.php'); 
?>

<table width="90%" border="0" align="center">
  <tr> 
    <td class="cabecalho">Relat&oacute;rio 
      de Levantamento de Hardware / Software</td>
  </tr>
  <tr> 
    <td class="descricao">Este relat&oacute;rio 
      exibe a configura&ccedil;&atilde;o da maquina atraves da escolha de uma 
      palavra. Trazendo as informacoes tanto do hardware como do software .</td>
  </tr>
  <tr> 
    <td>
				</td>
  </tr>
</table>
<form action="pesquisa_avancada.php" target="_blank" method="post" ENCTYPE="multipart/form-data" name="forma"   onsubmit="return valida_form()">
  <table width="90%" border="0" align="center" cellpadding="5" cellspacing="1">
    <tr> 
      <td valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="1">
          <tr> 
            <td class="label">Selecione qual dos itens abaixo deseja relacionar em sua consulta : 
          </tr>
          <tr> 
            <td height="1" bgcolor="#333333"></td>
          </tr>
 	  <tr>
            <td><input type="checkbox" name="te_desc_so" value="S"> Sistema Operacional </td>
          </tr> 
 	  <tr>
            <td><input type="checkbox" name="te_placa_video_desc" value="S"> Placa de Video </td>
          </tr> 
	  <tr>
            <td><input type="checkbox" name="te_placa_rede_desc" value="S"> Placa de Rede </td>
          </tr> 
	  <tr>
            <td><input type="checkbox" name="te_cpu_desc" value="S"> CPU </td>
          </tr> 
	  <tr>
            <td><input type="checkbox" name="te_placa_mae_desc" value="S"> Placa Mãe </td>
          </tr> 
	  <tr>
            <td><input type="checkbox" name="te_modem_desc" value="S"> Modem </td>
          </tr> 
	  
          <tr> 
            <td height="1" bgcolor="#333333"></td>
          </tr>
	         
	  <td width="165" align="left">Tamanho da Memória
	    <select name="qt_mem_ram" size="1" >
		  <option value=""></option>
		  <option value="32">Até 32 MBytes</option>
		  <option value="64">64 MBytes</option>
		  <option value="128">128 MBytes</option>
		  <option value="256">256 MBytes</option>
		  <option value="512">512 MBytes</option>
		  <option value="1024">Maior ou igual a 1024 MBytes</option>
	    </select>
          </td>
          	  
	  <tr> 
            <td>&nbsp;</td>
          </tr>
	  
	  <tr> 
            <td height="1" bgcolor="#333333"></td>
          </tr>
          
	  <tr>
	   <td> Palavra Chave: <input type="text" name="palavra_chave" size="50" > (nao utilize nenhum caracter especial na palavra-chave)</tr>
          </tr>
	  
          <tr> 
            <td height="1" bgcolor="#333333"></td>
          </tr>
	  
	  <tr> 
            <td>&nbsp;</td>
          </tr>
	  
	  <tr>
            <td><input type="checkbox" name="te_software" value="S"> <b>Software - Nome do Software a ser pesquisado : </b>
                    <input type="input" name="te_software_valor"> </td>  
	  </tr> 
	  
          <tr> 
            <td height="1" bgcolor="#333333"></td>
          </tr>
	  <tr> 
            <td>&nbsp;</td>
          </tr>
          <tr> 
            <td> <div align="center"> 
                <input name="submit" type="submit" value="       Gerar Relat&oacute;rio   ">
              </div></td>
    </tr>
  </table>
</form>
</body>
</html>
