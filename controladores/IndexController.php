<?php

/**
 * Incluimos todos los modelos que necesite este controlador
 */
require_once MODELS_FOLDER . 'UserModel.php';

/**
 * Clase controlador de la página principal, que será la encargada de obtener, para cada tarea, los datos
 * necesarios de la base de datos, y posteriormente, tras su proceso de elaboración,
 * enviarlos a la vista para su visualización, en este caso para las tareas en relación con el registro y login de usuarios.
 */
class IndexController extends BaseController
{

    /**
     * Clase modelo (en ete caso UserModel) que utilizaremos para acceder a los datos y operaciones de la 
     * base de datos desde el controlador
     * @var [view] Objeto de tipo UserModel
     */
    private $modelo;

    /**
     * $mensajes se utiliza para almacenar los mensajes generados en las tareas,
     * que serán posteriormente transmitidos a la vista para su visualización
     * @var [array] Array de mensajes
     */
    private $mensajes = [];

    /**
     * Constructor que crea automáticamente un objeto modelo en el controlador e
     * inicializa los mensajes a vacío
     */
    public function __construct()
    {
        parent::__construct();
        $this->modelo = new UserModel();
        $this->mensajes = [];
    }

    /**
     * Metodo que nos lleva a la página principal de la aplicación
     * @return void  No devuelve nada, pues simplemente devuelve la lista, pasándole los parámetros
     */
    public function index()
    {
        $parametros = [
            "tituloventana" => "Pagina principal",
        ];

        if (isset($_GET['fuera'])) { //Si hemos llegado aquí tras intentar acceder a una ventana sin permisos
            $this->mensajes[] = [
                "tipo" => "danger",
                "mensaje" => "No puedes acceder a otras páginas sin loguearte!! :(",
            ];
            $parametros["mensajes"] = $this->mensajes;
        } else { //Si no es el caso, y por ende hemos accedido aquí legalmente

            if (session_status() == PHP_SESSION_NONE) { //Abrimos una sesión en caso de ser necesario, para trabajar con dichas sesiones
                session_start();
            }

            if (isset($_COOKIE['mantener']) && !isset($_SESSION['logueado'])) { //Si está establecida la cookie de mantener pero no la de estar logueado
                //Por que hemos salido de la página desde otra ventana y hemos vuelto con la opción de mantenerla abierta
                header("Location: index.php?controller=home&accion=index"); //Nos dirigimos a la página de inicio del usuario
            } else if (isset($_SESSION['logueado'])) { //De lo contrario, eliminamos todas las sesiones y cookies
                unset($_SESSION['logueado']);
                unset($_SESSION['rol']);
                unset($_SESSION['id']);
                unset($_SESSION['hora']);

                if (isset($_COOKIE['mantener'])) {
                    unset($_COOKIE['mantener']);
                }
            }
        }
        // Incluimos la vista en la que visualizaremos los datos o un mensaje de error
        $this->view->show("Principal", $parametros);
    }

