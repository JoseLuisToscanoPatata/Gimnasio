<?php require_once 'includes/comprobarSesion.php'?>

<!--TODO Cambiar formato-->

<!DOCTYPE html>
<html>

<head>
   <?php require_once 'includes/head.php';?>
</head>

<body>
   <section class="cuerpo">

      <?php if($_SESSION['rol'] == 1) {
          require_once 'includes/cabeceraAdmin.php';
      } else {
           require_once 'includes/cabeceraSocio.php';
      } ?>

      <div class="text-xs-center bajar" id="cuerpoPrincipal">
         <div class="container">

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