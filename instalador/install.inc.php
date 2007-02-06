<?php
/*
	Versão modificada do arquivo install.inc.php do DotProject
	Modificação por Thomaz de Oliveira dos Reis (thor27 EM gmail PONTO com).
*/
function dPmsg($msg)
{
 echo $msg . "\n";
 flush();
}

/*
* Utility function to split given SQL-Code
* @param $sql string SQL-Code
* @param $last_update string last update that has been installed
*/

function InstallSplitSql($sql, $last_update) {
 global $lastDBUpdate;

 $buffer = array();
 $ret = array();

 $sql = trim($sql);

 $matched =  preg_match_all('/\n#\s*(\d{8})\b/', $sql, $matches);
 if ($matched) {
	 // Used for updating from previous versions, even if the update
	 // is not correctly set.
	 $len = count($matches[0]);
   $lastDBUpdate = $matches[1][$len-1];
 }
 
 if ($last_update && $last_update != '00000000') {
  // Find the first occurrance of an update that is
  // greater than the last_update number.
  dPmsg("Checking for previous updates");
  if ($matched) {
   for ($i = 0; $i < $len; $i++) {
    if ((int)$last_update < (int)$matches[1][$i]) {
     // Remove the SQL up to the point found
     $match = '/^.*' . trim($matches[0][$i]) . '/Us';
     $sql = preg_replace($match, "", $sql);
     break;
    }
   }
   // If we run out of indicators, we need to debunk, otherwise we will reinstall
   if ($i == $len)
    return $ret;
  }
 }
 $sql = ereg_replace("\n#[^\n]*\n", "\n", $sql);

 $in_string = false;

 for($i=0; $i<strlen($sql)-1; $i++) {
  if($sql[$i] == ";" && !$in_string) {
   $ret[] = substr($sql, 0, $i);
   $sql = substr($sql, $i + 1);
   $i = 0;
  }

  if($in_string && ($sql[$i] == $in_string) && $buffer[1] != "\\") {
   $in_string = false;
  }
  elseif(!$in_string && ($sql[$i] == '"' || $sql[$i] == "'") && (!isset($buffer[0]) || $buffer[0] != "\\")) {
   $in_string = $sql[$i];
  }
  if(isset($buffer[1])) {
   $buffer[0] = $buffer[1];
  }
  $buffer[1] = $sql[$i];
 }

 if(!empty($sql)) {
  $ret[] = $sql;
 }
 return($ret);
}

function InstallLoadSQL($sqlfile,$connection ,$last_update = null)
{

 // Don't complain about missing files.
 if (! file_exists($sqlfile))
	return;

 $mqr = @get_magic_quotes_runtime();
 @set_magic_quotes_runtime(0);

 $pieces = array();
 if ($sqlfile) {
  $query = fread(fopen($sqlfile, "r"), filesize($sqlfile));
  $pieces  = InstallSplitSql($query, $last_update);
 }

 @set_magic_quotes_runtime($mqr);
 $errors = 0;
 $piece_count = count($pieces);

 for ($i=0; $i<$piece_count; $i++) {
  $pieces[$i] = trim($pieces[$i]);
  if(!empty($pieces[$i]) && $pieces[$i] != "#") {
   if (!$result = mysql_query($pieces[$i],$connection)) {
    $errors++;
   }
  }
 }
 if ($errors > 0) {
	 dPmsg("AVISO: $errors dos $piece_count comandos SQL, n�o foram executados.");
	return false;
 }
 return true;
}
?>
