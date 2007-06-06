<html>
<head>
<title>Untitled Document</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body>
<?
include 'include/library.php';
echo 'Seu IP é: ' . $_SERVER['REMOTE_ADDR'] . '<br><br>';
$ip=exec("ifconfig | grep 'inet end.: 10' | cut -f13 -d' '");
$ip=exec("/sbin/ifconfig");

echo 'O IP do servidor é: *'.$ip.'*<br>';
if ($_REQUEST['txtData'])
	{
	$date 	= date('m-d-Y'); 
	$result = explode('-',$_REQUEST["txtData"]);	
	$diference = date_diff($date,trim(substr($result[1],0,2)).'-'.$result[2].'-'.$result[0]);	
	echo 'A diferença de dias é de: ' . $diference .'<br>';	
	}

?>
<form name="form1" method="post" action="">
  <input type="text" name="txtData">
</form>
</body>
</html>
