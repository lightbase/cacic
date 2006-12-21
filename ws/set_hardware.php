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
// Definição do nível de compressão (Default=máximo)
//$v_compress_level = '9';
$v_compress_level = '0';
 
require_once('../include/library.php');

// Essas variáveis conterão os indicadores de criptografia e compactação
$v_cs_cipher	= (trim($_POST['cs_cipher'])   <> ''?trim($_POST['cs_cipher'])   : '4');
$v_cs_compress	= (trim($_POST['cs_compress']) <> ''?trim($_POST['cs_compress']) : '4');

autentica_agente($key,$iv,$v_cs_cipher,$v_cs_compress);

$te_node_address 	= DeCrypt($key,$iv,$_POST['te_node_address']	,$v_cs_cipher, $v_cs_compress); 
$id_so           	= DeCrypt($key,$iv,$_POST['id_so']				,$v_cs_cipher, $v_cs_compress); 
$id_ip_rede     	= DeCrypt($key,$iv,$_POST['id_ip_rede']			,$v_cs_cipher, $v_cs_compress);
$te_ip 				= DeCrypt($key,$iv,$_POST['te_ip']				,$v_cs_cipher, $v_cs_compress); 
$te_nome_computador	= DeCrypt($key,$iv,$_POST['te_nome_computador']	,$v_cs_cipher, $v_cs_compress); 
$te_workgroup 		= DeCrypt($key,$iv,$_POST['te_workgroup']		,$v_cs_cipher, $v_cs_compress); 

/* Todas as vezes em que é feita a recuperação das configurações por um agente, é incluído 
 o computador deste agente no BD, caso ainda não esteja inserido. */
if ($te_node_address || $id_so || $te_nome_computador || $te_ip || $te_workgroup || $id_ip_rede  <> '')
	{ 
	inclui_computador_caso_nao_exista(	$te_node_address, 
										$id_so, 
										$id_ip_rede, 
										$te_ip, 
										$te_nome_computador, 
										$te_workgroup);										
	}

conecta_bd_cacic();

/* A consulta abaixo é usada para identificar se é um atualização das informações de hardware 
   ou uma inclusão. Apenas são notificadas as alterações de hardware.
 Atenção: não use o count (*) - Com espaço entre o count e o (*)				 */
$query = "SELECT COUNT(*) 
		  FROM historico_hardware 
		  WHERE te_node_address = '" . $te_node_address . "'
		  AND id_so = '" . $id_so . "'";
					
$result = mysql_query($query);
if (mysql_num_rows($result) > 0) {  // Atualização das informações de hardware (e não inclusão). 

   // Agora, verifica se os administradores deverão ser notificados da alteração na configuração de hardware.
  	if (trim($destinatarios = get_valor_campo('configuracoes_locais', 'te_notificar_mudanca_hardware')) != '') {

       /* Consulto todos os hardwares que foram selecionados para notificacao. Isso é setado pelo administrador na página de 'Configurações Gerais'.*/ 
		  	  	$query = "SELECT 	nm_campo_tab_hardware, te_desc_hardware
				          FROM 		descricao_hardware 
						  WHERE 	cs_notificacao_ativada = '1'";
  						$result_hardwares_selecionados = mysql_query($query) or die('Ocorreu um erro durante a consulta à tabela descricao_hardware.');

		  				/* Agora seleciono as colunas que serão consultadas na tabela 'computadores', para verificar as 
   								configurações de hardware atuais. 
											Aproveito esse mesmo loop para criar um array que tem nm_campo_tab_hardware => te_desc_hardware.
									*/ 
   					while($campos_hardwares_selecionados = mysql_fetch_array($result_hardwares_selecionados)) {
 				 		   $sql_aux = $sql_aux . $campos_hardwares_selecionados['nm_campo_tab_hardware'] . ','; 
           $descricao_campo[$campos_hardwares_selecionados[nm_campo_tab_hardware]] = $campos_hardwares_selecionados[te_desc_hardware];
  						}
								$sql_aux = substr($sql_aux, 0, strlen($sql_aux)-1);	 // Tiro a última vírgula

		  				/* Agora identifico quais são as configurações de hardware atualmente armazenadas no BD. */ 
     				$query = 'SELECT 	' . $sql_aux . " 
							  FROM 		computadores
							  WHERE 	te_node_address = '" . $te_node_address . "' AnD 
							  			id_so = '" . $id_so . "'";
     				$result = mysql_query($query);
     		  $campos = mysql_fetch_array($result);

				     // Varre todos os campos e verifica quais foram os que sofreram alterações, para montar o e-mail.
     				$cont_aux = 0;
     				$campos_alterados = '';
				     for ($i=0; $i < mysql_num_fields($result); $i++) {
						       	$nome_campo = mysql_field_name($result, $i); 
       							$tam_campo = mysql_field_len($result, $i); 
														if (($campos[$nome_campo] != substr(DeCrypt($key,$iv,$_POST[$nome_campo],$v_cs_cipher,$v_cs_compress), 0, $tam_campo)) && ($nome_campo != 'dt_hr_alteracao') && ($nome_campo != 'te_cpu_freq') ) {
														     $cabecalho_campo = $descricao_campo[$nome_campo] . ' (' . $nome_campo . ')';
         							   $campos_alterados =  $campos_alterados . $cabecalho_campo . "\n" . str_repeat('-', strlen($cabecalho_campo)) .  "\nValor Anterior.: " . $campos[$nome_campo] . "\nNovo Valor.....: " . DeCrypt($key,$iv,$_POST[$nome_campo],$v_cs_cipher,$v_cs_compress) . "\n\n";
						             $cont_aux++;
        						}
				     }
				
				     //Recupero informações sobre o computador, para montar o cabeçalho do e-mail.
        	$query = "SELECT 	te_nome_computador, id_ip_rede, te_ip 
     		  		  FROM 		computadores 
    			      WHERE 	te_node_address = '" . $te_node_address . "' AND 
					  			id_so = '" . $id_so . "'";
        	$result = mysql_query($query);

         if ($cont_aux > 0) { 
    		      $corpo_mail = "Prezado administrador,\n		  
Foi identificada uma alteração na configuração de hardware do seguinte computador:\n
Nome...........: ". mysql_result( $result, 0, "te_nome_computador" ) ." 
Endereço TCP/IP: ". mysql_result( $result, 0, "te_ip" ) . "
Rede...........: ". mysql_result( $result, 0, "id_ip_rede" ) ."\n
As configurações que sofreram alterações estão relacionadas abaixo:\n\n" . 
$campos_alterados .
"\n\nPara visualizar mais informações sobre esse computador, acesse o endereço\nhttp://" .
$_SERVER['SERVER_ADDR'] . '/cacic2/relatorios/computador/computador.php?te_node_address=' . $te_node_address . '&id_so=' . $id_so .
"\n\n________________________________________________
CACIC - " . date('d/m/Y H:i') . 'h
Desenvolvido pelo Escritório da Dataprev do ES';

							// Manda mail para os administradores.
								mail("$destinatarios", "Alteracao de Hardware Detectada", "$corpo_mail", "From: cacic@{$_SERVER['SERVER_NAME']}");

								//echo $corpo_mail;
								//Simulação de agente em browser.  É necessário inibir a linha autentica_agente() no início do script.
				}
	}
}






