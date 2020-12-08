<?php

/**
 * Incluimos los modelos que necesite este controlador, en este caso, Activity controler
 */
require_once MODELS_FOLDER . 'ActivityModel.php';

/**
 * Clase controlador de actividades, que será la encargada de obtener, para cada tarea, los datos
 * necesarios de la base de datos, y posteriormente, tras su proceso de elaboración,
 * enviarlos a la vista para su visualización, en este caso para las tareas en relación con las a ctividades.
 */
class ActivityController extends BaseController
{

    /**
     * Clase modelo (en ete caso ActivityModel) que utilizaremos para acceder a los datos y operaciones de la 
     * base de datos desde el controlador
     * @var [view] Objeto de tipo ActivityModel
     */
    private $modelo;

    /**
     * $mensajes se utiliza para almacenar los mensajes generados en las tareas,
     * que serán posteriormente transmitidos a la vista para su visualización
     * @var [array] Array de mensajes
     */
    private $mensajes;

    /**
     * Constructor que crea automáticamente un objeto modelo en el controlador e
     * inicializa los mensajes a vacío
     */
    public function __construct()
    {
        parent::__construct();
        $this->modelo = new ActivityModel();
        $this->mensajes = [];
    }

    /**
     * Método que obtiene de la base de datos el listado de actividades y envía dicha
     * infomación a la vista correspondiente para su visualización
     * @return void No devuelve nada, pues simplemente devuelve la lista, pasándole los parámetros
     */
    public function listado()
    {
        require_once CHECK_SESSION_FILE; //Comprobamos la sesión, abriendola en el proceso, para el posible uso de sesiones

        // Almacenamos en el array 'parametros[]'los valores que vamos a mostrar en la vista
        $parametros = [
            "tituloventana" => "Lista de actividades",
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
        $this->view->show("ListadoActivity", $parametros);
    }

    /**
     * Metodo que elimina una actividad seleccionada de la tabla de actividades, proveniendo su id
     * @return void No devuelve nada, pues simplemente devuelve la lista, pasándole los parámetros
     */
    public function delactivity()
    {

        require_once CHECK_SESSION_FILE;  //Comprobamos la sesión, abriendola en el proceso, para el posible uso de sesiones

        // verificamos que hemos recibido los parámetros desde la vista de listado
        if (isset($_GET['id']) && (is_numeric($_GET['id']))) {
            $id = $_GET["id"];
            //Realizamos la operación de suprimir el usuario con el id=$id
            $resultModelo = $this->modelo->delactivity($id);
            //Analizamos el valor devuelto por el modelo para definir el mensaje a
            //mostrar en la vista listado
            if ($resultModelo["correcto"]) :
                $this->mensajes[] = [
                    "tipo" => "success",
                    "mensaje" => "Se eliminó correctamente la actividad $id",
                ];
            else :
                $this->mensajes[] = [
                    "tipo" => "danger",
                    "mensaje" => "La eliminación de la actividad no se realizó correctamente!! :( <br/>({$resultModelo["error"]})",
                ];
            endif;
        } else { //Si no recibimos el valor del parámetro $id generamos el mensaje indicativo:
            $this->mensajes[] = [
                "tipo" => "danger",
                "mensaje" => "No se pudo acceder al id de la actividad a eliminar!! :(",
            ];
        }
        //Realizamos el listado de los usuarios
        $this->listado();
    }

    /**
     * Metodo que añade una actividad nueva, cuyas propiedades indicamos por formulario
     * @return void No devuelve nada, pues simplemente devuelve la lista, pasándole los parámetros
     */
    public function addactivity()
    {

        require_once CHECK_SESSION_FILE; //Comprobamos la sesión, abriendola en el proceso, para el posible uso de sesiones

        // Array asociativo que almacenará los mensajes de error que se generen por cada campo
        $errores = array();
        // Si se ha pulsado el botón guardar...
        if (isset($_POST) && !empty($_POST) && isset($_POST['submit'])) { // y hemos recibido las variables del formulario y éstas no están vacías...

            $aforo = $_POST['txtaforo'];
            $nombre = $_POST['txtnombre'];
            $descripcion = $_POST['txtdescripcion'];

            $datosSaneados = $this->modelo->sanearValores([ //Saneamos los datos introducidos, evitando carácteres especiales
                'aforo' => $aforo,
                'nombre' => $nombre,
                'descripcion' => $descripcion,

            ]);

            $errores = $this->modelo->comprobarRestricciones($datosSaneados); //Obtenemos los posibles errores del saneamiento
            //Para decidir si seguir o no con la operación

            // Si no se han producido errores realizamos el registro de la actividad
            if (count($errores) == 0) {
                $resultModelo = $this->modelo->addactivity([ //Llamamos al método del modelo para añadir la actividad, usando los datos saneados
                    'aforo' => $datosSaneados['aforo'],
                    'nombre' => $datosSaneados['nombre'],
                    'descripcion' => $datosSaneados['descripcion'],
                ]);
                if ($resultModelo["correcto"]) :
                    $this->mensajes[] = [
                        "tipo" => "success",
                        "mensaje" => "La actividad se añadió correctamente!! :)",
                    ];
                else :
                    $this->mensajes[] = [ //Si ha habido algún error en el método del modelo..
                        "tipo" => "danger",
                        "mensaje" => "La actividad no pudo añadirse!! :( <br />({$resultModelo["error"]})",
                    ];
                endif;
            } else { //Si ha habido algún error previo al envío de datos al modelo (de saneamiento u otros)

                foreach ($errores as &$error) { //Copiamos todos los errores, para mostrarlos
                    $this->mensajes[] = [
                        "tipo" => "danger",
                        "mensaje" => $error,
                    ];
                }
            }
        } //Si no hemos pulsado el botón submit (porque hemos venido ahora a esta ventana), tendremos los campos vacíos
        //De lo contrario tendremos los campos llenos con los valores introducidos
        $parametros = [
            "tituloventana" => "Registro de actividades",
            "datos" => [
                "txtnombre" => isset($datosSaneados['nombre']) ? $datosSaneados['nombre'] : "",
                "txtaforo" => isset($datosSaneados['aforo']) ? $datosSaneados['aforo'] : "",
                "txtdescripcion" => isset($datosSaneados['descripcion']) ? $datosSaneados['descripcion'] : "",

            ],
            "mensajes" => $this->mensajes,
        ];
        //Visualizamos la vista asociada al registro de actividades
        $this->view->show("AddActivity", $parametros);
    }

    /**
     * Método que permite nos permite actualizar los datos de una actividad elegida, cuya id coincide con la que
     * se pasa como parámetro desde la vista del listado, a través de GET
     *
     * @return void No devuelve nada, pues simplemente devuelve la lista, pasándole los parámetros
     */
    public function actactivity()
    {
        require_once CHECK_SESSION_FILE;  //Comprobamos la sesión, abriendola en el proceso, para el posible uso de sesiones

        // Array asociativo que almacenará los mensajes de error que se generen por cada campo
        $errores = array();
        // Inicializamos valores de los campos de texto
        $valnombre = "";
        $valaforo = "";
        $valdescripcion = "";

        // Si se ha pulsado el botón actualizar...
        if (isset($_POST['submit'])) { //Realizamos la actualización con los datos existentes en los campos
            $id = $_POST['id']; //Lo recibimos por el campo oculto, pues solo lo queremos para trabajar con el, no para cambiarlo
            $nuevonombre = $_POST['txtnombre'];
            $nuevoaforo = $_POST['txtaforo'];
            $nuevadescripcion = $_POST['txtdescripcion'];

            $datosSaneados = $this->modelo->sanearValores([ //Saneamos los datos introducidos, evitando carácteres especiales
                'descripcion' => $nuevadescripcion,
                'nombre' => $nuevonombre,
                'aforo' => $nuevoaforo,
            ]);

            $errores = $this->modelo->comprobarRestricciones($datosSaneados); //Obtenemos los posibles errores del saneamiento
            //Para decidir si seguir o no con la operación

            if (count($errores) == 0) {
                //Ejecutamos la instrucción de actualización a la que le pasamos los valores introducidos, así como la id, para poder elegir la actividad
                $resultModelo = $this->modelo->actactivity([
                    'descripcion' => $datosSaneados['descripcion'],
                    'nombre' => $datosSaneados['nombre'],
                    'aforo' => $datosSaneados['aforo'],
                    'id' => $id,
                ]);
                //Analizamos cómo finalizó la operación de registro y generamos un mensaje
                //indicativo del estado correspondiente
                if ($resultModelo["correcto"]) :
                    $this->mensajes[] = [
                        "tipo" => "success",
                        "mensaje" => "La actividad se actualizó correctamente!! :)",
                    ];
                else :
                    $this->mensajes[] = [
                        "tipo" => "danger",
                        "mensaje" => "La actividad no pudo actualizarse!! :( <br/>({$resultModelo["error"]})",
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
            $valaforo = $datosSaneados['aforo'];
            $valnombre = $datosSaneados['nombre'];
            $valdescripcion = $datosSaneados['descripcion'];
        } else { //Si estamos rellenando los campos con los valores recibidos del listado (entramos por primera vez)
            if (isset($_GET['id']) && (is_numeric($_GET['id']))) {
                $id = $_GET['id'];
                //Ejecutamos la consulta para obtener los datos de la actividad #id
                $resultModelo = $this->modelo->listaactividad($id);
                //Analizamos si la consulta se realiz´correctamente o no y generamos un mensaje indicativo
                if ($resultModelo["correcto"]) :
                    $this->mensajes[] = [
                        "tipo" => "success",
                        "mensaje" => "Los datos de la actividad se obtuvieron correctamente!! :)",
                    ];
                    //Si hemos obtenido los valores correctamente, los copiamos
                    $valaforo = $resultModelo["datos"]["aforo"];
                    $valnombre = $resultModelo["datos"]["act_nombre"];
                    $valdescripcion = $resultModelo["datos"]["descripcion"];

                else :
                    $this->mensajes[] = [
                        "tipo" => "danger",
                        "mensaje" => "No se pudieron obtener los datos de la actividad!! :( <br/>({$resultModelo["error"]})",
                    ];
                endif;
            }
        }

        //Preparamos un array con todos los valores que tendremos que rellenar en
        //la vista adduser: título de la página y campos del formulario
        $parametros = [
            "tituloventana" => "Actualización de actividad",
            "datos" => [
                "txtaforo" => $valaforo,
                "txtnombre" => $valnombre,
                "txtdescripcion" => $valdescripcion,
            ],
            "mensajes" => $this->mensajes,
            "id" => $id,
        ];
        //Mostramos la vista actuser, con los datos introducidos u obtenidos de la lista

        $this->view->show("ActActivity", $parametros);
    }
}
