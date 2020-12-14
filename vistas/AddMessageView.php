<!DOCTYPE html>
<html>

<head>
   <?php require_once 'includes/head.php'; ?>
</head>

<body>

   <section class="cuerpo">
      <?php if ($_SESSION['rol'] == 1) {
         require_once 'includes/cabeceraAdmin.php';
      } else {
         require_once 'includes/cabeceraSocio.php';
      } ?>

      <div class="text-xs-center bajar" id="cuerpoPrincipal">
         <div class="container">

            <h1 class="display-5 wow bounce font-weight-bold">ESCRIBIR MENSAJE</h2>

               <form action="?controller=home&accion=addmessage" method="post" enctype="multipart/form-data" id="formRegistro">

                  <div class="row">
                     <div class="form-group col-sm-4" style="margin-left: auto; margin-right: auto;">
                        <label for="usuario">Usuario </label>
                        <input type="text" class="form-control" id="nombre" name="txtlogin" placeholder="Introduzca destinatario.." value="<?= $datos["txtlogin"] ?>" required maxlength="25">
                     </div>
                  </div>

                  <div class="row">
                     <div class="form-group col-sm-5" style="margin-left: auto; margin-right: auto;">
                        <label for="asunto">Asunto </label>
                        <input type="text" class="form-control" id="aforo" name="txtasunto" placeholder="Máximo 25 carácteres" value="<?= $datos["txtasunto"] ?>" required maxlength="30">
                     </div>
                  </div>

                  <div class="row">
                     <div class="form-group col-sm-5" style="margin-left: auto; margin-right: auto;">
                        <label for="mensaje">Mensaje </label>
                        <textarea style="resize: none;" class="form-control" id="mensaje" rows="3" name="txtmensaje" placeholder="Maximo 70 carácteres" value="<?= $datos["txtmensaje"] ?>" required maxlength="70"></textarea>
                     </div>
                  </div>

                  <input type="hidden" name="id" value="<?php echo $id_origen; ?>">

                  <div class="form-group">
                     <input type="submit" value="ENVIAR" name="submit" id="btRegistro">
                  </div>

               </form>

               <div style="margin-left: 80%;">
                  <button id="btRegistro" onclick="location.href='?controller=home&accion=listado'"> VOLVER</button>
               </div>

               <div id="campoMensajes">
                  <?php if (isset($mensajes)) {
                     foreach ($mensajes as $mensaje) { ?>
                        <div class="alert alert-<?= $mensaje["tipo"] ?>"><?= $mensaje["mensaje"] ?></div>
                  <?php }
                  } ?>
               </div>

         </div>
      </div>

   </section>

   <?php require_once 'includes/Footer.php'; ?>
   <?php require_once 'includes/cargaJs.php'; ?>

</body>

</html>