// Inclui as informações do novo hardware no histórico e atualiza as informações do computador.
$query = "INSERT INTO historico_hardware 
										(te_node_address,
											id_so,
											dt_hr_alteracao,
											te_placa_rede_desc,
											te_mem_ram_desc,
											qt_mem_ram,
											te_cpu_serial,
											te_cpu_fabricante,
											te_cpu_desc,
											te_cpu_freq,
											te_bios_desc,
											te_bios_data,
											te_bios_fabricante,
											te_placa_mae_desc,
											te_placa_mae_fabricante,
											qt_placa_video_mem,
											qt_placa_video_cores,
											te_placa_video_desc,
											te_placa_video_resolucao,
											te_placa_som_desc,
											te_cdrom_desc,
											te_teclado_desc,
											te_mouse_desc,
											te_modem_desc)											
		 VALUES ('" . $te_node_address . "', 
		         '" . $id_so . "',
											NOW(),
											'" . DeCrypt($key,$iv,$_POST['te_placa_rede_desc']		,$v_cs_cipher,$v_cs_compress) . "', 
											'" . DeCrypt($key,$iv,$_POST['te_mem_ram_desc']			,$v_cs_cipher,$v_cs_compress) . "', 
											'" . DeCrypt($key,$iv,$_POST['qt_mem_ram']				,$v_cs_cipher,$v_cs_compress) . "', 
											'" . DeCrypt($key,$iv,$_POST['te_cpu_serial']			,$v_cs_cipher,$v_cs_compress) . "', 
											'" . DeCrypt($key,$iv,$_POST['te_cpu_fabricante']		,$v_cs_cipher,$v_cs_compress) . "', 
											'" . DeCrypt($key,$iv,$_POST['te_cpu_desc']				,$v_cs_cipher,$v_cs_compress) . "', 
											'" . DeCrypt($key,$iv,$_POST['te_cpu_freq']				,$v_cs_cipher,$v_cs_compress) . "',
											'" . DeCrypt($key,$iv,$_POST['te_bios_desc']			,$v_cs_cipher,$v_cs_compress) . "',
											'" . DeCrypt($key,$iv,$_POST['te_bios_data']			,$v_cs_cipher,$v_cs_compress) . "',
											'" . DeCrypt($key,$iv,$_POST['te_bios_fabricante']		,$v_cs_cipher,$v_cs_compress) . "',
											'" . DeCrypt($key,$iv,$_POST['te_placa_mae_desc']		,$v_cs_cipher,$v_cs_compress) . "',
											'" . DeCrypt($key,$iv,$_POST['te_placa_mae_fabricante']	,$v_cs_cipher,$v_cs_compress) . "',
											'" . DeCrypt($key,$iv,$_POST['qt_placa_video_mem']		,$v_cs_cipher,$v_cs_compress) . "',
											'" . DeCrypt($key,$iv,$_POST['qt_placa_video_cores']	,$v_cs_cipher,$v_cs_compress) . "',
											'" . DeCrypt($key,$iv,$_POST['te_placa_video_desc']		,$v_cs_cipher,$v_cs_compress) . "',
											'" . DeCrypt($key,$iv,$_POST['te_placa_video_resolucao'],$v_cs_cipher,$v_cs_compress) . "',
											'" . DeCrypt($key,$iv,$_POST['te_placa_som_desc']		,$v_cs_cipher,$v_cs_compress) . "',
											'" . DeCrypt($key,$iv,$_POST['te_cdrom_desc']			,$v_cs_cipher,$v_cs_compress) . "',
											'" . DeCrypt($key,$iv,$_POST['te_teclado_desc']			,$v_cs_cipher,$v_cs_compress) . "',
											'" . DeCrypt($key,$iv,$_POST['te_mouse_desc']			,$v_cs_cipher,$v_cs_compress) . "',
											'" . DeCrypt($key,$iv,$_POST['te_modem_desc']			,$v_cs_cipher,$v_cs_compress) . "')";																						
