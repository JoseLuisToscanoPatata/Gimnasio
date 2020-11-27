<?php
/**
 * Módelo base para las clases de models
 */
abstract class BaseModel
{
   /**
    * Tabla asociada al modelo
    *
    * @var string
    */
   protected $table;
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
    *
    * @return void
    */
   public function getAll() {
      $resultSet = null;
      
      $query = $this->db->query("SELECT * FROM $this->table WHERE deleted_at is null ORDER BY id DESC");
        
      //Devolvemos el resultset en forma de array de objetos
      while ($row = $query->fetchObject()) {
         $resultSet[] = $row;
      }
       
      return $resultSet;
  }
   
  public function getById($id) {
      $resultSet = null;

      $query = $this->db->query("SELECT * FROM $this->table WHERE id = $id");

      if($row = $query->fetchObject()) {
         $resultSet = $row;
      }
       
      return $resultSet;
  }
   
  public function getBy($column, $value) {
      $resultSet = null;

      $query = $this->db->query("SELECT * FROM $this->table WHERE $column = '$value'");

      while($row = $query->fetchObject()) {
         $resultSet[] = $row;
      }
       
      return $resultSet;
  }
   
  public function deleteById($id) { 
      $query = $this->db->query("DELETE FROM $this->table WHERE id = $id");
      // $query = $this->db->query("UPDATE $this->table SET deleted_at = NOW() WHERE id = $id");
      return $query;
  }
   
  public function deleteBy($column, $value) {
      $query = $this->db->query("DELETE FROM $this->table WHERE $column = '$value'");
      return $query;
  }

  public function setLog($user_id, $action, $description) {
      $this->db->query("CALL log($user_id, '$action', '$description')");
  }
}