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
     * @var [db]
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
     * Método genérico para obtener todos los registros de la tabla $table
     * @return void
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
     * Método genérico que obtiene una fila de una tabla, según el identificador que indiquemos (no sirve, pues las id no se llaman así)
     * @param [type] $id identificador de la fila a obtener
     * @return void
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
     * Método que nos permite buscar un elemento de una tabla, pero buscando por todas las columnas
     * @param [type] $column Columna en la que buscar
     * @param [type] $value Valor que buscar
     * @return void
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
     * Metodo que borra una fila de la tabla, en función del identificador que indiquemos
     * @param [type] $id identificador de la fila a eliminar
     * @return void
     */
    public function deleteById($id)
    {
        $query = $this->db->query("DELETE FROM $this->table WHERE id = $id");
        // $query = $this->db->query("UPDATE $this->table SET deleted_at = NOW() WHERE id = $id");
        return $query;
    }

    /**
     * Método que borra una fila de la tabla, pero buscando por todas las columnas
     * @param [type] $column Columna en la que queremos buscar el dato de la fila a eliminar
     * @param [type] $value Valor que contiene la fila a eliminar
     * @return void
     */
    public function deleteBy($column, $value)
    {
        $query = $this->db->query("DELETE FROM $this->table WHERE $column = '$value'");
        return $query;
    }
    /**
     * Metodo que añade una acción al log de acciones de los usuarios de la bd
     * @param [type] $user_id identificador del usuario
     * @param [type] $action Acción realizada
     * @param [type] $description Descripción de la acción
     * @return void
     */
    public function setLog($user_id, $action, $description)
    {
        $this->db->query("CALL log($user_id, '$action', '$description')");
    }
}
