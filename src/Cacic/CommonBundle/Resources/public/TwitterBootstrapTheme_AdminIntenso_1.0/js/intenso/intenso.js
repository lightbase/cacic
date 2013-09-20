$(document).ready(function(){
	// Left side navigation: Minimize
	$("#side_nav a.minimize").click(function(){
		$("#side_nav").toggleClass('closed', 800);
		$("#side_nav > ul li a > span").fadeToggle();
		$("#side_nav > ul li > span.icon").fadeToggle();
		
		// resize main container and side nav
		if($("#main_container").hasClass('span10')) {			
			$("#main_container").toggleClass('span10');
			$("#side_nav").toggleClass('span2');
			$("#side_nav").toggleClass('span1');
			$("#main_container").toggleClass('span11',800);	
			$('li.openable').siblings().removeClass('active').children('div.accordion').slideUp()		
		} else {
			$("#main_container").toggleClass('span11');	
			$("#side_nav").toggleClass('span1');					
			$("#main_container").toggleClass('span10');
			$("#side_nav").toggleClass('span2');					
		}
		
		$(this).toggleClass('minimize_closed');
		
		// change logo type
		if ($(this).hasClass('minimize_closed')) {
			$.cookie("AdminIntensoSideNav","closed");
			$('li.openable').siblings().removeClass('active').children('div.accordion').slideUp()
			$("#logo").removeClass("logo");
			$("#logo").addClass("logo-small");
		}
		else{
			// store state of sidenav
			$.cookie("AdminIntensoSideNav","open");
			$("#logo").removeClass("logo-small");
			$("#logo").addClass("logo");
		}
		
		return false;
		
		$('#side_nav > ul > li.openable > div.accordion').slideUp().parent().removeClass('active');
		$('#side_nav > ul > li.openable > div.accordion').prev().removeClass('active');	
	});

	// Left side navigation: Accordion 	
	$('li .accordion').parent().addClass('openable');
	//Set default open/close settings
	$('li .accordion').hide(); //Hide/close all containers
	
	//Left side navigation: On Click
	$('li.openable').click(function(){		
		if( $(this).children('div.accordion').is(':hidden') ) {
			 //If immediate next container is closed...	
			$(this).siblings().removeClass('active').children('div.accordion').slideUp(); //Remove all "active" state and slide up the immediate next container
			$(this).toggleClass('active').children('div.accordion').slideDown(); //Add "active" state to clicked trigger and slide down the immediate next container
		}
		else {
			$(this).removeClass('active').children('div.accordion').slideUp();
		}
		
		if( $(this).parent().parent().is('#side_nav')){
			openTheSidenav();				
		}

		return false; //Prevent the browser jump to the link anchor
	});
	
	// Left side navigation: external url open a new window
	$('ul.slide_left').siblings('span').addClass('left');
	$('.accordion > a').click(function(){
		var url = $(this).attr('href');
			window.location = url;
	});
	
	// Left side navigation: Open side nav function
	function openTheSidenav(){
		$("#side_nav").removeClass('closed', 800);
		$("#side_nav > ul li a span").fadeIn();
		$("#side_nav > ul li span.icon").fadeIn();
		$("#side_nav > ul li a span.icon").fadeIn();
	
		if(!$("#main_container").hasClass('span10')) {
			$("#main_container").toggleClass('span11');	
			$("#side_nav").toggleClass('span1');					
			$("#main_container").toggleClass('span10');
			$("#side_nav").toggleClass('span2');		
		}
			
		$(this).removeClass('minimize_closed');
		$("#logo").removeClass('logo-small');
		$("#logo").addClass('logo');
	}
		
	// Left side navigation: Cookies for storing Side Nav state
	if($.cookie('AdminIntensoSideNav') === "closed") {
		$("#side_nav").addClass('closed');
		$("#side_nav a.minimize").addClass('minimize_closed');
		$("#main_container").toggleClass('span10');
		$("#side_nav").toggleClass('span2');
		$("#side_nav").toggleClass('span1');
		$("#main_container").toggleClass('span11');
		$("#logo").removeClass('logo');
		$("#logo").addClass('logo-small');
	}
	
	// Portlets (boxes)
    $(".column").sortable({
        connectWith: '.column',
		items:'div.box',	
		opacity:0.8,
		helper:'original',
		revert:true,
		forceHelperSize:true,	
		placeholder: 'dashed_box_placeholder round_all',
		forcePlaceholderSize:true,
		tolerance:'pointer'
    });
	
	// Delete boxes stored in cookies */ 
	/* uncomment code to activate
	$(".box").each(function() {		
		var id = $(this).attr("id");
		var ckie = $.cookie("AdminIntensoDeleteBoxes_"+ $("body").attr("id" )+ "_" + id);
		if (ckie && ckie != '') {
			$(this).remove();
		}
	});*/


	// Store portlet update (move) in cookie
    $(".column").bind('sortupdate', function() {
        $('.column').each(function() {
            $.cookie("AdminIntensoColumn_" + $("body").attr("id") + ($(this).attr('id')), $(this).sortable('toArray'), { expires: 365 });
        });
    });
	
	// check for closed boxes cookie
	var ckie = $.cookie("AdminIntensoClosedBoxes_"+$("body").attr("id"));
	if (ckie && ckie != '')	{
		var list = ckie.split(',');

		for (var x = 0; x < list.length; x++) {	
		 	var toggle = $("#"+list[x]).find("a.toggle");
			toggle.toggleClass("toggle_closed").next().next().slideToggle("slow");
			toggle.parent("div").toggleClass("closed_box");
			toggle.siblings(".box_head").toggleClass("round_top").toggleClass("round_all")
		}	
	}
	
	//Portlets
	// check for order cookies
    for (var i = 0; i < 9; i++) {
        var ckie = $.cookie("AdminIntensoColumn_"+ $("body").attr("id") + "col"+i);

        if (ckie && ckie != ''){
            var list = ckie.split(',');

            for (var x = 0; x < list.length; x++) {
               $('#'+list[x]).appendTo('#col' + i);			  
            }
        }
    }
	
	// Control funtion for portlet (box) buttons clicks
	function setControls(ui) {		
		$('[class*=box_button_]').click(function() {
			var b = $(this);
			var p = b.parent('div');
			
			// Control functionality
			switch(b.attr('title').toLowerCase()) {
				case 'config':
					widgetConfig(b, p);
					break;
				
				case 'toggle':
					widgetToggle(b, p, i);
					break;
				
				case 'close':
					widgetClose(b, p);
					break;
			}
		});
	}
	
	// Toggle button widget
	function widgetToggle(b, p, i) {
		// Change the + into - and visa versa
		
		b.toggleClass("toggle_closed").next().next().slideToggle("slow");
		b.parent("div").toggleClass("closed_box");
		b.siblings(".box_head").toggleClass("round_top").toggleClass("round_all")
		var closedBoxes = [];
		var i = 0;
		$(".closed_box").each(function() 
		{
				closedBoxes[i] = $(this).attr("id");
				i++;		
		});
		$.cookie("AdminIntensoClosedBoxes_" + $("body").attr("id"), closedBoxes, { expires: 365 });
		
        

		return false; //Prevent the browser jump to the link anchor
		
	}
	
	// Close button widget with dialog
	function widgetClose(w, p) {
		$("#dialog-confirm-close-widget").dialog({
			resizable: false,
			modal: true,
			buttons: {
				"Close widget": function() {
					p.toggle('slide', {}, 500, function() {
						id = p.attr('id');
						$.cookie("AdminIntensoDeleteBoxes_" + $("body").attr("id") + "_" + id, "yes", { expires: 365 });
						p.remove();
					});
					$(this).dialog("close");
				},
				Cancel: function() {
					$(this).dialog("close");
				}
			}
		});
	}
	
	// Modify button widget
	function widgetConfig(w, p) {		
		$("#dialog-config-widget").dialog({
			resizable: false,
			modal: true,
			width: 500,
			buttons: {
				"Save changes": function(e, ui) {
					/* code the functionality here, could store in a cookie */					
					$(this).dialog("close");
				},
				Cancel: function() {					
					$(this).dialog("close");
				}
			}
		});
	}$('#tab').tab('show');
	
	// set portlet comtrols
	setControls();
	
	// Delete Cookies
	$("#cookie_delete").click(function() {
	  deleteCookies();
	});
			
	// funtion to get all cookies
	function getCookiesArray() {
	    var cookies = { };
	 
		if (document.cookie && document.cookie != '') {
	        var split = document.cookie.split(';');
        	for (var i = 0; i < split.length; i++) {
	            var name_value = split[i].split("=");
	            name_value[0] = name_value[0].replace(/^ /, '');
	            cookies[decodeURIComponent(name_value[0])] = decodeURIComponent(name_value[1]);
	        }
	    }
 
	    return cookies;  
	}
	
	// function to delete all cookies
	function deleteCookies() {
		var cookies = getCookiesArray();
		for(var name in cookies) {
		  $.cookie(name, null);
		}
	}
	 
 	// UI to top
	$().UItoTop({ easingType: 'easeOutQuart' });
	 
	// dropdown used in top nav
	$('.dropdown-toggle').dropdown();
	 
	// datatable
	$('#datatable').dataTable( {
		"bJQueryUI": true,
		"sPaginationType": "full_numbers"
	} );	
	
	// shortcut popover
	$(".popover_shortcut_on").popover({
		placement:"bottom",
		trigger:"hover",
		delay: { show: 500, hide: 5000 }
	});
	
	// tootips
	$('a[rel=tooltip]').tooltip();
	
	// popovers
	$('[rel=popover]').popover();
	
	// alerts
	$(".alert-message").alert();

	//Pretty print make code pretty
    window.prettyPrint && prettyPrint();

    // Select theme color (load from cookie)
    $.cookie("AdminIntensoThemeColor", null);
    var ckie = $.cookie("AdminIntensoThemeColor");


    if (ckie && ckie != ''){
        if(ckie == "grad_colour_light_blue"){
            $(".navbar .nav > li > a").css('color', '#FFF','!important');
            $(".navbar-inner").css('background-color', '#49afcd','!important');
            $(".navbar-inner").css('background-image', '-webkit-linear-gradient(top, #5bc0de, #2f96b4)','!important');
            $(".box").attr('class', 'box grad_colour_light_blue');
            $(".table thead th").css('background-color', '#49afcd');
            $(".table thead th").css('background-image', '-webkit-linear-gradient(top, #5bc0de, #2f96b4)');

        }
        else if(ckie == "grad_colour_dark_blue"){
            $(".navbar .nav > li > a").css('color', '#FFF','!important');
            $(".box").attr('class', 'box grad_colour_dark_blue');
            $(".navbar-inner").css('background-color', '#006dcc ','!important');
            $(".navbar-inner").css('background-image', '-webkit-linear-gradient(top, #0088cc, #0044cc)','!important');
            $(".table thead th").css('background-color', '#006dcc');
            $(".table thead th").css('background-image', '-webkit-linear-gradient(top, #0088cc, #0044cc)');

        }else if(ckie == "grad_colour_red"){
            $(".navbar .nav > li > a").css('color', '#FFF','!important');
            $(".navbar-inner").css('background-color', '#da4f49','!important');
            $(".navbar-inner").css('background-image', '-webkit-linear-gradient(top,  #ee5f5b, #bd362f)','!important');
            $(".table thead th").css('background-color', '#da4f49');
            $(".table thead th").css('background-image', '-webkit-linear-gradient(top,  #ee5f5b, #bd362f)');
            $(".box").attr('class', 'box grad_colour_red');

        }else if(ckie == "grad_colour_green"){
            $(".navbar .nav > li > a").css('color', '#FFF','!important');
            $(".navbar-inner").css('background-color', '#5bb75b','!important');
            $(".navbar-inner").css('background-image', '-webkit-linear-gradient(top, #62c462, #51a351)','!important');
            $(".box").attr('class', 'box grad_colour_green');
            $(".table thead th").css('background-image', '-webkit-linear-gradient(top, #62c462, #51a351) ');
            $(".table thead th").css('background-color', '#5bb75b');
        }else if(ckie == "grad_colour_orange"){
            $(".navbar .nav > li > a").css('color', '#FFF','!important');
            $(".navbar-inner").css('background-color', '#faa732','!important');
            $(".navbar-inner").css('background-image', '-webkit-linear-gradient(top, #fbb450, #f89406) ','!important');
            $(".box").attr('class', 'box grad_colour_orange');
            $(".table thead th").css('background-color', '#faa732');
            $(".table thead th").css('background-image', '-webkit-linear-gradient(top, #fbb450, #f89406)');
        }else if(ckie == "grad_colour_grey"){
            $(".navbar .nav > li > a").css('color', '#FFF','!important');
            $(".navbar-inner").css('background-color', '#f5f5f5','!important');
            $(".navbar-inner").css('background-image', '-webkit-linear-gradient(top,  #ffffff, #e6e6e6)','!important');
            $(".box").attr('class', 'box grad_colour_grey');
            $(".table thead th").css('background-color', '#f5f5f5');
            $(".table thead th").css('background-image', '-webkit-linear-gradient(top,  #ffffff, #e6e6e6)');
        }else if(ckie == "grad_colour_black"){
            $(".box").attr('class', 'box grad_colour_black');

        }else{
            $(".box").attr('class', 'box '+ ckie);

        }





    }

    // events for changing theme
    $("#lightblue").click(function() {

        $(".navbar .nav > li > a").css('color', '#FFF','!important');
        $(".navbar-inner").css('background-color', '#49afcd','!important');
        $(".navbar-inner").css('background-image', '-webkit-linear-gradient(top, #5bc0de, #2f96b4)','!important');
        $(".box").attr('class', 'box grad_colour_light_blue');
        $(".table thead th").css('background-color', '#49afcd');
        $(".table thead th").css('background-image', '-webkit-linear-gradient(top, #5bc0de, #2f96b4)');
        $.cookie("AdminIntensoThemeColor", "grad_colour_light_blue", { expires: 365 ,path: '/' });
    });

    $("#darkblue").click(function() {
        $(".navbar .nav > li > a").css('color', '#FFF','!important');
        $(".box").attr('class', 'box grad_colour_dark_blue');
        $(".navbar-inner").css('background-color', '#006dcc ','!important');
        $(".navbar-inner").css('background-image', '-webkit-linear-gradient(top, #0088cc, #0044cc)','!important');
        $(".table thead th").css('background-color', '#006dcc');
        $(".table thead th").css('background-image', '-webkit-linear-gradient(top, #0088cc, #0044cc)');
        $.cookie("AdminIntensoThemeColor", "grad_colour_dark_blue", { expires: 365 ,path: '/' });
    });

    $("#red").click(function() {
        $(".navbar .nav > li > a").css('color', '#FFF','!important');
        $(".navbar-inner").css('background-color', '#da4f49','!important');
        $(".navbar-inner").css('background-image', '-webkit-linear-gradient(top,  #ee5f5b, #bd362f)','!important');
        $(".table thead th").css('background-color', '#da4f49');
        $(".table thead th").css('background-image', '-webkit-linear-gradient(top,  #ee5f5b, #bd362f)');
        $(".box").attr('class', 'box grad_colour_red');
        $.cookie("AdminIntensoThemeColor", "grad_colour_red", { expires: 365 ,path: '/'});
    });

    $("#green").click(function() {
        $(".navbar .nav > li > a").css('color', '#FFF','!important');
        $(".navbar-inner").css('background-color', '#5bb75b','!important');
        $(".navbar-inner").css('background-image', '-webkit-linear-gradient(top, #62c462, #51a351)','!important');
        $(".box").attr('class', 'box grad_colour_green');
        $(".table thead th").css('background-image', '-webkit-linear-gradient(top, #62c462, #51a351) ');
        $(".table thead th").css('background-color', '#5bb75b');

        $.cookie("AdminIntensoThemeColor", "grad_colour_green", { expires: 365 ,path: '/'});

    });

    $("#orange").click(function() {
        $(".navbar .nav > li > a").css('color', '#FFF','!important');
        $(".navbar-inner").css('background-color', '#faa732','!important');
        $(".navbar-inner").css('background-image', '-webkit-linear-gradient(top, #fbb450, #f89406) ','!important');
        $(".box").attr('class', 'box grad_colour_orange');
        $(".table thead th").css('background-color', '#faa732');
        $(".table thead th").css('background-image', '-webkit-linear-gradient(top, #fbb450, #f89406)');
        $.cookie("AdminIntensoThemeColor", "grad_colour_orange", { expires: 365 ,path: '/'});
    });

    $("#grey").click(function() {
        $(".navbar .nav > li > a").css('color', '#FFF','!important');
        $(".navbar-inner").css('background-color', '#f5f5f5','!important');
        $(".navbar-inner").css('background-image', '-webkit-linear-gradient(top,  #ffffff, #e6e6e6)','!important');
        $(".box").attr('class', 'box grad_colour_grey');
        $(".table thead th").css('background-color', '#f5f5f5');
        $(".table thead th").css('background-image', '-webkit-linear-gradient(top,  #ffffff, #e6e6e6)');
        $.cookie("AdminIntensoThemeColor", "grad_colour_grey", { expires: 365 ,path: '/'});
    });

    $("#black").click(function() {
        $(".box").attr('class', 'box grad_colour_black');
        $.cookie("AdminIntensoThemeColor", "grad_colour_black", { expires: 365 ,path: '/'});
    });





    var id_default_body_bgcolor = $.cookie('the_cookie');

    $("body").css('background', id_default_body_bgcolor);
    $('#id_default_body_bgcolor').colorpicker().on('hide', function( ev ){
        $("body").css('background', id_default_body_bgcolor)
        if ( id_default_body_bgcolor != ev.color.toHex() ) // Verifica se a cor foi alterada

        {
            id_default_body_bgcolor = ev.color.toHex();
            salvaConfiguracao( 'id_default_body_bgcolor', id_default_body_bgcolor );
            $("body").css('background', id_default_body_bgcolor);
            location.reload ();

        }
    });

});  		