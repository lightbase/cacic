<?php  
session_start();
require_once('library.php');
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
  /*********************************************/
  /*  PHP TreeMenu 1.1                         */
  /*                                           */
  /*  Author: Bjorge Dijkstra                  */
  /*  email : bjorge@gmx.net                   */
  /*                                           */  
  /*  Placed in Public Domain                  */
  /*                                           */  
  /*********************************************/

  /*********************************************/
  /*  Settings                                 */
  /*********************************************/
  /*                                           */      
  /*  $treefile variable needs to be set in    */
  /*  main file                                */
  /*                                           */ 
  /*********************************************/
  
  if(isset($PATH_INFO)) {
	  $script       =  $PATH_INFO; 
  } else {
	  $script	=  $SCRIPT_NAME;
  }

  $img_expand   = "imgs/arvore/tree_expand.gif";
  $img_collapse = "imgs/arvore/tree_collapse.gif";
  $img_line     = "imgs/arvore/tree_vertline.gif";  
  $img_split	= "imgs/arvore/tree_split.gif";
  $img_end      = "imgs/arvore/tree_end.gif";
  $img_leaf     = "imgs/arvore/tree_leaf.gif";
  $img_spc      = "imgs/arvore/tree_space_menu_esq.gif";
  $img_begin	= "imgs/arvore/tree_begin.gif";
 
  /*********************************************/
  /*  Read text file with tree structure       */
  /*********************************************/
  
  /*********************************************/
  /* read file to $tree array                  */
  /* tree[x][0] -> tree level                  */
  /* tree[x][1] -> item text                   */
  /* tree[x][2] -> item link                   */
  /* tree[x][3] -> link target                 */
  /* tree[x][4] -> item image         NEW      */
  /* tree[x][5] -> item title         NEW      */
  /* tree[x][6] -> show item ?        NEW      */
  /* tree[x][7] -> last item in subtree        */
  /*********************************************/

  $maxlevel=0;
  $cnt=0;
  
  $fd = fopen($treefile, "r");
  if ($fd==0) die("Não foi possível acessar o arquivo '".$treefile."' ou sua sessão expirou!");
  while ($buffer = fgets($fd, 4096)) 
	{
    $tree[$cnt][0]=strspn($buffer,".");
    $tmp=rtrim(substr($buffer,$tree[$cnt][0]));
    $node=explode("|",$tmp);

	if (($node[0] <> 'Atualização Especial' and $node[0] <> 'Consulta Especial') or $_SESSION['cs_nivel_administracao']=='1')
		{
		// Desta forma eu permito apenas o nível "Administração" nas opções Atualização Especial e Consulta Especial.		
	    $tree[$cnt][1]=$node[0];
    	$tree[$cnt][2]=$node[1];
	    $tree[$cnt][3]=$node[2];	
		$tree[$cnt][4]=$node[3];
		$tree[$cnt][5]=$node[4];
		$tree[$cnt][6]=$node[5];
	    $tree[$cnt][7]=0;
    
	    if ($tree[$cnt][0] > $maxlevel) $maxlevel=$tree[$cnt][0];    
	    $cnt++;
		}
  	}
  fclose($fd);

  for ($i=0; $i<count($tree); $i++) {
     $expand[$i]=0;
     $visible[$i]=0;
     $levels[$i]=0;
  }

  /*********************************************/
  /*  Get Node numbers to expand               */
  /*********************************************/
 
  if ($_GET['p']!="") $explevels = explode("|",$_GET['p']);
  
  $i=0;
  while($i<count($explevels))
  {
	$expand[$explevels[$i]]=1;
    $i++;
  }


  /*********************************************/
  /*  Permito apenas uma expansão              */
  /*********************************************/
  $i=0;
  while($i<count($tree))
  	{
	if ($tree[$i][0] == 1 and $_GET['v_no'])
		{
		if ($_GET['v_no'] <> $i )
			{
		   	$expand[$i]=0;		
			}	
		}
    $i++;
  	}

  
  /*********************************************/
  /*  Find last nodes of subtrees              */
  /*********************************************/
  
  $lastlevel=$maxlevel;
  for ($i=count($tree)-1; $i>=0; $i--)
  {
     if ( $tree[$i][0] < $lastlevel )
     {
       for ($j=$tree[$i][0]+1; $j <= $maxlevel; $j++)
       {
          $levels[$j]=0;
       }
     }
     if ( $levels[$tree[$i][0]]==0 )
     {
       $levels[$tree[$i][0]]=1;
       $tree[$i][7]=1;
     }
     else
       $tree[$i][7]=0;
     $lastlevel=$tree[$i][0];  
  }
  
  
  /*********************************************/
  /*  Determine visible nodes                  */
  /*********************************************/
  
