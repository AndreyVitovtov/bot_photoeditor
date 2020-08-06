"use strict"

$(document).ready(function() {

//АВТОРИЗАЦИЯ
	$('body').on('keydown', '.auth input', function (e) {
		if (e.keyCode == 13) {
			$('#login').click();
		}
	});

	$('body').on('click', '#login', function() {
		var login = $('#inputLogin').val();
		var password = $('#inputPassword').val();

		if(login.length < 3 || password.length < 3) {
			alert("Login or password is too short");
		}
		else {
			$.ajax({
				type: "POST",
				url: "handler.php",
				data: {
					"type" : "auth",
					"login" : login,
					"password" : password
				},
				success: function(data) {
					if(data == "success") {
						$(location).attr('href', "index.php");
					}
					else {
						alert(data);
					}
				}
			});
		}
	});

//ОТКРЫТЬ МЕНЮ
	$('body').on('click', '.open-menu', function() {
		if( $('.sidebar').is('.rolled') ) {
			$('header .left-panel').html('Панель');
			$('main section.sidebar .menu-hidden.menu-active').show();
		}
		else {
			$('header .left-panel').html('П');
			$('header .right-panel').css('width: calc( 100% - 50px )');
			$('main section.sidebar .menu-hidden.menu-active').hide();
		}
		$('.sidebar').toggleClass('rolled');
		$('header .left-panel').toggleClass('rolled');
		$('header .right-panel').toggleClass('rolled');
	});

//ПУНКТЫ МЕНЮ
	$('body').on('click', '.item-menu', function() {
		if(! $('.sidebar').is('.rolled') ) {
			if( ! $(this).is('menu-active') ) {
				$('.item-menu').removeClass('active');
				$(this).addClass('active');
				$(this).find('.item-menu-hidden').toggle();

				$('.hidden.menu-active').slideToggle();
				$('.hidden.menu-active').removeClass('menu-active');
				$('main section.sidebar .menu-hidden div').removeClass('active');
				let className = $(this).data('item');
				$('.'+className).slideToggle();
				$('.'+className).addClass('menu-active');
			}
		}
	});

//РАСКРЫТЬ МЕНЮ
// 	$('body').on('click', 'main section.sidebar .menu-hidden div', function() {
// 		$('main section.sidebar .menu-hidden div').removeClass('active');
// 		$('.item-menu').removeClass('active');
// 		$(this).parent().siblings(".item-menu").addClass('active');
// 		$(this).addClass('active');
// 	});

//ПЕРЕХОД К ПУНКТУ МЕНЮ
    $('body').on('click', 'main section.sidebar.rolled .item-menu', function() {
        // if( $(this).data("go") ) {
            $('.item-menu').removeClass('active');
            $('main section.sidebar .menu-hidden div').removeClass('active');
            $(this).addClass('active');
        // }
    });

//ПОКАЗАТЬ ВЫПАДАЮЩЕЕ МЕНЮ
	$('body').on('mouseover', '.rolled-hidden', function() {
		if( $('.sidebar').is('.rolled') ) {
			$(this).addClass('rolled-hidden-active');
		}
	});

//СКРЫТЬ ВЫПАДАЮЩЕЕ МЕНЮ
	$('body').on('mouseout', '.rolled-hidden', function() {
		$(this).removeClass('rolled-hidden-active');
	});

//ОТКРЫТЬ МОБИЛЬНОЕ МЕНЮ
	$('body').on('click', '.open-menu-mob', function() {
		if( $('main section.sidebar').is('.no-active') ) {
			$('main section.sidebar').removeClass('no-active');
			$('main section.sidebar').addClass('active');

		}
		else {
			$('main section.sidebar').removeClass('active');
			$('main section.sidebar').addClass('no-active');
		}
	});

//ПО КЛИКУ СКРЫТЬ МЕНЮ
	$(document).on('mouseup', function (e) {
		if( $('main section.sidebar').is('.active') ) {
			var div = $(".sidebar");
			if (!div.is(e.target) && div.has(e.target).length === 0 && ! $('.open-menu-mob').is(e.target) && ! $('.icon-menu').is(e.target)) {
				$('main section.sidebar').removeClass('active');
				$('main section.sidebar').addClass('no-active');
				$('main section.content').css('margin-left: 0');
			}
		}

		if( $('div').is('.dropdown-menu') && ! $('.open-user-menu').is(e.target) && ! $('.open-user-menu img').is(e.target)) {
			$('.dropdown-menu').hide();
		}
	});

//ОТКРЫТЬ ПОЛЬЗОВАТЕЛЬСКОЕ МЕНЮ
	$('body').on('click', '.open-user-menu', function() {
		$('.dropdown-menu').toggle();
	});

//ИЗМЕНИТЬ ПОДПИСКУ
    $('body').on('change', '#category', function() {
        let category_id = $(this).val();
        $.ajax({
            type: "GET",
            url: "/admin/ajax-handler",
            data: {
                'id' : category_id
            },
            success: function(data) {
                $('#subcategories').html(data);
            }
        });
    });

    $('body').on('change', '#check_all', function() {
        if (!$("#check_all").is(":checked")) {
            $(".checkbox").removeAttr("checked");
        }
        else {
            $(".checkbox").attr("checked","checked");
        }
    });

    $('body').on('click', '#form-delete-check-submit', function() {
        var a=$('.checkbox:checked');
        var out={};

        for (var x=0; x<a.length;x++) {
            out[x] = a[x].value;
        }

        $('.data-form-delete-check').val(JSON.stringify(out));
        $('#form-delete-check').submit();
    });

}); //END document ready

function popUpWindow(text) {
    $('.pop-up-window').html(text);
    $('.pop-up-window').css('top', '0');
    setTimeout(function(){
        $('.pop-up-window').css('top', '-50px');
    }, 1200);
}
