<?php

/**
 *   Clase 'UserModel' que implementa el modelo de usuarios de nuestra aplicación en una
 * arquitectura MVC. Se encarga de gestionar el acceso a la tabla usuarios
 */
class UserModel extends BaseModel
{
/**
 *
 */
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

    public function __construct()
    {
        // Se conecta a la BD
        parent::__construct();
        $this->table = "usuario";
    }

    /**
     * Función que comprueba si existe una fila en la tabla usuarios, con los valores introducidos
     * Sirve para comprobar si hemos introducido datos válidos en el logueo de la aplicación
     * -'correcto': indica si hay o no un usuario con esos datos
     * -'datos': almacena todos los datos obtenidos de la consulta, en caso de que existan
     * -'error': almacena el mensaje asociado a una situación errónea (excepción)
     */
    public function loginCorrecto($usuario, $contraseña)
    {

        $resultado = [
            "correcto" => 0,
            "datos" => null,
            "error" => null,
        ];

        try {
            $sql = "SELECT * from usuario where ((login = :login) and(password = :password))";

            $resultquery = $this->db->prepare($sql);
            $resultquery->execute(['login' => $usuario,
                'password' => sha1($contraseña),
            ]);

            if ($resultquery->rowCount() > 0) {
                $resultado["datos"] = $resultquery->fetch(PDO::FETCH_ASSOC);

                if ($resultado["datos"]["estado"] == 1) {
                    $resultado["correcto"] = 1; //1 Significa que existe y está activo
                } else {
                    $resultado["correcto"] = 2; //2 Significa que existe pero no está activo
                }
            } else {
                $resultado["correcto"] = 3; //3 Significa que no hemos introducidos valores correctos
            }
        } catch (PDOException $ex) { //Si pasa por aquí, correcto seguirá siendo 0, que significará que ha petado
            $resultado["error"] = $ex->getMessage();
        }
        return $resultado;
    }

    /**
     * Función que habilita o deshabilita un usuario, usada al pulsar el switch correspondiente en la tabla de usuarios
     *
     * @param [number] $int Id del usuario cuyo estado queremos cambiar
     * @param [string] $cambio variable que especifica si queremos habilitarlo o deshabilitarlo
     * @return array con el resultado y los posibles errores de la operación
     */
    public function cambiarEstado($id, $cambio)
    {

        $resultado = [
            "correcto" => false,
            "error" => null,
        ];

        try {

            if ($cambio == "activar") {
                $sql = "UPDATE usuario set estado = 1 where usuario_id = :id";
            } else {
                $sql = "UPDATE usuario set estado = 2 where usuario_id = :id";
            }

            $resultquery = $this->db->prepare($sql);
            $resultquery->execute(['id' => $id]);

            $resultado["correcto"] = true;

        } catch (PDOException $ex) {
            $resultado["error"] = $ex->getMessage();
        }

        return $resultado;
    }

    /**
     * Función que realiza el listado de todos los usuarios registrados
     * Devuelve un array asociativo con tres campos:
     * -'correcto': indica si el listado se realizó correctamente o no.
     * -'datos': almacena todos los datos obtenidos de la consulta.
     * -'error': almacena el mensaje asociado a una situación errónea (excepción)
     * @return type
     */
    public function listado()
    {
        $resultado = [
            "correcto" => false,
            "datos" => null,
            "error" => null,
        ];
        //Realizamos la consulta...
        try { //Definimos la instrucción SQL
            $sql = "SELECT * FROM usuario";
            // Hacemos directamente la consulta al no tener parámetros
            $resultsquery = $this->db->query($sql);
            //Supervisamos si la inserción se realizó correctamente...
            if ($resultsquery):
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
     * @return boolean
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
                $sql = "DELETE FROM usuario WHERE usuario_id=:id";
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
     *
     * @param type $datos
     * @return type
     */
    public function adduser($datos)
    {
        $resultado = [
            "correcto" => false,
            "error" => null,
        ];

        if ($this->comprobarRepeticion($datos, "nuevo")['existe'] == true) {
            $resultado["error"] = "Has introducido un dni, nombre de usuario u correo electrónico que ya está usado por otro usuario!! :(";
        } else {

            try {
                //Inicializamos la transacción
                $this->db->beginTransaction();
                //Definimos la instrucción SQL parametrizada
                $sql = "INSERT INTO usuario(nif, usu_nombre, apellido1, apellido2, imagen, login,  password, email, telefono, direccion, rol_id)
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

    public function actuser($datos)
    {
        $resultado = [
            "correcto" => false,
            "error" => null,
        ];

        if ($this->comprobarRepeticion($datos, "existente")['existe'] == true) {
            $resultado["error"] = "Has introducido un dni, nombre de usuario u correo electrónico que ya está usado por otro usuario!! :(";
        } else {

            try {
                //Inicializamos la transacción
                $this->db->beginTransaction();
                //Definimos la instrucción SQL parametrizada
                $sql = "UPDATE usuario SET usu_nombre= :nombre, login = :login, password = :password, nif= :nif, apellido1= :apellido1, apellido2 = :apellido2, email= :email, imagen= :imagen, telefono = :telefono,
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

    public function listausuario($id)
    {
        $resultado = [
            "correcto" => false,
            "datos" => null,
            "error" => null,
        ];

        if ($id && is_numeric($id)) {
            try {
                $sql = "SELECT * FROM usuario WHERE usuario_id=:id";
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
     * Undocumented function
     *
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
     * función que me comprueba si los valores introducidos en los campos de un usuario, para el registro de uno nuevo o la actualización de uno existente,
     *  cumple con las restricciones establecidas de formato para estas
     *
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

        if (!preg_match("/^[\S]+$/", $datos["login"])) {
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
     * Función que comprueba si existe un usuario con determinados datos de los introducidos, que se utiliza para evitar que se repitan usuarios
     *
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

            if ($modo == "nuevo") {
                $sql = "SELECT * from usuario where (nif = :nif) OR (login = :login) or (email = :email)";
                $resultquery = $this->db->prepare($sql);
                $resultquery->execute(['nif' => $datosUsu['nif'], 'login' => $datosUsu['login'], 'email' => $datosUsu['email']]);

            } else {
                $sql = "SELECT * from usuario where ((nif = :nif) OR (login = :login) or (email = :email)) AND (usuario_id <> :id)";
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
