<!DOCTYPE html>
<html>

<head>
   <?php require_once 'includes/head.php';?>
</head>

<body>

   <section class="cuerpo">
      <?php if ($_SESSION['rol'] == 1) {
    require_once 'includes/cabeceraAdmin.php';
} else {
    require_once 'includes/cabeceraSocio.php';
}?>

      <div class="text-xs-center bajar" id="cuerpoPrincipal">
         <div class="container">

            <h1 class="display-5 wow bounce font-weight-bold">USUARIO</h2>

               <form action="?controller=user&accion=actuser&id=<?=$id?>" method="post" enctype="multipart/form-data"
                  id="formRegistro">

                  <div class="row">
                     <div class="form-group col-md-6">
                        <label for="NIF">NIF: </label>
                        <input type="text" class="form-control" id="NIF" name="txtnif" placeholder="Inserte su NIF"
                           value="<?=$datos["txtnif"]?>" required>
                     </div>

                     <div class="form-group col-md-6">
                        <label for="nombre">Nombre: </label>
                        <input type="text" class="form-control" name="txtnombre" placeholder="Inserte su nombre"
                           value="<?=$datos["txtnombre"]?>" required maxlength="50">
                     </div>
                  </div>

                  <div class="row">
                     <div class="form-group col-md-6">
                        <label for="Apellido1">Primer apellido: </label>
                        <input type="text" class="form-control" id="apellido1" name="txtapellido1"
                           placeholder="Primer apellido.." value="<?=$datos["txtapellido1"]?>" required maxlength="30">
                     </div>

                     <div class="form-group col-md-6">
                        <label for="apellido2">Segundo apellido: </label>
                        <input type="text" class="form-control" id="apellido2" name="txtapellido2"
                           placeholder="Segundo apellido.." value="<?=$datos["txtapellido2"]?>" required maxlength="30">
                     </div>
                  </div>

                  <div class="row">
                     <div class="form-group col-md-6">
                        <label for="usuario">Usuario: </label>
                        <input type="text" class="form-control" id="usuario" name="txtlogin"
                           placeholder="Inserte su nombre de usuario" value="<?=$datos["txtlogin"]?>" required
                           maxlength="30">
                     </div>

                     <div class="form-group col-md-6">
                        <label for="password">Contraseña: </label>
                        <input type="<?php if ($_SESSION['id'] == $id) {echo 'password';} else {echo 'hidden';}?>"
                           class="form-control" id="password" name="txtpass" placeholder="Inserte su contraseña"
                           value="<?=$datos["txtpass"]?>" required maxlength="30">
                     </div>
                  </div>

                  <div class="row">
                     <div class="form-group col-md-6">
                        <label for="email">Email: </label>
                        <input type="email" class="form-control" id="email" name="txtemail"
                           placeholder="Inserte su correo" value="<?=$datos["txtemail"]?>" required maxlength="40">
                     </div>

                     <div class="form-group col-md-6">
                        <label for="direccion">Direccion: </label>
                        <input type="text" class="form-control" id="direccion" name="txtdireccion"
                           placeholder="Inserte su dirección" value="<?=$datos["txtdireccion"]?>" required
                           maxlength="40">
                     </div>
                  </div>

                  <div class="row">
                     <div class="form-group col-md-3">
                        <label for="telefono">Telefono móvil: </label>
                        <input type="text" class="form-control" id="telefono" name="txttelefono"
                           placeholder="Inserte su teléfono movil" value="<?=$datos["txttelefono"]?>" required>
                     </div>

                     <div class="form-group col-md-3">

                        <label for="imagen">Imagen: </label>
                        <input type="file" class="form-control" id="imagen" name="imagen"
                           placeholder="Inserte su imagen de perfil" value="<?=$datos["imagen"]?>" required>
                     </div>

                     <div class="form-group col-md-3">
                        <?php if ($datos["imagen"] != null && $datos["imagen"] != "") {?>
                        <img src="assets/fotos/<?=$datos["imagen"]?>" width="60" /></br>
                        <?php }?>
                     </div>

                     <div class="form-group col-md-3">

                        <div class="radio-inline">
                           <label><input type="radio" name="rol_id" value="2" <?php if ($datos["rol_id"] == 2) {echo "checked";} else
if (($_SESSION['id'] == $id) || ($_SESSION['id'] == 2)) {
    echo "disabled";}?>>Socio</label>
                        </div>

                        <div class="radio-inline">
                           <label><input type="radio" name="rol_id" value="1" <?php if ($datos["rol_id"] == 1) {echo "checked";} else
if (($_SESSION['id'] == $id) || ($_SESSION['id'] == 2)) {
    echo "disabled";}
?>>Admin</label>
                        </div>
                     </div>
                  </div>

                  <div class="row">
                     <div class="form-group col-md-6">
                        <input type="submit" value="Actualizar" name="submit" id="btRegistro">
                     </div>

                     <a data-scroll href="?controller=home&accion=index" id="logo">
                        <img src="assets/images/pdf.svg" alt="Pasar a pdf">
                     </a>
                  </div>

                  <input type="hidden" name="id" value="<?php echo $id; ?>">

               </form>

               <div id="campoMensajes">
                  <?php if (isset($mensajes)) {
    foreach ($mensajes as $mensaje) {?>
                  <div class="alert alert-<?=$mensaje["tipo"]?>"><?=$mensaje["mensaje"]?></div>
                  <?php }}?>
               </div>

         </div>
      </div>

   </section>

   <?php require_once 'includes/Footer.php';?>
   <?php require_once 'includes/cargaJs.php';?>

</body>

</html>