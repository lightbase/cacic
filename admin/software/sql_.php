<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Untitled Document</title>
</head>

<body>
<? 
include_once "../../include/library.php";
   Conecta_bd_cacic();
   
   $nm_software_inventariado = strtoupper("update for");

   //$query = "SELECT * FROM softwares_inventariados where UCASE(nm_software_inventariado) like '%".$nm_software_inventariado."%' order by id_software_inventariado"; 
    $query = "SELECT * FROM softwares_inventariados where id_si_grupo is null order by id_software_inventariado"; 

   $result = mysql_query($query);  
	
   $conta = 1;	
   while($row = mysql_fetch_array($result)) {	
   		echo $conta." - ".$row['id_software_inventariado']." - ". $row['nm_software_inventariado']."<br>";
		$conta = $conta+1;	
		$sql_update = "Update softwares_inventariados set id_si_grupo = 1 where id_software_inventariado=".$row['id_software_inventariado'];
		mysql_query($sql_update) or die('Update falhou ou sua sessão expirou!');
   }
   
?>
</body>
</html>
