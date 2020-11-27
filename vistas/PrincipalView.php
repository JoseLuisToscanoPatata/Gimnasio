<!DOCTYPE html>

<!--TODO Cambiar formato-->

<html>
  <head>
    <?php require_once 'includes/head.php';?>
  </head>
  <body class="cuerpo">
    <div class="container centrar">
      <div class="container cuerpo text-center">
        <p><h1>GIMNASIO PELOTITA</h1> </p>
      </div>

      <h2>PAGINA PRINCIPAL</h2>
      <ul>
         <li><a href="?controller=index&accion=login"> LOGIN</a></li>
         <li><a href="?controller=index&accion=register"> REGISTRO</a></li>
      </ul>

       <!--<p>Bienvenido <?php if (isset($_SESSION['logueado'])) {echo $_SESSION['logueado'];}?></p>-->

      </br>

      <?php if (isset($mensajes)) {
    foreach ($mensajes as $mensaje) {?>
                <div class="alert alert-<?=$mensaje["tipo"]?>"><?=$mensaje["mensaje"]?></div>
         <?php }}?>
    </div>
  </body>
</html>
