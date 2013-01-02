<?php
/*
 Copyright 2000, 2001, 2002, 2003, 2004, 2005 Dataprev - Empresa de Tecnologia e Informa��es da Previd�ncia Social, Brasil

 Este arquivo � parte do programa CACIC - Configurador Autom�tico e Coletor de Informa��es Computacionais

 O CACIC � um software livre; voc� pode redistribui-lo e/ou modifica-lo dentro dos termos da Licen�a P�blica Geral GNU como 
 publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a, ou (na sua opni�o) qualquer vers�o.

 Este programa � distribuido na esperan�a que possa ser  util, mas SEM NENHUMA GARANTIA; sem uma garantia implicita de ADEQUA��O a qualquer
 MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU para maiores detalhes.

 Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "LICENCA.txt", junto com este programa, se n�o, escreva para a Funda��o do Software
 Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

================================================================================================================================
A classe navbar de Copyright Joao Prado Maia (jpm@phpbrasil.com) e tradu��o de
Thomas Gonzalez Miranda (thomasgm@hotmail.com) baixada do site www.phpbrasil.com
em 06/05/2002 foi modificada para melhor entendimento do seu funcionamento e
aperfei�oada deste que apareceram alguns "bugs", sendo transformada como classe
Mult_Pag (Multiplas paginas).
As informa��es acima foram retiradas da vers�o 1.3 da classe navbar do arquivo
navbar.zip.

Construi esta pequena classe para navega��o din�mica de links. Observe
por favor a simplicidade deste c�digo. Este c�digo � livre em
toda maneira que voc� puder imaginar. Se voc� o usar em seu
pr�prio script, por favor deixo os cr�ditos como est�o. Tamb�m,
envie-me um e-mail se voc� o fizer, isto me deixa feliz :-)

Adapta��es Realizadas / Motivos:
-------------------------------
06 a 09/05/2002	: Marco A. D. Freitas (madf@splicenet.com.br)
26/06/2006 		: Paulo Enok Sawazaki (pauloeno@yahoo.com.br)
24/06/2008		: Anderson Peterle (anderson@peterles.com)
				  Motivo: adequa��o para uso no Sistema CACIC - Configurador Autom�tico e Coletor de Informa��es Computacionais
				  http://www.softwarepublico.gov.br/ver-comunidade?community_id=3585
================================================================================================================================
*/
// classe que multiplica paginas
class Mult_Pag {
  // Valores padr�o para a navega��o dos links
  /*
   * define o n�mero de pesquisas (detalhada ou n�o) por p�gina
   */
  var $num_pesq_pag;
  var $str_anterior = "<span class='prev'>&nbsp;&nbsp;&nbsp;&nbsp;</span>Anterior";
  var $str_proxima 	= "Pr�xima<span class='next'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>";
  var $str_primeira	= "<span class='first'>&nbsp;&nbsp;&nbsp;&nbsp;</span>Primeira";
  var $str_ultima	= "�ltima<span class='last'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>";
  // Vari�veis usadas internamente
  var $nome_arq;
  var $total_reg;
  var $pagina;

  /*
     Metodo construtor. Isto � somente usado para setar
     o n�mero atual de colunas e outros m�todos que
     podem ser re-usados mais tarde.
  */
  function Mult_Pag ($num_lin_pag=100, $num_link_pag=100)
  {
    global $pagina;
    $this->pagina = $pagina ? $pagina : 0;
    $this->num_pesq_pag = $num_lin_pag;
  }

  /*
     O pr�ximo m�todo roda o que � necess�rio para as queries.
     � preciso rod�-lo para que ele pegue o total
     de colunas retornadas, e em segundo para pegar o total de
     links limitados.

         $sql par�metro:
           . o par�metro atual da query que ser� executada

         $conexao par�metro:
           . a liga��o da conex�o do banco de dados

         $tipo par�metro:
           . "mysql" - usa fun��es php mysql
           . "pgsql" - usa fun��es pgsql php
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
     Este m�todo cria uma string que ir� ser adicionada �
     url dos links de navega��o. Isto � especialmente importante
     para criar links din�micos, ent�o se voc� quiser adicionar
     op��es adicionais � estas queries, a classe de navega��o
     ir� adicionar automaticamente aos links de navega��o
     din�micos.
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
     Este m�todo cria uma liga��o de todos os links da barra de
     navega��o. Isto � �til, pois � totalmente independete do layout
     ou design da p�gina. Este m�todo retorna a liga��o dos links
     chamados no script php, sendo assim, voc� pode criar links de
     navega��o com o conte�do atual da p�gina.

         $opcao par�metro:
          . "todos" - retorna todos os links de navega��o
          . "numeracao" - retorna apenas p�ginas com links numerados
          . "strings" - retornar somente os links 'Pr�xima' e/ou 'Anterior'

         $mostra_string par�metro:
          . "nao" - mostra 'Pr�xima' ou 'Anterior' apenas quando for necess�rios
          . "sim" - mostra 'Pr�xima' ou 'Anterior' de qualqur maneira
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
		   		$array[++$indice] = '����<a href="' . $arquivo . '?pagina=' . ($num_mult_pag-1) . $extra_vars . '">' . $this->str_ultima . '</a>';
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
     Este m�todo � uma extens�o do m�todo Construir_Links() para
     que possa ser ajustado o limite 'n' de n�mero de links na p�gina.
     Isto � muito �til para grandes bancos de dados que desejam n�o
     ocupar todo o espa�o da tela para mostrar toda a lista de links
     paginados.

         $array par�metro:
          . retorna o array de Construir_Links()

         $atual par�metro:
          . a vari�vel da 'pagina' atual das p�ginas paginadas. ex: pagina=1

         $tamanho_desejado par�metro:
          . o n�mero desejado de links � serem exibidos
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
