<?php

/**
 * Incluimos los modelos que necesite este controlador, en este caso el modelo de usuarios únicamente
 */
require_once MODELS_FOLDER . 'UserModel.php';

/**
 * Clase controlador que será la encargada de obtener, para cada tarea, los datos
 * necesarios de la base de datos, y posteriormente, tras su proceso de elaboración,
 * enviarlos a la vista para su visualización, en este caso las tareas en relación con la actualización,
 * listado, adición y borrado de usuarios
 */
class UserController extends BaseController
{
    // El atributo $modelo es de la 'clase modelo' y será a través del que podremos
    // acceder a los datos y las operaciones de la base de datos desde el controlador
    private $modelo;
    //$mensajes se utiliza para almacenar los mensajes generados en las tareas,
    //que serán posteriormente transmitidos a la vista para su visualización
    private $mensajes;

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
     * Funcion que obtiene de la base de datos el listado de usuarios y envía dicha
     * infomación a la vista correspondiente para su visualización
     */
    public function listado()
    {
        require_once CHECK_SESSION_FILE;
        // Almacenamos en el array 'parametros[]'los valores que vamos a mostrar en la vista
        $parametros = [
            "tituloventana" => "Lista de usuarios",
            "datos" => null,
            "mensajes" => [],
            "paginacion" => [],
        ];
        // Realizamos la consulta y almacenamos los resultados en la variable $resultModelo
        $resultModelo = $this->modelo->listado();
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
        $this->view->show("ListadoUser", $parametros);
    }

    /**
     * Funcion que elimina un usuario seleccionado de la tabla de usuarios, proveniendo su id
     * @return void No devuelve nada, pues simplemente devuelve la lista, pasándole los parámetros
     */
    public function deluser()
    {

        require_once CHECK_SESSION_FILE; //Comprobamos la sesión, abriendola en el proceso, para el posible uso de sesiones
        // verificamos que hemos recibido los parámetros desde la vista de listado
        if (isset($_GET['id']) && (is_numeric($_GET['id']))) {
            $id = $_GET["id"];
            //Realizamos la operación de suprimir el usuario con el id=$id
            $resultModelo = $this->modelo->deluser($id);
            //Analizamos el valor devuelto por el modelo para definir el mensaje a
            //mostrar en la vista listado
            if ($resultModelo["correcto"]) :
                $this->mensajes[] = [
                    "tipo" => "success",
                    "mensaje" => "Se eliminó correctamente el usuario $id",
                ];
            else : //Si ha habido errores en el proceso de borrado del usuario
                $this->mensajes[] = [
                    "tipo" => "danger",
                    "mensaje" => "La eliminación del usuario no se realizó correctamente!! :( <br/>({$resultModelo["error"]})",
                ];
            endif;
        } else { //Si no recibimos el valor del parámetro $id generamos el mensaje indicativo:
            $this->mensajes[] = [
                "tipo" => "danger",
                "mensaje" => "No se pudo acceder al id del usuario a eliminar!! :(",
            ];
        }
        //Realizamos el listado de los usuarios
        $this->listado();
    }
    /**
     * Funcion que añade un usuario nuevo, cuyas propiedades indicamos por formulario
     * @return void No devuelve nada, pues simplemente devuelve la lista, pasándole los parámetros
     */
    public function adduser()
    {

        require_once CHECK_SESSION_FILE; //Comprobamos la sesión, abriendola en el proceso, para el posible uso de sesiones
        // Array asociativo que almacenará los mensajes de error que se generen por cada campo
        $errores = array();
        // Si se ha pulsado el botón guardar...
        if (isset($_POST) && !empty($_POST) && isset($_POST['submit'])) { // y hemos recibido las variables del formulario y éstas no están vacías...

            $nif = $_POST['txtnif']; //Guardamos todos los valores obtenidos
            $nombre = $_POST['txtnombre'];
            $apellido1 = $_POST['txtapellido1'];
            $apellido2 = $_POST['txtapellido2'];
            $login = $_POST['txtlogin'];
            $email = $_POST['txtemail'];
            $password = $_POST['txtpass'];
            $telefono = $_POST['txttelefono'];
            $direccion = $_POST['txtdireccion'];
            $rol_id = $_POST['rol_id'];

            $datosSaneados = $this->modelo->sanearValores([ //Saneamos dichos valores
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

            $errores = $this->modelo->comprobarRestricciones($datosSaneados); //Obtenemos los posibles errores del saneamiento
            //Para decidir si seguir o no con la operación

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
                    'rol_id' => $rol_id,

                ]);
                if ($resultModelo["correcto"]) :
                    $this->mensajes[] = [
                        "tipo" => "success",
                        "mensaje" => "El usuarios se registró correctamente!! :)",
                    ];
                else : //Si hemos obtenido errores en el proceso de adición del usuario a la tabla..
                    $this->mensajes[] = [
                        "tipo" => "danger",
                        "mensaje" => "El usuario no pudo registrarse!! :( <br />({$resultModelo["error"]})",
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
            "tituloventana" => "Creación de usuario",
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
                "rol_id" => isset($rol_id) ? $rol_id : 2,
            ],
            "mensajes" => $this->mensajes,
        ];
        //Visualizamos la vista asociada al registro de usuarios
        $this->view->show("AddUser", $parametros);
    }

