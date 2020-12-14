<?php

/**
 * Incluimos los modelos que necesite este controlador, en este caso, Activity controler
 */

require 'vendor/phpmailer/phpmailer/src/Exception.php';
require 'vendor/phpmailer/phpmailer/src/PHPMailer.php';
require 'vendor/phpmailer/phpmailer/src/SMTP.php';

require_once MODELS_FOLDER . 'UserModel.php';
require_once MODELS_FOLDER . 'MessageModel.php';

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

/**
 * Clase controlador de la página de inicio,  al portal desde la que se realizarán funcones varias,
 * como el trato de los mensajes entre usuarios, o la redirección a la página de inicio del usuario
 */
class HomeController extends BaseController
{
    /**
     * Clase modelo 1 (UserModel) que utilizaremos para acceder a los datos de usuarios
     * @var [view] Objeto de tipo UserModel
     */
    private $modeloUser;

    /**
     * Clase modelo 2 (MessageModel) que utilizaremos para acceder a los mensajes de los usuarios, así
     * como poder trabajar con ellos desde el controlador
     * @var [view] Objeto de tipo UserModel
     */
    private $modeloMens;

    /**
     * Constructor que crea automáticamente un objeto modelo en el controlador e
     * inicializa los mensajes a vacío
     */
    public function __construct()
    {
        parent::__construct();
        $this->modeloUser = new UserModel();
        $this->modeloMens = new MessageModel();
        $this->mensajes = [];
    }

    /**
     * Metodo que me lleva a la página de inicio de un usuario
     * @return void No devuelve nada, pues simplemente muestra la lista, pasándole los parámetros
     */
    public function index()
    {
        require_once CHECK_SESSION_FILE;

        $parametros = [
            "tituloventana" => "Página de inicio",
        ];

        if (isset($_SESSION['modo'])) { //Si existe la sesión del modo de la bandeja, la borramos
            unset($_SESSION['modo']);
        }
        $this->view->show("inicio", $parametros);
    }

    /**
     * Método que obtiene de la base de datos el listado de mensajes y envía dicha
     * infomación a la vista correspondiente para su visualización
     */
    public function listado()
    {
        require_once CHECK_SESSION_FILE;
        // Almacenamos en el array 'parametros[]'los valores que vamos a mostrar en la vista
        $parametros = [
            "tituloventana" => "Lista de mensajes",
            "datos" => null,
            "mensajes" => [],
            "paginacion" => [],
        ];

        if (!isset($_SESSION['modo'])) { //Sesión que utilizamos para diferenciar entre la bandeja de entrada y de salida
            $_SESSION['modo'] = $_GET['modo']; //Si no existe dicha sesión, la creamos
        }
        // Realizamos la consulta y almacenamos los resultados en la variable $resultModelo
        $resultModelo = $this->modeloMens->listado($_SESSION['modo']);
        // Si la consulta se realizó correctamente transferimos los datos obtenidos
        // de la consulta del modelo ($resultModelo["datos"]) a nuestro array parámetros
        // ($parametros["datos"]), que será el que le pasaremos a la vista para visualizarlos
        if ($resultModelo["correcto"]) :
            $parametros["datos"] = $resultModelo["datos"];
            $parametros["paginacion"] = $resultModelo["paginacion"]; //También obtenemos los datos necesarios para la paginación
        //Definimos el mensaje para el alert de la vista de que todo fue correctamente

        else :
            //Definimos el mensaje para el alert de la vista de que se produjeron errores al realizar el listado
            $this->mensajes[] = [
                "tipo" => "danger",
                "mensaje" => "El listado no pudo realizarse correctamente!! :( <br/>({$resultModelo["error"]})",
            ];
        endif;
        //Asignamos al campo 'mensajes' del array de parámetros el valor del atributo
        //'mensaje', que recoge cómo finalizó la operación:
        $parametros["mensajes"] = $this->mensajes;
        // Incluimos la vista en la que visualizaremos los datos o un mensaje de error
        $this->view->show("ListadoMessage", $parametros);
    }

