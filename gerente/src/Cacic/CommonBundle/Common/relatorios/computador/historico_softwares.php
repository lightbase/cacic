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
// Arquivo de hist�rico de rede, hardware e patrim�nio
include_once("cab.html");
require_once($_SERVER['DOCUMENT_ROOT'] . '/cacic2/include/library.php');
conecta_bd_cacic();

	$query = "SELECT DATE_FORMAT(data,'%d/%m/%Y �s %H:%ih') as Data, 
                         nm_software_inventariado as Software,
                         sih.ind_acao as A��o 
                  FROM   softwares_inventariados si, historico_softwares_inventariados_estacoes sih
		  WHERE sih.te_node_address = '". $_POST['te_node_address'] ."' 
                                AND sih.id_so = '". $_POST['id_so'] ."'  
                                AND sih.id_software_inventariado = si.id_software_inventariado
		  ORDER BY sih.data DESC";
	$result = mysql_query($query) or die ('Erro no select:'. mysql_error().' ou sua sess�o expirou!');
        
        $consulta_estacao = "SELECT te_dominio_windows, te_nome_computador, te_workgroup
                             FROM   computadores
		             WHERE  te_node_address = '". $_POST['te_node_address'] ."' 
                             AND id_so = '". $_POST['id_so'] ."'";
        $resultado_estacao= mysql_query($consulta_estacao) or die ('Erro no select:'. mysql_error().' ou sua sess�o expirou!');

        echo "<p><font size=2 face=verdana>Esta��o:<b> ". mysql_result($resultado_estacao, 0, 'te_nome_computador') . "</b>";
        echo "<br>Grupo de Trabalho (�ltimo Login):<b> ". mysql_result($resultado_estacao, 0, 'te_workgroup');
        echo "</b> (". mysql_result($resultado_estacao, 0, 'te_dominio_windows') . ")</font>"

?>
<br>
<? 
$cor = 0;
$num_registro = 1;

$fields=mysql_num_fields($result);
if (mysql_num_rows($result) > 0) {
	echo '<table cellpadding="1" cellspacing="0" border="1" bordercolor="#999999" bordercolordark="#E1E1E1">
		 <tr bgcolor="#E1E1E1" >
		  <td nowrap align="left">&nbsp;</td>';
	
	for ($i=0; $i < mysql_num_fields($result); $i++) { //Table Header
		if (mysql_field_name($result, $i) <> 'dt_hr_alteracao')
			{
	   		print '<td nowrap align="center"><b>'. mysql_field_name($result, $i) .'<b></td>';
			}
	}
	echo '</tr>';

	while ($row = mysql_fetch_array($result)) { //Table body
		echo '<tr ';
		if ($cor) { echo 'bgcolor="#E1E1E1"'; } 
		echo '>';
		echo '<td nowrap align="left">&nbsp;' . $num_registro . '&nbsp;</td>';
	
		/* for ($i=0; $i < $fields; $i++) {
			if (mysql_field_name($result, $i)<>'dt_hr_alteracao')
				{
                                        echo 
				echo '<td nowrap align="left"><font size="1" face="Verdana, Arial">' . $row[$i] .'&nbsp;</td>'; 
				}
		}*/
                $data=$row['Data'];
                $soft=$row['Software'];
                $acao=$row['A��o'];

                if ($acao == 1){
                $acao = "Adicionado";
                $color="Green";
                }else{
                $acao = "Removido";
                $color="Red";
                }
                
                echo "<td>&nbsp;$data&nbsp;</td><td>$soft</td><td><font color=$color>&nbsp;$acao&nbsp;</td>";
		$cor=!$cor;
		$num_registro++;
		echo '</tr>';
	}
	echo '</table>';
}
else {
	echo '</table>';
	echo mensagem('N�o foi encontrado nenhum registro');
}
include_once("rod.html");
?>
</body>
</html>
