	<SCRIPT LANGUAGE="JavaScript">
	function SetaDescGrupo(p_descricao,p_destino) 
		{
		document.forms[0].elements[p_destino].value = p_descricao;		
		}
			
	function valida_form() 
		{
		if (document.form.frm_id_local.selectedIndex==0) 
			{	
			alert("O local do usuário é obrigatório");
			document.form.frm_id_local.focus();
			return false;
			}
	
		if (document.form.frm_nm_usuario_acesso.value == "" ) 
			{	
			alert("A identificação do usuário é obrigatória");
			document.form.frm_nm_usuario_acesso.focus();
			return false;
			}
		if (document.form.frm_nm_usuario_completo.value == "" ) 
			{	
			alert("O nome completo do usuário é obrigatório");
			document.form.frm_nm_usuario_completo.focus();
			return false;
			}
	
		if (document.form.frm_sel_id_locais_secundarios.value != "" ) 
			{
			var intLoop;
			var strIdLocaisSecundarios;
			
			strIdLocaisSecundarios = "";
			
			for (intLoop = 0; intLoop < document.form.frm_sel_id_locais_secundarios.options.length; intLoop++)
				{
				if (document.form.frm_sel_id_locais_secundarios.options[intLoop].selected)
					{
					if (strIdLocaisSecundarios != "")
						strIdLocaisSecundarios += ",";
						
					strIdLocaisSecundarios += Trim(document.form.frm_sel_id_locais_secundarios.options[intLoop].value);
					}
				}
			document.form.frm_te_locais_secundarios.value = strIdLocaisSecundarios;
			}		
		return true;
		}
	arrayServidoresAutenticacao = new Array(
	<?php
	$sql='SELECT DISTINCT sa.id_servidor_autenticacao,
				 sa.nm_servidor_autenticacao,
				 l.id_local	             
		  FROM   locais l,
		  		 redes r, 
				 servidores_autenticacao sa 
		  WHERE ';
	$where = '';
	if ($_SESSION['te_locais_secundarios']<>'')
		{
		$where = ' l.id_local = '.$_SESSION['id_local'].' OR l.id_local in ('.$_SESSION['te_locais_secundarios'].') ';
		$where .= ' AND ';
		}
	$where .= ' l.id_local = r.id_local AND r.id_servidor_autenticacao = sa.id_servidor_autenticacao';		
	
	$sql .= $where . ' ORDER BY sa.nm_servidor_autenticacao'; 

	conecta_bd_cacic();

	$sql_result=mysql_query($sql); 
	$num=mysql_numrows($sql_result); 
	$contador = 0;
	while ($row=mysql_fetch_array($sql_result))
		{ 
		$contador ++;
		if ($contador > 1)
			echo ",\n";
	
		$v_id_local					= $row["id_local"]; 
		$v_id_servidor_autenticacao = $row["id_servidor_autenticacao"] . '__' . $row["nm_servidor_autenticacao"]; 
		echo "new Array(\"$v_id_local\", \"$v_id_servidor_autenticacao\")"; 
		} 
	echo ");\n"; 	
	?> 
	
	function fillModoAutenticacao(itemLocalPrimario,selectModoAutenticacao) 
		{ 	
		var i, j; 
		selectModoAutenticacao.disabled = true;			
		// empty existing items 
		for (i = selectModoAutenticacao.options.length; i >= 0; i--) 
			{ 
			selectModoAutenticacao.options[i] = null; 
			} 
	
		if (itemLocalPrimario != null && itemLocalPrimario != 0) 
			{ 
			selectModoAutenticacao.size 	= 5;
			selectModoAutenticacao.disabled = false;			
			j = 1;
			// add new items 
			selectModoAutenticacao.options[0] = new Option("Base CACIC","0"); 		

			for (i = 0; i < arrayServidoresAutenticacao.length; i++) 
				{ 
				if (arrayServidoresAutenticacao[i][0] == itemLocalPrimario)
					{
					arrayServidorAutenticacao 				= arrayServidoresAutenticacao[i][1].split('__');
					selectModoAutenticacao.options[j] 		= new Option(''); 
					selectModoAutenticacao.options[j].value = arrayServidorAutenticacao[0]; 					
					selectModoAutenticacao.options[j].text 	= arrayServidorAutenticacao[1]; 										
					j++; 
					}				
				} 

			if (j > 1)
				selectModoAutenticacao.options[1].selected = true; 
			else
				selectModoAutenticacao.options[0].selected = true; 			
		   } 
		return true;		  
		}
	
//			
	arrayLocais = new Array(
	<?php
	$sql='select * from locais ';
	$where = '';
	if ($_SESSION['te_locais_secundarios']<>'')
		{
		$where = ' where id_local = '.$_SESSION['id_local'].' OR id_local in ('.$_SESSION['te_locais_secundarios'].') ';
		}
	$sql .= $where . ' order by nm_local'; 

	conecta_bd_cacic();

	$sql_result=mysql_query($sql); 
	$num=mysql_numrows($sql_result); 
	$contador = 0;
	while ($row=mysql_fetch_array($sql_result))
		{ 
		$contador ++;
		if ($contador > 1)
			echo ",\n";
	
		$v_id_local=$row["id_local"]; 
		$v_sg_local=$row["sg_local"] . ' / '.$row["nm_local"]; 
		echo "new Array(\"$v_id_local\", \"$v_sg_local\")"; 
		} 
	echo ");\n"; 	
	?> 
	
	function fillSecundariosFromPrimarios(selectCtrl, itemArray, itemAtual) 
		{ 	
		var i, j; 
		selectCtrl.disabled = true;	
				
		// empty existing items 
		for (i = selectCtrl.options.length; i >= 0; i--) 
			{ 
			selectCtrl.options[i] = null; 
			} 
	
		if (itemArray != null && itemAtual != 0) 
			{ 
			selectCtrl.size = 5;
			selectCtrl.disabled = false;			
			j = 1;
			// add new items 
			selectCtrl.options[0] = new Option("","          "); 		

			for (i = 0; i < itemArray.length; i++) 
				{ 
				if (itemArray[i][0] != itemAtual)
					{
					selectCtrl.options[j] = new Option(itemArray[i][0]); 
					if (itemArray[i][0] != null) 
						{ 
						selectCtrl.options[j].value = itemArray[i][0]; 					
						selectCtrl.options[j].text = itemArray[i][1]; 										
						} 
					j++; 
					}				
				} 
			// select first item (prompt) for sub list 
			selectCtrl.options[0].selected = true; 
		   } 
		return true;
		}
	
	</script>