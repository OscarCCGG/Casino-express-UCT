$(document).ready(function() {
    $("#contact-form").submit(function(event) {
        event.preventDefault();

        var nombre = $("input[name='nombre']").val();
        var email = $("input[name='email']").val();
        var mensaje = $("textarea[name='mensaje']").val();

        $.ajax({
            type: "POST",
            url: "PHP/php2.php",
            data: {
                nombre: nombre,
                email: email,
                mensaje: mensaje
            },
            success: function(response) {
                $("#mensaje-confirmacion").html(response);
            }
        });
    });
});
