<html>

<head>
   <?php require_once 'includes/head.php'; ?>
   <script src="https://www.google.com/recaptcha/api.js" async defer></script>
   <script src="https://apis.google.com/js/platform.js" async defer></script>
   <meta name="google-signin-client_id" content="583790614403-n8rstllbj6st59enh2dt1hv7dtm82rnb.apps.googleusercontent.com">
</head>

<body>

   <section class="cuerpo">

      <?php require_once 'includes/cabeceraOut.php'; ?>

      <div class="text-xs-center bajar" id="cuerpoPrincipal">
         <div class="container">


            <h1 class="display-5 wow bounce font-weight-bold">LOGIN</h2>

               <form action="?controller=index&accion=login" method="post" id="formLogin">

                  <div class="form-group row">
                     <label for="usuario" class="col-xs-2 col-form-label font-weight-bold">Usuario:</label>
                     <div class="col-xs-4">
                        <input type="text" name="usuario" class="form-control" id="usuario" value="<?php if (isset($_COOKIE['usuario'])) {
                                                                                                      echo $_COOKIE['usuario'];
                                                                                                   } ?>" maxlength="30" required />
                     </div>
                  </div>

                  <div class="form-group row">
                     <label for="constraseña" class="col-xs-2 col-form-label font-weight-bold">Contraseña:</label>
                     <div class="col-xs-4">
                        <input type="password" name="password" class="form-control" id="contraseña" value="<?php if (isset($_COOKIE['password'])) {
                                                                                                               echo $_COOKIE['password'];
                                                                                                            } ?>" maxlength="30" required />
                     </div>
                  </div>

                  <div class="form-group row casilla">
                     <div class="form-check">
                        <input type="checkbox" class="form-check-input" name="recordar" id="recordar" <?php if (isset($_COOKIE['recordar'])) {
                                                                                                         echo " checked";
                                                                                                      } ?>>
                        <label class="form-check-label" for="recordar">Recordar datos </label>
                     </div>
                  </div>

                  <div class="form-group row casilla">
                     <div class="form-check">
                        <input type="checkbox" class="form-check-input" name="mantener" id="mantener" <?php if (isset($_COOKIE['mantener'])) {
                                                                                                         echo " checked";
                                                                                                      } ?>>
                        <label class="form-check-label" for="recordar">Mantener la sesión abierta </label>
                     </div>
                  </div>

                  <div class="g-recaptcha" style="margin-left: 24rem;" data-sitekey="6LeA9PwZAAAAAHnhsojtNxBmwdV0LZVbJVtwFQCi"></div>
                  <br />
                  <div class="row" style="margin-top:2rem;">
                     <div class="g-signin2 casilla" data-onsuccess="redireccionar"> </div> <input type="submit" value="Entrar" name="submit" id="btEntrar" style="position: relative; left: 3%;" />
                  </div>

               </form>

               <div id=" campoMensajes">
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

   <script type="text/javascript">
      function redireccionar() {
         window.location.href = 'http://localhost/algo/Gimnasio/index.php?controller=index&accion=logingoogle';
      }
   </script>
</body>

</html>