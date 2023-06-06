<?php
/*session_start();
// Verificar si se recibieron datos por el método POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	if (isset($_POST["rfid"])) {
		// Leer los datos enviados por el ESP32
		$dato1 = $_POST["rfid"];
		// Realizar cualquier procesamiento adicional necesario con los datos recibidos

		// Crear una respuesta en formato JSON
		$response = array(
			"status" => "success",
			"mensaje" => "Datos recibidos correctamente"
		);

		// Enviar la respuesta al ESP32 en formato JSON
		header("Content-Type: application/json");
		echo json_encode($response);
	} else{
		// Si la clave "rfid" no está presente en $_POST, enviar una respuesta de error
		$response = array(
			"status" => "error",
			"mensaje" => "No se recibió el dato 'rfid' por POST"
		  );
		  
		header("Content-Type: application/json");
		echo json_encode($response);
	}
} else {
  // Si no se recibieron datos por POST, enviar una respuesta de error
  $response = array(
    "status" => "error",
    "mensaje" => "No se recibieron datos por POST",
  );
  
  header("Content-Type: application/json");
  echo json_encode($response);
}*/

/*************************************************************************************************
 *  Created By: Tauseef Ahmad
 *  Created On: 3 April, 2023
 *  
 *  YouTube Video: https://youtu.be/VEN5kgjEuh8
 *  My Channel: https://www.youtube.com/channel/UCOXYfOHgu-C-UfGyDcu5sYw/
 ***********************************************************************************************/
 
$hostname = "localhost"; 
$username = "root"; 
$password = ""; 
$database = "sitio"; 

$conn = mysqli_connect($hostname, $username, $password, $database);

if (!$conn) { 
	die("Connection failed: " . mysqli_connect_error()); 
} 

echo "Database connection is OK<br>";


if(isset($_POST["rfid"])) {

$rfid = $_POST["rfid"];
//$dato1 = $_POST["rfid"];

	//$sql = "INSERT INTO biodi (rfid) VALUES (20)"; 
	$sql = "INSERT INTO biodi (rfid) VALUES (".$rfid.")"; 
	mysqli_query($conn, $sql);
	if (mysqli_query($conn, $sql)) { 
		echo "\nNew record created successfully"; 
		/*
		<script language="javascript" src="js/jquery-1.7.2.min.js"></script>
		<script language="javascript" src="js/fancywebsocket.js"></script>
		<script language="javascript">
			
		var mensaje = '<?php echo $rfid?>';
		var tipo             = 'text';
			
		$.ajax({
			type: "POST",
			url: "insertar.php",
			data: "mensaje="+mensaje+"&tipo="+tipo,
			dataType:"html",
			success: function(data) 
			{
				send(data);// array JSON
			}
			});
		
		</script>*/
	} else { 
		echo "Error: " . $sql . "<br>" . mysqli_error($conn); 
	}
/*$response = array(
	'status' => 'success',
	'message' => 'Datos recibidos correctamente',
);
echo json_encode($response);*/
}

?>