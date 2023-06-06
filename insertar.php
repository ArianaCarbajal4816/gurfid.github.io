<?php
$hostname = "localhost"; 
$username = "root"; 
$password = ""; 
$database = "sitio"; 

$conn = mysqli_connect($hostname, $username, $password, $database);

$mensaje = $_POST['mensaje'];
$tipo = $_POST['tipo'];

$timestamp = date("Y-m-d H:i:s");

$q = "INSERT INTO medidasRFID values ('$mensaje','$timestamp','1','$tipo')";
$res = mysqli_query($conn, $q) or die (mysql_error());

$arrayjson = array();

$arrayjson[] = array(
					'tipo'          => $tipo,//tipo de actualizacion
					'mensaje'      => $mensaje,//mensaje
					'fecha'         => $timestamp,//fecha de envio
					'actualizacion' => '1'
);

echo json_encode($arrayjson);
?>