// all root nodes are always visible
  for ($i=0; $i < count($tree); $i++) if ($tree[$i][0]==1) $visible[$i]=1;


  for ($i=0; $i < count($explevels); $i++)
  {
    $n=$explevels[$i];
    if ( ($visible[$n]==1) && ($expand[$n]==1) )
    {
       $j=$n+1;
       while ( $tree[$j][0] > $tree[$n][0] )
       {
         if ($tree[$j][0]==$tree[$n][0]+1) $visible[$j]=1;     
         $j++;
       }
    }
  }
  
  
  /*********************************************/
  /*  Output nicely formatted tree             */
  /*********************************************/
  
  for ($i=0; $i<$maxlevel; $i++) $levels[$i]=1;

  $maxlevel++;
  
  echo "<table cellspacing=0 cellpadding=0 border=0 cols=".($maxlevel+3)." width=100%>\n";
  echo "<tr>";
  for ($i=0; $i<$maxlevel; $i++) echo "<td width=16></td>";
  echo "<td width=100%>&nbsp;</td></tr>\n";
  $cnt=0;
  while ($cnt<count($tree))
  {
    if ($tree[$cnt][6]) 
		{	
		$ShowTheNode=0;
		}
	else
		{
		$ShowTheNode=1;
		}

    if ($visible[$cnt] and $ShowTheNode)
      { 	  	  
      /****************************************/
      /* start new row                        */
      /****************************************/      
      echo "<tr nowrap>";
      
      /****************************************/
      /* vertical lines from higher levels    */
      /****************************************/
      $i=0;
      while ($i<$tree[$cnt][0]-1) 
      {
        if ($levels[$i]==1)
            echo "<td nowrap><a name='$cnt'></a><img src=\"".$img_line."\"></td>";
        else
            echo "<td nowrap><a name='$cnt'></a><img src=\"".$img_spc."\"></td>";
        $i++;
      }
      
      /****************************************/
      /* corner at end of subtree or t-split  */
      /****************************************/         
      if ($tree[$cnt][7]==1) 
      {
        echo "<td nowrap><img src=\"".$img_end."\"></td>";
        $levels[$tree[$cnt][0]-1]=0;
      }
      else
      {
	  	$levels[$tree[$cnt][0]-1]=1; 
	  	if ($cnt == 0)
		{
			echo "<td nowrap><img src=\"".$img_begin."\"></td>"; 
		}
		else
		{
        	echo "<td nowrap><img src=\"".$img_split."\"></td>";                  
        }
      } 
      
      /********************************************/
      /* Node (with subtree) or Leaf (no subtree) */
      /********************************************/
      if ($tree[$cnt+1][0]>$tree[$cnt][0])
      {
        
        /****************************************/
        /* Create expand/collapse parameters    */
        /****************************************/
        $i=0; $params="?p=";		
        while($i<count($expand))
        {
          if ( ($expand[$i]==1) && ($cnt!=$i) || ($expand[$i]==0 && $cnt==$i))
          {
            $params=$params.$i;
            $params=$params."|";
          }
          $i++;
        }
		if ($tree[$cnt][0]==1) $params.="&v_no=".$cnt;				
//        echo "<td nowrap>";               
        if ($expand[$cnt]==0)
			{
            echo "<td nowrap><a href=\"".$script.$params."#$cnt\"><img src=\"".$img_expand."\" border=no></a></td>";
//            echo "<a href=\"".$script.$params."#$cnt\"><img src=\"".$img_expand."\" border=no></a>";
//			echo "<a href=\"".$script.$params."#$cnt\"><img src=\"".$tree[$cnt][4]."\" border=no></a>";
			}
        else
			{
            echo "<td nowrap><a href=\"".$script.$params."#$cnt\"><img src=\"".$img_collapse."\" border=no></a></td>";         
//            echo "<a href=\"".$script.$params."#$cnt\"><img src=\"".$img_collapse."\" border=no></a>";
//            echo "<a href=\"".$script.$params."#$cnt\"><img src=\"".$tree[$cnt][4]."\" border=no></a>";
			}
//		echo "</td>";				
      }
      else
      {
        /*************************/
        /* Tree Leaf             */
        /*************************/
        if ($tree[$cnt][4])
        	{
			echo "<td nowrap><img src=\"".$tree[$cnt][4]."\"></td>";
			}
		else
			{
        	echo "<td nowrap><img src=\"".$img_leaf."\"></td>";         
			}
      }
      
      /****************************************/
      /* output item text                     */
      /****************************************/
      if ($tree[$cnt][2]=="")
//          echo "<td colspan=".($maxlevel-$tree[$cnt][0]).">".$tree[$cnt][1]."</td>";
          echo "<td nowrap colspan=".($maxlevel-$tree[$cnt][0])."><a href=\"".$script.$params."#$cnt\">".$tree[$cnt][1]."</a></td>";
      else
	  	{	  
		// Mostro as opções de Unidades Organizacionais conforme convencionadas...
		
		$strAux = 'U. O. Nível 2';	

		$pos1 = stripos2($tree[$cnt][1],$strAux,false);	
		if ($pos1)	
			{
			$tree[$cnt][1] = str_replace($strAux,$_SESSION['plural_etiqueta2'],$tree[$cnt][1]);					
			}

		$strAux = 'U. O. Nível 1a';	

		$pos1 = stripos2($tree[$cnt][1],$strAux,false);	
		if ($pos1)	
			{
			$tree[$cnt][1] = str_replace($strAux,$_SESSION['plural_etiqueta1a'],$tree[$cnt][1]);					
			}

		$strAux = 'U. O. Nível 1';	

		$pos1 = stripos2($tree[$cnt][1],$strAux,false);	
		if ($pos1)	
			{
			$tree[$cnt][1] = str_replace($strAux,$_SESSION['plural_etiqueta1'],$tree[$cnt][1]);					
			}
			
        echo "<td nowrap colspan=".($maxlevel-$tree[$cnt][0])."><a href=\"".$tree[$cnt][2]."\" target=\"".$tree[$cnt][3]."\" title=\"".$tree[$cnt][5]."\">".$tree[$cnt][1]."</a></td>";
        }  
      /****************************************/
      /* end row                              */
      /****************************************/
              
      echo "</tr>\n";      
    }
    $cnt++;    
  }
  echo "</table>\n";
?>