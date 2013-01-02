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

$where 	= ($_REQUEST['cs_nivel_administracao'] <> 1 &&
		   $_REQUEST['cs_nivel_administracao'] <> 2 ? ' AND c.id_local = '.$_REQUEST['id_local']:'');

if ($_SESSION['te_locais_secundarios']<>'' && $where <> '')
	{
	// Fa�o uma inser��o de "(" para ajuste da l�gica para consulta
	$where = str_replace('c.id_local = ','(c.id_local = ',$where);
	$where .= ' OR c.id_local in ('.$_SESSION['te_locais_secundarios'].')) ';
	}		
	
if ($_GET['in_detalhe'])
	$where = ' AND c.id_local = '.$_GET['in_detalhe'];
			  
$query = 'SELECT 	count(a.id_so) as qtd, 
					b.te_desc_so 
		  FROM		computadores a,
		  			so b,
					redes c
		  WHERE 	(a.id_so = b.id_so OR a.te_so = b.te_so) AND 
		  			a.te_nome_computador IS NOT NULL AND 
					a.dt_hr_ult_acesso IS NOT NULL AND
					a.id_ip_rede = c.id_ip_rede '.
					$where . ' 
		  GROUP BY 	a.id_so 
		  ORDER BY 	a.id_so';

$result = mysql_query($query) or die($oTranslator->_('Ocorreu um erro no acesso a tabela %1 ou sua sessao expirou!',array('computadores, redes, so')));
while ($row_result = mysql_fetch_assoc($result))		
	{ 
	$v_row_result = str_pad($row_result['te_desc_so'],20,'.',STR_PAD_RIGHT);
	$arr[$v_row_result] = $row_result['qtd'];			
	} 


$CreatePie 		= substr_count($te_exibe_graficos,'[so]');
$Sort      		= 1;		
$width     		= 550;
$height    		= 250;
phPie($arr, $width, $height, $CenterX, $CenterY, $DiameterX, $DiameterY, $MinDisplayPct, $DisplayColors, $BackgroundColor, $LineColor, true, 3,$CreatePie, $Sort);
?>