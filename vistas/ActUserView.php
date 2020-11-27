<?php require_once 'includes/comprobarSesion.php'?>

<!--TODO Cambiar formato-->

<!DOCTYPE html>
<html>
  <head>
    <?php require_once 'includes/head.php';?>
  </head>
  <body class="cuerpo">
    <div class="container centrar">
      <div class="container cuerpo text-center">
        <ul>
          <li> <a href="?controller=user&accion=listado"> Listar usuarios</a></li>
          <li> <a href="?controller=user&accion=adduser"> Añadir usuario</a></li>
        </ul>
        <p><h2><img class="alineadoTextoImagen" src="assets/images/user.png" width="50px"/>Actualizar Usuario</h2> </p>
      </div>
      <?php // Mostramos los mensajes procedentes del controlador que se hayn generado
foreach ($mensajes as $mensaje): ?>
             <div class="alert alert-<?=$mensaje["tipo"]?>"><?=$mensaje["mensaje"]?></div>
      <?php endforeach;?>
      <form action="?controller=user&accion=actuser" method="post" enctype="multipart/form-data">
        <!-- Rellenamos los campos con los valores recibidos desde el controlador -->
          <label for="txtnif">NIF
               <input type="text" class="form-control" name="txtnif" value="<?=$datos["txtnif"]?>" required></label>
            <br />

            <label for="txtnombre">Nombre
               <input type="text" class="form-control" name="txtnombre" value="<?=$datos["txtnombre"]?>" required></label>
            <br />

            <label for="txtapellido1">Primer apellido
               <input type="text" class="form-control" name="txtapellido1" value="<?=$datos["txtapellido1"]?>" required></label>
            <br />

            <label for="txtapellido2">Segundo apellido
               <input type="text" class="form-control" name="txtapellido2" value="<?=$datos["txtapellido2"]?>" required></label>
            <br />

            <label for="txtlogin">Nombre de usuario
               <input type="text" class="form-control" name="txtlogin" value="<?=$datos["txtlogin"]?>" required></label>
            <br />

            <label for="txtpassword">Contraseña
               <input type="<?php if ($_SESSION['id'] == $id) {echo 'password';} else {echo 'hidden';}?>" class="form-control" name="txtpassword" required></label>
            <br />

            <label for="txtemail">Email
               <input type="email" class="form-control" name="txtemail" value="<?=$datos["txtemail"]?>" required></label>
            <br />
            <!--
            <label for="txtpass">Contraseña
               <input type="password" class="form-control" name="txtpass" required value="<?=$datos["txtpass"]?>"></label>
            <br />
            -->

            <label for="txttelefono">Telefono móvil
               <input type="text" class="form-control" name="txttelefono" value="<?=$datos["txttelefono"]?>" required></label>
            <br />

            <label for="txtdireccion">Direccion
               <input type="text" class="form-control" name="txtdireccion" value="<?=$datos["txtdireccion"]?>" required></label>
            <br />

          <?php if ($datos["imagen"] != null && $datos["imagen"] != "") {?>
            </br>Imagen del Perfil: <img src="assets/fotos/<?=$datos["imagen"]?>" width="60" /></br>
          <?php }?>
          </br>
          <label for="imagen">Actualizar imagen de perfil:
            <input type="file" name="imagen" class="form-control" value="<?=$datos["imagen"]?>" /></label>
          </br>

         <div class="radio-inline">
               <label><input type="radio" name="rol_id" value="2" <?php if ($datos["rol_id"] == 2) {echo "checked";}?>>Socio</label>
          </div>

            <div class="radio-inline">
               <label><input type="radio" name="rol_id" value="1" <?php if ($datos["rol_id"] == 1) {echo "checked";}?>>Admin</label>
            </div>
          </br>


        <!--Creamos un campo oculto para mantener el valor del id que deseamos modificar cuando pulsemos el botón actualizar-->
          <input type="hidden" name="id" value="<?php echo $id; ?>">
          <br/>
          <input type="submit" value="Actualizar" name="submit" class="btn btn-success">
      </form>
    </div>
  </body>
</html>