<?php

/**
 * Módelo base para las clases de models
 */
abstract class BaseModel
{
    /**
     * Tabla asociada al modelo
     * @var string nombre de la tabla
     */
    protected $table;

    /**
     * Base de datos a utilizar
     * @var db
     */
    protected $db;

    /**
     * Se conecta a la base de datos
     */
    public function __construct()
    {
        $this->db = DBManager::getInstance()->getConnection();
    }

    /**
     * Funcion genérico para obtener todos los registros de la tabla $table
     * @return array $resultSet Todas las líneas de la tabla
     */
    public function getAll()
    {
        $resultSet = null;

        $query = $this->db->query("SELECT * FROM $this->table WHERE deleted_at is null ORDER BY id DESC");

        //Devolvemos el resultset en forma de array de objetos
        while ($row = $query->fetchObject()) {
            $resultSet[] = $row;
        }

        return $resultSet;
    }

    /**
     * Funcion genérico que obtiene una fila de una tabla, según el identificador que indiquemos (no sirve, pues las id no se llaman así)
     * @param integer $id identificador de la fila a obtener
     * @return array $resultSet Fila obtenida de la tabla
     */
    public function getById($id)
    {
        $resultSet = null;

        $query = $this->db->query("SELECT * FROM $this->table WHERE id = $id");

        if ($row = $query->fetchObject()) {
            $resultSet = $row;
        }

        return $resultSet;
    }

    /**
     * Funcion que nos permite buscar un elemento de una tabla, pero buscando por todas las columnas
     * @param integer $column Columna en la que buscar
     * @param  String $value Valor que buscar
     * @return array $resultSet Fila obtenida de la tabla
     */
    public function getBy($column, $value)
    {
        $resultSet = null;

        $query = $this->db->query("SELECT * FROM $this->table WHERE $column = '$value'");

        while ($row = $query->fetchObject()) {
            $resultSet[] = $row;
        }

        return $resultSet;
    }

    /**
     * Funcion que borra una fila de la tabla, en función del identificador que indiquemos
     * @param  $id identificador de la fila a eliminar
     * @return void No devuelve nada, pues simplemente borra la fila
     */
    public function deleteById($id)
    {
        $query = $this->db->query("DELETE FROM $this->table WHERE id = $id");
        // $query = $this->db->query("UPDATE $this->table SET deleted_at = NOW() WHERE id = $id");
        return $query;
    }

    /**
     * Funcion que borra una fila de la tabla, pero buscando por todas las columnas
     * @param integer $column Columna en la que queremos buscar el dato de la fila a eliminar
     * @param String $value Valor que contiene la fila a eliminar
     * @return void No devuelve nada, pues simplemente borra la fila
     */
    public function deleteBy($column, $value)
    {
        $query = $this->db->query("DELETE FROM $this->table WHERE $column = '$value'");
        return $query;
    }
    /**
     * Funcion que añade una acción al log de acciones de los usuarios de la bd
     * @param integer $user_id identificador del usuario
     * @param String $action Acción realizada
     * @param String $description Descripción de la acción
     * @return void No devuelve nada, pues simplemente añade un nuevo evento al Log
     */
    public function setLog($user_id, $action, $description)
    {
        $this->db->query("CALL log($user_id, '$action', '$description')");
    }
}
