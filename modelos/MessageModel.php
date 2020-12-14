<?php

/**
 *   Clase 'UserModel' que implementa el modelo de usuarios de nuestra aplicación en una
 * arquitectura MVC. Se encarga de gestionar el acceso a la tabla usuarios
 */
class MessageModel extends BaseModel
{
    private $id;

    private $nombre;

    private $apellido1;

    private $apellido2;

    private $login;

    private $direccion;

    private $rol_id;

    private $email;

    private $password;

    private $image;

    private $estado;

    private $table2;

    /**
     * Constructor que se onecta a la base de datos y establece la tabla de este modelo
     */
    public function __construct()
    {
        parent::__construct();
        $this->table = "usuario";
        $this->table2 = "mensaje";
    }

    /**
     * Función que realiza el listado de todos los usuarios registrados
     * @return type Devuelve el array con los parámetros
     */
    public function listado($modo)
    {
        /**
         *  -'correcto': indica si el listado se realizó correctamente o no.
         * -'datos': almacena todos los datos obtenidos de la consulta.
         * -'error': almacena el mensaje asociado a una situación errónea (excepción)
         * -'paginacion: almacena los datos necesarios para la paginación
         */

        $resultado = [
            "correcto" => false,
            "datos" => null,
            "error" => null,
            "paginacion" => [],
        ];

        $orden = (isset($_GET['orden'])) ? $_GET['orden'] : 'asc';

        $columna = (isset($_GET['columna'])) ? $_GET['columna'] : 'login';

        //Establecemos el número de registros a mostrar por página,por defecto 2
        $regsxpag = (isset($_GET['regsxpag'])) ? (int) $_GET['regsxpag'] : 3;
        //Establecemos la página que vamos a mostrar, por página,por defecto la 1
        $pagina = (isset($_GET['pagina'])) ? (int) $_GET['pagina'] : 1;

        $resultado['paginacion']["regsxpag"] = $regsxpag;
        $resultado['paginacion']['pagina'] = $pagina;
        $resultado['paginacion']['orden'] = $orden;
        $resultado['paginacion']['columna'] = $columna;


        //Definimos la variable $inicio que indique la posición del registro desde el que se
        // mostrarán los registros de una página dentro de la paginación.
        $offset = ($pagina > 1) ? (($pagina - 1) * $regsxpag) : 0;

        //Calculamos el número de registros obtenidos
        if ($modo == 'IN') {
            $sql = "SELECT count(*) as total FROM $this->table2 where $this->table2.usu_origen = :usuario";
        } else {
            $sql = "SELECT count(*) as total FROM $this->table2 where $this->table2.usu_destino = :usuario";
        }

        $query = $this->db->prepare($sql);
        $query->execute(["usuario" => $_SESSION['id']]);
        $totalregistros = $query->fetch()['total'];

        $resultado['paginacion']['numpaginas'] = ceil($totalregistros / $regsxpag);

        $resultado['paginacion']['totalRegistros'] = $totalregistros;

        //Realizamos la consulta...
        try { //Definimos la instrucción SQL

            if ($modo == "IN") {
                $sql = "SELECT $this->table.login AS persona, $this->table2.mensaje_id,   $this->table2.asunto AS asunto FROM $this->table LEFT JOIN
                $this->table2 ON   $this->table2.usu_destino = $this->table.usuario_id where   $this->table2.usu_origen = :usuario
                order by $columna $orden LIMIT $regsxpag OFFSET $offset";
            } else {
                $sql = "SELECT $this->table.login AS persona,  $this->table2.mensaje_id, $this->table2.asunto AS asunto FROM $this->table LEFT JOIN
                $this->table2 ON   $this->table2.usu_origen = $this->table.usuario_id where   $this->table2.usu_destino = :usuario
                order by $columna $orden LIMIT $regsxpag OFFSET $offset";
            }
            // Hacemos directamente la consulta al no tener parámetros
            $resultsquery = $this->db->prepare($sql);
            $resultsquery->execute(["usuario" => $_SESSION['id']]);
            //Supervisamos si la inserción se realizó correctamente...
            if ($resultsquery) :
                $resultado["correcto"] = true;
                $resultado["datos"] = $resultsquery->fetchAll(PDO::FETCH_ASSOC);
            endif; // o no :(
        } catch (PDOException $ex) {
            $resultado["error"] = $ex->getMessage();
        }

        return $resultado;
    }

    /**
     * Método que elimina el mensaje cuyo id es el que se le pasa como parámetro
     * @param $id es un valor numérico. Es el campo clave de la tabla
     * @return boolean Array con el resultado, true o false, y con los errores en el último caso
     */
    public function delmessage($id)
    {
        // La función devuelve un array con dos valores:'correcto', que indica si la
        // operación se realizó correctamente, y 'mensaje', campo a través del cual le
        // mandamos a la vista el mensaje indicativo del resultado de la operación
        $resultado = [
            "correcto" => false,
            "error" => null,
        ];
        //Si hemos recibido el id y es un número realizamos el borrado...
        if ($id && is_numeric($id)) {
            try {
                //Inicializamos la transacción
                $this->db->beginTransaction();
                //Definimos la instrucción SQL parametrizada
                $sql = "DELETE FROM $this->table2 WHERE mensaje_id=:id";
                $query = $this->db->prepare($sql);
                $query->execute(['id' => $id]);
                //Supervisamos si la eliminación se realizó correctamente...
                if ($query) {
                    $this->db->commit(); // commit() confirma los cambios realizados durante la transacción
                    $resultado["correcto"] = true;
                } // o no :(
            } catch (PDOException $ex) {
                $this->db->rollback(); // rollback() se revierten los cambios realizados durante la transacción
                $resultado["error"] = $ex->getMessage();
            }
        } else {
            $resultado["correcto"] = false;
        }

        return $resultado;
    }

