<?php

/**
 * Clase base para los controladores
 */
abstract class BaseController
{
   protected $view;

   function __construct()
   {
      $this->view = new View();
   }

   /**
    * Redirige a un controlador dado, con una acción y una serie de parámetros
    *
    * @param string $controlador
    * @param string $accion
    * @param array $params  Parejas clave-valor para luego añadir a la url que llama al controlador
    * @return void
    */
   public function redirect($controlador = DEFAULT_CONTROLLER, $accion = DEFAULT_ACTION, $params = null)
   {
      if ($params != null) {
         $urlpar="";
         foreach ($params as $key => $valor) {
            $urlpar .= "&$key=$valor";
         }
         header("Location: ?controller=" . $controlador . "&action=" . $accion . $urlpar);
      } else {
         header("Location: ?controller=" . $controlador . "&action=" . $accion);
      }
   }
}
