<?php require_once 'includes/comprobarSesion.php'?>

<!--TODO Cambiar formato-->
<html>
  <head>
    <?php require_once 'includes/head.php';?>
  </head>
  <body class="cuerpo">
    <div class="container centrar">

              <!-- <p>Bienvenido <?php if (isset($_COOKIE['mantener'])) {echo $_COOKIE['mantener'];} else {echo $_SESSION['logueado'];}?></p> -->

      <a href="?controller=home&accion=index">Inicio</a>
      <div class="container cuerpo text-center centrar">
        <p><h2><img class="alineadoTextoImagen" src="assets/images/user.png" width="50px"/>Listar Usuarios</h2> </p>
      </div>
      <!--Mostramos los mensajes que se hayan generado al realizar el listado-->
      <?php foreach ($mensajes as $mensaje): ?>
        <div class="alert alert-<?=$mensaje["tipo"]?>"><?=$mensaje["mensaje"]?></div>
      <?php endforeach;?>
      <!--Creamos la tabla que utilizaremos para el listado:-->
      <table class="table table-striped">
        <tr>
          <th>Id</th>
          <th>DNI</th>
          <th>Usuario</th>
          <th>Email</th>
          <th>Telefono</th>
          <th> Rol</th>
          <th>Foto</th>
          <!-- Añadimos una columna para las operaciones que podremos realizar con cada registro -->
          <th>Operaciones</th>
        </tr>
        <!--Los datos a listar están almacenados en $parametros["datos"], que lo recibimos del controlador-->
        <?php foreach ($datos as $d): ?>
          <!--Mostramos cada registro en una fila de la tabla-->
          <tr>
            <td><?=$d["usuario_id"]?></td>
            <td><?=$d["nif"]?></td>
            <td><?=$d["login"]?></td>
            <td><?=$d["email"]?></td>
            <td><?=$d["telefono"]?></td>

            <?php if ($d["rol_id"] == 1): ?>
              <td>Admin</td>
            <?php else: ?>
              <td>Socio</td>
            <?php endif;?>

            <?php if ($d["imagen"] !== null): ?>
              <td><img src="assets/fotos/<?=$d['imagen']?>" width="40" /></td>
            <?php else: ?>
              <td>----</td>
            <?php endif;?>
            <!-- Enviamos a actuser.php, mediante GET, el id del registro que deseamos editar o eliminar: -->
            <td><a href="?controller=user&accion=actuser&id=<?=$d['usuario_id']?>">Editar </a><a href="?controller=user&accion=deluser&id=<?=$d['usuario_id']?>">Eliminar</a></td>
          </tr>
        <?php endforeach;?>
      </table>
    </div>
  </body>
</html>