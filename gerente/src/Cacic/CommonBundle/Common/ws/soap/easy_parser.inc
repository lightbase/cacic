<? 

class easy_parser{ 

var $old_element = ""; 
var $attribute = array(); 
var $dado = ""; 
var $array_element = array(); 
var $array_attribute_value = array(); 
var $array_attributes = array(); 
var $array_nodes = array(); 
var $current_element = ""; 
var $array_texto = array(); 
var $get_err = ""; 
var $texto = ""; 

function getpart($texto,$string,$reset=TRUE,$position=0) 
{ 
  static $p0=0; 
  $p0 += $position; 
  if($reset){ 
    $p0 = 0; 
  } 
  $get = ""; 
  $string = trim($string); 
  if(strpos($texto,"<$string>",$p0) >=0) 
  { 
    $p1 =  strpos($texto,"<$string>",$p0); 
    $p2 = strpos($texto,"</$string>",$p0); 
    $len = strlen("<$string>"); 
    $get = substr($texto,($p1 + $len),($p2 - $p1 - $len)); 
    $p0 = $p2+$len; 
  } 
  return $get; 
} 


function getrpart($texto,$string) 
{ 
   
  $get = ""; 
  $string = trim($string); 

  if(strrpos($texto,"<$string>") >=0) 
  { 

    $p1 =  strrpos($texto,"<$string>"); 
    $texto2 = substr($texto,0,$p1); 
    $p2 = strrpos($texto2,"</$string>"); 
    $len = strlen("<$string>"); 
    $get = substr($texto2,$p2); 
  } 
  return $get; 
} 

function parser($document,$is_file=TRUE){ 
    $result = TRUE; 
    $parser = xml_parser_create(); 
    xml_set_object($parser,&$this);      
    xml_parser_set_option($parser,XML_OPTION_CASE_FOLDING, 0); 
    xml_set_element_handler($parser,"start_element","end_element"); 
    xml_set_character_data_handler($parser,"character_data"); 

    if($is_file){ 
      if(file_exists($document)){ 
          
         $xml_file = fopen($document,"r"); 
         $data = ""; 
         $ind = 0; 
        while (!feof($xml_file)) { 
           $data = fgets($xml_file,256); 
           if(ereg("<!ENTITY.*SYSTEM",$data,$matchs) && strpos($data,"NDATA") < 1){ 
            $arrai = split(" ",$data); 
            $arrai[3] = str_replace("\">","",$arrai[3]); 
            $arrai[3] = trim(str_replace("\"","",$arrai[3])); 
            if(file_exists($arrai[3])){ 
                $fp = fopen($arrai[3],"r"); 
                while(!feof($fp)){ 
                    $ext_val[$ind] .= fgets($fp,1024); 
                } 
                fclose($fp); 
                $ext_key[$ind] = trim($arrai[1]); 
                ++$ind; 
            } 
           } 

           $this->texto .= $data; 
        } 
          
        fclose($xml_file); 
      }else{ 
        print "<b><h3>The file: <font color=\"blue\">$document</font> not found.</b><h3>"; 
      } 
    }else{ 
      $this->texto = $document;      
     } 

    if(isset($ext_val)){ 
      $ind = 0; 
      foreach($ext_val as $value){ 
        $this->texto = str_replace("&".$ext_key[$ind].";","$value\\n",$this->texto); 
        ++$ind; 
      } 
    } 
    $this->array_texto = split("\\n",$this->texto); 

     if (!xml_parse($parser, $this->texto,TRUE)) { 
        $err_line = $this->array_texto[(xml_get_current_line_number($parser)-1)]; 
        $err_col = xml_get_current_column_number($parser); 
        $text1 = "<font color=\"blue\" size=\"3\" face=\"arial\">".htmlentities(substr($err_line,0,($err_col -1)))."</font><font color=\"red\" size=\"3\" face=\"arial\">"; 
        $text2 = htmlentities(substr($err_line,($err_col),1))."</font><font color=\"blue\" size=\"3\" face=\"arial\">"; 
        $text3 = htmlentities(substr($err_line,($err_col +1)))."<font>"; 
        $this->get_err = "<font color=\"black\" size=\"3\" face=\"arial\">Fonte do documento: $document<br>" 
        ."<b>XML error: </b>".xml_error_string(xml_get_error_code($parser))." <b>at</b> line " 
        .xml_get_current_line_number($parser)." and colunm $err_col<br>" 
        ."<b> Texto: </b>$text1$text2$text3"; 
        $result = FALSE; 
    } 

    xml_parser_free($parser); 
    return $result; 
} 

function start_element($parser,$element_name,$attributes){ 
  $this->attribute = $attributes; 
  $this->dado = ""; 
  $this->current_element = $element_name; 
    
  if(!in_array($element_name,$this->array_nodes)){ 
    $this->array_nodes[] = $element_name; 
  } 
     
  while(list($key,$value) = each($attributes)){ 
      $attr[] = $key; 
    } 
$this->array_attributes[$this->current_element] = $attr; 

} 

function end_element($parser,$element_name){ 
  if(trim($this->dado) != ""){ 
    $this->array_element[$this->current_element][] = $this->dado; 
  } 
  reset($this->attribute); 
  $this->dado = ""; 
  if(count($this->attribute)>0){ 
      $this->array_attribute_value[$this->current_element][] = $this->attribute; 
  } 
} 


function character_data($parser,$data){ 
  $this->dado .= $data; 
     
} 


function get_element_value($element,$ind = 0){ 
    return $this->array_element[$element][$ind]; 
} 



function get_element_rows($element){ 
    return count($this->array_element[$element]); 
} 



function get_elements(){ 
    return $this->array_nodes; 
} 


function get_element_attribute($element,$ind = 0,$attribute){ 
    return $this->array_attribute_value[$element][$ind][$attribute]; 
} 



function get_attributes($element){ 
    return $this->array_attributes[$element]; 
} 

function view_source(){ 
    $retorno = htmlentities($this->texto); 
    return  str_replace("&gt;","&gt;<br>",$retorno); 
} 

} 

?> 
