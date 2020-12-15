<?php

/**
 * Inclusión de los archivos que contienen las clases de core
 * Cuando PHP usa una clase que no encuentra va a llamar a la función anónima definida en el callback
 * que requiere (incluye) la clase
 * @return void
 */
spl_autoload_register(function ($nombre) {
   require 'core/' . $nombre . '.php';
});

try {
   //Lo iniciamos con su Funcion estático main.
   FrontController::main();
} catch (\Exception $e) {
   echo $e->getMessage();
}
