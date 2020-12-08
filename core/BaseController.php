<?php

/**
 * Clase base para los controladores
 */
abstract class BaseController
{
   /**
    * Vista protegida, que utilizaremos en el controlador en uso para guardar y mostrar una vista
    * @var [view] Vista a mostrar
    */
   protected $view;

   /**
    * Metodo constructor, que incializa la propiedad vista
    */
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
         $urlpar = "";
         foreach ($params as $key => $valor) { //Obtenemos un array de parámetros, y obtenemos una variable local separada por cada parámetro del dicho array
            $urlpar .= "&$key=$valor";
         }
         header("Location: ?controller=" . $controlador . "&action=" . $accion . $urlpar);
      } else {
         header("Location: ?controller=" . $controlador . "&action=" . $accion);
      }
   }
}
