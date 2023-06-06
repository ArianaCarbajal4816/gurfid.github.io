<?php 

session_start();
$_SESSION['rfid'] = 'hola';
$_SESSION['llenar'] = 'Si';
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script>
    $(document).ready(function() {
      console.log("El script se está ejecutando...");
      // Función para realizar la solicitud Ajax y actualizar la página
      function actualizarPagina() {
        console.log("Ejecutando la función actualizarPagina");
        $.ajax({
            url: 'http://localhost/sitioweb/obtener_datos.php', // Ruta al archivo PHP que procesará los datos
            type: 'GET',
            dataType: 'json',
          success: function(response) {
            console.log("Respuesta recibida del servidor");
            console.log(response);
            // Procesa la respuesta del servidor
            if (response.status === 'success') {
              // Actualiza los elementos HTML según los datos recibidos
              $('#dato1').text(response.valor);
            }
          }, 
          error: function(error){
            console.log(error);
          },
          complete: function() {
            console.log("Cargando de nuevo");
            console.log("Respuesta recibida del servidor");
            // Llama a la función de nuevo después de un cierto tiempo
            setTimeout(actualizarPagina, 1000); // 5000 milisegundos = 5 segundos
          }
        } ) ;
      }

      // Llama a la función por primera vez para iniciar el proceso de actualización
      actualizarPagina();
    } ) ;
  </script>
</head>
<body>
  <h1>Datos del ESP</h1>
  <div>
    <p>RFID: <span id="dato1"></span></p>
  </div>
</body>
</html>
