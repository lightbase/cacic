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

================================================================================================================================
A classe navbar de Copyright Joao Prado Maia (jpm@phpbrasil.com) e tradução de
Thomas Gonzalez Miranda (thomasgm@hotmail.com) baixada do site www.phpbrasil.com
em 06/05/2002 foi modificada para melhor entendimento do seu funcionamento e
aperfeiçoada deste que apareceram alguns "bugs", sendo transformada como classe
Mult_Pag (Multiplas paginas).
As informações acima foram retiradas da versão 1.3 da classe navbar do arquivo
navbar.zip.

Construi esta pequena classe para navegação dinâmica de links. Observe
por favor a simplicidade deste código. Este código é livre em
toda maneira que você puder imaginar. Se você o usar em seu
próprio script, por favor deixo os créditos como estão. Também,
envie-me um e-mail se você o fizer, isto me deixa feliz :-)

Adaptações Realizadas / Motivos:
-------------------------------
06 a 09/05/2002	: Marco A. D. Freitas (madf@splicenet.com.br)
26/06/2006 		: Paulo Enok Sawazaki (pauloeno@yahoo.com.br)
24/06/2008		: Anderson Peterle (anderson@peterles.com)
				  Motivo: adequação para uso no Sistema CACIC - Configurador Automático e Coletor de Informações Computacionais
				  http://www.softwarepublico.gov.br/ver-comunidade?community_id=3585
================================================================================================================================
*/
// classe que multiplica paginas
class Mult_Pag {
  // Valores padrão para a navegação dos links
  /*
   * define o número de pesquisas (detalhada ou não) por página
   */
  var $num_pesq_pag;
  var $str_anterior = "<span class='prev'>&nbsp;&nbsp;&nbsp;&nbsp;</span>Anterior";
  var $str_proxima 	= "Próxima<span class='next'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>";
  var $str_primeira	= "<span class='first'>&nbsp;&nbsp;&nbsp;&nbsp;</span>Primeira";
  var $str_ultima	= "Última<span class='last'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>";
  // Variáveis usadas internamente
  var $nome_arq;
  var $total_reg;
  var $pagina;

  /*
     Metodo construtor. Isto é somente usado para setar
     o número atual de colunas e outros métodos que
     podem ser re-usados mais tarde.
  */
  function Mult_Pag ($num_lin_pag=100, $num_link_pag=100)
  {
    global $pagina;
    $this->pagina = $pagina ? $pagina : 0;
    $this->num_pesq_pag = $num_lin_pag;
  }

  /*
     O próximo método roda o que é necessário para as queries.
     É preciso rodá-lo para que ele pegue o total
     de colunas retornadas, e em segundo para pegar o total de
     links limitados.

         $sql parâmetro:
           . o parâmetro atual da query que será executada

         $conexao parâmetro:
           . a ligação da conexão do banco de dados

         $tipo parâmetro:
           . "mysql" - usa funções php mysql
           . "pgsql" - usa funções pgsql php
  	*/
  	function Executar($sql, $conexao, $velocidade, $tipo)
  		{
    	// variavel para o inicio das pesquisas
    	$inicio_pesq = $this->pagina * $this->num_pesq_pag;

    	if ($velocidade == "otimizada") 
			{
      		$total_sql = preg_replace("/SELECT (.*?) FROM /sei", "'SELECT COUNT(*) FROM '", $sql);
    		} 
		else 
			{
      		$total_sql = $sql;
    		}  
    	// tipo da pesquisa
    	if ($tipo == "mysql") 
			{
      		$resultado = mysql_query($total_sql);
      		$this->total_reg = mysql_num_rows($resultado); // total de registros da pesquisa inteira	  
      		$sql .= " LIMIT $inicio_pesq, $this->num_pesq_pag";
      		$resultado = mysql_query($sql); // pesquisa com limites por pagina
    		}
    	else if ($tipo == "pgsql") 
			{    
      		$resultado = pg_exec($conexao, $total_sql);
      		if ( pg_numrows( $resultado )  > 0 ) 
				{
          		// total de registros da pesquisa inteira
         		$this->total_reg = pg_numrows( $resultado );//pg_Result($resultado, 0, 0);
      			}
      		$sql .= " LIMIT $this->num_pesq_pag, $inicio_pesq";
      		$resultado = pg_Exec($conexao, $sql);// pesquisa com limites por pagina
    		}
    	return $resultado;
  		}

  	/*
     Este método cria uma string que irá ser adicionada à
     url dos links de navegação. Isto é especialmente importante
     para criar links dinâmicos, então se você quiser adicionar
     opções adicionais à estas queries, a classe de navegação
     irá adicionar automaticamente aos links de navegação
     dinâmicos.
  	*/
  	function Construir_Url()
  		{
    	global $REQUEST_URI, $REQUEST_METHOD, $HTTP_GET_VARS, $HTTP_POST_VARS;

    	// separa o link em 2 strings
    	@list($this->nome_arq, $voided) = @explode("?", $REQUEST_URI);

    	if ($REQUEST_METHOD == "GET")    $cgi = $HTTP_GET_VARS;
    	else                             $cgi = $HTTP_POST_VARS;
    	reset($cgi); // posiciona no inicio do array

    	// separa a coluna com o seu respectivo valor
    	while (list($chave, $valor) = each($cgi))
      		if ($chave != "pagina")
        		$query_string .= "&" . $chave . "=" . $valor;

    	return $query_string;
  		}

