
<?php

/**
 * Fichero de comprueba si hemos estamos logueados, o, que por defecto, no lo estamos, pero tenemos la cookie de mantener
 * la sesión abierta activada, y que además inicia la sesión en caso de no existir, para poder trabajar con las sesiones en caso necesario
 */

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if ((!isset($_SESSION['logueado'])) && (!isset($_COOKIE['mantener']))) { //Si no tenemos activadas ninguna de las dos, nos manda a la página principal
    header("Location: index.php?controller=index&accion=index&fuera=true"); //Con un mensaje de error que se obtiene mediante un GET
}
?>