    /**
     * Funcion que permite nos permite actualizar los datos de un usuario elegido, cuya id coincide con la que
     * se pasa como parámetro desde la vista del listado, a través de GET
     * @return void No devuelve nada, pues simplemente devuelve la lista, pasándole los parámetros
     */
    public function actuser()
    {
        require_once CHECK_SESSION_FILE;
        // Array asociativo que almacenará los mensajes de error que se generen por cada campo
        $errores = array();
        // Inicializamos valores de los campos de texto
        $valnombre = "";
        $valapellido1 = "";
        $valapellido2 = "";
        $vallogin = "";
        $valemail = "";
        $valtelefono = "";
        $valdireccion = "";
        $valimagen = "";
        $valrol_id = "";
        $valpass = "";

        // Si se ha pulsado el botón actualizar...
        if (isset($_POST['submit'])) { //Realizamos la actualización con los datos existentes en los campos
            $id = $_POST['id']; //Lo recibimos por el campo oculto
            $nuevorol_id = $_POST['rol_id'];
            $nuevonif = $_POST['txtnif'];
            $nuevonombre = $_POST['txtnombre'];
            $nuevoemail = $_POST['txtemail'];
            $nuevoapellido1 = $_POST['txtapellido1'];
            $nuevoapellido2 = $_POST['txtapellido2'];
            $nuevologin = $_POST['txtlogin'];
            $nuevotelefono = $_POST['txttelefono'];
            $nuevodireccion = $_POST['txtdireccion'];
            $nuevaimagen = "";
            $nuevapassword = $_POST['txtpass'];

            $datosSaneados = $this->modelo->sanearValores([ //Saneamos los datos introducidos, evitando carácteres especiales
                'nif' => $nuevonif,
                'nombre' => $nuevonombre,
                'apellido1' => $nuevoapellido1,
                'apellido2' => $nuevoapellido2,
                'login' => $nuevologin,
                "password" => $nuevapassword,
                'email' => $nuevoemail,
                'telefono' => $nuevotelefono,
                'direccion' => $nuevadireccion,
            ]);

            $errores = $this->modelo->comprobarRestricciones($datosSaneados); //Obtenemos los posibles errores del saneamiento
            //Para decidir si seguir o no con la operación

            // Definimos la variable $imagen que almacenará el nombre de imagen
            // que almacenará la Base de Datos inicializada a NULL
            $imagen = null;

            if (isset($_FILES["imagen"]) && (!empty($_FILES["imagen"]["tmp_name"]))) {
                // Verificamos la carga de la imagen
                // Comprobamos si existe el directorio fotos, y si no, lo creamos
                if (!is_dir("assets/fotos")) {
                    $dir = mkdir("assets/fotos", 0777, true);
                } else {
                    $dir = true;
                }
                // Ya verificado que la carpeta fotos existe movemos el fichero seleccionado a dicha carpeta
                if ($dir) {
                    //Para asegurarnos que el nombre va a ser único...
                    $nombrefichimg = time() . "-" . $_FILES["imagen"]["name"];
                    // Movemos el fichero de la carpeta temportal a la nuestra
                    $movfichimg = move_uploaded_file($_FILES["imagen"]["tmp_name"], "assets/fotos/" . $nombrefichimg);
                    $imagen = $nombrefichimg;
                    // Verficamos que la carga se ha realizado correctamente
                    if (!$movfichimg) {
                        //Si no pudo moverse a la carpeta destino generamos un mensaje que se le
                        //mostrará al usuario en la vista actuser
                        $errores["imagen"] = "Error: La imagen no se cargó correctamente! :(";
                        $this->mensajes[] = [
                            "tipo" => "danger",
                            "mensaje" => "Error: La imagen no se cargó correctamente! :(",
                        ];
                    }
                }
            }
            $nuevaimagen = $imagen;

            if (count($errores) == 0) {
                //Ejecutamos la instrucción de actualización a la que le pasamos los valores
                $resultModelo = $this->modelo->actuser([
                    'nif' => $datosSaneados['nif'],
                    'nombre' => $datosSaneados['nombre'],
                    'apellido1' => $datosSaneados['apellido1'],
                    'apellido2' => $datosSaneados['apellido2'],
                    'login' => $datosSaneados['login'],
                    "password" => $datosSaneados['password'],
                    'email' => $datosSaneados['email'],
                    'telefono' => $datosSaneados['telefono'],
                    'direccion' => $datosSaneados['direccion'],
                    'imagen' => $nuevaimagen,
                    'rol_id' => $rol_id,
                    'id' => $id,
                ]);
                //Analizamos cómo finalizó la operación de registro y generamos un mensaje
                //indicativo del estado correspondiente
                if ($resultModelo["correcto"]) :
                    $this->mensajes[] = [
                        "tipo" => "success",
                        "mensaje" => "El usuario se actualizó correctamente!! :)",
                    ];
                else :
                    $this->mensajes[] = [
                        "tipo" => "danger",
                        "mensaje" => "El usuario no pudo actualizarse!! :( <br/>({$resultModelo["error"]})",
                    ];
                endif;
            } else {
                foreach ($errores as &$error) {
                    $this->mensajes[] = [
                        "tipo" => "danger",
                        "mensaje" => $error,
                    ];
                }
            }

            // Obtenemos los valores para mostrarlos en los campos del formulario
            $valnif = $datosSaneados['nif'];
            $valnombre = $datosSaneados['nombre'];
            $valapellido1 = $datosSaneados['apellido1'];
            $valapellido2 = $datosSaneados['apellido2'];
            $vallogin = $datosSaneados['login'];
            $valemail = $datosSaneados['email'];
            $valimagen = $nuevaimagen;
            $valtelefono = $datosSaneados['telefono'];
            $valdireccion = $datosSaneados['direccion'];
            $valrol_id = $nuevorol_id;
            $valpass = $datosSaneados['password'];
        } else { //Estamos rellenando los campos con los valores recibidos del listado
            if (isset($_GET['id']) && (is_numeric($_GET['id']))) {
                $id = $_GET['id'];
                //Ejecutamos la consulta para obtener los datos del usuario #id
                $resultModelo = $this->modelo->listausuario($id);
                //Analizamos si la consulta se realiz´correctamente o no y generamos un
                //mensaje indicativo
                if ($resultModelo["correcto"]) :
                    $this->mensajes[] = [
                        "tipo" => "success",
                        "mensaje" => "Los datos del usuario se obtuvieron correctamente!! :)",
                    ];
                    //Si hemos obtenido los valores correctamente, los copiamos
                    $valnif = $resultModelo["datos"]["nif"];
                    $valnombre = $resultModelo["datos"]["usu_nombre"];
                    $valapellido1 = $resultModelo["datos"]["apellido1"];
                    $valapellido2 = $resultModelo["datos"]["apellido2"];
                    $vallogin = $resultModelo["datos"]["login"];
                    $valemail = $resultModelo["datos"]["email"];
                    $valimagen = $resultModelo["datos"]["imagen"];
                    $valtelefono = $resultModelo["datos"]["telefono"];
                    $valdireccion = $resultModelo["datos"]["direccion"];
                    $valrol_id = $resultModelo["datos"]["rol_id"];
                    $valpass = "";

                else :
                    $this->mensajes[] = [
                        "tipo" => "danger",
                        "mensaje" => "No se pudieron obtener los datos de usuario!! :( <br/>({$resultModelo["error"]})",
                    ];
                endif;
            }
        }

        //Preparamos un array con todos los valores que tendremos que rellenar en
        //la vista adduser: título de la página y campos del formulario
        $parametros = [
            "tituloventana" => "Actualización de usuario",
            "datos" => [
                "txtnif" => $valnif,
                "txtnombre" => $valnombre,
                "txtapellido1" => $valapellido1,
                "txtapellido2" => $valapellido2,
                "txtlogin" => $vallogin,
                "txtemail" => $valemail,
                "txttelefono" => $valtelefono,
                "txtdireccion" => $valdireccion,
                "imagen" => $valimagen,
                "rol_id" => $valrol_id,
                "txtpass" => $valpass,
            ],
            "mensajes" => $this->mensajes,
            "id" => $id,
        ];
        //Mostramos la vista actuser
        $this->view->show("ActUser", $parametros);
    }


