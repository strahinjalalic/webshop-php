function generatePassword(length) {
    var result = '';
    var chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789`!@#$%^&*_+-"|:{}<>';
    var charsLength = chars.length;
    for(var i=0; i<10; i++) {
        result += chars.charAt(Math.floor(Math.random() * charsLength));
    }

    return result;
}

// function showAndHide(id) {
//     if(document.getElementById(id).style.display == 'none')  {
//         document.getElementById(id).style.display = 'block';
//     } else {
//         document.getElementById(id).style.display = 'none';
//     }
// }



var ratedIndex = -1;
var userID = 0;
function saveToDb() {
    $.ajax({
        url: 'item.php',
        type: 'POST',
        dataType: 'json',
        data: {
            save: 1,
            userID: userID,
            ratedIndex: ratedIndex,
            productId: localStorage.getItem('productId')
        },
        success: function(response) {
            localStorage.setItem('userID', response.user_id); //ne salje sa fronta user_id iako ga loguje i pamti ?
        },
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

