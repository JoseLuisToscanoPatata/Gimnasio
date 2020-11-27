<?php
/**
 * Controlador de la página index desde la que se puede hacer el login y el registro
 */

/**
 * Incluimos todos los modelos que necesite este controlador
 */
require_once MODELS_FOLDER . 'UserModel.php';

class IndexController extends BaseController
{

    private $modelo;
    private $mensajes = [];

    public function __construct()
    {
        parent::__construct();
        $this->modelo = new UserModel();
        $this->mensajes = [];
    }

    public function index()
    {
        $parametros = [
            "tituloventana" => "Pagina de inicio",
        ];

        if (isset($_GET['fuera'])) {
            $this->mensajes[] = [
                "tipo" => "danger",
                "mensaje" => "No puedes acceder a otras páginas sin loguearte!! :(",
            ];
            $parametros["mensajes"] = $this->mensajes;

        } else {

            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }

            if (isset($_COOKIE['mantener']) && !isset($_SESSION['logueado'])) {
                header("Location: index.php?controller=home&accion=index");

            } else if (isset($_SESSION['logueado'])) {
                unset($_SESSION['logueado']);
                unset($_SESSION['rol']);
                unset($_SESSION['id']);

                if (isset($_COOKIE['mantener'])) {
                    unset($_COOKIE['mantener']);
                }
            }
        }

