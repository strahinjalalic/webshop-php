function generatePassword(length) {
    var result = '';
    var chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789`!@#$%^&*_+-"|:{}<>';
    var charsLength = chars.length;
    for(var i=0; i<10; i++) {
        result += chars.charAt(Math.floor(Math.random() * charsLength));
    }

    return result;
}

var ratedIndex = -1;

$(document).ready(function() {
    starResetColor();
    $(".far, .fa-eye").click(function() {
        $(this).toggleClass("far fa-eye far fa-eye-slash");
        var input = $($(this).attr("toggle"));
        if(input.attr("type") == "password") {
            input.attr("type", "text");
        } else {
            input.attr("type", "password");
        }
    });

    $('form#form_login').submit(function() {
        var response_p;
        var p_text;
        var p;
        $.ajax({
            type: 'POST',
            url: 'index.php',
            async: false,
            data: $(this).serialize(),
            dataType: "html",
            success: function(response) {
                response_p = $(response).find('#display_login_msg').text();
                p_text = $('#display_login_msg').text(response_p);
                localStorage.setItem('p_msg', p_text[0].innerText);
                p = localStorage.getItem('p_msg');
            }
        });
          if(p == '') {
            return true;
          }
            return false;
    });

    $("#suggest_password").click(function() {
        $("#password_field").val(generatePassword(10));
    }); 

    $('#popup').click(function() {
        $('#abc').fadeIn(300);
    });

    $('#close').click(function() {
        $('#abc').fadeOut(500);
    });

    $('#body').click(function() {
        $('#abc').fadeOut(500);
    });

    $('#popupLogin').click(function(ev) {
        ev.stopPropagation();
    });

    $('.ul_brand_man').click(function() {
        let id = $(this).next().attr('id');
        if(document.getElementById(id).style.display == 'none') {
            $('#'+id).toggle(500);
        } else {
           $('#'+id).toggle(500);
        }
    });

    var productId = parseInt($('#product_id').val());

    if(localStorage.getItem('ratedIndex'+productId) != null) {
        setStar(parseInt(localStorage.getItem('ratedIndex'+productId)));
    }

    $('.fa-star').on('click', function() {
        ratedIndex = parseInt($(this).data('index'));
    
        localStorage.setItem('ratedIndex'+productId, ratedIndex);
        localStorage.setItem('productId', productId);
        saveToDb();
    });

    $('.fa-star').mouseover(function() {
      starResetColor();

      var currentIndex = parseInt($(this).data('index'));
      setStar(currentIndex);
    });

    $('.fa-star').mouseleave(function() {
        starResetColor();

        if(ratedIndex != -1) {
           setStar(ratedIndex);
        }
    });
});

function saveToDb() {
    $.ajax({
        url: 'item.php',
        type: 'POST',
        dataType: 'json',
        data: {
            save: 1,
            ratedIndex: ratedIndex,
            productId: localStorage.getItem('productId')
        }
    });
}

function starResetColor() {
    $('.fa-star').css('color', '#d17581')
}

function setStar(max) {
    for(var i=0; i < max; i++) {
        $('.fa-star:eq('+i+')').css('color', '#FFD700');
    }
}

function liveSearch(value) {
    $.post("ajax_search.php", {query: value}, function(data) {
        if($('.search_res_footer_empty')[0]) {
            $('.search_res_footer_empty').toggleClass('.search_res_footer');
            $('.search_res_footer_empty').toggleClass('.search_res_footer_empty');
        }
        $('.search_res').html(data);
        $('.search_res_footer').html("<a href='search.php?search=" + value + "'>See Results</a>");
        $('.search_res_footer').css({"padding": "3px 9px 0px 0px", "height": "25px", "border": "1px solid #f1f1f1", "border-top": "none", "background-color": "skyblue", "text-align": "center", "font-size": "14px"});

        if(data == "") {
            $('.search_res_footer').html("");
            $('.search_res_footer').removeAttr('style');
            $('.search_res_footer').toggleClass('.search_res_footer_empty');
            $('.search_res_footer').toggleClass('.search_res_footer');
        }
    });
}

$(document).click(function(e) { //kada se izlistaju live search rezultati, klikom sa strane nestaju
    if(e.target.class != 'search_res' && e.target.id != 'search') {
        $('.search_res').html("");
        $('.search_res_footer').html("");
        $('.search_res_footer').toggleClass('.search_res_footer_empty');
        $('.search_res_footer').toggleClass('.search_res_footer');
    }
});
