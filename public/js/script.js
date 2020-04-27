function generatePassword(length) {
    var result = '';
    var chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789`!@#$%^&*_+-"|:{}<>';
    var charsLength = chars.length;
    for(var i=0; i<10; i++) {
        result += chars.charAt(Math.floor(Math.random() * charsLength));
    }

    return result;
}

$(document).ready(function() {
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

});