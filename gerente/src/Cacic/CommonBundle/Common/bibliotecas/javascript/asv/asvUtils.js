/**
 * JavaScript Utils
 * @copyright Copyright (C) 2007 Adriano dos Santos Vieira. All rights reserved.
 * @author Adriano dos Santos Vieira <harpiain at gmail.com>
 * @version 0.0.1
 * @license GNU/GPL
 * @description A sample "suisse knife" for web pages using JavaScript
 */ 
 
 /**
  * Alternar entre mostrar e ocultar
  */
  function toggleDetails( obj ) {
    var el = document.getElementById(obj);
    if ( el.style.display != 'none' ) {
      el.style.display = 'none';
    }
    else {
      el.style.display = '';
    }
  }
  
 /**
  * Mostra detalhes
  */
  function showDetails( obj ) {
    var el = document.getElementById(obj);
    el.style.display = '';
  }
  
 /**
  * Oculta detalhes
  */
  function hideDetails( obj ) {
    var el = document.getElementById(obj);
    el.style.display = 'none';
  }
  
 /**
  * Alternar a classe de um objeto
  */
  function toggleClass( obj, stdClass, newClass ) {
    var el = document.getElementById(obj);
    if ( el.className != stdClass ) {
      el.className = stdClass;
    }
    else {
      el.className = newClass;
    }
  }
  
 /**
  * Alterar a classe de um objeto
  */
  function setClass( obj, newClass ) {
    obj.className = newClass;
  }
  
  /**
   * Atribuir valor a uma vari�vel de formul�rio
   */
   function setFormVar( frm, setValue ) {
    frm.installstep.value = setValue;
    return false;
   }

  /**
   * Envio de formul�rio
   */
   function sendForm( frm ) {
		frm.submit();
	}

  /**
   * Envio de formul�rio
   */
   function submitForm( frm, setValue ) {
      frm.step.value = setValue;
      frm.submit();
   }

  /**
   * Atribuir valor a uma vari�vel
   */
   function setDocVar( frmVar, setValue ) {
     frmVar = document.getElementById(frmVar);
     frmVar.value = setValue;
   }

  /**
   * Trocar valor de uma vari�vel (por id)
   */
   function toggleVarId( frmVar, setValue ) {
     frmVar = document.getElementById(frmVar);
	 if ( frmVar.value != setValue ) {
	   frmVar.value = setValue;
	 }
	 else {
	   frmVar.value = null;
	 }
     return false;
   }

  /**
   * Trocar valor de uma vari�vel
   */
   function toggleVar( oVar, setValue ) {
	 if ( oVar.value != setValue ) {
	   oVar.value = setValue;
	 }
	 else {
	   oVar.value = null;
	 }
     return false;
   }

  /**
   * Atribui valor a uma vari�vel
   */
   function setVarValue( oVar, setValue ) {
	 oVar.value = setValue;
   }
	
  /**
   * Atribui valor a uma vari�vel
   */
   function setFocus( idVar ) {
	 document.getElementById(idVar).focus();
	 return;
   }
	
