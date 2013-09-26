<SCRIPT LANGUAGE="JavaScript">
function valida_form_cadastro_aquisicao() {
	alert("Um alert fora do if");
	if ((document.formol.date_aquisicao.value == "")) {
		alert("Você deve sinformar a data no formato (mm/aaaa).");
		return false; 
	}
	else if (document.formol.date_aquisicao.length > 7) {
		alert("Você deve selecionar ao menos um aplicativo.");
		return false; 
	}
	
	else{ alert("Veio aqui.");return true;	}
}

</script>
