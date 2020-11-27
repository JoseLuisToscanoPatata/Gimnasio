<?php
/**
 * DBManager implementa el patron Singleton para mantener una única instancia de PDO
 */
class DBManager
{
   /**
    * Contenedor de la instancia de la Clase
    *
    * @var DBManager
    */
   private static $instance;
   /**
    * Manejador de la base de datos
    *
    * @var PDO
    */
   private $db;
   private function __construct()
   {
   }
   /**
    * Garantiza que se crea una sóla instancia de Config
    *
    * @return DBManager
    */
   public static function getInstance()
   {
      if (is_null(self::$instance)) {
         self::$instance = new self();
      }
      return self::$instance;
   }
   /**
    * Se conecta a la base de datos con los valores de configuración de la app
    *
    * @return PDO
    */
   public function getConnection()
   {
      if (is_null($this->db)) {
         try {
            $this->db = new \PDO(DBDRIVER . ":host=" . DBHOST . ";dbname=" . DBNAME, DBUSER, DBPASS);
            $this->db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            $this->db->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);
         } catch (\PDOException $ex) {
            return $ex->getMessage();
         }
      }
      return $this->db;
   }
}