  	/*
     Este método cria uma ligação de todos os links da barra de
     navegação. Isto é útil, pois é totalmente independete do layout
     ou design da página. Este método retorna a ligação dos links
     chamados no script php, sendo assim, você pode criar links de
     navegação com o conteúdo atual da página.

         $opcao parâmetro:
          . "todos" - retorna todos os links de navegação
          . "numeracao" - retorna apenas páginas com links numerados
          . "strings" - retornar somente os links 'Próxima' e/ou 'Anterior'

         $mostra_string parâmetro:
          . "nao" - mostra 'Próxima' ou 'Anterior' apenas quando for necessários
          . "sim" - mostra 'Próxima' ou 'Anterior' de qualqur maneira
  	*/
  	function Construir_Links($opcao, $mostra_string)
  		{
    	$extra_vars = $this->Construir_Url();
    	$arquivo = $this->nome_arq;
    	$num_mult_pag = ceil($this->total_reg / $this->num_pesq_pag); // numero de multiplas paginas
    	$indice = -1; // indice do array final
		$numero_links_proximos=4;

    	for ($atual = 0; $atual < $num_mult_pag; $atual++) 
			{		
      		// escreve a string esquerda (Pagina Anterior)
      		if ((($opcao == "todos") || ($opcao == "strings")) && ($atual == 0)) 
				{
        		if ($this->pagina != 0)
					{
		  			$array[++$indice] = '<a href="' . $arquivo . '?pagina=' . $atual . $extra_vars . '">' . $this->str_primeira . '</a>';
          			$array[++$indice] = '<a href="' . $arquivo . '?pagina=' . ($this->pagina - 1) . $extra_vars . '">' . $this->str_anterior . '</a>';                 
					}
		        elseif (($this->pagina == 0) && ($mostra_string == "sim"))
					{		
		  			//$array[++$indice] = $this->str_primeira;
          			//$array[++$indice] = $this->str_anterior;
		  			}
      			}

      		// escreve a numeracao (1 2 3 ...)
    		if (($opcao == "todos") || ($opcao == "numeracao")) 
				{		  
	  			if (($atual > $this->pagina - $numero_links_proximos)&&($atual < $this->pagina + $numero_links_proximos) )
					{		  
        			if ($this->pagina == $atual)
						{
		  				$array[++$indice] = "<b>";
          				$array[++$indice] = ($atual > 0 ? ($atual + 1) : 1);
		  				$array[++$indice] = "</b>";
        				}
					else
						{
		  				if (($atual == ($this->pagina -($numero_links_proximos-1)))&&($atual != 0))      
							{
	   		   				$array[++$indice] = "<b>...</b>";
	      					}		
          				$array[++$indice] = '<a href="' . $arquivo . '?pagina=' . $atual . $extra_vars . '">' . ($atual + 1) . '</a>';
		  				if (($atual == ($this->pagina +($numero_links_proximos-1)))&&($atual !=  $num_mult_pag-1))
							{
	    	  				$array[++$indice] = "<b>...</b>";
	      					}		  
						}		
      				}	  
				}
	  

      	// escreve a string direita (Proxima Pagina)
      	if ((($opcao == "todos") || ($opcao == "strings")) && ($atual == ($num_mult_pag - 1))) 
			{
        	if ($this->pagina != ($num_mult_pag - 1))
				{
          		$array[++$indice]  = '    <a href="' . $arquivo . '?pagina=' . ($this->pagina + 1) . $extra_vars . '">' . $this->str_proxima . '</a>';		
		   		$array[++$indice] = '    <a href="' . $arquivo . '?pagina=' . ($num_mult_pag-1) . $extra_vars . '">' . $this->str_ultima . '</a>';
				}  
        	elseif (($this->pagina == ($num_mult_pag - 1)) && ($mostra_string == "sim"))
				{
          		//$array[++$indice] = $this->str_proxima;
		  		//$array[++$indice] = $this->str_ultima;
				}
      		}
    	}
    return $array;
  	}

  	/*
     Este método é uma extensão do método Construir_Links() para
     que possa ser ajustado o limite 'n' de número de links na página.
     Isto é muito útil para grandes bancos de dados que desejam não
     ocupar todo o espaço da tela para mostrar toda a lista de links
     paginados.

         $array parâmetro:
          . retorna o array de Construir_Links()

         $atual parâmetro:
          . a variável da 'pagina' atual das páginas paginadas. ex: pagina=1

         $tamanho_desejado parâmetro:
          . o número desejado de links à serem exibidos
  	*/
  	function Mostrar_Parte($array, $atual, $tam_desejado)
  		{
    	$size = count($array);
    	if (($size <= 2) || ($size < $tam_desejado)) 
			{
      		$temp = $array;
    		}
    	else 
			{
      		$temp = array();
      		if (($atual + $tamanho_desejado) > $size) 
				{
        		$temp = array_slice($array, $size - $tam_desejado);
      			} 
			else 
				{
        		$temp = array_slice($array, $atual, $tam_desejado);
        		if ($size >= $tamanho_desejado) 
					{
          			array_push($temp, $array[$size - 1]);
        			}	
      			}
      		if ($atual > 0) 
				{
        		array_unshift($temp, $array[0]);
      			}
    		}
    	return $temp;
  		}
	}
?>
