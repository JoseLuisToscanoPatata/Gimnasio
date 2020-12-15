<?php

/**
 *   Clase 'ActivityModel' que implementa el modelo de actividades de nuestra aplicación en una
 * arquitectura MVC. Se encarga de gestionar el acceso a la tabla actividad
 */
class ActivityModel extends BaseModel
{

    private $id;

    private $act_nombre;

    private $descripcion;

    private $aforo;

    public function __construct()
    {
        // Se conecta a la BD
        parent::__construct();
        $this->table = "actividad";
    }

    /**
     * Función que realiza el listado de todas las actividades registrados
     * @return array Devuelve el array con los errores, datos, comprobación y paginación
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

        $columna = (isset($_GET['columna'])) ?  $_GET['columna'] : 'actividad_id';

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
        $totalregistros = $this->db->query("SELECT count(*) as total FROM $this->table");
        $totalregistros = $totalregistros->fetch()['total'];

        $resultado['paginacion']['numpaginas'] = ceil($totalregistros / $regsxpag);

        $resultado['paginacion']['totalRegistros'] = $totalregistros;

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
     * Funcion que elimina la actividad cuya id es el que se le pasa como parámetro
     * @param int $id es un valor numérico. Es el campo clave de la tabla
     * @return boolean Array con el resultado, true o false, y con los errores en el último caso
     */
    public function delactivity($id)
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
                $sql = "DELETE FROM $this->table WHERE actividad_id=:id";
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
     *Funcion que añade una actividad a la base de datos, cuyos datos hemos introducido previamente por un formulario
     * @param array $datos Datos de la actividad a crear
     * @return array Array con el resultado, true o false, y con los errores en el último caso
     */
    public function addactivity($datos)
    {
        $resultado = [
            "correcto" => false,
            "error" => null,
        ];

        if ($this->comprobarRepeticion($datos, "nuevo")['existe'] == true) {
            $resultado["error"] = "Has introducido un nombre de actividad ya usada por otra actividad!! :(";
        } else {

            try {
                //Inicializamos la transacción
                $this->db->beginTransaction();
                //Definimos la instrucción SQL parametrizada
                $sql = "INSERT INTO $this->table (act_nombre, descripcion, aforo)VALUES (:nombre, :descripcion, :aforo)";
                // Preparamos la consulta...
                $query = $this->db->prepare($sql);
                // y la ejecutamos indicando los valores que tendría cada parámetro
                $query->execute([
                    'aforo' => $datos["aforo"],
                    'nombre' => $datos["nombre"],
                    'descripcion' => $datos["descripcion"],
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
     * Funcion que actualiza un elemento de la tabla actividad, con los datos que hayamos introducido previamente mediante formulario
     * @param array $datos Nuevos datos de la actividad
     * @return array Array  con el resultado, true o false, así como los errores en el último caso
     */
    public function actactivity($datos)
    {
        $resultado = [
            "correcto" => false,
            "error" => null,
        ];

        if ($this->comprobarRepeticion($datos, "existente")['existe'] == true) {
            $resultado["error"] = "Has introducido un nombre de actividad ya usado por otra actividad!! :(";
        } else {

            try {
                //Inicializamos la transacción
                $this->db->beginTransaction();
                //Definimos la instrucción SQL parametrizada
                $sql = "UPDATE $this->table SET act_nombre= :nombre, aforo = :aforo, descripcion = :descripcion WHERE actividad_id=:id";
                $query = $this->db->prepare($sql);
                $query->execute([
                    'id' => $datos["id"],
                    'nombre' => $datos["nombre"],
                    'aforo' => $datos["aforo"],
                    'descripcion' => $datos["descripcion"],
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
     * Funcion que devuelve los datos de una actividad
     * @param int $id Id de la actividad a mostrar
     * @return array Array con el resultado, incluyendo además los datos del usuario en caso existoso
     */
    public function listaactividad($id)
    {
        $resultado = [
            "correcto" => false,
            "datos" => null,
            "error" => null,
        ];

        if ($id && is_numeric($id)) {
            try {
                $sql = "SELECT * FROM $this->table WHERE actividad_id=:id";
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
     * Funcion que sanea los valores introducidos en los formularios
     * @param array $valores Datos introducidos sin sanear
     * @return array $valores Datos saneados, elimiando carácteres especiales
     */
    public function sanearValores($valores)
    {
        array_walk_recursive($valores, function (&$valor) {
            $valor = trim(filter_var($valor, FILTER_SANITIZE_STRING));
        });

        return $valores;
    }

    /**
     * Funcion que me comprueba si los valores introducidos en los campos de una actividad, para el registro de una nueva o la actualización de una existente,
     *  cumple con las restricciones establecidas de formato para estas
     * @param array $datos Array con los datos establecidos
     * @return array $errores Posibles errores generados, devueltos para enseñarlos en caso de que existan
     */
    public function comprobarRestricciones($datos)
    {

        $errores = array();

        if (!preg_match("/^[a-zA-ZáÁéÉíÍóÓúÚ]+(\s[a-zA-ZáÁéÉíÍóÓúÚ]+)*$/", $datos["nombre"])) {
            $errores["nombre"] = "No has introducido un nombre válido!!<br>";
        }

        if (!preg_match("/^(0[1-9]|[12][0-9]|30)$/", $datos["aforo"])) {
            $errores["aforo"] = "No has introducido un aforo válido!!<br>";
        }

        if (preg_match("/^\s*$/", $datos["descripcion"])) {
            $errores["descripcion"] = "No puedes introducir una descripción vacía!!<br>";
        }

        return $errores;
    }

    /**
     * Función que comprueba si existe una actividad con el nombre introducido, que se utiliza para evitar que se repitan actividades
     * @param array $datosAct datos del usuario a introducir
     * @param String $modo Variable que nos permite diferenciar entre un nuevo usuario o uno ya existente (pues la consulta es distinta)
     * @return array Array con el resultado de la consulta y un error, en caso de que se provocase
     */
    public function comprobarRepeticion($datosAct, $modo)
    {

        $resultado = [
            "existe" => false,
            "error" => null,
        ];

        try {

            if ($modo == "nuevo") {
                $sql = "SELECT * from $this->table where (act_nombre = :nombre)";
                $resultquery = $this->db->prepare($sql);
                $resultquery->execute(['nombre' => $datosAct['nombre']]);
            } else {
                $sql = "SELECT * from $this->table where ((act_nombre = :nombre)) AND (actividad_id <> :id)";
                $resultquery = $this->db->prepare($sql);
                $resultquery->execute(['nombre' => $datosAct['nombre'], 'id' => $datosAct['id']]);
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
