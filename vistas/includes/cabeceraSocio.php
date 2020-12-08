  <header class="encabezado navbar-fixed-top" role="banner" id="Encabezado">
     <div class="container">

        <a data-scroll href="?controller=home&accion=index" id="logo">
           <img src="assets/images/logo.svg" alt="Logo del sitio">
        </a>

        <div id="nombreGim2">GIMNASIO PELOTITA</div>

        <nav class="collapse" id="enlaces">
           <ul>
              <li id="enlace1"> <a data-scroll href="?controller=home&accion=index">INICIO</a> </li>
              <li id="enlace2"> <a data-scroll href="?controller=user&accion=listado">HORARIO</a> </li>
           </ul>
        </nav>

        <div id="Saludo">
           <div> Bienvenido
              <a href="?controller=user&accion=actuser&id= <?= $_SESSION['id'] ?>">
                 <?php echo $_SESSION['logueado'] ?></a>
           </div>
           <div>Ultima conexión: <?php echo $_SESSION['hora'] ?></div>
        </div>

        <a data-scroll href="?controller=index&accion=index" <?php if (isset($_SESSION['logingoogle'])) {
                                                                  echo 'onclick="signOut();';
                                                               } ?>id="logoSalir">
           <img src="assets/images/logout.svg" alt="Salir">
        </a>
     </div>
  </header>