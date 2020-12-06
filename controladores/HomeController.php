<?php

/**
 * Controlador de la página de entrada al portal desde la que se pueden hacer las funciones que te permita tu rol
 */
class HomeController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
require_once CHECK_SESSION_FILE;

        $parametros = [
            "tituloventana" => "Página de inicio",
        ];
        $this->view->show("inicio", $parametros);
    }
}