    /**
     *Método que añade un usuario a la base de datos, cuyos datos hemos introducido previamente por un formulario
     * @param type $datos Datos del usuario a crear
     * @return type Array con el resultado, true o false, y con los errores en el último caso
     */
    public function addmessage($datos)
    {
        $resultado = [
            "correcto" => false,
            "error" => null,
        ];

        $comprobacion = $this->comprobarDestinatario($datos['receptor']);

        if ($comprobacion['existe'] == 0) { //Comprobamos que no hayamos introducido datos ya existentes
            $resultado["error"] = "El usuario al que quieres enviar el mensaje no existe!! :( ";
        } else if ($comprobacion['existe'] == 1) {
            $resultado["error"] = "No puedes mandarte mensajes a tí mismo!!";
        } else {
            try {
                //Inicializamos la transacción
                $this->db->beginTransaction();
                //Definimos la instrucción SQL parametrizada
                $sql = "INSERT INTO $this->table2(usu_origen, usu_destino, asunto, mensaje)
                         VALUES (:origen, :destino, :asunto, :mensaje)";
                // Preparamos la consulta...
                $query = $this->db->prepare($sql);
                // y la ejecutamos indicando los valores que tendría cada parámetro
                $query->execute([
                    'asunto' => $datos['asunto'],
                    'mensaje' => $datos['mensaje'],
                    'destino' => $comprobacion['usuario']['usuario_id'],
                    'origen' => $datos['enviador'],
                ]); //Supervisamos si la inserción se realizó correctamente...

                if ($query) {
                    $this->db->commit(); // commit() confirma los cambios realizados durante la transacción
                    $resultado["correcto"] = true;
                } // o no :(
            } catch (PDOException $ex) {
                $this->db->rollback(); // rollback() se revierten los cambios realizados durante la transacción
                $resultado["error"] = $ex->getMessage();
                //die();
            }
        }

        return $resultado;
    }

    /**
     * Método que devuelve los datos de un mensaje
     * @param [type] $id Id del mensaje a mostrar
     * @return void Array con el resultado, incluyendo además los datos del mensaje en caso existoso
     */
    public function listamensaje($id, $modo)
    {
        $resultado = [
            "correcto" => false,
            "datos" => null,
            "error" => null,
        ];

        if ($id && is_numeric($id)) { //Comprobamos que hayamos introducido id y que sea numérica
            try {

                if ($modo == "IN") {
                    $sql = "SELECT $this->table.login AS persona, $this->table2.mensaje as texto,   $this->table2.asunto AS asunto FROM $this->table LEFT JOIN
                $this->table2 ON   $this->table2.usu_destino = $this->table.usuario_id where $this->table2.mensaje_id = :cod_mensaje";
                } else {
                    $sql = "SELECT $this->table.login AS persona, $this->table2.mensaje as texto,   $this->table2.asunto AS asunto FROM $this->table LEFT JOIN
                $this->table2 ON   $this->table2.usu_origen = $this->table.usuario_id where $this->table2.mensaje_id = :cod_mensaje";
                }

                $query = $this->db->prepare($sql);
                $query->execute(['cod_mensaje' => $id]);
                //Supervisamos que la consulta se realizó correctamente...
                if ($query) {
                    $resultado["correcto"] = true;
                    $resultado["datos"] = $query->fetch(PDO::FETCH_ASSOC);
                } // o no :(
            } catch (PDOException $ex) {
                $resultado["error"] = $ex->getMessage();
                //die();
            }
        }
        return $resultado;
    }

    /**
     * Método que me comprueba si los valores introducidos en los campos de un mensaje
     *  no están vacíos o únicamente llenos de espacios
     * @param [type] $datos Array con los datos establecidos
     * @return $errores Posibles errores generados, devueltos para enseñarlos en caso de que existan
     */
    public function comprobarRestricciones($datos, $modo)
    {

        $errores = array();

        if ($modo == "mensaje") {

            if (preg_match("/^\s*$/", $datos["receptor"])) {
                $errores["descripcion"] = "No puedes introducir un receptor vacío!!<br>";
            }
        }

        if (preg_match("/^\s*$/", $datos["asunto"])) {
            $errores["descripcion"] = "No puedes introducir un asunto vacío!!<br>";
        }

        if (preg_match("/^\s*$/", $datos["mensaje"])) {
            $errores["descripcion"] = "No puedes introducir un mensaje vacío!!<br>";
        }

        return $errores;
    }

    /**
     * Función que comprueba si vamos a enviar un mensaje a un destinatario existente
     *En función del nombre de usuario de destinatario que hemos introducido
     * @param [type] $destino
     * @return void Devuelve un array con un booleano para comprobar si existe, así como un error en caso de que falle la consulta
     */
    public function comprobarDestinatario($destino)
    {

        $resultado = [
            "usuario" => [],
            "error" => null,
            "existe" => 0,
        ];

        try {

            $sql = "SELECT * from $this->table where login = :login";
            $resultquery = $this->db->prepare($sql);
            $resultquery->execute(['login' => $destino]);

            if ($resultquery->rowCount() > 0) {

                $resultado["usuario"] = $resultquery->fetch(PDO::FETCH_ASSOC);

                if ($resultado["usuario"]["usuario_id"] == $_SESSION['id']) {
                    $resultado["existe"] = 1;
                } else {
                    $resultado["existe"] = 2;
                }
            }
        } catch (PDOException $ex) { //Si pasa por aquí, correcto seguirá siendo 0, que significará que ha petado
            $resultado["error"] = $ex->getMessage();
        }
        return $resultado;
    }
}
