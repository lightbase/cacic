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
include_once("cab.html");
require_once('../../include/library.php');
conecta_bd_cacic();

// Se nao existir a variavel encontrar ou se eh te_nome_computador eh setado:
// encontrar (necessario para busca) e a op��o selecionada eh marcada
if ((!$encontrar) || ($encontrar == "te_nome_computador")){
        $encontrar="te_nome_computador";
        $marcado0="checked";
}else{
        $encontrar="te_node_address";
        $marcado1="checked";

}
        
// Quando clicamos no botao ele eh submitado.
echo "<form action=#>
      <table border=\"0\" cellspacing=1 cellpadding=0>
        <tr>
                <td>Encontar:</td>
                <td><input onClick=\"javascript:document.forms[0].submit()\" type=\"radio\" name=encontrar $marcado0 value=te_nome_computador>
                Nomes duplicados</td>
                <td><input onClick=\"javascript:document.forms[0].submit()\" type=\"radio\" name=encontrar $marcado1 value=te_node_address>
                Macs duplicados</td>
        </tr>
      </table>
      </form>
      <br>";
      
$nomes_duplicados = true;


if ($nomes_duplicados) {
	$query = " SELECT a.te_nome_computador, a.id_ip_rede, b.te_desc_so, a.te_dominio_windows, a.te_ip, 
			a.te_node_address, a.dt_hr_ult_acesso
				FROM computadores a, so b
				WHERE a.id_so = b.id_so
				GROUP  BY $encontrar
				HAVING Count(*) > 1  
				ORDER  BY te_nome_computador";

//	echo $query;
	$result = mysql_query($query) or die ('Erro no select ou sua sess�o expirou!');
	$tipo_historico = 'encontrados';
}

$cor = 0;
$num_registro = 1;

//$fields=mysql_num_fields($result);


if (mysql_num_rows($result) > 0) {
        
        mysql_data_seek($result, 0);
        //Retorna todas as linhas da consulta conforme num_dias
        echo '<table  width="80%" cellpadding="2" cellspacing="0" border="1" bordercolor="#999999" bordercolordark="#E1E1E1">
         <tr bgcolor="#E1E1E1" >
          <td nowrap align="left"><font size="1" face="Verdana, Arial">&nbsp;</font></td>
          <td>Computador</td>
          <td>Endere�o IP</td>
          <td>Endere�o MAC</td>
          <td>�ltimo Acesso</td>
         </tr>
          ';

        $cor = 0;
        $num_registro = 1;

        while ($linha = mysql_fetch_array($result))
        {
                $NOME_COMPUTADOR = $linha['te_nome_computador'];
                $U_LOGADO = $linha['te_dominio_windows'];
                $MAC = $linha['te_node_address'];
                $IP =  $linha['te_ip'];
                $U_ACESSO = $linha['dt_hr_ult_acesso'];

                if ($encontrar == "te_nome_computador"){
                        $consultatipo="nome";
                        $consultastring=$NOME_COMPUTADOR;
                }else{
                        $consultatipo="te_node_address";
                        $consultastring=$MAC;
                }
        
                echo "<tr ";
	            if ($cor) { echo 'bgcolor="#E1E1E1"'; }
	            echo ">
                         <td>$num_registro</td>
                         <td>
                         <a href=computadoresconsulta.php?tipo_consulta=$consultatipo&string_consulta=$consultastring target=_blank>
                         <B>$NOME_COMPUTADOR</B></a>
                         </td>
                         <td>$IP</td>
                         <td>$MAC</td>
                         <td>$U_ACESSO</td>
                </tr>";
                $cor=!$cor;
            	$num_registro++;
        }
        echo "</table><br>";
}
else {
	echo '</table>';
	echo mensagem('N�o foi encontrado nenhum registro');
}

include_once("rod.html");
?>