    /**
     * Funcion que cambia el estado de un usuario cuya id obtenemos mediante GET
     */
    public function cambiarEstado()
    {

        require_once CHECK_SESSION_FILE;

        // verificamos que hemos recibido los parámetros desde la vista de listado
        if (isset($_GET['id']) && (is_numeric($_GET['id']))) {
            $id = $_GET["id"];
            $cambio = $_GET["cambio"]; //Valor que indica si queremos habilitarlo o deshabilitarlo

            if ($_SESSION['id'] == $id) { //Si intentamos cambiar el estado del usuario activo..
                $this->mensajes[] = [
                    "tipo" => "danger",
                    "mensaje" => "No puedes deshabilitarte a ti mismo!! :(",
                ];
            } else {
                //Realizamos la operación de suprimir el usuario con el id=$id
                $resultModelo = $this->modelo->cambiarEstado($id, $cambio);
                //Analizamos el valor devuelto por el modelo para definir el mensaje a
                //mostrar en la vista listado
                if ($resultModelo["correcto"]) {
                    $this->mensajes[] = [
                        "tipo" => "success",
                        "mensaje" => "El estado del usuario $id se cambió correctamente",
                    ];
                } else {
                    $this->mensajes[] = [
                        "tipo" => "danger",
                        "mensaje" => "El cambio de estado del usuario no se realizó correctamente!! :( <br/>({$resultModelo["error"]})",
                    ];
                }
            }
        } else { //Si no recibimos el valor del parámetro $id generamos el mensaje indicativo:
            $this->mensajes[] = [
                "tipo" => "danger",
                "mensaje" => "No se pudo acceder a la id del usuario cuyo estado desea actualizar!! :(",
            ];
        }
        //Realizamos el listado de los usuarios
        $this->listado();
    }
}
