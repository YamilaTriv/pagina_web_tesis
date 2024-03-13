<?php 
	session_start();
	if(!isset($_SESSION['user'])){
		header("location: login.php");	exit();
	}

	if(isset($_GET['logout'])){
		unset($_SESSION['user']);
		header("location: login.php");	exit();
	}

    function hex2string($hex_string) {
        $str = array();
      // Extrae los primeros tres caracteres hexadecimales
        $parte_entera = hexdec(substr($hex_string, 0, 2)); // Los dos primeros caracteres
        $parte_decimal = hexdec(substr($hex_string, 2, 1)); // El tercer caracter
        // Combina la parte entera y la parte decimal para obtener la temperatura
        $temperatura1 = $parte_entera + ($parte_decimal / 10.0);
        $parte_entera = hexdec(substr($hex_string, 3, 2)); // Los dos primeros caracteres
        $parte_decimal = hexdec(substr($hex_string, 5, 1)); // El tercer caracter
        // Combina la parte entera y la parte decimal para obtener la temperatura
        $temperatura2 = $parte_entera + ($parte_decimal / 10.0);

        $hexHumedad = substr($hex_string, 6, 2);
        $bin1 = base_convert($hexHumedad, 16, 2);
        $bin1 = str_pad($bin1, 8, '0', STR_PAD_LEFT);
        $humedad1 = bindec(substr($bin1, 0, 7)); // 7 cifras altas

        $hexHumedad2 = substr($hex_string, 7, 3);
        $bin2 = base_convert($hexHumedad2, 16, 2);
        $bin2 = str_pad($bin2, 12, '0', STR_PAD_LEFT);
        $humedad2 = bindec(substr($bin2, 3, 7)); // 7 cifras altas

        $hexHumedadSuelo1 = substr($hex_string, 9, 3);
        $bin1 = base_convert($hexHumedadSuelo1, 16, 2);
        $bin1 = str_pad($bin1, 12, '0', STR_PAD_LEFT);
        $humedadSuelo1 = bindec(substr($bin1, 2, 7)); // 7 cifras altas


        $hexHumedadSuelo2 = substr($hex_string, 12, 2);
        $bin2 = base_convert($hexHumedadSuelo2, 16, 2);
        $bin2 = str_pad($bin2, 8, '0', STR_PAD_LEFT);
        $humedadSuelo2 = bindec(substr($bin2, 1, 7)); // 7 cifras altas

        $hexPresion1 = substr($hex_string, 13, 3);
        $bin2 = base_convert($hexPresion1, 16, 2);
        $bin2 = str_pad($bin2, 12, '0', STR_PAD_LEFT);
        $presion1 = bindec(substr($bin2, 0, 10)); // 7 cifras altas

        $hexPresion2 = substr($hex_string, 15, 3);
        $bin2 = base_convert($hexPresion2, 16, 2);
        $bin2 = str_pad($bin2, 12, '0', STR_PAD_LEFT);
        $presion2 = bindec(substr($bin2, 2, 10)); // 7 cifras altas

        $str[0] = $temperatura1; 
        $str[1] = $temperatura2;
        $str[2] = $humedad1;
        $str[3] = $humedad2;
        $str[4] = $humedadSuelo1;
        $str[5] = $humedadSuelo2;
        $str[6] = $presion1;
        $str[7] = $presion2;
      return $str;
    }
      $url = "http://3.18.181.47:8000/posts/803238";
      $json = file_get_contents($url);
      $obj = json_decode($json);
      foreach($obj as $o){
        $fecha_objeto = new DateTime($o->created_at);

        // Restar 3 horas
        $fecha_objeto->sub(new DateInterval('PT3H'));

        // Formatear la nueva fecha en el mismo formato
        $fecha_formateada = $fecha_objeto->format("Y-m-d\TH:i:s.u\Z");
        $o->created_at = $fecha_formateada;
      }
// Verificar si la decodificación fue exitosa
      if ($obj === null && json_last_error() !== JSON_ERROR_NONE) {
          // Manejar el error si la decodificación falló
          echo "Error al decodificar el JSON";
      } else {
          // Ordenar los elementos por el atributo "seqNumber"
          usort($obj, function($a, $b) {
              return $a->seqNumber - $b->seqNumber;
          });
          // Crear un nuevo objeto JSON con los datos ordenados
          $sortedJson = json_encode($obj);
        $obj = json_decode($sortedJson);
      }

 ?>

