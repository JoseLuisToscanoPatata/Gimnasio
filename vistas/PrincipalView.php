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

            <button id="btReg" onclick="location.href='?controller=index&accion=register'"> REGISTRATE AQUÍ</button>
            <button id="btLog" onclick="location.href='?controller=index&accion=login'"> LOGUEATE AQUÍ</button>

         </div>
      </div>

      <div id="campoMensajes">
         <?php if (isset($mensajes)) {
    foreach ($mensajes as $mensaje) {?>
         <div class="alert alert-<?=$mensaje["tipo"]?>"><?=$mensaje["mensaje"]?></div>
         <?php }}?>
      </div>


   </section>

   <?php require_once 'includes/Footer.php';?>
   <?php require_once 'includes/cargaJs.php';?>
</body>

</html>