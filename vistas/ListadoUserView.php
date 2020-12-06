<html>

<head>
   <?php require_once 'includes/head.php';?>
</head>

<body>

   <section class="cuerpo">

      <?php require_once 'includes/cabeceraAdmin.php';?>

      <div class="text-xs-center bajar" id="cuerpoPrincipal">
         <div class="container">

            <h1 class="display-5 wow bounce font-weight-bold">LISTADO DE USUARIOS</h2>


               <!--Creamos la tabla que utilizaremos para el listado:-->
               <table class="table" style="color: white;" id="usuarios">

                  <tr>
                     <td><input type="text" placeholder="Id.." onkeyup="filtrarTabla('fillId', 'usuarios',0)" id="fillId"></td>
                     <td><input type="text" placeholder="DNI.." onkeyup="filtrarTabla('fillDNI', 'usuarios',1)" id="fillDNI"></td>
                     <td><input type="text" placeholder="Usuarios.." onkeyup="filtrarTabla('fillUsu', 'usuarios',2)" id="fillUsu"></td>
                     <td><input type="text" placeholder="Email.." onkeyup="filtrarTabla('fillEm', 'usuarios',3)" id="fillEm"></td>
                     <td><input type="text" placeholder="Telefono.." onkeyup="filtrarTabla('fillTel', 'usuarios',4)" id="fillTel"></td>
                     <td style="display: none;"></td>
                     <td style="display: none;"></td>
                     <td style="display: none;"></td>
                  </tr>

                  <tr>
                     <th onclick="ordenarTabla(0,'usuarios')"  class="bg-primary">Id</th>
                     <th onclick="ordenarTabla(1,'usuarios')"  class="bg-info">DNI</th>
                     <th onclick="ordenarTabla(2,'usuarios')" class="bg-success">Usuario</th>
                     <th onclick="ordenarTabla(3,'usuarios')" style="background-color: rgb(201, 175, 32);;">Email</th>
                     <th onclick="ordenarTabla(4,'usuarios')" class="bg-warning">Telefono</th>
                     <th onclick="ordenarTabla(5,'usuarios')"  style="background-color: pink;"> Rol</th>
                     <th class="bg-danger">Foto</th>
                     <!-- Añadimos una columna para las operaciones que podremos realizar con cada registro -->
                     <td style="background-color: #cd5afa;;">Operaciones</th>
                  </tr>
                  <!--Los datos a listar están almacenados en $parametros["datos"], que lo recibimos del controlador-->
                  <?php foreach ($datos as $d): ?>
                  <!--Mostramos cada registro en una fila de la tabla-->
                  <tr>
                     <td class="bg-primary"><?=$d["usuario_id"]?></td>
                     <td class="bg-info"><?=$d["nif"]?></td>
                     <td class="bg-success"><?=$d["login"]?></td>
                     <td style="background-color: rgb(201, 175, 32);"><?=$d["email"]?></td>
                     <td class="bg-warning"><?=$d["telefono"]?></td>

                     <?php if ($d["rol_id"] == 1): ?>
                     <td style="background-color: pink;">Admin</td>
                     <?php else: ?>
                     <td style="background-color: pink;">Socio</td>
                     <?php endif;?>

                     <?php if ($d["imagen"] !== null): ?>
                     <td class="bg-danger"><img src="assets/fotos/<?=$d['imagen']?>" width="40" /></td>
                     <?php else: ?>
                     <td class="bg-danger">----</td>
                     <?php endif;?>
                     <!-- Enviamos a actuser.php, mediante GET, el id del registro que deseamos editar o eliminar: -->
                     <td style="background-color: #cd5afa;" id="iconosTabla">

                        <a href="?controller=user&accion=cambiarEstado&id=<?=$d['usuario_id'];
if ($d['estado'] == 1) {echo '&cambio=desactivar';} else {echo '&cambio=activar';}?>">
                           <i class="<?php if ($d['estado'] == 1) {echo 'fa fa-toggle-on';} else {echo 'fa fa-toggle-off';}?>"
                              aria-hidden="true"></i>
                        </a>

                        <a href=" ?controller=user&accion=actuser&id=<?=$d['usuario_id']?>">
                           <img src="assets/images/edit.svg" alt="Editar usuario">
                        </a>
                        <a href="?controller=user&accion=deluser&id=<?=$d['usuario_id']?>">
                           <img src="assets/images/delete.svg" alt="Eliminar usuario">
                        </a>
                     </td>
                  </tr>
                  <?php endforeach;?>

               </table>

               <div class="row" id="botonesLista">
                  <div class="form-group col-md-6">
                     <input type="submit" value="NUEVO" name="añadir" id="btRegistro"
                        onclick="location.href='?controller=user&accion=adduser'">
                  </div>

                  <a data-scroll href="?controller=home&accion=index" id="logo">
                     <img src="assets/images/pdf.svg" alt="Pasar a pdf">
                  </a>
               </div>

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

   <script  type="text/javascript" src="assets/js/scriptTablas.js"></script>


</body>

</html>