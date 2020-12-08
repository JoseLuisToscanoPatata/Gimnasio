<!DOCTYPE html>
<html>

<head>
   <?php require_once 'includes/head.php'; ?>
</head>

<body>

   <section class="cuerpo">
      <?php require_once 'includes/cabeceraAdmin.php'; ?>

      <div class="text-xs-center bajar" id="cuerpoPrincipal">
         <div class="container">

            <h1 class="display-5 wow bounce font-weight-bold">AÑADIR ACTIVIDAD</h2>

               <form action="?controller=activity&accion=addactivity" method="post" enctype="multipart/form-data" id="formRegistro">

                  <div class="form-group">
                     <label for="nombre">Nombre: </label>
                     <input type="text" class="form-control" id="nombre" name="txtnombre" placeholder="Solo se aceptan valores alfabéticos" value="<?= $datos["txtnombre"] ?>" required maxlength="30">
                  </div>

                  <div class="form-group">
                     <label for="descripcion">Descripción: </label>
                     <input type="textarea" class="form-control" id="descripcion" name="txtdescripcion" placeholder="Descripción de la actividad.." value="<?= $datos["txtdescripcion"] ?>" required maxlength="50">
                  </div>


                  <div class="form-group">
                     <label for="aforo">Aforo: </label>
                     <input type="text" class="form-control" id="aforo" name="txtaforo" placeholder="Máximo 30 personas" value="<?= $datos["txtaforo"] ?>" required maxlength="2" size="5">
                  </div>

                  <div class="form-group">
                     <input type="submit" value="Añadir" name="submit" id="btRegistro">
                  </div>

               </form>

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