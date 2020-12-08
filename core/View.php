<?php

/**
 * Clase que hace de motor de plantillas, aunque con pocas funcionalidades. Sólo nos permite incluir una plantilla y asignarle variables
 */
class View
{
   /**
    * Muestra una vista y pasa un array de valores
    * @param string $name Nombre de nuestra vista, por ej, Login
    * @param array $vars Contenedor de variables, es un array del tipo clave => valor (opcional).
    * @return void
    */
   public function show($name, $vars = array())
   {
      //Creamos la ruta real a la plantilla
      $path = VIEWS_FOLDER . ucwords($name) . 'View.php';

      //Si no existe el fichero en cuestion, lanzamos una excepción
      if (file_exists($path) == false)
         throw new \Exception('La plantilla ' . $path . ' no existe');

      //Si hay variables para asignar, las pasamos una a una.
      if (is_array($vars)) {
         foreach ($vars as $key => $value) {
            $$key = $value;   // Es una variable variable, el valor de la variable hace de nombre de otra variable
         }
      }

      //Finalmente, incluimos el archivo plantilla o vista.

      require_once($path);
   }
}
