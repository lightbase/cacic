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
session_start();
$v_versao = '2.3.0-RC28';
/* 
2.2.3-RC28 (Ulysses Rangel Ribeiro - Dataprev/ES - Agosto/2008)
		      + Foi acrescentada a geração de relatórios nos formatos PDF, CSV e ODS para as opções de Antivírus Officescan e Configurações de Hardwares;
		   (Anderson Peterle - Dataprev/ES - Agosto/2008)		  
		      * Foi acrescentada a opção "Sistema Operacional MS-Windows" às páginas de Inclusão e Alteração dos ítens de Sistemas Operacionais, para aplicação no
		        conceito "Apenas um MS-Windows por estação".

2.2.3-RC27 (Anderson Peterle - Dataprev/ES - Julho/2008)
		      + Foi implementado o destaque colorido para o registro "S.O. a Cadastrar" no link/opção "Administração/Cadastros/S.Operacionais";

2.2.3-RC26 (Anderson Peterle - Dataprev/ES - Junho/2008)
		      * Redimensionamento das legendas constantes das estatísticas gráficas do Cacic, para melhor discriminação dos ítens medidos;
		      + Foi acrescentada a seção "Agentes para GNU/Linux" à página "Updates de SubRedes"

2.2.3-RC25 (Anderson Peterle - Dataprev/ES - Maio/2008)
		      * Adequação de relatórios à detecção de múltiplo componente de hardware nas estações;
		      + Foram acrescentados os critérios "Período de Instalação", "Servidores de Atualização" e "Exibição de Informações Patrimoniais" para a geração do relatório de Informações do Antivírus Officescan.

2.2.3-RC24 (Anderson Peterle - Dataprev/ES - Março/2008)
		      + Foi acrescentada a detecção de múltiplo componente de hardware nas estações;

2.2.3-RC23 (Anderson Peterle - Dataprev/ES - Fevereiro/2008)
		      + Foi aberta ao usuário de nível "Supervisão" a opção "Perfis Sistemas", onde este usuário poderá associar redes aos perfis de sistemas monitorados;
		      + Foi acrescentada a informação "Total de Redes Alvo" na página principal dos Perfis de Sistemas Monitorados (Administração / Cadastros / Perfis Sistemas);
		      + Foi acrescentada a informação de sequência dos ítens constantes dos Detalhes do Computador;		  
		      * Foi implementada inteligência aos gráficos de pizza "Totais de computadores monitorados por local" e "Últimos acessos dos agentes por local nesta data", para que os
		        gráficos não cresçam além dos limites impostos pela quantidade de locais.

2.2.3-RC22 (Anderson Peterle - Dataprev/ES - Fevereiro/2008)
		      + Implementadas as opções Atualização Especial e Consulta Especial para o nível Administração, para operações de baixo nível no 
		  	    Servidor de Aplicações.
			    ATENÇÃO: Estas opções dependem do conjunto de permissões existentes nas pastas e arquivos objetos das operações.
		      + Foi reativado o script "instalacacic.php" que recebe uma indicação de instalação mal sucedida, enviada pelo ChkCacic,
		        e armazena na tabela "insucessos_instalacao";
		      + Implementado o Log de Insucessos, para visualização das tentativas de instalação com insucesso.
	
2.2.3-RC21 (Anderson Peterle - Dataprev/ES - Janeiro/2008)
		      * Melhoria na recepção de valores de Softwares Inventariados e Variáveis de Ambiente, com a implementação de controle por HASH MD5;
		      + Implementado o sequenciamento de ítens nas seções dos Detalhes do Computador;

2.2.3-RC20 (Anderson Peterle - Dataprev/ES - Janeiro/2008)
		      + Implementação em updates de SubRedes, onde o usuário poderá marcar/desmarcar subredes por local;
		      * Correção no envio de email a partir da gravação de informações patrimoniais.

2.2.3-RC19 (Anderson Peterle - Dataprev/ES - Janeiro/2008)
		      + Exibição de alerta para falha de conexão ao servidor de updates nos detalhes da subrede. Os dois usuários/senhas são testados 
		        e os devidos campos destacados em amarelo em caso de falha, facilitando as providências de correção;
		      + Exibição de informações de patrimônio (Unidades Organizacionais) associadas ao local; (Administração / Cadastros / Locais)
		      + Exibição de destaques na opção Update de SubRedes: (Manutenção / Update de SubRedes)
		        * AMARELO.: SubRedes com VERSÕES DIFERENTES DE MÓDULOS;
		        * LARANJA.: SubRedes com INEXISTÊNCIA PARCIAL DE MÓDULOS;
 		        * VERMELHO: SubRedes com INEXISTÊNCIA TOTAL DE MÓDULOS.

2.2.3-RC18 (Anderson Peterle - Dataprev/ES - Janeiro/2008)
		      + Alterada a marca de nível de acesso na página dos detalhes do Local, passando a ser representada pelas duas 
		        letras iniciais do grupo de acesso conforme legenda na versão 2.2.3-RC17;
		      + Correção na rotina de Updates de SubRedes;
		      + Correção na rotina de Detalhes de SubRedes.

2.2.3-RC17 (Anderson Peterle - Dataprev/ES - Janeiro/2008)
		      + Alterada a marca de nível de acesso na página Lista de Usuários (Administração / Cadastros / Usuários), passando a ser representada pelas duas 
		        letras iniciais do grupo de acesso conforme legenda:
			    AD : Vermelho : Administração
			    GC : Verde    : Gestão Central			
			    SU : Amarelo  : Supervisão
			    TE : Laranja  : Técnico
			    CO : Azul     : Comum
		      + Acrescentada a informação de total de usuários por nível de acesso na página Administração / Cadastros / Usuários;
		      + Acrescentado "hint" para identificação das iniciais referentes aos níveis de acesso.

2.2.3-RC16 (Anderson Peterle - Dataprev/ES - Dezembro/2007)
		      + Acrescentada mensagem de "Operação Efetuada com Sucesso" ao fim do cadastramento de subrede seguido de updates;
		      * Alterada a crítica de conexão ao servidor de updates nos detalhes da subrede, quando serão destacados apenas os campos relevantes;
		      + Acrescentada a exibição da classificação de nível de acesso "Comum" na grade de visualização de usuários cadastrados;
		      + Acrescentada a exibição de Totais de Redes, Usuários Primários e Usuários Secundários na visualização dos locais cadastrados;
		      + Acrescentada a exibição de mensagem de sucesso/insucesso na conexão FTP nos detalhes das subredes.
		  

2.2.3-RC15 (Anderson Peterle - Dataprev/ES - Dezembro/2007)
		      * Adequação do recebimento de informações patrimoniais ao conceito de Linhas de Negócio;
		      * Correções no script get_config.php para o tratamento do sistema operacional Windows VISTA;
		      * Correções no script library.php para o tratamento do sistema operacional Windows VISTA.

2.2.3-RC14 (Anderson Peterle - Dataprev/ES - Dezembro/2007)
		      * Adequação da opção Relatórios/Patrimônio ao conceito de Linhas de Negócio

2.2.3-RC13 (Anderson Peterle - Dataprev/ES - Dezembro/2007)
		      * Correção da opção de cadastramento de U.O.N. 2;
		      * Adequação da opção Administração/Patrimônio/Interface ao novo layout da janela de coleta de informações patrimoniais

2.2.3-RC12 (Anderson Peterle - Dataprev/ES - Dezembro/2007)
		      + Exibição das sub-opções de Patrimônio de acordo com as definições efetuadas para UON1, UON1a e UON2
		      * Correção do envio de dados ao MapaCacic, retirando as restrições ao local da estação
		      * Restrição dos cadastramentos de U.O.N. 1, U.O.N. 1a e U.O.N. 2 ao nível "Administração"		  
		  
2.2.3-RC11 (Anderson Peterle - Dataprev/ES - Dezembro/2007)
		      + Acrescentados diversas funcionalidades pertinentes ao conceito de "Linha de Negócio"
		      * Correção do envio de dados ao MapaCacic, restringindo informações ao local da estação

2.2.3-RC10 (Anderson Peterle - Dataprev/ES - Dezembro/2007)
		      + Implementação do conceito de "Linha de Negócio" para as informações patrimoniais
		  	  * Implementação da opção "Administração / Patrimônio / U.O. Nível 1a" para o nível "Administração"			

2.2.3-RC9  (Anderson Peterle - Dataprev/ES - Novembro/2007)
		      * Correção na exibição de módulos a usuários com nível "Supervisão".

2.2.3-RC8  (Anderson Peterle - Dataprev/ES - Novembro/2007)
		      + Acrescentado o nível "Técnico" para classificação(tick) na opção "Administração/Cadastros/Usuários";
		      * Correção no cadastramento de usuários, quando da seleção de Local Secundário em conjunto (CTRL + Click) com valor vazio.

2.2.3-RC7  (Anderson Peterle - Dataprev/ES - Novembro/2007)
		      * Correção da consulta gerada na página retornada pela opção "Administração/Patrimônio/U.O. Nível 2".

2.2.3-RC6  (Anderson Peterle - Dataprev/ES - Novembro/2007)
		      * Apenas redução da fonte constante (de 3 para 2) das legendas das Estatísticas do CACIC, na página principal,
		  	    visando melhor distribuição dos dados relativos aos nomes dos locais quando essa quantidade for superior a 25.

2.2.3-RC5  (Anderson Peterle - Dataprev/ES - Outubro/2007)
		      + Implementado o destaque para os computadores com mais de 5 dias sem acesso ao gerente WEB; (Computadores/Navegação)
		      + Implementada a seleção de Locais para as opções de Log de Acessos e Log de Atividades; (Administração/Log de Acessos e Administração/Log de Atividades)
		      + Implementada a seleção de Redes para aplicação dos Perfis de Sistemas Monitorados; (Administração/Cadastros/Perfis Sistemas)		  
		      * Efetuadas diversas pequenas correções. (Administração/Cadastros/Locais, Relatórios, Estatísticas, etc.)

2.2.3-RC4  (Anderson Peterle - Dataprev/ES - Julho a Setembro/2007)
		      + Implementado o destaque de ações dos usuários: INS:Verde UPD:Amarelo DEL:Vermelho
		      + Implementado o detalhamento dos dados constantes dos detalhes das estatísticas da página inicial
		      + Implementada a opção "Administração/Configurar Gerente/Exibir Gráficos na Página Principal e Detalhes" para exibição de pizza ou tabela
		      + Implementada a manutenção do cadastro sistemas operacionais em "Administração/Cadastros/S.Operacionais"
		      + Implementada a ordenação por colunas nas opções "Administração/Cadastros/Locais", "Administração/Cadastros/SubRedes", "Administração/Cadastros/Usuários" e "Administração/Cadastros/S.Operacionais"
		      * Efetuadas correções em consultas e atualizações de informações do banco de dados
		  		  		  
2.2.3-dev-1 (Anderson Peterle - Dataprev/ES - Junho/2007)
		      * Corrigido o processo de liberação de FTP, onde a tabela redes_grupos_ftp é liberada após a operação
			    de transferência de arquivos por parte do módulo cliente Gerente de Coletas.

2.2.3-dev (Anderson Peterle - Dataprev/ES - Maio/2007)
		      + Implementado o detalhamento para as estatísticas exibidas na página principal quando representarem
		        mais de um local (local primário + local(is) secundário(s))
		      + Os níveis Administração e Gestão Central também acessam a opção de detalhamento.

2.2.3     (Anderson Peterle - Dataprev/ES - Março e Abril/2007)
		      + Implementada a opção de seleção de "Locais Secundários" nas janelas de inclusão e detalhes de usuários,
		        para simulação de pseudo "relação de confiança" entre usuários e locais, possibilitando a estes usuários 
		        o acesso e manipulação das informações dos locais "confiantes";
		      + Adequação das consultas ao conceito de "relação de confiança" implementado.		  

2.2.2     (Anderson Peterle - Dataprev/ES - Fevereiro/2007)
		      * Corrigida a atribuição indevida do nome de usuário constande de resultado de Log de Atividades ao nome do usuário logado na aplicação;
		      * Corrigidas algumas correlações de "local" em consultas realizadas por usuários com níveis diferentes de "Administração" e "Gestão Central";
		      + Implementada a opção de seleção de Coletas de Sistemas Monitorados quando do cadastramento da subrede;
		      + Implementada a opção de seleção/alteração de Coletas de Sistemas Monitorados quando da edição de configurações da subrede.

2.2.1     (Anderson Peterle - Dataprev/ES - Janeiro/2007)
		      * Efetuadas adaptações para suporte a base centralizada de dados, quando as subredes cadastradas 
   		        passam a fazer parte de uma "localização" ou "local".
		        As adaptações impactaram na definição dos seguintes níveis de acesso:
		        1) Administração :  Acesso irrestrito, com visão total de todos os dados de todos os "locais".
		  		      			    Tem total permissão para alteração de dados constantes de tabelas centralizadas;
		        2) Gestão Central:  Acesso irrestrito, com visão total de todos os dados de todos os "locais".
							        Não tem permissão para alteração de dados constantes de tabelas centralizadas;
		        3) Supervisão	 :  Acesso restrito aos dados do "local" de cadastro. Seu cadastro é realizado pelo nível "Administração";
							        Tem permissão para visão/alteração de dados locais e cadastramento de usuários
		 					        de níveis "Técnico" ou "Comum";
		        4) Técnico	     :  Acesso restrito aos dados do "local" de cadastro. Seu cadastro é realizado pelo nível "Supervisão".
							        Tem permissão para acesso a configuracoes de rede e relatórios de Patrimônio e Hardware;
		        5) Comum		 :  Acesso restrito aos dados do "local" de cadastro. Seu cadastro é realizado pelo nível "Supervisão".
							        Não tem acesso a informações "confidenciais" como Softwares Inventariados e Opções Administrativas 
							        como Forçar Coletas e Excluir Computador. Poderá alterar sua própria senha.		
*/
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Topo</title></head>
<body bgcolor=<?php echo ($_SESSION['id_default_body_bgcolor']<>''?$_SESSION['id_default_body_bgcolor']:'#EBEBEB'); ?> leftmargin="1" topmargin="0">	

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
          <td width="5"> </td>
          <td><strong><font size="5" face="Verdana, Arial, Helvetica, sans-serif"><img src="imgs/cacic_logo.png" width="50" height="50"></font></strong></td>
          <td><table width="75%" border="0">
              <tr>
                <td><img src="imgs/cacic_tit.gif"></td>
              </tr>
              <tr>
                <td><div align="center"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">v.<?php echo $v_versao;?></font></div></td>
              </tr>
            </table>
            
          </td>
          <td><table border="0" align="right" cellpadding="0" cellspacing="0">
              <tr> 
                <td><img src="imgs/cacic_ext.gif" align="bottom"></td>
                <td> </td>
              </tr>
              <tr> 
                <td><div align="right"><b><font size="2" face="Verdana, Arial, Helvetica, sans-serif">
				<?php if ($_SESSION['nm_local'])
					echo $_SESSION['nm_local']; 					
				else 
					{
					require_once('include/library.php');					
					echo get_valor_campo('configuracoes_padrao', 'nm_organizacao'); 
					}				
				?>
				</font></b></div></td>
                <td>  </td>
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
<p> </p>
<p> </p>
</body>
</html>
