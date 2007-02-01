<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Busca Instantanea com Ajax</title>
<style type="text/css">
/*<![CDATA[*/
<!--
body {
	margin: 2px;
	padding: 2px;
	background-color: #fff;
	color: #000;
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: small;
}
h1 {
	font-family:Georgia, "Times New Roman", Times, serif;
	font-size: 2.5em;
}
h1 small {
	display: block;
	font-size: 0.4em;
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-weight: normal;
}
fieldset {
	border: 1px solid #ccc;
	padding: 10px;
}
legend {
}
label {
	display: block;
	margin-bottom: 5px;
}
label span {
	text-decoration: underline;
	font-weight: bold;
}
#q {
	width: 250px;
}
-->
/*]]>*/
</style>
</head>

<body>

<h1>Busca Instantânea <small>Por: Leandro Vieira Pinho</small></h1>

<form method="get" action="busca.php" id="frmBusca">
	<fieldset>
		<legend>Busca Instantânea</legend>
		
		<p>
			<label for="q"><span>P</span>rocurando por? (<small>digite no mínimo três caracteres</small>)</label>
			<input type="text" id="q" name="q" accesskey="p" tabindex="1" />
		</p>
		<noscript>
			<p>
				<input type="submit" id="btnSubmit" name="btnSubmit" value="OK" />
			</p>
		</noscript>
	</fieldset>
</form>

<fieldset>
	<legend>Resultado da busca</legend>
	<div id="resultadoBusca">&nbsp;</div>
</fieldset>

</body>
</html>