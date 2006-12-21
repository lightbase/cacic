<?
require $_SERVER['DOCUMENT_ROOT'] . '/cacic2/verificar.php';
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
include_once("cab.html");
require_once('../../include/library.php');
conecta_bd_cacic();

// Se nao existir a variavel encontrar ou se eh te_nome_computador eh setado:
// encontrar (necessario para busca) e a opção selecionada eh marcada
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
	$result = mysql_query($query) or die ('Erro no select');
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
          <td>Endereço IP</td>
          <td>Endereço MAC</td>
          <td>Último Acesso</td>
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
	echo mensagem('Não foi encontrado nenhum registro');
}

include_once("rod.html");
?>
