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

            <?php if ($_SESSION['modo'] == "IN") { ?>
               <h1 class="display-5 wow bounce font-weight-bold">BANDEJA DE ENTRADA</h1>
            <?php } else { ?>
               <h1 class="display-5 wow bounce font-weight-bold">BANDEJA DE SALIDA</h1>
            <?php } ?>
            <div id="paginasMensajes" class="btn-group">
               <a class="btn " href="#"><i class="fa fa-user"></i> Registros por página:</a>
               <a class="btn  dropdown-toggle" data-toggle="dropdown" href="#">
                  </span></a>
               <ul class="dropdown-menu">
                  <li><a href="index.php?controller=home&accion=listado&regsxpag=3&orden=<?= $paginacion['orden'] ?>&columna=<?= $paginacion['columna'] ?>"> <i class="fa fa-th"></i> 3</a></li>
                  <li><a href="index.php?controller=home&accion=listado&regsxpag=5&orden=<?= $paginacion['orden'] ?>&columna=<?= $paginacion['columna'] ?>"> <i class="fa fa-th"> </i> 5</a></li>
                  <li><a href="index.php?controller=home&accion=listado&regsxpag=7&orden=<?= $paginacion['orden'] ?>&columna=<?= $paginacion['columna'] ?>"> <i class="fa fa-th"></i> 7</a></li>
               </ul>
            </div>

            <!--Creamos la tabla que utilizaremos para el listado:-->
            <table class="table" style="color: white;" id="mensajes">

               <?php if ($paginacion['totalRegistros'] > 0) { ?>
                  <tr>
                     <td><input type="text" placeholder="Usuario.." onkeyup="filtrarTabla('fillUsuario', 'mensajes',0)" id="fillUsuario"></td>
                     <td><input type="text" placeholder="Asunto.." onkeyup="filtrarTabla('fillAsunto', 'mensajes',1)" id="fillAsunto"></td>
                     <td style="display: none;"></td>
                  </tr>

               <?php } ?>
               <tr>
                  <th class="bg-primary" style="width: 50px;"> <a href="index.php?controller=home&accion=listado&regsxpag=<?= $paginacion['regsxpag'] ?>&orden=<?php if ($paginacion['columna'] == 'login' && $paginacion['orden'] == 'desc') {
                                                                                                                                                                  echo 'asc';
                                                                                                                                                               } else {
                                                                                                                                                                  echo 'desc';
                                                                                                                                                               } ?><?= '&columna=login' ?>"><?php if ($_SESSION['modo'] == 'IN') {
                                                                                                                                                                                                echo 'Receptor';
                                                                                                                                                                                             } else {
                                                                                                                                                                                                echo 'Remitente';
                                                                                                                                                                                             } ?></a> </th>

                  <th class="bg-info" style="width: 250px;"> <a href="index.php?controller=home&accion=listado&regsxpag=<?= $paginacion['regsxpag'] ?>&orden=<?php if ($paginacion['columna'] == 'asunto' && $paginacion['orden'] == 'desc') {
                                                                                                                                                                  echo 'asc';
                                                                                                                                                               } else {
                                                                                                                                                                  echo 'desc';
                                                                                                                                                               } ?><?= '&columna=asunto' ?>">Asunto</a></th>

                  <th style="background-color: #cd5afa; width: 100px;">Operaciones</th>
               </tr>
               <!--Los datos a listar están almacenados en $parametros["datos"], que lo recibimos del controlador-->

               <?php if ($paginacion['totalRegistros'] > 0) {

                  foreach ($datos as $d) : ?>
                     <!--Mostramos cada registro en una fila de la tabla-->
                     <tr>
                        <td class="bg-primary"><?= $d["persona"] ?></td>
                        <td class="bg-info"><?= $d["asunto"] ?></td>
                        <td style="background-color: #cd5afa;" id="iconosTabla">
                           <a href=" ?controller=home&accion=seemessage&id=<?= $d['mensaje_id'] ?>">
                              <img src="assets/images/edit.svg" alt="mirar Mensaje">
                           </a>
                           <?php if ($_SESSION['modo'] == "IN") { ?>
                              <a href="?controller=home&accion=delmessage&id=<?= $d['mensaje_id'] ?>">
                                 <img src="assets/images/delete.svg" alt="eliminar Mensaje">
                              </a>
                           <?php } ?>
                        </td>
                     <tr>
                     </tr>
                  <?php endforeach;
               } else { ?>

                  <tr>
                     <td style="background-color:white; text-align:center; color: black;" colspan="3"> No se han encontrado mensajes!!</td>
                  </tr>
               <?php } ?>


            </table>

            <nav aria-label="Page navigation example" class="text-center">
               <ul class="pagination">

                  <?php
                  if ($paginacion['pagina'] == 1) : ?>
                     <li class="page-item disabled"><a class="page-link" href="#">&laquo;&laquo;</a></li>
                     <li class="page-item disabled"><a class="page-link" href="#">&laquo;</a></li>
                  <?php else : ?>
                     <li class="page-item"><a class="page-link" href="index.php?controller=home&accion=listado&pagina=1&regsxpag=<?= $paginacion['regsxpag'] ?>&orden=<?= $paginacion['orden'] ?>&columna=<?= $paginacion['columna'] ?>"> &laquo; &laquo;</a></li>

                     <li class="page-item"><a class="page-link" href="index.php?controller=home&accion=listado&pagina=<?= $paginacion['pagina'] - 1 ?>&regsxpag=<?= $paginacion['regsxpag'] ?>&orden=<?= $paginacion['orden'] ?>&columna=<?= $paginacion['columna'] ?>"> &laquo; </a></li>
                  <?php endif; ?>

                  <li class="page-item active"><a class="page-link" href="#"><?php echo $paginacion['pagina'] ?> </a> </li>

                  <?php
                  if ($paginacion['pagina'] != $paginacion['numpaginas']) { ?>
                     <li class="page-item"><a class="page-link" href="index.php?controller=home&accion=listado&pagina=<?php echo $paginacion['pagina'] + 1; ?>&regsxpag=<?= $paginacion['regsxpag'] ?>&orden=<?= $paginacion['orden'] ?>&columna=<?= $paginacion['columna'] ?>"> &raquo; </a></li>

                     <li class="page-item"><a class="page-link" href="index.php?controller=home&accion=listado&pagina=<?php echo $paginacion['numpaginas']; ?>&regsxpag=<?= $paginacion['regsxpag'] ?>&orden=<?= $paginacion['orden'] ?>&columna=<?= $paginacion['columna'] ?>"> &raquo;&raquo; </a></li>
                  <?php } else { ?>

                     <li class="page-item disabled"><a class="page-link" href="#">&raquo;</a></li>
                     <li class="page-item disabled"><a class="page-link" href="#">&raquo;&raquo;</a></li>
                  <?php } ?>
               </ul>

            </nav>

            <div class="row" id="botonesLista">
               <div class="form-group col-md-4">
                  <input type="submit" value="NUEVO MENSAJE" name="añadir" id="btRegistroMensaje" onclick="location.href='?controller=home&accion=addmessage'">
               </div>

               <?php if ($_SESSION['rol'] == 1) { ?>
                  <div class="form-group col-md-4">
                     <input type="submit" value="NUEVO CORREO" name="añadir" id="btRegistroCorreo" onclick="location.href='?controller=home&accion=addmail'">
                  </div>
            </div>
         <?php } ?>

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

   <script type="text/javascript" src="assets/js/scriptTablas.js"></script>


</body>

</html>