    /**
     * Método que se encargará del login de los usuarios en nuestra página
     * @return void  No devuelve nada, pues simplemente devuelve la lista, pasándole los parámetros
     */
    public function login()
    {

        $parametros = [
            "tituloventana" => "Página de login",
            "datos" => null,
            "mensajes" => [],
        ];

        if (isset($_POST['submit'])) { //Si hemos pulsado llegado aquí tras pulsar el botón de login

            if ((isset($_POST['usuario']) && isset($_POST['password'])) && (!empty($_POST['usuario']) && !empty($_POST['password']))) { //Si hemos introducido datos..

                $captcha = $_POST['g-recaptcha-response'];

                if ($captcha) {

                    $respuesta = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=" . SECRET_KEY_CAPTCHA . "&response=$captcha");
                    $correcto = json_decode($respuesta, TRUE);

                    $datosSaneados = $this->modelo->sanearValores([ //Los saneamos
                        "usuario" => $_POST['usuario'],
                        "password" => $_POST['password'],
                    ]);

                    $usuarioSaneado = $datosSaneados['usuario']; //Utilizamos los datos saneados
                    $passwordSaneada = $datosSaneados['password'];

                    $resultModelo = $this->modelo->loginCorrecto($usuarioSaneado, $passwordSaneada); //Comprobamos que existe un usuario con esos datos

                    if ($resultModelo["correcto"] == 1) { //Si ese es el caso, iniciamos las sesiones y las cookies pertinentes

                        if ($correcto) {
                            session_start();
                            $_SESSION['logueado'] = $usuarioSaneado;
                            $_SESSION['rol'] = $resultModelo['datos']['rol_id'];
                            $_SESSION['id'] = $resultModelo['datos']['usuario_id'];
                            $_SESSION['hora'] = date("H:i:s");

                            if (isset($_POST['recordar']) && ($_POST['recordar'] == "on")) {
                                setcookie('usuario', $_POST['usuario'], time() + (15 * 24 * 60 * 60));
                                setcookie('password', $_POST['password'], time() + (15 * 24 * 60 * 60));
                                setcookie('recordar', $_POST['recordar'], time() + (15 * 24 * 60 * 60));
                            } else {
                                if (isset($_COOKIE['usuario'])) {
                                    setcookie('usuario', "");
                                }

                                if (isset($_COOKIE['password'])) {
                                    setcookie('password', "");
                                }

                                if (isset($_COOKIE['recordar'])) {
                                    setcookie('recordar', "");
                                }
                            }

                            if (isset($_POST['mantener']) && ($_POST['mantener'] == "on")) {
                                setcookie('mantener', $_POST['usuario'], time() + (15 * 24 * 60 * 60));
                            } else {
                                if (isset($_COOKIE['mantener'])) {
                                    setcookie('mantener', "");
                                }
                            }

                            $parametros["tituloventana"] = "Pagina de inicio";
                            $parametros["datos"] = $resultModelo["datos"];
                            $this->view->show("Inicio", $parametros); //Si  hemos introducido los datos correctos, mostramos la ventana de inicio del usuario

                        } else {
                            $this->mensajes[] = [
                                "tipo" => "danger",
                                "mensaje" => "El captcha no se ha validado correctamente!! :(",
                            ];
                            $parametros["mensajes"] = $this->mensajes;
                            $this->view->show("Login", $parametros);
                        }
                    } else { //Si no existe un usuario activo con esos datos, mostramos el error en cuesión

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
                        $this->view->show("Login", $parametros); //Volvemos a mostrar la ventana de login, con los errores
                    }
                } else {
                    $this->mensajes[] = [
                        "tipo" => "danger",
                        "mensaje" => "No has validado el captcha!! :(",
                    ];
                    $parametros["mensajes"] = $this->mensajes;
                    $this->view->show("Login", $parametros);
                }
            } else { //Si no hemos introducido todos los datos, mostramos igualmente la ventana de login, con los errores
                $this->mensajes[] = [
                    "tipo" => "danger",
                    "mensaje" => "Faltan datos por introducir!!",
                ];
                $parametros["mensajes"] = $this->mensajes;
                $this->view->show("Login", $parametros);
            }
        } else { //Si hemos venido a esta página por primera vez, mostramos la ventan de login
            $this->view->show("Login", $parametros);
        }
    }

    /**
     * Metodo que lleva a cabo el proceso del logueo a nuestra página mediante cuentas de google
     * @return void
     */
    public function loginGoogle()
    {
        include_once 'gpConfig.php';

        if (isset($_GET['code'])) {
            $gClient->authenticate($_GET['code']);
            $_SESSION['token'] = $gClient->getAccessToken();
        }

        if (isset($_SESSION['token'])) {
            $gClient->setAccessToken($_SESSION['token']);
        }

        if ($gClient->getAccessToken()) {
            //Get user profile data from google
            $gpUserProfile = $google_oauthV2->userinfo->get();

            //Insert or update user data to the database
            $datosUsuGoogle = array(
                'autentificacion' => 'google',
                'idgoogle' => $gpUserProfile['id'],
                'email' => $gpUserProfile['email'],
            );

            $datosUsuWeb = $this->modelo->loginGoogle($datosUsuGoogle);

            $_SESSION['logueado'] = $datosUsuWeb['usu_nombre'];
            $_SESSION['rol'] = $datosUsuWeb['rol_id'];
            $_SESSION['id'] = $datosUsuWeb['usuario_id'];
            $_SESSION['hora'] = date("H:i:s");
            $_SESSION['google'] = '1';

            if ($datosUsuWeb['correcto']) {
                $parametros["tituloventana"] = "Pagina de inicio";
                $parametros["datos"] = $datosUsuWeb['datos'];

                $this->mensajes[] = [
                    "tipo" => "success",
                    "mensaje" => $datosUsuWeb['mensaje'],
                ];
                $this->view->show("Inicio", $parametros); //Si  hemos introducido los datos correctos, mostramos la ventana de inicio del usuario


            } else {
                $parametros["tituloventana"] = "Pagina de login";

                $this->mensajes[] = [
                    "tipo" => "danger",
                    "mensaje" => "No se ha podido realizar el registro!" . $datosUsuWeb['mensaje'],
                ];
                $this->view->show("Login", $parametros);
            }
        } else {

            $this->mensajes[] = [
                "tipo" => "danger",
                "mensaje" => "No se ha podido establecer la conexión con google!! :(",
            ];
            $parametros["mensajes"] = $this->mensajes;
            $this->view->show("Login", $parametros);
        }
    }

