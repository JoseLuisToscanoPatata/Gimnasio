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

            <h1 class="display-5 wow bounce font-weight-bold">MENSAJE</h2>

            <form action="?controller=home&accion=seemessage" method="post" enctype="multipart/form-data" id="formRegistro">

                  <div class="form-group">
                     <label for="usuario">Destino: </label>
                     <input type="text" class="form-control" id="nombre" value="<?= $datos["txtlogin"] ?>">
                  </div>

                  <div class="form-group">
                     <label for="asunto">Asunto: </label>
                     <input type="text" class="form-control" id="aforo" value="<?= $datos["txtasunto"] ?>">
                  </div>

                  <div class="form-group">
                     <label for="mensaje">Mensaje: </label>
                     <input type="textarea" class="form-control" id="mensaje" value="<?= $datos["txtmensaje"] ?>">
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