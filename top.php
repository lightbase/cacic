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
$v_versao = '2.2.2';
/* 
2.2.2 - Algumas correções lógicas: 
		* Corrigida a atribuição indevida do nome de usuário constande de resultado de Log de Atividades ao nome do usuário logado na aplicação;
		* Corrigidas algumas correlações de "local" em consultas realizadas por usuários com níveis diferentes de "Administração" e "Gestão Central";
		+ Implementada a opção de seleção de Coletas de Sistemas Monitorados quando do cadastramento da subrede;
		+ Implementada a opção de seleção/alteração de Coletas de Sistemas Monitorados quando da edição de configurações da subrede.

2.2.1 - Efetuadas adaptações para suporte a base centralizada de dados, quando as subredes cadastradas 
   		passam a fazer parte de uma "localização" ou "local".
		As adaptações impactaram na definição dos seguintes níveis de acesso:
		1) Administração => Acesso irrestrito, com visão total de todos os dados de todos os "locais".
							Tem total permissão para alteração de dados constantes de tabelas centralizadas;
		2) Gestão Central=>	Acesso irrestrito, com visão total de todos os dados de todos os "locais".
							Não tem permissão para alteração de dados constantes de tabelas centralizadas;
		3) Supervisor	 => Acesso restrito aos dados do "local" de cadastro. Seu cadastro é realizado pelo nível "Administração";
							Tem permissão para visão/alteração de dados locais e cadastramento de usuários
		 					de níveis "Técnico" ou "Comum";
		4) Técnico		 => Acesso restrito aos dados do "local" de cadastro. Seu cadastro é realizado pelo nível "Supervisão".
							Tem permissão para acesso a configuracoes de rede e relatórios de Patrimônio e Hardware;
		5) Comum		 => Acesso restrito aos dados do "local" de cadastro. Seu cadastro é realizado pelo nível "Supervisão".
							Não tem acesso a informações "confidenciais" como Softwares Inventariados e Opções Administrativas 
							como Forçar Coletas e Excluir Computador. Poderá alterar sua própria senha.		
*/
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Topo</title></head>
<?
echo '<body bgcolor='.($_SESSION['id_default_body_bgcolor']<>''?$_SESSION['id_default_body_bgcolor']:'#EBEBEB').' leftmargin="1" topmargin="0">';	
?>
<SCRIPT language=JavaScript>
<!--
function scrollit(seed) 
	{
	var msg="*** CACIC - Configurador Automático e Coletor de Informações Computacionais ***";
	var out = " ";
	var c = 1;
	if (seed > 100) 
		{
		seed--;
		cmd="scrollit("+seed+")";
		timerTwo=window.setTimeout(cmd,100);
		}
	else if (seed <= 100 && seed > 0) 
		{
		for (c=0 ; c < seed ; c++) 
			{
			out+=" ";
			}
		out+=msg;
		seed--;
		window.status=out;
		cmd="scrollit("+seed+")";
		timerTwo=window.setTimeout(cmd,100);
		}
	else if (seed <= 0) 
		{
		if (-seed < msg.length) 
			{
			out+=msg.substring(-seed,msg.length);
			seed--;
			window.status=out;
			cmd="scrollit("+seed+")";
			timerTwo=window.setTimeout(cmd,100);
			}
		else 
			{
			window.status=" ";
			timerTwo=window.setTimeout("scrollit(100)",75);
			}
		}
	}
//scrollit(100);
</SCRIPT>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td><table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr> 
          <td width="5">&nbsp;</td>
          <td><strong><font size="5" face="Verdana, Arial, Helvetica, sans-serif"><img src="imgs/cacic_logo.png" width="50" height="50"></font></strong></td>
          <td><table width="75%" border="0">
              <tr>
                <td><img src="imgs/cacic_tit.gif"></td>
              </tr>
              <tr>
                <td><div align="center"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">v.<? echo $v_versao;?></font></div></td>
              </tr>
            </table>
            
          </td>
          <td><table border="0" align="right" cellpadding="0" cellspacing="0">
              <tr> 
                <td><img src="imgs/cacic_ext.gif" align="bottom"></td>
                <td>&nbsp;</td>
              </tr>
              <tr> 
                <td><div align="right"><b><font size="2" face="Verdana, Arial, Helvetica, sans-serif">
				<? 
				if ($_SESSION['nm_local'])
					echo $_SESSION['nm_local']; 					
				else 
					{
					require_once('include/library.php');					
					echo get_valor_campo('configuracoes_padrao', 'nm_organizacao'); 
					}				
				?>
				</font></b></div></td>
                <td>&nbsp;&nbsp;</td>
              </tr>
            </table>
          </td>
        </tr>
      </table></td>
  </tr>
  <tr>
    <td height="2" background="imgs/linha_h.gif"></td>
  </tr>
</table>
</body>
</html>