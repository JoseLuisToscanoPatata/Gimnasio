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
         <div class="container" id="formRegistro">

            <h1 class="display-5 wow bounce font-weight-bold">MENSAJE</h2>

               <div class="row">
                  <div class="form-group col-sm-4" style="margin-left: auto; margin-right: auto;">
                     <?php if ($_SESSION['modo'] == "IN") { ?>
                        <label for="usuario">Destino </label>
                     <?php } else { ?>
                        <label for="usuario">Origen </label>
                     <?php } ?>
                     <input type="text" class="form-control" id="nombre" value="<?= $datos["persona"] ?>" readonly>
                  </div>
               </div>

               <div class="row">
                  <div class="form-group col-sm-5" style="margin-left: auto; margin-right: auto;">
                     <label for="asunto">Asunto </label>
                     <input type="text" class="form-control" id="aforo" value="<?= $datos["asunto"] ?>" readonly>
                  </div>
               </div>


               <div class="row">
                  <div class="form-group col-sm-5" style="margin-left: auto; margin-right: auto;">
                     <label for="mensaje">Mensaje </label>
                     <textarea style="resize: none;" class="form-control" rows="4" id="mensaje" readonly>  <?= $datos["texto"] ?></textarea>
                  </div>
               </div>

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