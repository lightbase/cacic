function getAjaxRequest()
	{
	var ajaxRequest;  // The variable that makes Ajax possible!
	
	try
		{
		// Opera 8.0+, Firefox, Safari
		ajaxRequest = new XMLHttpRequest();
		} 
	catch (e)
		{
		// Internet Explorer Browsers
		try
			{
			ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
			} 
		catch (e) 
			{
			try
				{
				ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
				} 
			catch (e)
				{
				// Something went wrong
				alert("Seu browser não aceitou o objeto AJAX XMLHTTP!");
				return false;
				}
			}
		}
	return ajaxRequest;
	}