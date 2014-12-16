
$(document).ready(function(){
    // Select theme color (load from cookie)
    var ckie = $.cookie("AdminIntensoThemeColor");

    if (ckie && ckie != ''){
        if(ckie == "grad_colour_light_blue"){
            $(".navbar .nav > li > a").css('color', '#FFF','!important');
            $(".navbar-inner").css('background-color', '#49afcd','!important');
            $(".navbar .navbar-text").css('color', '#FFF','!important');
            $(".navbar-inner").css('background-image', '-webkit-linear-gradient(top, #5bc0de, #2f96b4)','!important');
            $(".box").attr('class', 'box grad_colour_light_blue');
            $(".table thead th").css('background-color', '#49afcd');
            $(".table thead th").css('background-image', '-webkit-linear-gradient(top, #5bc0de, #2f96b4)');

        }
        else if(ckie == "grad_colour_dark_blue"){
            $(".navbar .nav > li > a").css('color', '#FFF','!important');
            $(".box").attr('class', 'box grad_colour_dark_blue');
            $(".navbar .navbar-text").css('color', '#FFF','!important');
            $(".navbar-inner").css('background-color', '#006dcc ','!important');
            $(".navbar-inner").css('background-image', '-webkit-linear-gradient(top, #0088cc, #0044cc)','!important');
            $(".table thead th").css('background-color', '#006dcc');
            $(".table thead th").css('background-image', '-webkit-linear-gradient(top, #0088cc, #0044cc)');

        }else if(ckie == "grad_colour_red"){
            $(".navbar .nav > li > a").css('color', '#FFF','!important');
            $(".navbar .navbar-text").css('color', '#FFF','!important');
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
            $(".navbar .navbar-text").css('color', '#FFF','!important');
            $(".table thead th").css('background-image', '-webkit-linear-gradient(top, #62c462, #51a351) ');
            $(".table thead th").css('background-color', '#5bb75b');
        }else if(ckie == "grad_colour_orange"){
            $(".navbar .nav > li > a").css('color', '#FFF','!important');
            $(".navbar-inner").css('background-color', '#faa732','!important');
            $(".navbar-inner").css('background-image', '-webkit-linear-gradient(top, #fbb450, #f89406) ','!important');
            $(".box").attr('class', 'box grad_colour_orange');
            $(".navbar .navbar-text").css('color', '#FFF','!important');
            $(".table thead th").css('background-color', '#faa732');
            $(".table thead th").css('background-image', '-webkit-linear-gradient(top, #fbb450, #f89406)');
        }else if(ckie == "grad_colour_grey"){
            $(".navbar .nav > li > a").css('color', '#FFF','!important');
            $(".navbar-inner").css('background-color', '#f5f5f5','!important');
            $(".navbar-inner").css('background-image', '-webkit-linear-gradient(top,  #ffffff, #e6e6e6)','!important');
            $(".box").attr('class', 'box grad_colour_grey');
            $(".navbar .navbar-text").css('color', '#FFF','!important');
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
        $(".navbar .navbar-text").css('color', '#FFF','!important');
        $(".table thead th").css('background-color', '#49afcd');
        $(".table thead th").css('background-image', '-webkit-linear-gradient(top, #5bc0de, #2f96b4)');
        $.cookie("AdminIntensoThemeColor", "grad_colour_light_blue", { expires: 365 ,path: '/' });
    });

    $("#darkblue").click(function() {
        $(".navbar .nav > li > a").css('color', '#FFF','!important');
        $(".box").attr('class', 'box grad_colour_dark_blue');
        $(".navbar .navbar-text").css('color', '#FFF','!important');
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
        $(".navbar .navbar-text").css('color', '#FFF','!important');
        $(".table thead th").css('background-image', '-webkit-linear-gradient(top,  #ee5f5b, #bd362f)');
        $(".box").attr('class', 'box grad_colour_red');
        $.cookie("AdminIntensoThemeColor", "grad_colour_red", { expires: 365 ,path: '/'});
    });

    $("#green").click(function() {
        $(".navbar .nav > li > a").css('color', '#FFF','!important');
        $(".navbar-inner").css('background-color', '#5bb75b','!important');
        $(".navbar-inner").css('background-image', '-webkit-linear-gradient(top, #62c462, #51a351)','!important');
        $(".box").attr('class', 'box grad_colour_green');
        $(".navbar .navbar-text").css('color', '#FFF','!important');
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
        $(".navbar .navbar-text").css('color', '#FFF','!important');
        $(".table thead th").css('background-image', '-webkit-linear-gradient(top, #fbb450, #f89406)');
        $.cookie("AdminIntensoThemeColor", "grad_colour_orange", { expires: 365 ,path: '/'});
    });

    $("#grey").click(function() {
        $(".navbar .nav > li > a").css('color', '#FFF','!important');
        $(".navbar-inner").css('background-color', '#f5f5f5','!important');
        $(".navbar-inner").css('background-image', '-webkit-linear-gradient(top,  #ffffff, #e6e6e6)','!important');
        $(".box").attr('class', 'box grad_colour_grey');
        $(".navbar .navbar-text").css('color', '#FFF','!important');
        $(".table thead th").css('background-color', '#f5f5f5');
        $(".table thead th").css('background-image', '-webkit-linear-gradient(top,  #ffffff, #e6e6e6)');
        $.cookie("AdminIntensoThemeColor", "grad_colour_grey", { expires: 365 ,path: '/'});
    });

    $("#black").click(function() {
        $(".table thead th").css('background-color', '#393939');
        $(".table thead th").css('background-image', '-webkit-linear-gradient(top,  #454545, #262626)');
        $(".navbar-inner").css('background-color', '#393939','!important');
        $(".navbar-inner").css('background-image', '-webkit-linear-gradient(top,   #454545, #262626)','!important');

        $(".box").attr('class', 'box grad_colour_black');
        $.cookie("AdminIntensoThemeColor", "grad_colour_black", { expires: 365 ,path: '/'});
    });



    $( "#id_background_default" ).click(function() {
        $.cookie('the_cookie',null);

    });

    var id_default_body_bgcolor = $.cookie('the_cookie');

    $("body").css('background', id_default_body_bgcolor);

    });