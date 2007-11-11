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
/*
 * verifica se houve login e também regras para outras verificações (ex: permissões do usuário)!
 */
if(!isset($_SESSION['id_usuario'])) 
  die('Acesso negado!');
else { // Inserir regras para outras verificações (ex: permissões do usuário)!
}


			  /*********************************************/
			  /*  PHP TreeMenu 1.1                         */
			  /*  Author: Bjorge Dijkstra                  */
			  /*  email : bjorge@gmx.net                   */
			  /*  Placed in Public Domain                  */
			  /*********************************************/
			
			  if(isset($PATH_INFO)) 
			  {
				  $script       =  $PATH_INFO; 
			  } 
			  else 
			  {
				  $script	=  $SCRIPT_NAME;
			  }
			
			  $img_expand   = "../../imgs/arvore/tree_expand.gif";
			  $img_collapse = "../../imgs/arvore/tree_collapse.gif";
			  $img_line     = "../../imgs/arvore/tree_vertline.gif";  
			  $img_split	= "../../imgs/arvore/tree_split.gif";
			  $img_end      = "../../imgs/arvore/tree_end.gif";
			  $img_leaf     = "../../imgs/arvore/tree_leaf.gif";
			  $img_spc      = "../../imgs/arvore/tree_space.gif";
			  $img_workgroup= "../../imgs/arvore/tree_workgroup.gif";  
			
			 
			  /*********************************************/
			  /* read file to $tree array                  
				 tree[x][0] -> tree level                  
				 tree[x][1] -> item text                   
				 tree[x][2] -> item link                   
				 tree[x][3] -> link target                 
				 tree[x][4] ->  1 = Green   OK!
								2 = Yellow  Até 5 dias
								3 = Red     Acima de 5 dias
				 tree[x][5] -> Nome do WorkGroup  
				 tree[x][6] -> last item in subtree        
			  /*********************************************/
			  $maxlevel=0;
			  $cnt=0;
			  $Arvore = explode('#',$Tripa);  
			  $TamanhoArvore = count($Arvore)-1;
			  while ($cnt <= $TamanhoArvore)
			  {
				$tree[$cnt][0]=strspn($Arvore[$cnt],".");
				$tmp=rtrim(substr($Arvore[$cnt],$tree[$cnt][0]));
				$node=explode("|",$tmp); 
				$tree[$cnt][1]=$node[0];
				if ($tree[$cnt][0]==3)
					{
					$tree[$cnt][2]=$node[1];
					}	
				$tree[$cnt][3]=$node[1];
				$tree[$cnt][4]=$node[1];	
				$tree[$cnt][5]=$node[2];
				$tree[$cnt][6]=1;	
				if ($tree[$cnt][0] > $maxlevel) $maxlevel=$tree[$cnt][0];    
				$cnt++;
			  }
						
			  for ($i=0; $i<count($tree); $i++) 
			  {
				 $expand[$i]=0;
				 $visible[$i]=0;
				 $levels[$i]=0;
			  }
			  //  Get Node numbers to expand	 
			  if ($p!="") $explevels = explode("|",$p);
			  
			  $i=0;
			  while($i<count($explevels))			  
			  {  
				$expand[$explevels[$i]]=1;	
				$i++;
			  }
			
			  //  Find last nodes of subtrees         
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
				   $tree[$i][6]=1;	   
				 }
				 else
				   $tree[$i][6]=0;	   
				 $lastlevel=$tree[$i][0];  
			  }
			 		  
			  // Determine visible nodes                  
 			  // all root nodes are always visible
			  for ($i=0; $i < count($tree); $i++) 
				{
				if ($tree[$i][0]==1) $visible[$i]=1;
				}
			
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
			  
			  
			  //  Output nicely formatted tree             		  
			  for ($i=0; $i<$maxlevel; $i++) $levels[$i]=1;
			
			  $maxlevel++;
			  ?>
			  <p align="center">
			  <table cellspacing=0 cellpadding=0 border=0 cols="<? echo ($maxlevel+3); ?>" width=500 align='left'>
			  <tr>
			  <?
			  for ($i=0; $i<$maxlevel; $i++) 
			  echo "<td width=16></td>";
			  echo "<td width=16></td>";    
			  echo "<td width=16></td></tr>";
			  $cnt=0;
			  while ($cnt<count($tree))
			  {
				if ($visible[$cnt])
				{
				  // start new row                        
				  echo "<tr>";
				  
				  // vertical lines from higher levels    
				  $i=0;
				  while ($i<$tree[$cnt][0]-1) 
				  {	  
					echo "<td><font size='2' face='Verdana, Arial, Helvetica, sans-serif'><a name='$cnt'></a><img src='";	  
					if ($levels[$i]==1)
						{
						//echo "<td><font size="2" face='Verdana, Arial, Helvetica, sans-serif'><a name='$cnt'></a><img src=\'".$img_line."\'></td>";
						echo $img_line;
						}
					else
						{
						//echo "<td><font size="2" face='Verdana, Arial, Helvetica, sans-serif'><a name='$cnt'></a><img src=\'".$img_spc."\'></td>";
						echo $img_spc;			
						}
					echo "'></td>";			
					$i++;
				  }
				  
				  // corner at end of subtree or t-split  
				  if ($tree[$cnt][6]==1) 	  
				  {
					echo "<td><img src=\"".$img_end."\"></td>";
					$levels[$tree[$cnt][0]-1]=0;
				  }
				  else
				  {
					echo "<td><img src=\"".$img_split."\"></td>";                  
					$levels[$tree[$cnt][0]-1]=1;    
				  } 
				  
				  // Node (with subtree) or Leaf (no subtree) 
				  if ($tree[$cnt+1][0]>$tree[$cnt][0])
				  {
					
					// Create expand/collapse parameters    
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
						   
					if ($expand[$cnt]==0)
						echo "<td><a href=\"".$script.$params."#$cnt\"><img src=\"".$img_expand."\" border=no></a></td>";
					else
						echo "<td><a href=\"".$script.$params."#$cnt\"><img src=\"".$img_collapse."\" border=no></a></td>";
				  }
				  else
				  {

					// Tree Leaf             
					if ($tree[$cnt][4]=='3')
						{
						$img_leaf = "../../imgs/arvore/tree_computer_red.gif";
						}
					elseif ($tree[$cnt][4]=='2')
						{
						$img_leaf = "../../imgs/arvore/tree_computer_yellow.gif";			
						}
					else
						{
						$img_leaf = "../../imgs/arvore/tree_computer_green.gif";			
						}
						echo "<td><img src=\"".$img_leaf."\"></td>";         
				  }
				  

				  // output item text                     
				  echo "<td colspan=".($maxlevel-$tree[$cnt][0])." width=450 valign='middle' nowrap><font size='2' face='Verdana, Arial, Helvetica, sans-serif'>";
			
				  if ($tree[$cnt][2]=="")
					{
					if ($tree[$cnt][0]==2) 
						{
						echo "<img src=\"".$img_workgroup."\">";
						}			
					echo $tree[$cnt][1];
					}
				  else
					{
					  echo '<a href='.$tree[$cnt][1]."\" target='_top'>".$tree[$cnt][1]."</a>";
					}

				  // end row                           
				  echo "</tr>";                
				}
				$cnt++;
		}
?>
</table>