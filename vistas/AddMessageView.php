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

            <h1 class="display-5 wow bounce font-weight-bold">ESCRIBIR MENSAJE</h2>

               <form action="?controller=home&accion=addmessage" method="post" enctype="multipart/form-data" id="formRegistro">

                  <div class="form-group">
                     <label for="usuario">Usuario: </label>
                     <input type="text" class="form-control" id="nombre" name="txtlogin" placeholder="Introduzca destinatario.." value="<?=$datos["txtlogin"]?>" required maxlength="30">
                  </div>

                  <div class="form-group">
                     <label for="asunto">Asunto: </label>
                     <input type="text" class="form-control" id="aforo" name="txtasunto" placeholder="Máximo 30 carácteres" value="<?=$datos["txtasunto"]?>" required maxlength="2" size="5">
                  </div>

                  <div class="form-group">
                     <label for="mensaje">Mensaje: </label>
                     <input type="textarea" class="form-control" id="mensaje" name="txtmensaje" placeholder="Maximo 70 carácteres" value="<?=$datos["txtmensaje"]?>" required maxlength="70">
                  </div>

                  <input type="hidden" name="id" value="<?php echo $id_origen; ?>">

                  <div class="row">
                     <div class="form-group col-md-6">
                        <input type="submit" value="ENVIAR" name="submit" id="btRegistro">
                     </div>
                  </div>

               </form>

               <div id="campoMensajes">
                  <?php if (isset($mensajes)) {
    foreach ($mensajes as $mensaje) {?>
                        <div class="alert alert-<?=$mensaje["tipo"]?>"><?=$mensaje["mensaje"]?></div>
                  <?php }
}?>
               </div>

         </div>
      </div>

   </section>

   <?php require_once 'includes/Footer.php';?>
   <?php require_once 'includes/cargaJs.php';?>

</body>

</html>