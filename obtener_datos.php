<?php
session_start();
$host="localhost";
$bd="sitio";
$usuario="root";
$contrasenia="";

// Realizar la conexión a la base de datos
$conexion = new PDO("mysql:host=$host;dbname=$bd", $usuario, $contrasenia);

// Verificar si la conexión fue exitosa y si necesitamos llenar
if($_SESSION['llenar'] == 'si'){
  if(isset($_SESSION['rfid'])){
      // Consultar el último valor ingresado en la tabla
      $sql = $conexion -> prepare("SELECT * FROM biodi ORDER BY id DESC LIMIT 1");
      $sql->execute();
      
      $row = $sql->fetch(PDO::FETCH_LAZY);
      $valor = $row["rfid"];

      // Crear una respuesta en formato JSON
      $response = array(
          "status" => "success",
          "valor" => $valor
      );


      // Enviar la respuesta al cliente en formato JSON
      header("Content-Type: application/json");
      echo json_encode($response);
  }
 /* else{
      //recien vamos a tomar la primera medición
      $sql = $conexion -> prepare("SELECT * FROM biodi ORDER BY id DESC LIMIT 2");
      $sql->execute();
      
      $row = $resultado->fetch(PDO::FETCH_LAZY);
      if ($resultado->num_rows > 0) {
          $row = $resultado->fetch(PDO::FETCH_LAZY);
          $valor = $row["valor"];
        
          // Crear una respuesta en formato JSON
          $response = array(
            "status" => "success",
            "valor" => $valor
          );
        } else {
          // Si no hay datos, enviar una respuesta en formato JSON indicando que no hay valores disponibles
          $response = array(
            "status" => "error",
            "valor" => "No hay valores disponibles"
          );
      }
  }*/
}
?>
