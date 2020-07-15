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
var userID = 0;

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
        userID = localStorage.getItem('userID');
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
        //async: false,
        dataType: 'html',
        data: {
            save: 1,
            userID: userID,
            ratedIndex: ratedIndex,
            productId: localStorage.getItem('productId')
        },
        success: function(response) {
           var user_id = $(response).find('#user_id').val();
           console.log(user_id);
           //localStorage.setItem('userID', user_id);
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


