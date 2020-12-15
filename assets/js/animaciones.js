
/**Iniciamos WOW */

new WOW().init();

/**Funcion que oculta y muestra el botón para subir a la cabecera, según su posición. */
$(function () {
    $(window).scroll(function () {
        var scrolltop = $(this).scrollTop();
        if (scrolltop >= 50) {
            $(".flechaSubir").fadeIn();
        } else {
            $(".flechaSubir").fadeOut();
        }
    });

});

/**Funcion que anima la cabecera cuando estemos a más de 80 pixeles por debajo del máximo, aplicándole una nueva clase (que editaremos) a la dicha*/
$(window).scroll(function () {

    var nav = $('.encabezado');
    var scroll = $(window).scrollTop();

    if (scroll >= 80) {
        nav.addClass("Animado");
    } else {
        nav.removeClass("Animado");
    }
});
