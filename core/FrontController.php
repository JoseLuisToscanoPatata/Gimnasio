<?php

/**
 * El FrontController es el que recibe todas las peticiones, incluye algunos ficheros, busca el controlador y llama a la acción que corresponde.
 * Con el objetivo de no repetir nombre de clases nuestros controladores terminarán todos en Controller.
 * Por ej, la clase controladora Users, será UsersController
 */
class FrontController
{
   static function main()
   {
      //Requiere archivos con configuraciones.
      foreach (glob('config/*.php') as $filename) {
         require_once "$filename";
      }

      //Formamos el nombre del Controlador o el controlador por defecto
      if (!empty($_GET['controller'])) {
         $controller = ucwords($_GET['controller']);
      } else {
         $controller = DEFAULT_CONTROLLER;
      }

      //Lo mismo sucede con las acciones, si no hay accion tomamos index como accion
      if (!empty($_GET['accion'])) {
         $action = $_GET['accion'];
      } else {
         $action = DEFAULT_ACTION;
      }

      $controller .= "Controller";
      $controller_path = CONTROLLERS_FOLDER . $controller . '.php';

      //Incluimos el fichero que contiene nuestra clase controladora solicitada
      if (!is_file($controller_path)) {
         throw new \Exception('El controlador no existe ' . $controller_path . ' - 404 not found');
      }
      require $controller_path;

      //Si no existe la clase que buscamos y su Funcion mostramos un error
      if (!is_callable(array($controller, $action))) {
         $controller = DEFAULT_CONTROLLER;
         $action = DEFAULT_ACTION;
      }

      //Si todo esta bien, creamos una instancia del controlador
      //  y llamamos a la accion
      $controller = new $controller();
      $controller->$action();
   }
}
