<!DOCTYPE html>
<html>

<head>
   <?php require_once 'includes/head.php';?>
</head>

<body>

   <section class="cuerpo">
      <?php require_once 'includes/cabeceraOut.php';?>

      <div class="text-xs-center bajar" id="cuerpoPrincipal">
         <div class="container">

            <h1 class="display-5 wow bounce font-weight-bold">REGISTRO</h2>

               <form action="?controller=index&accion=register" method="post" enctype="multipart/form-data"
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
                           placeholder="Sin restricciones alfanuméricas.." value="<?=$datos["txtlogin"]?>" required
                           maxlength="30">
                     </div>

                     <div class="form-group col-md-6">
                        <label for="password">Contraseña: </label>
                        <input type="password" class="form-control" id="password" name="txtpass"
                           placeholder="8 Caráct., Mayus, número y caracter especial.." required maxlength="30">
                     </div>
                  </div>

                  <div class="row">
                     <div class="form-group col-md-6">
                        <label for="email">Email: </label>
                        <input type="email" class="form-control" id="email" name="txtemail"
                           placeholder="Inserte su correo electrónico.." value="<?=$datos["txtemail"]?>" required maxlength="40">
                     </div>

                     <div class="form-group col-md-6">
                        <label for="direccion">Direccion: </label>
                        <input type="text" class="form-control" id="direccion" name="txtdireccion"
                           placeholder="Válido hasta 2 espacios" value="<?=$datos["txtdireccion"]?>" required
                           maxlength="40">
                     </div>
                  </div>

                  <div class="row">
                     <div class="form-group col-md-6">
                        <label for="telefono">Telefono móvil: </label>
                        <input type="text" class="form-control" id="telefono" name="txttelefono"
                           placeholder="Ej: 633259523" value="<?=$datos["txttelefono"]?>" required>
                     </div>

                     <div class="form-group col-md-6">
                        <label for="imagen">Imagen: </label>
                        <input type="file" class="form-control" id="imagen" name="imagen"
                           placeholder="Inserte su imagen de perfil" value="<?=$datos["imagen"]?>" required>
                     </div>
                  </div>

                  <input type="submit" value="Registrar" name="submit" id="btRegistro">

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