$result = mysql_query($query);

// Lembre-se de que o computador já existe. Ele é criado durante a obtenção das configurações, no arquivo get_config.php.
$query = "UPDATE computadores 
										SET	te_placa_rede_desc       			 = '" . DeCrypt($key,$iv,$_POST['te_placa_rede_desc']		,$v_cs_cipher,$v_cs_compress) . "', 
														te_mem_ram_desc          = '" . DeCrypt($key,$iv,$_POST['te_mem_ram_desc']			,$v_cs_cipher,$v_cs_compress) . "', 
														qt_mem_ram               = '" . DeCrypt($key,$iv,$_POST['qt_mem_ram']				,$v_cs_cipher,$v_cs_compress) . "',
														te_cpu_serial            = '" . DeCrypt($key,$iv,$_POST['te_cpu_serial']			,$v_cs_cipher,$v_cs_compress) . "',
														te_cpu_fabricante        = '" . DeCrypt($key,$iv,$_POST['te_cpu_fabricante']		,$v_cs_cipher,$v_cs_compress) . "',
														te_cpu_desc              = '" . DeCrypt($key,$iv,$_POST['te_cpu_desc']				,$v_cs_cipher,$v_cs_compress) . "',
														te_cpu_freq              = '" . DeCrypt($key,$iv,$_POST['te_cpu_freq']				,$v_cs_cipher,$v_cs_compress) . "',
														te_bios_desc             = '" . DeCrypt($key,$iv,$_POST['te_bios_desc']				,$v_cs_cipher,$v_cs_compress) . "',
														te_bios_data             = '" . DeCrypt($key,$iv,$_POST['te_bios_data']				,$v_cs_cipher,$v_cs_compress) . "',
														te_bios_fabricante       = '" . DeCrypt($key,$iv,$_POST['te_bios_fabricante']		,$v_cs_cipher,$v_cs_compress) . "',
														te_placa_mae_desc        = '" . DeCrypt($key,$iv,$_POST['te_placa_mae_desc']		,$v_cs_cipher,$v_cs_compress) . "',
														te_placa_mae_fabricante  = '" . DeCrypt($key,$iv,$_POST['te_placa_mae_fabricante']	,$v_cs_cipher,$v_cs_compress) . "',
														qt_placa_video_mem       = '" . DeCrypt($key,$iv,$_POST['qt_placa_video_mem']		,$v_cs_cipher,$v_cs_compress) . "',
														qt_placa_video_cores     = '" . DeCrypt($key,$iv,$_POST['qt_placa_video_cores']		,$v_cs_cipher,$v_cs_compress) . "',
														te_placa_video_desc      = '" . DeCrypt($key,$iv,$_POST['te_placa_video_desc']		,$v_cs_cipher,$v_cs_compress) . "',
														te_placa_video_resolucao = '" . DeCrypt($key,$iv,$_POST['te_placa_video_resolucao']	,$v_cs_cipher,$v_cs_compress) . "',
														te_placa_som_desc        = '" . DeCrypt($key,$iv,$_POST['te_placa_som_desc']		,$v_cs_cipher,$v_cs_compress) . "',
														te_cdrom_desc            = '" . DeCrypt($key,$iv,$_POST['te_cdrom_desc']			,$v_cs_cipher,$v_cs_compress) . "',
														te_teclado_desc          = '" . DeCrypt($key,$iv,$_POST['te_teclado_desc']			,$v_cs_cipher,$v_cs_compress) . "',
														te_mouse_desc            = '" . DeCrypt($key,$iv,$_POST['te_mouse_desc']			,$v_cs_cipher,$v_cs_compress) . "',
														te_modem_desc            = '" . DeCrypt($key,$iv,$_POST['te_modem_desc']			,$v_cs_cipher,$v_cs_compress) . "' 
								WHERE te_node_address          = '" . $te_node_address . "' and
													 id_so                    = '" . $id_so . "'";
$result = mysql_query($query);


echo '<?xml version="1.0" encoding="iso-8859-1" ?><STATUS>OK</STATUS>';
?>