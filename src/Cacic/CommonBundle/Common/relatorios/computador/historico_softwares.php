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
// Arquivo de histórico de rede, hardware e patrimônio
include_once("cab.html");
require_once($_SERVER['DOCUMENT_ROOT'] . '/cacic2/include/library.php');
conecta_bd_cacic();

	$query = "SELECT DATE_FORMAT(data,'%d/%m/%Y às %H:%ih') as Data, 
                         nm_software_inventariado as Software,
                         sih.ind_acao as Ação 
                  FROM   softwares_inventariados si, historico_softwares_inventariados_estacoes sih
		  WHERE sih.id_computador = ". $_POST['id_computador'] ." AND 
		  		sih.id_software_inventariado = si.id_software_inventariado
		  ORDER BY sih.data DESC";
	$result = mysql_query($query) or die ('Erro no select:'. mysql_error().' ou sua sessão expirou!');
        
        $consulta_estacao = "SELECT te_dominio_windows, te_nome_computador, te_workgroup
                             FROM   computadores
		             WHERE  id_computador = ". $_POST['id_computador'];
        $resultado_estacao= mysql_query($consulta_estacao) or die ('Erro no select:'. mysql_error().' ou sua sessão expirou!');

        echo "<p><font size=2 face=verdana>Estação:<b> ". mysql_result($resultado_estacao, 0, 'te_nome_computador') . "</b>";
        echo "<br>Grupo de Trabalho (Último Login):<b> ". mysql_result($resultado_estacao, 0, 'te_workgroup');
        echo "</b> (". mysql_result($resultado_estacao, 0, 'te_dominio_windows') . ")</font>"

?>
<br>
<?php 
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
                $acao=$row['Ação'];

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
	echo mensagem('Não foi encontrado nenhum registro');
}
include_once("rod.html");
?>
</body>
</html>