    /**
     * Método que se encargará del registro de los usuarios en nuestra página
     * @return void  No devuelve nada, pues simplemente devuelve la lista, pasándole los parámetros
     */
    public function register()
    {
        // Array asociativo que almacenará los mensajes de error que se generen por cada campo
        $errores = array();

        // Si se ha pulsado el botón de registro...
        if (isset($_POST) && !empty($_POST) && isset($_POST['submit'])) { // y hemos recibido las variables del formulario y éstas no están vacías...

            $nif = $_POST['txtnif']; //Guardamos los valores introducidos
            $nombre = $_POST['txtnombre'];
            $apellido1 = $_POST['txtapellido1'];
            $apellido2 = $_POST['txtapellido2'];
            $login = $_POST['txtlogin'];
            $email = $_POST['txtemail'];
            $password = $_POST['txtpass'];
            $telefono = $_POST['txttelefono'];
            $direccion = $_POST['txtdireccion'];

            $datosSaneados = $this->modelo->sanearValores([ //Saneamos los valores
                'nif' => $nif,
                'nombre' => $nombre,
                'apellido1' => $apellido1,
                'apellido2' => $apellido2,
                'login' => $login,
                "password" => $password,
                'email' => $email,
                'telefono' => $telefono,
                'direccion' => $direccion,
            ]);

            $errores = $this->modelo->comprobarRestricciones($datosSaneados); //Comprobamos que los datos introducidos cumplan con las restricciones

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
                $resultModelo = $this->modelo->adduser([ //LLamamos al método para añadir el usuario, con los datos ya saneados
                    'nif' => $datosSaneados['nif'],
                    'nombre' => $datosSaneados['nombre'],
                    'apellido1' => $datosSaneados['apellido1'],
                    'apellido2' => $datosSaneados['apellido2'],
                    'login' => $datosSaneados['login'],
                    "password" => $datosSaneados['password'],
                    'email' => $datosSaneados['email'],
                    'telefono' => $datosSaneados['telefono'],
                    'direccion' => $datosSaneados['direccion'],
                    'imagen' => $imagen,
                    'rol_id' => 2,

                ]);
                if ($resultModelo["correcto"]) {
                    $this->mensajes[] = [
                        "tipo" => "success",
                        "mensaje" => "El usuarios se registró correctamente!! :)",
                    ];
                } else { //Si hemos tenido errores en el proceso de creación del ususario
                    $this->mensajes[] = [
                        "tipo" => "danger",
                        "mensaje" => "El usuario no pudo registrarse!! :( <br />({$resultModelo["error"]})",
                    ];
                }
            } else { //Si hemos tenido errores previos en relación con las restricciones de valores
                foreach ($errores as &$error) {
                    $this->mensajes[] = [
                        "tipo" => "danger",
                        "mensaje" => $error,
                    ];
                }
            }
        }

        $parametros = [
            "tituloventana" => "Registro de usuarios",
            "datos" => [
                "txtnif" => isset($datosSaneados['nif']) ? $datosSaneados['nif'] : "",
                "txtnombre" => isset($datosSaneados['nombre']) ? $datosSaneados['nombre'] : "",
                "txtapellido1" => isset($datosSaneados['apellido1']) ? $datosSaneados['apellido1'] : "",
                "txtapellido2" => isset($datosSaneados['apellido2']) ? $datosSaneados['apellido2'] : "",
                "txtlogin" => isset($datosSaneados['login']) ? $datosSaneados['login'] : "",
                "txtpass" => isset($datosSaneados['password']) ? $datosSaneados['password'] : "",
                "txtemail" => isset($datosSaneados['email']) ? $datosSaneados['email'] : "",
                "imagen" => isset($imagen) ? $imagen : "",
                "txttelefono" => isset($datosSaneados['telefono']) ? $datosSaneados['telefono'] : "",
                "txtdireccion" => isset($datosSaneados['direccion']) ? $datosSaneados['direccion'] : "",
            ],
            "mensajes" => $this->mensajes,
        ];
        //Visualizamos la vista asociada al registro de usuarios
        $this->view->show("Registro", $parametros);
    }
}
