<html>
	<meta http-equiv="Content-type" content="text/html;charset=UTF-8">
	<body>
		Clique no botão abaixo para extrair os dados do banco do cacic 2.6 usando o padrão do banco de dados da versão 3.0<br>
		Obs: Esse processo pode demorar alguns minutos<br><br>
    	<center><button onclick="window.location.href='extrai_cacic30.php'">Extrair dados</button></center>

		Todos os registros de aquisicao_item com um id_software não existente na tabela software serão ignorados.<br>
		Todos os registros de computador com id_ip_rede = 0.0.0.0 serão ignorados.<br>
		Todos os registros de rede_grupo_ftp com id_ftp repetidos serão ignorados.<br>
		Todos os registros de unid_organizacional_nivel1a com id do id_unid_organizacional_nivel1 = 0 serão ignorados.<br>
		Todos os registros de unid_organizacional_nivel2 com id_local = 0 serão ignorados.<br>
		Todos os registros de usb_device com nm_device e id_vendor e id_device repetidos serão ignorados.<br>
		Todas as aspas duplas dos registros serão removidas.
	</body>
</html>