function ordenarTabla(columna, nomTabla) {
  var tabla, numLineas, cambiando, linSiguiente, linActual, linSiguiente, cambiar, sentido, cambiadas = 0;
  tabla = document.getElementById(nomTabla); //Obtenemos la tabla con id indicada
  cambiando = true;
  // Establecemos por defecto el sentido a ascendente
  sentido = "asc";
  //Este bucle se repite hasta que dejen de cambiarse posiciones de filas, lo cual significará que se ha terminado de ordenar//
  while (cambiando) {
    // Establecemos por defecto que no hemos realizado cambios
    cambiando = false;
    numLineas = tabla.rows; //Obtenemos el número de filas
    // Recorremos todas las filas de la tabla, a excepción de la primera, pues esta contiene el nombre de los campos 
    for (i = 2; i < (numLineas.length - 1); i++) {
      // Empezamos estableciendo que no haya que cambiar
      cambiar = false;
      // Obtenemos los campos de la fila actual y la siguiente, para compararlas
      linActual = numLineas[i].getElementsByTagName("TD")[columna];
      linSiguiente = numLineas[i + 1].getElementsByTagName("TD")[columna];
      // Comprueba si las filas deberían intercambiarse, según el sentido establecido y el contenido de cada campo
      if (sentido == "asc") {
        if (linActual.innerHTML.toLowerCase() > linSiguiente.innerHTML.toLowerCase()) {
          // Establecemos que haya que cambiar, y nos salimos del bucle for para hacer el cambio
          cambiar = true;
          break;
        }
      } else if (sentido == "desc") {
        if (linActual.innerHTML.toLowerCase() < linSiguiente.innerHTML.toLowerCase()) {
          // Establecemos que haya que cambiar, y nos salimos del bucle for para hacer el cambio
          cambiar = true;
          break;
        }
      }
    }
    if (cambiar) {
      //Si hemos establecido que haya que cambiar filas, hacemos el cambio y decimos que hemos cambiado una fila, para así seguir el bucle*/
      numLineas[i].parentNode.insertBefore(numLineas[i + 1], numLineas[i]);
      cambiando = true;
      //Cada vez que hayamos cambiado una, aumentamos el valor
      cambiadas ++;
    } else {
      /* Si el sentido es ascendente y no hemos realizado ningún en la primera vez, será porque ya están ordenadas y lo que queremos es ordenarlas al contrario
      Por lo que cambiamos el sentido y volvemos a recorrer el bucle para ordenarlas. */
      if (cambiadas == 0 && sentido == "asc") {
        sentido = "desc";
        cambiando = true;
      }
    }
  }
}

function filtrarTabla(columna, idTabla, numCol) {
  // Declare variables
  var input, filter, table, tr, td, i, txtValue;
  input = document.getElementById(columna);
  filter = input.value.toUpperCase();
  table = document.getElementById(idTabla);
   tr = table.getElementsByTagName("tr");

   for (i = 0; i < 5; i++) {
      if (i != numCol)
         busqueda = tr[0].getElementsByTagName("input")[i];
      busqueda.value = "";
   }
   
  // Loop through all table rows, and hide those who don't match the search query
   for (i = 2; i < tr.length; i++) {
     

           td = tr[i].getElementsByTagName("td")[numCol];
            if (td) {
               txtValue = td.textContent || td.innerText;
               if (txtValue.toUpperCase().indexOf(filter) > -1) {
                     tr[i].style.display = "";

               } else {
                  tr[i].style.display = "none";
               }
            }
      

   
  }
}

