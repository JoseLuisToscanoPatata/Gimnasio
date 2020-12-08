/*
function ordenarTabla(columna, nomTabla) {
	var tabla,
		numLineas,
		cambiando,
		linSiguiente,
		linActual,
		linSiguiente,
		cambiar,
		sentido,
		cambiadas = 0;
	tabla = document.getElementById(nomTabla); //Obtenemos la tabla con id indicada
	cambiando = true;
	// Establecemos por defecto el sentido a ascendente
	sentido = 'asc';
	//Este bucle se repite hasta que dejen de cambiarse posiciones de filas, lo cual significará que se ha terminado de ordenar//
	while (cambiando) {
		// Establecemos por defecto que no hemos realizado cambios
		cambiando = false;
		numLineas = tabla.rows; //Obtenemos el número de filas
		// Recorremos todas las filas de la tabla, a excepción de la primera, pues esta contiene el nombre de los campos
		for (i = 2; i < numLineas.length - 1; i++) {
			// Empezamos estableciendo que no haya que cambiar
			cambiar = false;
			// Obtenemos los campos de la fila actual y la siguiente, para compararlas
			linActual = numLineas[i].getElementsByTagName('TD')[columna];
			linSiguiente = numLineas[i + 1].getElementsByTagName('TD')[columna];
			// Comprueba si las filas deberían intercambiarse, según el sentido establecido y el contenido de cada campo

      if (columna == 0 || columna == 5) {
        $primera = parseInt(linActual.innerHTML.toLowerCase());
        $segunda = parseInt(linSiguiente.innerHTML.toLowerCase());
        if (sentido == 'asc') {
          if ($primera > $segunda) {
            // Establecemos que haya que cambiar, y nos salimos del bucle for para hacer el cambio
            cambiar = true;
            break;
          }
        } else if (sentido == 'desc') {
          if ($primera < $segunda) {
            // Establecemos que haya que cambiar, y nos salimos del bucle for para hacer el cambio
            cambiar = true;
            break;
          }
        }
      } else {

        if (sentido == 'asc') {
          if (linActual.innerHTML.toLowerCase() > linSiguiente.innerHTML.toLowerCase()) {
            // Establecemos que haya que cambiar, y nos salimos del bucle for para hacer el cambio
            cambiar = true;
            break;
          }
        } else if (sentido == 'desc') {
          if (linActual.innerHTML.toLowerCase() < linSiguiente.innerHTML.toLowerCase()) {
            // Establecemos que haya que cambiar, y nos salimos del bucle for para hacer el cambio
            cambiar = true;
            break;
          }
        }
      }
		}
		if (cambiar) {
			//Si hemos establecido que haya que cambiar filas, hacemos el cambio y decimos que hemos cambiado una fila, para así seguir el bucle
			numLineas[i].parentNode.insertBefore(numLineas[i + 1], numLineas[i]);
			cambiando = true;
			//Cada vez que hayamos cambiado una, aumentamos el valor
			cambiadas++;
		} else {
			//Si el sentido es ascendente y no hemos realizado ningún en la primera vez, será porque ya están ordenadas y lo que queremos es ordenarlas al contrario
      	//Por lo que cambiamos el sentido y volvemos a recorrer el bucle para ordenarlas.
			if (cambiadas == 0 && sentido == 'asc') {
				sentido = 'desc';
				cambiando = true;
			}
		}
	}
}
*/


/**
 * 
 * @param {*} columna Columna por la que vamos a filtrar (nombre)
 * @param {*} idTabla Tabla que vamos a filtrar
 * @param {*} numCol Número de columna por la que filtramos
 */
function filtrarTabla(columna, idTabla, numCol) {
	// Declare variables
	var input, filter, table, tr, td, i, txtValue;
	input = document.getElementById(columna); //Cogemos la columna en cuestión
	filter = input.value.toUpperCase();
	table = document.getElementById(idTabla); //Cogemos la tabla
	tr = table.getElementsByTagName('tr');//Cogemos todos los tr de la tabla

	// Recorremos todas las filas, empezando por la segunda (omitiendo la cabecera)
	for (i = 2; i < tr.length; i++) {
		td = tr[i].getElementsByTagName('td')[numCol]; //Obtenemos el contenido el campo td de la columna en cuestión
		if (td) { //Si existe el dicho..
			txtValue = td.textContent || td.innerText; //Obtenemos el contenido de este
			if (txtValue.toUpperCase().indexOf(filter) > -1) { //Mostramos o no la fila, según si encontramos el índice del filtro introducido
				tr[i].style.display = '';
			} else {
				tr[i].style.display = 'none';
			}
		}
	}
}