<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
	<link rel="stylesheet" href="styles.css">
  <link rel="stylesheet" href="contacto.css">  

  <title>Gráficas de Humedad, Temperatura y Polvosidad</title>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <link rel="stylesheet"href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
      <!-- Incluye íconos de Material Icons -->
      <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  </head>
<body>
  <nav>
    <div class="nav-wrapper">
      <a href="datos_cultivos.php" class="brand-logo">
        <img class="logo" src="img/1_utec.png" alt="Logo de mi página">
        <img class="logo" src="img/inia.png" alt="Logo INIA">
        <img class="logo" src="img/3_utec.png" alt="Logo IMEC">
      </a>
      <ul class="right hide-on-med-and-down">
        <li><a href="datos_cultivos.php">Inicio</a></li>
        <li><a href="contacto.php">Contacto</a></li>
      </ul>
    </div>
  </nav>
	<div class="content-datos">
		<header>
		<!--	<h2>Bienvenido <?php echo $_SESSION['user']; ?><h2>
			<a href="?logout">Cerrar sesión</a>	-->
		</header>

		<main>
			<!--<h3>Some user actions ......</h3>-->
      <h4>Gráficas de Humedad, Temperatura y Presión Atmosférica</h4>
      <div class="container">
          <div class="row">
              <div class="input-field col s6">
                  <input type="text" id="fecha_inicio" class="datepicker">
                  <label for="fecha_inicio">Fecha de inicio</label>
              </div>
              <div class="input-field col s6">
                  <input type="text" id="fecha_fin" class="datepicker">
                  <label for="fecha_fin">Fecha de fin</label>
              </div>
          </div>
      </div>
      <div class="row">
          <div class="input-field col s12">
              <button class="btn waves-effect waves-light" id="btnGenerar" type="button">Generar Gráfico
                  <i class="material-icons right">send</i>
              </button>
          </div>
      </div>
      <div>
         <canvas id="humedad-suelo1"></canvas>
      </div>
      <div>
         <canvas id="humedad-suelo2"></canvas>
      </div>
      <div>
         <canvas id="temperatura1"></canvas>
      </div>
      <div>
         <canvas id="temperatura2"></canvas>
      </div>
      <div>
          <canvas id="humedad-ambiente1"></canvas>
      </div>
      <div>
          <canvas id="humedad-ambiente2"></canvas>
      </div>
      <div>
          <canvas id="presion-atmosferica1"></canvas>
      </div>
      <div>
          <canvas id="presion-atmosferica2"></canvas>
      </div>
      <script>
        
    
    var humedadSuelo1 = new Chart(document.getElementById("humedad-suelo1"), {
      type: 'line',
      data: {
        labels: [
          <?php
          
          foreach($obj as $o){
            $fecha_hora = $o->created_at;
            $fecha_formateada = date("d/m/Y H:i:s", strtotime($fecha_hora));
            echo "\"" . $fecha_formateada . "\"" . ", ";
          }
          ?>
        ],
        datasets: [{
          label: 'Humedad del Suelo 1',
          data: [
            <?php
                foreach($obj as $o){
                  $str = hex2string($o->data);
                  echo $str[4] . ", ";
                }
            ?>
          ],
          backgroundColor: 'rgba(0, 255, 0, 0.2)',
          borderColor: 'rgba(0, 255, 0, 1)',
          borderWidth: 1
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
          y: {
              max: 100, // Establecer el límite superior del eje y en 1100
              min: 0, // Puedes establecer un límite inferior si lo deseas
              ticks: {
                  stepSize: 50 // Opcional: Puedes establecer el tamaño del paso de los ticks del eje y
              }
          }
        }
      }
    });
        
    var humedadSuelo2 = new Chart(document.getElementById("humedad-suelo2"), {
      type: 'line',
      data: {
        labels: [
          <?php

          foreach($obj as $o){
            $fecha_hora = $o->created_at;
            $fecha_formateada = date("d/m/Y H:i:s", strtotime($fecha_hora));
            echo "\"" . $fecha_formateada . "\"" . ", ";
          }
          ?>
        ],
        datasets: [{
          label: 'Humedad del Suelo 2',
          data: [
            <?php
                foreach($obj as $o){
                  $str = hex2string($o->data);
                  echo $str[5] . ", ";
                }
            ?>
          ],
          backgroundColor: 'rgba(0, 255, 0, 0.2)',
          borderColor: 'rgba(0, 255, 0, 1)',
          borderWidth: 1
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
          y: {
              max: 100, // Establecer el límite superior del eje y en 1100
              min: 0, // Puedes establecer un límite inferior si lo deseas
              ticks: {
                  stepSize: 50 // Opcional: Puedes establecer el tamaño del paso de los ticks del eje y
              }
          }
        }
      }
    });
        
    var temperatura1 = new Chart(document.getElementById("temperatura1"), {
      type: 'line',
      data: {
        labels: [
          <?php

          foreach($obj as $o){
            $fecha_hora = $o->created_at;
            $fecha_formateada = date("d/m/Y H:i:s", strtotime($fecha_hora));
            echo "\"" . $fecha_formateada . "\"" . ", ";
          }
          ?>
        ],
        datasets: [{
          label: 'Temperatura 1',
          data: [
            <?php
                foreach($obj as $o){
                  $str = hex2string($o->data);
                  echo $str[0] . ", ";
                }
            ?>
          ],
          backgroundColor: 'rgba(255, 0, 0, 0.2)',
          borderColor: 'rgba(255, 0, 0, 1)',
          borderWidth: 1
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
         
        }
      }
    });

    var temperatura2 = new Chart(document.getElementById("temperatura2"), {
      type: 'line',
      data: {
        labels: [
          <?php

          foreach($obj as $o){
            $fecha_hora = $o->created_at;
            $fecha_formateada = date("d/m/Y H:i:s", strtotime($fecha_hora));
            echo "\"" . $fecha_formateada . "\"" . ", ";
          }
          ?>
        ],
        datasets: [{
          label: 'Temperatura 2',
          data: [
            <?php
                foreach($obj as $o){
                  $str = hex2string($o->data);
                  echo $str[1] . ", ";
                }
            ?>
          ],
          backgroundColor: 'rgba(255, 0, 0, 0.2)',
          borderColor: 'rgba(255, 0, 0, 1)',
          borderWidth: 1
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {

        }
      }
    });
        
    var humedadAmbiente1 = new Chart(document.getElementById("humedad-ambiente1"), {
      type: 'line',
      data: {
        labels: [
          <?php

          foreach($obj as $o){
            $fecha_hora = $o->created_at;
            $fecha_formateada = date("d/m/Y H:i:s", strtotime($fecha_hora));
            echo "\"" . $fecha_formateada . "\"" . ", ";
          }
          ?>
        ],
        datasets: [{
          label: 'Humedad Ambiente 1',
          data: [
            <?php
                foreach($obj as $o){
                  $str = hex2string($o->data);
                  echo $str[2] . ", ";
                }
            ?>
          ],
          backgroundColor: 'rgba(0, 0, 255, 0.2)',
          borderColor: 'rgba(0, 0, 255, 1)',
          borderWidth: 1
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
          y: {
              max: 100, // Establecer el límite superior del eje y en 1100
              min: 0, // Puedes establecer un límite inferior si lo deseas
              ticks: {
                  stepSize: 50 // Opcional: Puedes establecer el tamaño del paso de los ticks del eje y
              }
          }
        }
      }
    });

    var humedadAmbiente2 = new Chart(document.getElementById("humedad-ambiente2"), {
      type: 'line',
      data: {
        labels: [
          <?php

          foreach($obj as $o){
            $fecha_hora = $o->created_at;
            $fecha_formateada = date("d/m/Y H:i:s", strtotime($fecha_hora));
            echo "\"" . $fecha_formateada . "\"" . ", ";
          }
          ?>
        ],
        datasets: [{
          label: 'Humedad Ambiente 2',
          data: [
            <?php
                foreach($obj as $o){
                  $str = hex2string($o->data);
                  echo $str[3] . ", ";
                }
            ?>
          ],
          backgroundColor: 'rgba(0, 0, 255, 0.2)',
          borderColor: 'rgba(0, 0, 255, 1)',
          borderWidth: 1
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
          y: {
              max: 100, // Establecer el límite superior del eje y en 1100
              min: 0, // Puedes establecer un límite inferior si lo deseas
              ticks: {
                  stepSize: 50 // Opcional: Puedes establecer el tamaño del paso de los ticks del eje y
              }
          }
        }
      }
    });

    var presionAtmosferica1 = new Chart(document.getElementById("presion-atmosferica1"), {
      type: 'line',
      data: {
        labels: [
          <?php

          foreach($obj as $o){
            $fecha_hora = $o->created_at;
            $fecha_formateada = date("d/m/Y H:i:s", strtotime($fecha_hora));
            echo "\"" . $fecha_formateada . "\"" . ", ";
          }
          ?>
        ],
        datasets: [{
          label: 'Presión Atmosférica 1',
          data: [
            <?php
                foreach($obj as $o){
                  $str = hex2string($o->data);
                  echo $str[6] . ", ";
                }
            ?>
          ],
          backgroundColor: 'rgba(255, 0, 255, 0.2)',
          borderColor: 'rgba(255, 0, 255, 1)',
          borderWidth: 1
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
          y: {
              max: 1100, // Establecer el límite superior del eje y en 1100
              min: 0, // Puedes establecer un límite inferior si lo deseas
              ticks: {
                  stepSize: 500 // Opcional: Puedes establecer el tamaño del paso de los ticks del eje y
              }
          }
        }
      }
    });

    var presionAtmosferica2 = new Chart(document.getElementById("presion-atmosferica2"), {
      type: 'line',
      data: {
        labels: [
          <?php

          foreach($obj as $o){
            $fecha_hora = $o->created_at;
            $fecha_formateada = date("d/m/Y H:i:s", strtotime($fecha_hora));
            echo "\"" . $fecha_formateada . "\"" . ", ";
          }
          ?>
        ],  
        datasets: [{
          label: 'Presión Atmosférica 2',
          data: [
            <?php
                foreach($obj as $o){
                  $str = hex2string($o->data);
                  echo $str[7] . ", ";
                }
            ?>
          ],
          backgroundColor: 'rgba(255, 0, 255, 0.2)',
          borderColor: 'rgba(255, 0, 255, 1)',
          borderWidth: 1
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
                  y: {
                      max: 1100, // Establecer el límite superior del eje y en 1100
                      min: 0, // Puedes establecer un límite inferior si lo deseas
                      ticks: {
                          stepSize: 500 // Opcional: Puedes establecer el tamaño del paso de los ticks del eje y
                      }
                  }
        }
      }
    });
        var originalHumedadSuelo1 = humedadSuelo1.data;
        var originalHumedadSuelo2 = humedadSuelo2.data;
        var originalTemperatura1 = temperatura1.data;
        var originalTemperatura2 = temperatura2.data;
        var originalHumedadAmbiente1 = humedadAmbiente1.data;
        var originalHumedadAmbiente2 = humedadAmbiente2.data;
        var originalPresionAtmosferica1 = presionAtmosferica1.data;
        var originalPresionAtmosferica2 = presionAtmosferica2.data;
    
  </script>  
      <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
      <!-- Incluye Materialize JS -->
      <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
      <script>    
        $(document).ready(function(){
            var fecha_inicio, fecha_fin;
            // Configuración del datepicker
            $('.datepicker').datepicker({
                onSelect: function() {
                    // No necesitas obtener la fecha seleccionada aquí
                    // La acción se realizará cuando se haga clic en el botón
                }
            });

            // Agregar evento al botón
            $('#btnGenerar').click(function() {
                // Obtener los valores de los campos de fecha
                fecha_inicio = $("#fecha_inicio").val();
                fecha_fin = $("#fecha_fin").val();

                // Verificar si se han seleccionado ambas fechas
                if (fecha_inicio && fecha_fin) {
                    // Realizar la acción que desees
                    console.log("Fechas seleccionadas:", fecha_inicio, fecha_fin);
                  
                    var fechaInicio = new Date($('#fecha_inicio').val());
                    var fechaFin = new Date($('#fecha_fin').val());
                    
                    var data = originalHumedadSuelo1;
                    var labels = data.labels;
                    var datasets = data.datasets;

                    // Filtrar los datos del eje x (fechas) entre fechaInicio y fechaFin
                    var datosFiltrados = {
                        labels: [],
                        datasets: []
                    };

                    for (var i = 0; i < labels.length; i++) {
                        var fechaStr = labels[i];
                        var partesFecha = fechaStr.split(" "); // Separar la cadena en fecha y hora
                        var fechaPartes = partesFecha[0].split("/"); // Separar la parte de la fecha
                        var horaPartes = partesFecha[1].split(":"); // Separar la parte de la hora

                        // Crear el objeto Date
                        var fechaActual = new Date(
                            parseInt(fechaPartes[2]),   // Año
                            parseInt(fechaPartes[1]) - 1,  // Mes (restamos 1 porque los meses van de 0 a 11)
                            parseInt(fechaPartes[0]),   // Día
                            parseInt(horaPartes[0]),     // Hora
                            parseInt(horaPartes[1]),     // Minuto
                            parseInt(horaPartes[2])      // Segundo
                        );
                        //var fechaActual = new Date(labels[i]);
                        if (fechaActual >= fechaInicio && fechaActual <= fechaFin) {
                            datosFiltrados.labels.push(labels[i]);
                            for (var j = 0; j < datasets.length; j++) {
                                if (!datosFiltrados.datasets[j]) {
                                    datosFiltrados.datasets[j] = {
                                        label: datasets[j].label,
                                        data: [],
                                        backgroundColor: datasets[j].backgroundColor,
                                        borderColor: datasets[j].borderColor,
                                        borderWidth: datasets[j].borderWidth
                                    };
                                }
                                datosFiltrados.datasets[j].data.push(datasets[j].data[i]);
                            }
                        }
                      }

                    // Actualizar el gráfico con los datos filtrados
                      humedadSuelo1.data = datosFiltrados;
                      humedadSuelo1.update();

                  var data = originalHumedadSuelo2;
                  var labels = data.labels;
                  var datasets = data.datasets;

                  // Filtrar los datos del eje x (fechas) entre fechaInicio y fechaFin
                  var datosFiltrados = {
                      labels: [],
                      datasets: []
                  };

                  for (var i = 0; i < labels.length; i++) {
                      var fechaStr = labels[i];
                      var partesFecha = fechaStr.split(" "); // Separar la cadena en fecha y hora
                      var fechaPartes = partesFecha[0].split("/"); // Separar la parte de la fecha
                      var horaPartes = partesFecha[1].split(":"); // Separar la parte de la hora

                      // Crear el objeto Date
                      var fechaActual = new Date(
                          parseInt(fechaPartes[2]),   // Año
                          parseInt(fechaPartes[1]) - 1,  // Mes (restamos 1 porque los meses van de 0 a 11)
                          parseInt(fechaPartes[0]),   // Día
                          parseInt(horaPartes[0]),     // Hora
                          parseInt(horaPartes[1]),     // Minuto
                          parseInt(horaPartes[2])      // Segundo
                      );
                      //var fechaActual = new Date(labels[i]);
                      if (fechaActual >= fechaInicio && fechaActual <= fechaFin) {
                          datosFiltrados.labels.push(labels[i]);
                          for (var j = 0; j < datasets.length; j++) {
                              if (!datosFiltrados.datasets[j]) {
                                  datosFiltrados.datasets[j] = {
                                      label: datasets[j].label,
                                      data: [],
                                      backgroundColor: datasets[j].backgroundColor,
                                      borderColor: datasets[j].borderColor,
                                      borderWidth: datasets[j].borderWidth
                                  };
                              }
                              datosFiltrados.datasets[j].data.push(datasets[j].data[i]);
                          }
                      }
                    }

                  // Actualizar el gráfico con los datos filtrados
                    humedadSuelo2.data = datosFiltrados;
                    humedadSuelo2.update();

                  var data = originalTemperatura1;
                  var labels = data.labels;
                  var datasets = data.datasets;

                  // Filtrar los datos del eje x (fechas) entre fechaInicio y fechaFin
                  var datosFiltrados = {
                      labels: [],
                      datasets: []
                  };

                  for (var i = 0; i < labels.length; i++) {
                      var fechaStr = labels[i];
                      var partesFecha = fechaStr.split(" "); // Separar la cadena en fecha y hora
                      var fechaPartes = partesFecha[0].split("/"); // Separar la parte de la fecha
                      var horaPartes = partesFecha[1].split(":"); // Separar la parte de la hora

                      // Crear el objeto Date
                      var fechaActual = new Date(
                          parseInt(fechaPartes[2]),   // Año
                          parseInt(fechaPartes[1]) - 1,  // Mes (restamos 1 porque los meses van de 0 a 11)
                          parseInt(fechaPartes[0]),   // Día
                          parseInt(horaPartes[0]),     // Hora
                          parseInt(horaPartes[1]),     // Minuto
                          parseInt(horaPartes[2])      // Segundo
                      );
                      //var fechaActual = new Date(labels[i]);
                      if (fechaActual >= fechaInicio && fechaActual <= fechaFin) {
                          datosFiltrados.labels.push(labels[i]);
                          for (var j = 0; j < datasets.length; j++) {
                              if (!datosFiltrados.datasets[j]) {
                                  datosFiltrados.datasets[j] = {
                                      label: datasets[j].label,
                                      data: [],
                                      backgroundColor: datasets[j].backgroundColor,
                                      borderColor: datasets[j].borderColor,
                                      borderWidth: datasets[j].borderWidth
                                  };
                              }
                              datosFiltrados.datasets[j].data.push(datasets[j].data[i]);
                          }
                      }
                    }

                  // Actualizar el gráfico con los datos filtrados
                    temperatura1.data = datosFiltrados;
                    temperatura1.update();

                  var data = originalTemperatura2;
                  var labels = data.labels;
                  var datasets = data.datasets;

                  // Filtrar los datos del eje x (fechas) entre fechaInicio y fechaFin
                  var datosFiltrados = {
                      labels: [],
                      datasets: []
                  };

                  for (var i = 0; i < labels.length; i++) {
                      var fechaStr = labels[i];
                      var partesFecha = fechaStr.split(" "); // Separar la cadena en fecha y hora
                      var fechaPartes = partesFecha[0].split("/"); // Separar la parte de la fecha
                      var horaPartes = partesFecha[1].split(":"); // Separar la parte de la hora

                      // Crear el objeto Date
                      var fechaActual = new Date(
                          parseInt(fechaPartes[2]),   // Año
                          parseInt(fechaPartes[1]) - 1,  // Mes (restamos 1 porque los meses van de 0 a 11)
                          parseInt(fechaPartes[0]),   // Día
                          parseInt(horaPartes[0]),     // Hora
                          parseInt(horaPartes[1]),     // Minuto
                          parseInt(horaPartes[2])      // Segundo
                      );
                      //var fechaActual = new Date(labels[i]);
                      if (fechaActual >= fechaInicio && fechaActual <= fechaFin) {
                          datosFiltrados.labels.push(labels[i]);
                          for (var j = 0; j < datasets.length; j++) {
                              if (!datosFiltrados.datasets[j]) {
                                  datosFiltrados.datasets[j] = {
                                      label: datasets[j].label,
                                      data: [],
                                      backgroundColor: datasets[j].backgroundColor,
                                      borderColor: datasets[j].borderColor,
                                      borderWidth: datasets[j].borderWidth
                                  };
                              }
                              datosFiltrados.datasets[j].data.push(datasets[j].data[i]);
                          }
                      }
                    }

                  // Actualizar el gráfico con los datos filtrados
                    temperatura2.data = datosFiltrados;
                    temperatura2.update();

                  var data = originalHumedadAmbiente1;
                  var labels = data.labels;
                  var datasets = data.datasets;

                  // Filtrar los datos del eje x (fechas) entre fechaInicio y fechaFin
                  var datosFiltrados = {
                      labels: [],
                      datasets: []
                  };

                  for (var i = 0; i < labels.length; i++) {
                      var fechaStr = labels[i];
                      var partesFecha = fechaStr.split(" "); // Separar la cadena en fecha y hora
                      var fechaPartes = partesFecha[0].split("/"); // Separar la parte de la fecha
                      var horaPartes = partesFecha[1].split(":"); // Separar la parte de la hora

                      // Crear el objeto Date
                      var fechaActual = new Date(
                          parseInt(fechaPartes[2]),   // Año
                          parseInt(fechaPartes[1]) - 1,  // Mes (restamos 1 porque los meses van de 0 a 11)
                          parseInt(fechaPartes[0]),   // Día
                          parseInt(horaPartes[0]),     // Hora
                          parseInt(horaPartes[1]),     // Minuto
                          parseInt(horaPartes[2])      // Segundo
                      );
                      //var fechaActual = new Date(labels[i]);
                      if (fechaActual >= fechaInicio && fechaActual <= fechaFin) {
                          datosFiltrados.labels.push(labels[i]);
                          for (var j = 0; j < datasets.length; j++) {
                              if (!datosFiltrados.datasets[j]) {
                                  datosFiltrados.datasets[j] = {
                                      label: datasets[j].label,
                                      data: [],
                                      backgroundColor: datasets[j].backgroundColor,
                                      borderColor: datasets[j].borderColor,
                                      borderWidth: datasets[j].borderWidth
                                  };
                              }
                              datosFiltrados.datasets[j].data.push(datasets[j].data[i]);
                          }
                      }
                    }

                  // Actualizar el gráfico con los datos filtrados
                      humedadAmbiente1.data = datosFiltrados;
                      humedadAmbiente1.update();

                  var data = originalHumedadAmbiente2;
                  var labels = data.labels;
                  var datasets = data.datasets;

                  // Filtrar los datos del eje x (fechas) entre fechaInicio y fechaFin
                  var datosFiltrados = {
                      labels: [],
                      datasets: []
                  };

                  for (var i = 0; i < labels.length; i++) {
                      var fechaStr = labels[i];
                      var partesFecha = fechaStr.split(" "); // Separar la cadena en fecha y hora
                      var fechaPartes = partesFecha[0].split("/"); // Separar la parte de la fecha
                      var horaPartes = partesFecha[1].split(":"); // Separar la parte de la hora

                      // Crear el objeto Date
                      var fechaActual = new Date(
                          parseInt(fechaPartes[2]),   // Año
                          parseInt(fechaPartes[1]) - 1,  // Mes (restamos 1 porque los meses van de 0 a 11)
                          parseInt(fechaPartes[0]),   // Día
                          parseInt(horaPartes[0]),     // Hora
                          parseInt(horaPartes[1]),     // Minuto
                          parseInt(horaPartes[2])      // Segundo
                      );
                      //var fechaActual = new Date(labels[i]);
                      if (fechaActual >= fechaInicio && fechaActual <= fechaFin) {
                          datosFiltrados.labels.push(labels[i]);
                          for (var j = 0; j < datasets.length; j++) {
                              if (!datosFiltrados.datasets[j]) {
                                  datosFiltrados.datasets[j] = {
                                      label: datasets[j].label,
                                      data: [],
                                      backgroundColor: datasets[j].backgroundColor,
                                      borderColor: datasets[j].borderColor,
                                      borderWidth: datasets[j].borderWidth
                                  };
                              }
                              datosFiltrados.datasets[j].data.push(datasets[j].data[i]);
                          }
                      }
                    }

                  // Actualizar el gráfico con los datos filtrados
                      humedadAmbiente2.data = datosFiltrados;
                      humedadAmbiente2.update();

                  var data = originalPresionAtmosferica1;
                  var labels = data.labels;
                  var datasets = data.datasets;

                  // Filtrar los datos del eje x (fechas) entre fechaInicio y fechaFin
                  var datosFiltrados = {
                      labels: [],
                      datasets: []
                  };

                  for (var i = 0; i < labels.length; i++) {
                      var fechaStr = labels[i];
                      var partesFecha = fechaStr.split(" "); // Separar la cadena en fecha y hora
                      var fechaPartes = partesFecha[0].split("/"); // Separar la parte de la fecha
                      var horaPartes = partesFecha[1].split(":"); // Separar la parte de la hora

                      // Crear el objeto Date
                      var fechaActual = new Date(
                          parseInt(fechaPartes[2]),   // Año
                          parseInt(fechaPartes[1]) - 1,  // Mes (restamos 1 porque los meses van de 0 a 11)
                          parseInt(fechaPartes[0]),   // Día
                          parseInt(horaPartes[0]),     // Hora
                          parseInt(horaPartes[1]),     // Minuto
                          parseInt(horaPartes[2])      // Segundo
                      );
                      //var fechaActual = new Date(labels[i]);
                      if (fechaActual >= fechaInicio && fechaActual <= fechaFin) {
                          datosFiltrados.labels.push(labels[i]);
                          for (var j = 0; j < datasets.length; j++) {
                              if (!datosFiltrados.datasets[j]) {
                                  datosFiltrados.datasets[j] = {
                                      label: datasets[j].label,
                                      data: [],
                                      backgroundColor: datasets[j].backgroundColor,
                                      borderColor: datasets[j].borderColor,
                                      borderWidth: datasets[j].borderWidth
                                  };
                              }
                              datosFiltrados.datasets[j].data.push(datasets[j].data[i]);
                          }
                      }
                    }

                  // Actualizar el gráfico con los datos filtrados
                        presionAtmosferica1.data = datosFiltrados;
                        presionAtmosferica1.update();

                  var data = originalPresionAtmosferica2;
                  var labels = data.labels;
                  var datasets = data.datasets;

                  // Filtrar los datos del eje x (fechas) entre fechaInicio y fechaFin
                  var datosFiltrados = {
                      labels: [],
                      datasets: []
                  };

                  for (var i = 0; i < labels.length; i++) {
                      var fechaStr = labels[i];
                      var partesFecha = fechaStr.split(" "); // Separar la cadena en fecha y hora
                      var fechaPartes = partesFecha[0].split("/"); // Separar la parte de la fecha
                      var horaPartes = partesFecha[1].split(":"); // Separar la parte de la hora

                      // Crear el objeto Date
                      var fechaActual = new Date(
                          parseInt(fechaPartes[2]),   // Año
                          parseInt(fechaPartes[1]) - 1,  // Mes (restamos 1 porque los meses van de 0 a 11)
                          parseInt(fechaPartes[0]),   // Día
                          parseInt(horaPartes[0]),     // Hora
                          parseInt(horaPartes[1]),     // Minuto
                          parseInt(horaPartes[2])      // Segundo
                      );
                      //var fechaActual = new Date(labels[i]);
                      if (fechaActual >= fechaInicio && fechaActual <= fechaFin) {
                          datosFiltrados.labels.push(labels[i]);
                          for (var j = 0; j < datasets.length; j++) {
                              if (!datosFiltrados.datasets[j]) {
                                  datosFiltrados.datasets[j] = {
                                      label: datasets[j].label,
                                      data: [],
                                      backgroundColor: datasets[j].backgroundColor,
                                      borderColor: datasets[j].borderColor,
                                      borderWidth: datasets[j].borderWidth
                                  };
                              }
                              datosFiltrados.datasets[j].data.push(datasets[j].data[i]);
                          }
                      }
                    }

                  // Actualizar el gráfico con los datos filtrados
                        presionAtmosferica2.data = datosFiltrados;
                        presionAtmosferica2.update();
                  
                } else {
                    console.log("Por favor selecciona ambas fechas");
                }
            });
        });

        function filtrarDatosPorFecha(datos, fechaInicio, fechaFin) {
            // Filtrar los datos según el rango de fechas
            return datos.map(function(dataset) {
                return dataset.filter(function(data) {
                    var fechaData = new Date(data.fecha); // Suponiendo que cada dato tiene un atributo "fecha"
                    return fechaData >= fechaInicio && fechaData <= fechaFin;
                });
            });
        }
      </script>
      <!-- Tu código JavaScript y Chart.js -->
		</main>
	</div>

</body>
</html>

