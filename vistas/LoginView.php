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

      <h2>LOGIN</h2>

       <?php if (isset($mensajes)) {
    foreach ($mensajes as $mensaje) {?>
                <div class="alert alert-<?=$mensaje["tipo"]?>"><?=$mensaje["mensaje"]?></div>
         <?php }}?>

         <form  action="?controller=index&accion=login" method="post">

            <label for="name">Usuario:
            <input type="text" name="usuario" class="form-control" value="<?php if (isset($_COOKIE['usuario'])) {echo $_COOKIE['usuario'];}?>" required />
            </label>
            <br/>

            <label for="password">Contraseña:
            <input type="password" name="password" class="form-control" value="<?php if (isset($_COOKIE['password'])) {echo $_COOKIE['password'];}?>" required/>
            </label>
            <br/>

            <label><input type="checkbox" name="recordar" <?php if (isset($_COOKIE['recordar'])) {echo " checked";}?> >Recordar datos </label>
            <br/>

            <label><input type="checkbox" name="mantener" <?php if (isset($_COOKIE['mantener'])) {echo " checked";}?> >Mantener la sesión abierta</label>
            <br/>

            <input type="submit" value="Enviar" name="submit" class="btn btn-success" />
         </form>

    </div>
  </body>
</html>