        $this->view->show("Principal", $parametros);
    }

    /**
     * Podemos implementar la acción login
     *
     * @return void
     */
    public function login()
    {

        $parametros = [
            "tituloventana" => "Página de login",
            "datos" => null,
            "mensajes" => [],
        ];

        if (isset($_POST['submit'])) {

            if ((isset($_POST['usuario']) && isset($_POST['password'])) && (!empty($_POST['usuario']) && !empty($_POST['password']))) {

                $resultModelo = $this->modelo->loginCorrecto($_POST['usuario'], $_POST['password']);

                if ($resultModelo["correcto"] == 1) {

                    session_start();
                    $_SESSION['logueado'] = $_POST['usuario'];
                    $_SESSION['rol'] = $resultModelo['datos']['rol_id'];
                    $_SESSION['id'] = $resultModelo['datos']['usuario_id'];

                    if (isset($_POST['recordar']) && ($_POST['recordar'] == "on")) {
                        setcookie('usuario', $_POST['usuario'], time() + (15 * 24 * 60 * 60));
                        setcookie('password', $_POST['password'], time() + (15 * 24 * 60 * 60));
                        setcookie('recordar', $_POST['recordar'], time() + (15 * 24 * 60 * 60));

                    } else {
                        if (isset($_COOKIE['usuario'])) {
                            setcookie('usuario', "");}

                        if (isset($_COOKIE['password'])) {
                            setcookie('password', "");}

                        if (isset($_COOKIE['recordar'])) {
                            setcookie('recordar', "");}
                    }

                    if (isset($_POST['mantener']) && ($_POST['mantener'] == "on")) {
                        setcookie('mantener', $_POST['usuario'], time() + (15 * 24 * 60 * 60));

                    } else {
                        if (isset($_COOKIE['mantener'])) {
                            setcookie('mantener', "");}
                    }

                    $parametros["tituloventana"] = "Pagina de inicio";
                    $parametros["datos"] = $resultModelo["datos"];
                    $this->view->show("Inicio", $parametros);

                } else {

                    if ($resultModelo["correcto"] == 2) {
                        $this->mensajes[] = [
                            "tipo" => "danger",
                            "mensaje" => "El usuario no está activo!! :(",
                        ];

                    } else if ($resultModelo["correcto"] == 3) {
                        $this->mensajes[] = [
                            "tipo" => "danger",
                            "mensaje" => "Has introducido datos incorrectos!! :(",
                        ];

                    } else {
                        $this->mensajes[] = [
                            "tipo" => "danger",
                            "mensaje" => "El login no pudo realizarse correctamente!! :( {$resultModelo["error"]}",
                        ];
                    }

                    $parametros["mensajes"] = $this->mensajes;
                    $this->view->show("Login", $parametros);
                }
            } else {
                $this->mensajes[] = [
                    "tipo" => "danger",
                    "mensaje" => "Faltan datos por introducir!!",
                ];
                $parametros["mensajes"] = $this->mensajes;
                $this->view->show("Login", $parametros);
            }

        } else {
            $this->view->show("Login", $parametros);

        }

    }

    /**
     * Podemos implementar la acción registro de usuarios
     *
     * @return void
     */
    public function register()
    {
        // Array asociativo que almacenará los mensajes de error que se generen por cada campo
        $errores = array();
// Si se ha pulsado el botón guardar...
        if (isset($_POST) && !empty($_POST) && isset($_POST['submit'])) { // y hemos recibido las variables del formulario y éstas no están vacías...

            $nif = $_POST['txtnif'];
            $nombre = $_POST['txtnombre'];
            $apellido1 = $_POST['txtapellido1'];
            $apellido2 = $_POST['txtapellido2'];
            $login = $_POST['txtlogin'];
            $email = $_POST['txtemail'];
            $password = sha1($_POST['txtpass']);
            $telefono = $_POST['txttelefono'];
            $direccion = $_POST['txtdireccion'];

            /* Realizamos la carga de la imagen en el servidor */
            //       Comprobamos que el campo tmp_name tiene un valor asignado para asegurar que hemos
            //       recibido la imagen correctamente
            //       Definimos la variable $imagen que almacenará el nombre de imagen
            //       que almacenará la Base de Datos inicializada a NULL
            $imagen = null;

            if (isset($_FILES["imagen"]) && (!empty($_FILES["imagen"]["tmp_name"]))) {
                // Verificamos la carga de la imagen
                // Comprobamos si existe el directorio fotos, y si no, lo creamos
                if (!is_dir("assets/fotos")) {
                    $dir = mkdir("assets/fotos", 0777, true);
                } else {
                    $dir = true;
                }
                // Ya verificado que la carpeta uploads existe movemos el fichero seleccionado a dicha carpeta
                if ($dir) {
                    //Para asegurarnos que el nombre va a ser único...
                    $nombrefichimg = time() . "-" . $_FILES["imagen"]["name"];
                    // Movemos el fichero de la carpeta temportal a la nuestra
                    $movfichimg = move_uploaded_file($_FILES["imagen"]["tmp_name"], "assets/fotos/" . $nombrefichimg);
                    $imagen = $nombrefichimg;
                    // Verficamos que la carga se ha realizado correctamente
                    if (!$movfichimg) {

                        $this->mensajes[] = [
                            "tipo" => "danger",
                            "mensaje" => "Error: La imagen no se cargó correctamente! :(",
                        ];
                        $errores["imagen"] = "Error: La imagen no se cargó correctamente! :(";
                    }
                }
            }
            // Si no se han producido errores realizamos el registro del usuario
            if (count($errores) == 0) {
                $resultModelo = $this->modelo->adduser([
                    'nif' => $nif,
                    'nombre' => $nombre,
                    'apellido1' => $apellido1,
                    'apellido2' => $apellido2,
                    'login' => $login,
                    "password" => $password,
                    'email' => $email,
                    'telefono' => $telefono,
                    'direccion' => $direccion,
                    'imagen' => $imagen,
                    'rol_id' => 2,

                ]);
                if ($resultModelo["correcto"]) {
                    $this->mensajes[] = [
                        "tipo" => "success",
                        "mensaje" => "El usuarios se registró correctamente!! :)",
                    ];
                } else {
                    $this->mensajes[] = [
                        "tipo" => "danger",
                        "mensaje" => "El usuario no pudo registrarse!! :( <br />({$resultModelo["error"]})",
                    ];
                }
            } else {
                $this->mensajes[] = [
                    "tipo" => "danger",
                    "mensaje" => "Datos de registro de usuario erróneos!! :(",
                ];
            }
        }

        $parametros = [
            "tituloventana" => "Base de Datos con PHP y PDO",
            "datos" => [
                "txtnif" => isset($nif) ? $nif : "",
                "txtnombre" => isset($nombre) ? $nombre : "",
                "txtapellido1" => isset($apellido1) ? $apellido1 : "",
                "txtapellido2" => isset($apellido2) ? $apellido2 : "",
                "txtlogin" => isset($login) ? $login : "",
                "txtpass" => isset($password) ? $password : "",
                "txtemail" => isset($email) ? $email : "",
                "imagen" => isset($imagen) ? $imagen : "",
                "txttelefono" => isset($telefono) ? $telefono : "",
                "txtdireccion" => isset($direccion) ? $direccion : "",
            ],
            "mensajes" => $this->mensajes,
        ];
//Visualizamos la vista asociada al registro de usuarios
        $this->view->show("Registro", $parametros);

    }

    /**
     * Otras acciones que puedan ser necesarias
     */
}
