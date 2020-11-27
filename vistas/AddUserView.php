<?php require_once 'includes/comprobarSesion.php'?>


<!DOCTYPE html>
<html>

<!--TODO Cambiar formato-->

<head>
   <?php require_once 'includes/head.php';?>
</head>

<body class="cuerpo">
   <div class="centrar">
      <div class="container centrar">
         <a href="?controller=home&accion=index">Inicio</a>
         <div class="container cuerpo text-center centrar">
            <p>
               <h2><img class="alineadoTextoImagen" src="assets/images/user.png" width="50px" />Añadir Usuario</h2>
            </p>
         </div>
         <?php foreach ($mensajes as $mensaje): ?>
            <div class="alert alert-<?=$mensaje["tipo"]?>"><?=$mensaje["mensaje"]?></div>
         <?php endforeach;?>
         <form action="?controller=user&accion=adduser" method="post" enctype="multipart/form-data">
            <label for="txtnif">NIF
               <input type="text" class="form-control" name="txtnif" required value="<?=$datos["txtnif"]?>" required></label>
            <br />

            <label for="txtnombre">Nombre
               <input type="text" class="form-control" name="txtnombre" required value="<?=$datos["txtnombre"]?>" required></label>
            <br />

            <label for="txtapellido1">Primer apellido
               <input type="text" class="form-control" name="txtapellido1" required value="<?=$datos["txtapellido1"]?>" required></label>
            <br />

            <label for="txtapellido2">Segundo apellido
               <input type="text" class="form-control" name="txtapellido2" required value="<?=$datos["txtapellido2"]?>" required></label>
            <br />

            <label for="txtlogin">Nombre de usuario
               <input type="text" class="form-control" name="txtlogin" required value="<?=$datos["txtlogin"]?>" required></label>
            <br />

            <label for="txtemail">Email
               <input type="email" class="form-control" name="txtemail" value="<?=$datos["txtemail"]?>" required></label>
            <br />

            <label for="txtpass">Contraseña
               <input type="password" class="form-control" name="txtpass" required value="<?=$datos["txtpass"]?>" required></label>
            <br />

            <label for="txttelefono">Telefono móvil
               <input type="text" class="form-control" name="txttelefono" required value="<?=$datos["txttelefono"]?>" required></label>
            <br />

            <label for="txtdireccion">Direccion
               <input type="text" class="form-control" name="txtdireccion" required value="<?=$datos["txtdireccion"]?>" required></label>
            <br />

            <label for="imagen">Imagen
               <input type="file" name="imagen" class="form-control" value="<?=$datos["imagen"]?>" /></label>
            </br>

            <div class="radio-inline">
               <label><input type="radio" name="rol_id" value="2" checked>Socio</label>
            </div>

            <div class="radio-inline">
               <label><input type="radio" name="rol_id" value="1">Admin</label>
            </div>
            <input type="submit" value="Guardar" name="submit" class="btn btn-success">
         </form>
      </div>
</body>

</html>