    /**
     * Metodo que elimina un mensaje seleccionado de la tabla de mensaje, proveniendo su id
     * @return void No devuelve nada, pues simplemente devuelve la lista, pasándole los parámetros
     */
    public function delmessage()
    {

        require_once CHECK_SESSION_FILE; //Comprobamos la sesión, abriendola en el proceso, para el posible uso de sesiones
        // verificamos que hemos recibido los parámetros desde la vista de listado
        if (isset($_GET['id']) && (is_numeric($_GET['id']))) {
            $id = $_GET["id"];
            //Realizamos la operación de suprimir el mensaje con el id=$id
            $resultModelo = $this->modeloMens->delmessage($id);
            //Analizamos el valor devuelto por el modelo para definir el mensaje a
            //mostrar en la vista listado
            if ($resultModelo["correcto"]) :
                $this->mensajes[] = [
                    "tipo" => "success",
                    "mensaje" => "Se eliminó correctamente el mensaje!!",
                ];
            else : //Si ha habido errores en el proceso de borrado del mensaje
                $this->mensajes[] = [
                    "tipo" => "danger",
                    "mensaje" => "La eliminación del mensaje no se realizó correctamente!! :( <br/>({$resultModelo["error"]})",
                ];
            endif;
        } else { //Si no recibimos el valor del parámetro $id generamos el mensaje indicativo:
            $this->mensajes[] = [
                "tipo" => "danger",
                "mensaje" => "No se pudo acceder al id del mensaje a eliminar!! :(",
            ];
        }
        //Realizamos el listado de los mensajes
        $this->listado();
    }
    /**
     * Metodo que añade un mensaje nuevo, cuyas propiedades indicamos por formulario
     * @return void No devuelve nada, pues simplemente devuelve la lista, pasándole los parámetros
     */
    public function addmessage()
    {
        require_once CHECK_SESSION_FILE; //Comprobamos la sesión, abriendola en el proceso, para el posible uso de sesiones
        // Array asociativo que almacenará los mensajes de error que se generen por cada campo
        $errores = array();
        $enviador = $_SESSION['id'];
        // Si se ha pulsado el botón guardar...
        if (isset($_POST) && !empty($_POST) && isset($_POST['submit'])) { // y hemos recibido las variables del formulario y éstas no están vacías...

            $receptor = $_POST['txtlogin']; //Guardamos todos los valores obtenidos
            $asunto = $_POST['txtasunto'];
            $mensaje = $_POST['txtmensaje'];


            $datosSaneados = $this->modeloUser->sanearValores([ //Saneamos dichos valores
                'receptor' => $receptor,
                'asunto' => $asunto,
                'mensaje' => $mensaje,
            ]);

            $errores = $this->modeloMens->comprobarRestricciones($datosSaneados, "mensaje"); //Obtenemos los posibles errores del saneamiento

            // Si no se han producido errores realizamos el registro del mensaje
            if (count($errores) == 0) {
                $resultModelo = $this->modeloMens->addmessage([
                    'receptor' => $datosSaneados['receptor'],
                    'asunto' => $datosSaneados['asunto'],
                    'mensaje' => $datosSaneados['mensaje'],
                    'enviador' => $enviador,
                ]);

                if ($resultModelo["correcto"]) :
                    $this->mensajes[] = [
                        "tipo" => "success",
                        "mensaje" => "El mensaje se envió correctamente!! :)",
                    ];
                else : //Si hemos obtenido errores en el proceso de adición del mensaje a la tabla..
                    $this->mensajes[] = [
                        "tipo" => "danger",
                        "mensaje" => "El mensaje no pudo enviarse!! :( <br />({$resultModelo["error"]})",
                    ];
                endif;
            } else {
                //Si hemos tenido errores de restricciones de campos del formulario..
                foreach ($errores as &$error) {
                    $this->mensajes[] = [
                        "tipo" => "danger",
                        "mensaje" => $error,
                    ];
                }
            }
        } //Si no hemos pulsado el botón submit (porque hemos venido ahora a esta ventana), tendremos los campos vacíos
        //De lo contrario tendremos los campos llenos con los valores introducidos

        $parametros = [
            "tituloventana" => "Envío de mensaje",
            "datos" => [
                "txtlogin" => isset($datosSaneados['receptor']) ? $datosSaneados['receptor'] : "",
                "txtasunto" => isset($datosSaneados['asunto']) ? $datosSaneados['asunto'] : "",
                "txtmensaje" => isset($datosSaneados['mensaje']) ? $datosSaneados['mensaje'] : "",
            ],
            "mensajes" => $this->mensajes,
            "id_origen" => isset($enviador) ? $enviador : "",
        ];
        //Visualizamos la vista asociada al registro de mensajes
        $this->view->show("AddMessage", $parametros);
    }

