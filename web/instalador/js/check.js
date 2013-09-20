function validate() {
	if(document.terms.lido.checked) {
	    document.getElementById("go").innerHTML="<input class='btn btn-default' type='submit' value='Continuar'>";
	} else {
	    document.getElementById("go").innerHTML="<input class='btn btn-default' type='submit' value='Continuar' disabled='true'>";
	}
};