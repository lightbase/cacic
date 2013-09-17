function validate() {
	if(document.terms.lido.checked) {
	    document.getElementById('go').innerHTML='<input type="submit" value="Continuar">';
	} else {
	    document.getElementById('go').innerHTML='<input type="submit" value="Continuar" disabled="true">';
	}
}