    /**
     * Método que permite nos permite actualizar los datos de un mensaje elegido, cuya id coincide con la que
     * se pasa como parámetro desde la vista del listado, a través de GET
     * @return void No devuelve nada, pues simplemente devuelve la lista, pasándole los parámetros
     */
    public function seemessage()
    {
        require_once CHECK_SESSION_FILE;

        $parametros = [
            "tituloventana" => "Visualizacion de mensaje",
            "datos" => null,
            "mensajes" => [],
        ];
        // Array asociativo que almacenará los mensajes de error que se generen por cada campo
        $errores = array();
        // Inicializamos valores de los campos de texto

        //Estamos rellenando los campos con los valores recibidos del listado
        if (isset($_GET['id']) && (is_numeric($_GET['id']))) {
            $id = $_GET['id'];
            //Ejecutamos la consulta para obtener los datos del mensaje #id
            $resultModelo = $this->modeloMens->listamensaje($id, $_SESSION['modo']);
            //Analizamos si la consulta se realiz´correctamente o no y generamos un
            //mensaje indicativo
            if ($resultModelo["correcto"]) :
                $parametros['datos'] = $resultModelo['datos'];

                $this->mensajes[] = [
                    "tipo" => "success",
                    "mensaje" => "Los datos del mensaje se obtuvieron correctamente!! :)",
                ];
            //Si hemos obtenido los valores correctamente, los copiamos

            else :
                $this->mensajes[] = [
                    "tipo" => "danger",
                    "mensaje" => "No se pudieron obtener los datos de mensaje!! :( <br/>({$resultModelo["error"]})",
                ];
            endif;
        }


        //Preparamos un array con todos los valores que tendremos que rellenar en
        //la vista adduser: título de la página y campos del formulario
        $parametros['mensajes'] = $this->mensajes;
        //Mostramos la vista actuser
        $this->view->show("seeMessage", $parametros);
    }

    /**
     * Función que se utiliza para enviar correos electrónicos por parte de un administrador hacia una dirección de correo
     *perteneciente a uno de los usuarios de la página web
     * @return void
     */
    public function addmail()
    {

        require_once CHECK_SESSION_FILE;

        if (isset($_POST) && !empty($_POST) && isset($_POST['submit'])) { // y hemos recibido las variables del formulario y éstas no están vacías...

            $asunto = $_POST['txtasunto'];
            $mensaje = $_POST['txtmensaje'];
            $receptor = $_POST['txtemail'];

            $datosSaneados = $this->modeloUser->sanearValores([ //Saneamos dichos valores
                'asunto' => $asunto,
                'mensaje' => $mensaje,
                'receptor' => $receptor,
            ]);

            $errores = $this->modeloMens->comprobarRestricciones($datosSaneados, "correo"); //Obtenemos los posibles errores del saneamiento

            // Si no se han producido errores realizamos el registro del mensaje
            if (count($errores) == 0) {

                $mail = new PHPMailer(true);
                try {

                    $mail->SMTPDebug = 0; // Inhabilita la salida de depuración verbosa
                    $mail->isSMTP(); // Habilita el uso de SMTP
                    $mail->Host = 'smtp.gmail.com';  // Especifica el servidor de SMTP principal
                    $mail->SMTPAuth = true;  // Habilita la autentificación SMTP
                    $mail->Username = 'ejemplogimnasio@gmail.com'; // Nombre de usuario de SMTP (usuario que existe en 
                    //el servidor especificado, en mi caso un correo electrónico)
                    $mail->Password = 'Gimnasio2@'; // Contraseña del usuario indicado SMTP
                    $mail->SMTPSecure = 'tls';   // Habilita la encriptación TLS
                    $mail->Port = 587;    // Puerto TCP al que nos conectamos

                    $mail->setFrom('ejemplogimnasio@gmail.com', 'Gimnasio pelotita'); //Dirección de correo desde la que enviamos los mensajes
                    $mail->addAddress("$receptor", 'Usuario del gimnasio');  //Podemos añadir más correos

                    //Contenido
                    $mail->isHTML(true);    // Establecemos el formato del email a html
                    $mail->Subject = $datosSaneados['asunto']; //Establecemos el asunto, según el introducido
                    $mail->Body    = $datosSaneados['mensaje'];

                    $mail->send();
                } catch (Exception $e) {
                    $this->mensajes[] = [
                        'tipo' => 'danger',
                        'mensaje' => "El correo a la dirección $receptor no se ha podido enviar!! $mail->ErrorInfo",
                    ];
                }
            } else {
                //Si hemos tenido errores de restricciones de campos del formulario..
                foreach ($errores as &$error) {
                    $this->mensajes[] = [
                        "tipo" => "danger",
                        "mensaje" => $error,
                    ];
                }
            }
        } //Si no hemos pulsado el botón submit (porque hemos venido ahora a esta ventana), tendremos los campos vacíos
        //De lo contrario tendremos los campos llenos con los valores introducidos

        $parametros = [
            "tituloventana" => "Envío de correos",
            "datos" => [
                "txtasunto" => isset($datosSaneados['asunto']) ? $datosSaneados['asunto'] : "",
                "txtmensaje" => isset($datosSaneados['mensaje']) ? $datosSaneados['mensaje'] : "",
                "txtemail" => isset($datosSaneados['receptor']) ? $datosSaneados['receptor'] : "",
            ],
            "mensajes" => $this->mensajes,
        ];
        //Visualizamos la vista asociada al registro de mensajes
        $this->view->show("AddMail", $parametros);
    }
}
