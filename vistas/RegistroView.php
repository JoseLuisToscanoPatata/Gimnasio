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

     <a href="?controller=index&accion=index"> Volver</a>

      <h2>REGISTRO</h2>

       <?php if (isset($mensajes)) {
    foreach ($mensajes as $mensaje) {?>
                <div class="alert alert-<?=$mensaje["tipo"]?>"><?=$mensaje["mensaje"]?></div>
         <?php }}?>

       <form action="?controller=index&accion=register" method="post" enctype="multipart/form-data">
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
            <label for="imagen">Imagen <input type="file" name="imagen" class="form-control" value="<?=$datos["imagen"]?>" /></label>
            </br>
            <input type="submit" value="Registrar" name="submit" class="btn btn-success">
         </form>
    </div>
  </body>
</html>
