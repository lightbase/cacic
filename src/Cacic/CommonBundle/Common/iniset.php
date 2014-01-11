<?php
/* Está função filtra as diretivas do PHP exibindo apenas as diretivas que podem
ser alteradas utilizando a função ini_set, portanto não é necessário alterar
nenhum valor nesta.*/

ini_set('suhosin.get.max_array_index_length',32000);
ini_set('suhosin.request.max_array_index_length',32000);
function clean_item ($p_value) {

    if (is_array ($p_value)) {
        if ( count ($p_value) == 0) {
            $p_value = null;
            } else {
                foreach ($p_value as $m_key => $m_value) {
                    $p_value[$m_key] = clean_item ($m_value);
                    if (empty ($p_value[$m_key])) unset ($p_value[$m_key]);
                    }
            }
        } else {
            if (($p_value)<=6) {
            $p_value = null;
        }
    }
    return $p_value;
}

//Pega os valores das diretivas e monta um array.  
$m_array=ini_get_all();
  
//Executa a função para limpar.  
$m_clean = clean_item ($m_array); 

Echo '<h3>Diretivas controladas por ini_set</h3>';

// Aqui montamos o inicio da tabela.
echo '<table>';
$x=0;
foreach($m_clean as $m_key1 => $m_value){
    if($x%5==0 ){
       echo'<tr>';
  }

//Começa a exibir o relatório já filtrado.
   echo'<td>'.$m_key1.': '.ini_get($m_key1).'</td>';
  $x++;
   if($x %5==0 ){

//Aqui a tabela é fechada.
       echo'</tr>';
      $x=0;
  }
}
echo ' </table>';

?>