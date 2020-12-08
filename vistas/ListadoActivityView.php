<html>

<head>
   <?php require_once 'includes/head.php'; ?>
</head>

<body>

   <section class="cuerpo">

      <?php require_once 'includes/cabeceraAdmin.php'; ?>

      <div class="text-xs-center bajar" id="cuerpoPrincipal">
         <div class="container">

            <h1 class="display-5 wow bounce font-weight-bold">LISTADO DE ACTIVIDADES</h2>

               <div class="btn-group" id="paginasActividades">
                  <a class="btn " href="#"><i class="fa fa-user"></i> Registros por página:</a>
                  <a class="btn  dropdown-toggle" data-toggle="dropdown" href="#">
                     </span></a>
                  <ul class="dropdown-menu">
                     <li><a href="index.php?controller=activity&accion=listado&regsxpag=3&orden=<?= $paginacion['orden'] ?>&columna=<?= $paginacion['columna'] ?>"> <i class="fa fa-th"></i> 3</a></li>
                     <li><a href="index.php?controller=activity&accion=listado&regsxpag=5&orden=<?= $paginacion['orden'] ?>&columna=<?= $paginacion['columna'] ?>"> <i class="fa fa-th"> </i> 5</a></li>
                     <li><a href="index.php?controller=activity&accion=listado&regsxpag=7&orden=<?= $paginacion['orden'] ?>&columna=<?= $paginacion['columna'] ?>"> <i class="fa fa-th"></i> 7</a></li>
                  </ul>
               </div>

               <!--Creamos la tabla que utilizaremos para el listado:-->
               <table class="table" style="color: white;" id="actividades">

                  <tr>
                     <td><input type="text" placeholder="Id.." onkeyup="filtrarTabla('fillId', 'actividades',0)" id="fillId"></td>
                     <td><input type="text" placeholder="Nombre.." onkeyup="filtrarTabla('fillNombre', 'actividades',1)" id="fillNombre"></td>
                     <td><input type="text" placeholder="Aforo.." onkeyup="filtrarTabla('fillAforo', 'actividades',2)" id="fillAforo"></td>
                     <td><input type="text" placeholder="Descripcion.." onkeyup="filtrarTabla('fillDescp', 'actividades',3)" id="fillDescp"></td>
                     <td style="display: none;"></td>
                  </tr>
                  <tr>
                     <th class="bg-primary" style="width: 50px;"> <a href="index.php?controller=activity&accion=listado&regsxpag=<?= $paginacion['regsxpag'] ?>&orden=<?php if ($paginacion['columna'] == 'actividad_id' && $paginacion['orden'] == 'desc') {
                                                                                                                                                                           echo 'asc';
                                                                                                                                                                        } else {
                                                                                                                                                                           echo 'desc';
                                                                                                                                                                        } ?><?= '&columna=actividad_id' ?>">Id</a> </th>

                     <th class="bg-info" style="width: 153px;"> <a href="index.php?controller=activity&accion=listado&regsxpag=<?= $paginacion['regsxpag'] ?>&orden=<?php if ($paginacion['columna'] == 'act_nombre' && $paginacion['orden'] == 'desc') {
                                                                                                                                                                        echo 'asc';
                                                                                                                                                                     } else {
                                                                                                                                                                        echo 'desc';
                                                                                                                                                                     } ?><?= '&columna=act_nombre' ?>">Nombre</a></th>

                     <th class="bg-success" style="width: 60px;"><a href="index.php?controller=activity&accion=listado&regsxpag=<?= $paginacion['regsxpag'] ?>&orden=<?php if ($paginacion['columna'] == 'aforo' && $paginacion['orden'] == 'desc') {
                                                                                                                                                                        echo 'asc';
                                                                                                                                                                     } else {
                                                                                                                                                                        echo 'desc';
                                                                                                                                                                     } ?><?= '&columna=aforo' ?>">Aforo</a></th>
                     <th class="bg-warning" style="width: 200px;"><a href="index.php?controller=activity&accion=listado&regsxpag=<?= $paginacion['regsxpag'] ?>&orden=<?php if ($paginacion['columna'] == 'descripcion' && $paginacion['orden'] == 'desc') {
                                                                                                                                                                           echo 'asc';
                                                                                                                                                                        } else {
                                                                                                                                                                           echo 'desc';
                                                                                                                                                                        } ?><?= '&columna=descripcion' ?>">Descripcion</a></th>

                     <th style="background-color: #cd5afa; width: 100px;">Operaciones</th>
                  </tr>
                  <!--Los datos a listar están almacenados en $parametros["datos"], que lo recibimos del controlador-->
                  <?php foreach ($datos as $d) : ?>
                     <!--Mostramos cada registro en una fila de la tabla-->
                     <tr>
                        <td class="bg-primary"><?= $d["actividad_id"] ?></td>
                        <td class="bg-info"><?= $d["act_nombre"] ?></td>
                        <td class="bg-success"><?= $d["aforo"] ?></td>
                        <td class="bg-warning"><?= $d["descripcion"] ?></td>
                        <td style="background-color: #cd5afa;" id="iconosTabla">
                           <a href=" ?controller=activity&accion=actactivity&id=<?= $d['actividad_id'] ?>">
                              <img src="assets/images/edit.svg" alt="Editar Actividad">
                           </a>
                           <a href="?controller=activity&accion=delactivity&id=<?= $d['actividad_id'] ?>">
                              <img src="assets/images/delete.svg" alt="Eliminar usuario">
                           </a>
                        </td>
                     <tr>


                     </tr>
                  <?php endforeach; ?>

               </table>

               <nav aria-label="Page navigation example" class="text-center">
                  <ul class="pagination">

                     <?php
                     if ($paginacion['pagina'] == 1) : ?>
                        <li class="page-item disabled"><a class="page-link" href="#">&laquo;&laquo;</a></li>
                        <li class="page-item disabled"><a class="page-link" href="#">&laquo;</a></li>
                     <?php else : ?>
                        <li class="page-item"><a class="page-link" href="index.php?controller=activity&accion=listado&pagina=1&regsxpag=<?= $paginacion['regsxpag'] ?>
                        &orden=<?= $paginacion['orden'] ?>&columna=<?= $paginacion['columna'] ?>"> &laquo; &laquo;</a></li>

                        <li class="page-item"><a class="page-link" href="index.php?controller=activity&accion=listado&pagina=<?= $paginacion['pagina'] - 1 ?>&regsxpag=<?= $paginacion['regsxpag'] ?>
                        &orden=<?= $paginacion['orden'] ?>&columna=<?= $paginacion['columna'] ?>"> &laquo; </a></li>
                     <?php endif; ?>

                     <li class="page-item active"><a class="page-link" href="#"><?php echo $paginacion['pagina'] ?> </a> </li>

                     <?php
                     if ($paginacion['pagina'] != $paginacion['numpaginas']) { ?>
                        <li class="page-item"><a class="page-link" href="index.php?controller=activity&accion=listado&pagina=<?php echo $paginacion['pagina'] + 1; ?>&regsxpag=<?= $paginacion['regsxpag'] ?>
                        &orden=<?= $paginacion['orden'] ?>&columna=<?= $paginacion['columna'] ?>"> &raquo; </a></li>

                        <li class="page-item"><a class="page-link" href="index.php?controller=activity&accion=listado&pagina=<?php echo $paginacion['numpaginas']; ?>&regsxpag=<?= $paginacion['regsxpag'] ?>
                        &orden=<?= $paginacion['orden'] ?>&columna=<?= $paginacion['columna'] ?>"> &raquo;&raquo; </a></li>
                     <?php } else { ?>

                        <li class="page-item disabled"><a class="page-link" href="#">&raquo;</a></li>
                        <li class="page-item disabled"><a class="page-link" href="#">&raquo;&raquo;</a></li>
                     <?php } ?>
                  </ul>

               </nav>

               <div class="row" id="botonesLista">
                  <div class="form-group col-md-6">
                     <input type="submit" value="NUEVO" name="añadir" id="btRegistro" onclick="location.href='?controller=activity&accion=addactivity'">
                  </div>

                  <a data-scroll href="?controller=home&accion=index" id="logo">
                     <img src="assets/images/pdf.svg" alt="Pasar a pdf">
                  </a>
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

   <script type="text/javascript" src="assets/js/scriptTablas.js"></script>


</body>

</html>