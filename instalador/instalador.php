<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.5/js/bootstrap.min.js" integrity="sha384-BLiI7JTZm+JWlgKa0M0kGRpJbF2J8q+qreVrKBC47e3K6BW78kGLrCkeRX6I9RoK" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.5/css/bootstrap.min.css" integrity="sha384-AysaV+vQoT3kOAXZkl02PThvDr8HYKPZhNT5h/CXfBThSRXQ6jW5DO2ekP5ViFdi" crossorigin="anonymous">
  <link rel="stylesheet" href="../assets/css/font-awesome.min.css">
  <link rel="stylesheet" type="text/css" href="../assets/css/estilos.css">
  <title>Instalador</title>
</head>

<body>
  <?php
  if (!$_POST) { //Si visitamos el instalador por primera vez, mostramos el formulario
    formulario($error = false);
  } else { //Si hemos introducido datos en el formulario
    creaConfig($_POST["servidor"], $_POST["usuario"], $_POST["password"], $_POST["basedatos"]);
    $error = errores(); //Intentamos crear la configuración nueva, y comprobamos si hay errores
    if ($error) //Si hay errores, volvemos al formulario a mostrarlos
      formulario($error);
    else //Si no los hay, creamos la BD
      creaBD();
  }
  ?>
</body>

</html>

<?php

/**
 * Formulario del instalador
 * @param boolean $error Indica si se ha producido algún error al enviar el formulario
 */
function formulario($error)
{
?>
  <div class="container">
    <div class="col-md-8 offset-md-2">
      <div class="card card-block" style=" margin-top: 10rem;">
        <h2 class="card-title text-md-center">Instalador de la base de datos</h2>
        <hr>
        <?php if ($error) { ?>
          <div class="row">
            <div class="alert alert-danger col-md-4 offset-md-4 text-md-center">
              Los datos especificados en el formulario son incorrectos :(!!
            </div>
          </div>
        <?php } ?>
        <form method="post">
          <div class="form-group row">
            <label for="servidor" class="col-md-3 col-form-label"><strong>Servidor</strong></label>
            <div class="col-md-9">
              <input type="text" class="form-control" name="servidor" value="<?php if (isset($_POST["servidor"])) {
                                                                                echo $_POST["servidor"];
                                                                              } ?>"></input>
            </div>
          </div>
          <div class="form-group row">
            <label for="usuario" class="col-md-3 col-form-label"><strong>Usuario</strong></label>
            <div class="col-md-9">
              <input class="form-control" type="text" name="usuario" value="<?php if (isset($_POST["usuario"])) {
                                                                              echo $_POST["usuario"];
                                                                            } ?>"></input>
            </div>
          </div>
          <div class="form-group row">
            <label for="password" class="col-md-3 col-form-label"><strong>Contraseña</strong></label>
            <div class="col-md-9">
              <input class="form-control" type="password" name="password" value="<?php if (isset($_POST["password"])) {
                                                                                    echo $_POST["password"];
                                                                                  } ?>">
            </div>
          </div>
          <div class="form-group row">
            <label for="basedatos" class="col-md-3 col-form-label"><strong>Base de datos</strong></label>
            <div class="col-md-9">
              <input class="form-control" type="text" name="basedatos" value="<?php if (isset($_POST["basedatos"])) {
                                                                                echo $_POST["basedatos"];
                                                                              } ?>"></input>
            </div>
          </div>
          <div class="text-md-right">
            <input type="submit" value="Aceptar" class="btn btn-primary">
          </div>
        </form>
      </div>
    </div>
  </div>

<?php
} // función formulario($error)

/**
 * Nos indica si se produce algún error al enviar el formulario
 * @return boolean Comprobación de la existencia de errores
 */
function errores()
{
  //Cargamos los valores de configuración que deberá tener nuestra base de datos
  include "../config/database.php";
  //Verificamos que los valores del formulario coinciden con los de nuestro fichero de configuración
  if ($_POST["servidor"] != DBHOST || $_POST["usuario"] != DBUSER || $_POST["password"] != DBPASS || empty(trim($_POST["basedatos"]))) {
    return true;
  } else {
    return false;
  }
}

/**
 * Función que cambia el fichero de configuración (database.php) según los datos que hayamos introducido
 * @param String $servidor
 * @param String $usuario
 * @param String $password
 * @param String $basedatos
 * @return void
 */
function creaConfig($servidor, $usuario, $password, $basedatos)
{
  //Generamos un fichero con la información de configuración de la BD
  //Abrimos el fichero config.php en modo escritura mediante la función fopen (crear/leer archivos)
  $file = fopen("../config/database.php", "w");
  //Escribimos en el fichero los datos de configuración, de los que sólo es modificable el nombre de la base datos
  fwrite($file, "
   <?php

/**
 * Definimos los parametros con los que conectarnos a la base de datos
 */

define('DBDRIVER', 'mysql');
define('DBHOST', '$servidor');
define('DBNAME', '$basedatos');
define('DBUSER', '$usuario');
define('DBPASS', '$password');
  ");
  //Cerramos el fichero
  fclose($file);
  unset($file);
}

/**
 * Función que crea la base de datos, en función de los datos existentes en el fichero de configuración
 *También añade a la base de datos todo lo que esté en el fichero bd.sql
 * @return void
 */
function creaBD()
{
  // Incluimos el contenido del archivo de configuración...
  require_once '../config/database.php';
  //Creamos un objeto de la clase db_conf, que es la definida en config.php
  //Definimos el conjunto de instrucciones SQL para crear nuestra base de datos
  $creabd = "CREATE DATABASE IF NOT EXISTS " . DBNAME . " DEFAULT CHARACTER SET utf8 COLLATE utf8_spanish_ci;
  	     USE " . DBNAME . ";";
  //Le añadimos a la consulta de creación el contenido del script sql de nuestros elementos de la base de datos
  $sql = $creabd . file_get_contents("bd.sql");
  //Creamos nuestra base de datos como una instancia PDO
  $bd  = new PDO('mysql:host=' . DBHOST, DBUSER, DBPASS);
  try {
    $bd->exec($sql);
  } catch (PDOException $e) {
    echo $e->getMessage();
    // die();
  }
?>
  <div class="card card-block text-md-center col-md-6 offset-md-3" style="margin-top: 10rem;">
    <h1>Base de datos creada con éxito :)</h1>
    <a href="../index.php" class="btn btn-primary">Ir a la aplicación...</a>
  </div>

<?php
}
?>