// Style changer
$(document).ready(function(){
    if (!($("html").hasClass("mobile"))){

        $('.btn-changer').on('click', function(){
            $(this).parent().toggleClass('open');
        })

        $('.color-changer a').on('click', function(){
            var style = $(this).attr('data-style');
            $('#theme').attr('href', 'css/theme/' + style + '.css');
            return false;
        })


        $('a.btn-ch-changer').on('click', function(){
            // $('.theme-changer').toggleClass('open');
            // $('.theme-changer').fadeOut('600');
            if(!$('#custom-style').length) {
                var appendStyle = '<link id="custom-style" href="css/style-custom.css" rel="stylesheet">';
                $('head').append(appendStyle);
            }
            else {
                $('#custom-style').remove();
            }
            return false;
        });


        $('a.btn-ch-navbar').on('click', function(){
            var newNav = $(this).attr('data-navbar');
            var nav = $('.main-nav');
            if($(this).hasClass('disabled') || nav.hasClass(newNav))
                return false

            $('a.btn-ch-navbar').removeClass('disabled')
            
            $(this).addClass('disabled')

            if(nav.hasClass('white')) {
                nav.removeClass('white')
                nav.addClass('dark')
            }
            else {
                nav.removeClass('dark')
                nav.addClass('white')
            }

            return false;
        })


    }
});

