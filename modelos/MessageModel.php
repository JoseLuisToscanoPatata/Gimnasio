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
    public function listado()
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

        $orden = (isset($_GET['orden'])) ?  $_GET['orden'] : 'asc';

        $columna = (isset($_GET['columna'])) ?  $_GET['columna'] : 'usuario_id';

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
        $totalregistros = $this->db->query("SELECT count(*) as total FROM usuario");
        $totalregistros = $totalregistros->fetch()['total'];

        $resultado['paginacion']['numpaginas'] = ceil($totalregistros / $regsxpag);

        //Realizamos la consulta...
        try { //Definimos la instrucción SQL
            $sql = "SELECT * FROM $this->table order by $columna $orden LIMIT $regsxpag OFFSET $offset";
            // Hacemos directamente la consulta al no tener parámetros
            $resultsquery = $this->db->prepare($sql);
            $resultsquery->execute();
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
     * Método que elimina el usuario cuyo id es el que se le pasa como parámetro
     * @param $id es un valor numérico. Es el campo clave de la tabla
     * @return boolean Array con el resultado, true o false, y con los errores en el último caso
     */
    public function deluser($id)
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
                $sql = "DELETE FROM $this->table WHERE usuario_id=:id";
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
    public function adduser($datos)
    {
        $resultado = [
            "correcto" => false,
            "error" => null,
        ];

        if ($this->comprobarRepeticion($datos, "nuevo")['existe'] == true) { //Comprobamos que no hayamos introducido datos ya existentes
            $resultado["error"] = "Has introducido un dni, nombre de usuario u correo electrónico que ya está usado por otro usuario!! :(";
        } else {

            try {
                //Inicializamos la transacción
                $this->db->beginTransaction();
                //Definimos la instrucción SQL parametrizada
                $sql = "INSERT INTO $this->table(nif, usu_nombre, apellido1, apellido2, imagen, login,  password, email, telefono, direccion, rol_id)
                         VALUES (:nif, :nombre, :apellido1, :apellido2, :imagen, :login, :password,:email , :telefono, :direccion, :rol_id)";
                // Preparamos la consulta...
                $query = $this->db->prepare($sql);
                // y la ejecutamos indicando los valores que tendría cada parámetro
                $query->execute([
                    'nif' => $datos["nif"],
                    'nombre' => $datos["nombre"],
                    'apellido1' => $datos["apellido1"],
                    'apellido2' => $datos["apellido2"],
                    'imagen' => $datos["imagen"],
                    'login' => $datos["login"],
                    'password' => sha1($datos["password"]),
                    'email' => $datos["email"],
                    'telefono' => $datos["telefono"],
                    'direccion' => $datos["direccion"],
                    'rol_id' => $datos["rol_id"],
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
     * Método que actualiza un elemento de la tabla usuario, con los datos que hayamos introducido previamente mediante formulario
     * @param [type] $datos Nuevos datos del usuario
     * @return void Array  con el resultado, true o false, así como los errores en el último caso
     */
    public function actuser($datos)
    {
        $resultado = [
            "correcto" => false,
            "error" => null,
        ];

        if ($this->comprobarRepeticion($datos, "existente")['existe'] == true) { //Comprobamos que no hemos introducidos valores ya existentes en otro usuario
            $resultado["error"] = "Has introducido un dni, nombre de usuario u correo electrónico que ya está usado por otro usuario!! :(";
        } else {

            try {
                //Inicializamos la transacción
                $this->db->beginTransaction();
                //Definimos la instrucción SQL parametrizada
                $sql = "UPDATE $this->table SET usu_nombre= :nombre, login = :login, password = :password, nif= :nif, apellido1= :apellido1, apellido2 = :apellido2, email= :email, imagen= :imagen, telefono = :telefono,
         direccion = :direccion, rol_id = :rol_id WHERE usuario_id=:id";
                $query = $this->db->prepare($sql);
                $query->execute([
                    'id' => $datos["id"],
                    'nif' => $datos["nif"],
                    'password' => sha1($datos["password"]),
                    'login' => $datos['login'],
                    'nombre' => $datos["nombre"],
                    'email' => $datos["email"],
                    'imagen' => $datos["imagen"],
                    'apellido1' => $datos["apellido1"],
                    'apellido2' => $datos["apellido2"],
                    'telefono' => $datos["telefono"],
                    'direccion' => $datos["direccion"],
                    'rol_id' => $datos["rol_id"],
                ]);
                //Supervisamos si la inserción se realizó correctamente...
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
     * Método que devuelve los datos de un usuario
     * @param [type] $id Id del usuario a mostrar
     * @return void Array con el resultado, incluyendo además los datos del usuario en caso existoso
     */
    public function listausuario($id)
    {
        $resultado = [
            "correcto" => false,
            "datos" => null,
            "error" => null,
        ];

        if ($id && is_numeric($id)) { //Comprobamos que hayamos introducido id y que sea numérica
            try {
                $sql = "SELECT * FROM $this->table WHERE usuario_id=:id";
                $query = $this->db->prepare($sql);
                $query->execute(['id' => $id]);
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
     * Método que sanea los valores introducidos en los formularios
     * @param [type] $valores Datos introducidos sin sanear
     * @return [array] $valores Datos saneados, elimiando carácteres especiales
     */
    public function sanearValores($valores)
    {
        array_walk_recursive($valores, function (&$valor) {
            $valor = trim(filter_var($valor, FILTER_SANITIZE_STRING));
        });

        return $valores;
    }

    /**
     * Método que me comprueba si los valores introducidos en los campos de un usuario, para el registro de uno nuevo o la actualización de uno existente,
     *  cumple con las restricciones establecidas de formato para estas
     * @param [type] $datos Array con los datos establecidos
     * @return $errores Posibles errores generados, devueltos para enseñarlos en caso de que existan
     */
    public function comprobarRestricciones($datos)
    {

        $errores = array();

        if (!preg_match("/^[0-9]{8}(?![OUILÑ])[a-zA-Z]$/", $datos["nif"])) {
            $errores["DNI"] = "No has introducido un DNI válido!!<br>";
        }

        if (!preg_match("/^[a-zA-ZáÁéÉíÍóÓúÚ]+(\s[a-zA-ZáÁéÉíÍóÓúÚ]+)*$/", $datos["nombre"])) {
            $errores["nombre"] = "No has introducido un nombre válido!!<br>";
        }

        if (!preg_match("/^[a-zA-ZáÁéÉíÍóÓúÚ]+$/", $datos["apellido1"])) {
            $errores["apellido1"] = "No has introducido un primer apellido válido!!<br>";
        }

        if (!preg_match("/^[a-zA-ZáÁéÉíÍóÓúÚ]+$/", $datos["apellido2"])) {
            $errores["apellido2"] = "No has introducido un segundo apellido válido!!<br>";
        }

        if (!preg_match("/^[(\S|@)]+$/", $datos["login"])) {
            $errores["usuario"] = "No has introducido un nombre de usuario válido!!<br>";
        }

        if (!preg_match("/^\w+([\.-_]?w+)*@\w+(\.(com|es|net|org|yahoo))+$/", $datos["email"])) {
            $errores["email"] = "No has introducido una dirección de correo válida!!<br>";
        }

        if (!preg_match("/^(?=.*[0-9])(?=.*[!@#$%^&*-])(?=.*[A-Z]).{8,}$/", $datos["password"])) {
            $errores["password"] = "No has introducido una contraseña válida!!<br>";
        }

        if (!preg_match("/^[a-zA-ZáÁéÉíÍóÓúÚ]+(\s[a-zA-ZáÁéÉíÍóÓúÚ0-9]+)*$/", $datos["direccion"])) {
            $errores["direccion"] = "No has introducido una dirección válida!!<br>";
        }

        if (!preg_match("/^[986][0-9]{8}$/", $datos["telefono"])) {
            $errores["telefono"] = "No has introducido un telefono válido!!<br>";
        }

        return $errores;
    }

    /**
     * Método que comprueba si existe un usuario con determinados datos de los introducidos, que se utiliza para evitar que se repitan usuarios
     * @param [type] $datosUsu datos del usuario a introducir
     * @param [type] $modo Variable que nos permite diferenciar entre un nuevo usuario o uno ya existente (pues la consulta es distinta)
     * @return void Array con el resultado de la consulta y un error, en caso de que se provocase
     */
    public function comprobarRepeticion($datosUsu, $modo)
    {

        $resultado = [
            "existe" => false,
            "error" => null,
        ];

        try {

            if ($modo == "nuevo") { //Si el usuario es nuevo, busca por toda la tabla
                $sql = "SELECT * from $this->table where (nif = :nif) OR (login = :login) or (email = :email)";
                $resultquery = $this->db->prepare($sql);
                $resultquery->execute(['nif' => $datosUsu['nif'], 'login' => $datosUsu['login'], 'email' => $datosUsu['email']]);
            } else { //Si estamos actualizando, busca por toda la tabla, a excepción del usuario que estamos actualizando
                $sql = "SELECT * from $this->table where ((nif = :nif) OR (login = :login) or (email = :email)) AND (usuario_id <> :id)";
                $resultquery = $this->db->prepare($sql);
                $resultquery->execute(['nif' => $datosUsu['nif'], 'login' => $datosUsu['login'], 'email' => $datosUsu['email'], 'usuario_id' => $datosUsu['id']]);
            }

            if ($resultquery->rowCount() > 0) {
                $resultado["existe"] = true;
            }
        } catch (PDOException $ex) { //Si pasa por aquí, correcto seguirá siendo 0, que significará que ha petado
            $resultado["error"] = $ex->getMessage();
        }
        return $resultado;
    }
}
