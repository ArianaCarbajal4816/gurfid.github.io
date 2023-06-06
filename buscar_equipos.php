<?php include("template/cabecera.php"); ?>

<script>
    $(document).ready(function() {
      console.log("El script se está ejecutando...");
      // Función para realizar la solicitud Ajax y actualizar la página
      function actualizarPagina() {
        console.log("Ejecutando la función actualizarPagina");
        $.ajax({
            url: 'obtener_datos.php', // Ruta al archivo PHP que procesará los datos
            type: 'GET',
            dataType: 'json',
          success: function(response) {
            console.log("Respuesta recibida del servidor");
            console.log(response);
            // Procesa la respuesta del servidor
            if (response.status === 'success') {
              // Actualiza los elementos HTML según los datos recibidos
              //$('#txtRFID').text(response.valor);
              document.getElementById('txtRFID').value = response.valor;
              
            }
          }, 
          error: function(error){
            console.log(error);
          },
          complete: function() {
            console.log("Cargando de nuevo");
            // Llama a la función de nuevo después de un cierto tiempo
            setTimeout(actualizarPagina, 1000); // 1000 milisegundos = 5 segundos
          }
        } ) ;
      }

      // Llama a la función por primera vez para iniciar el proceso de actualización
      actualizarPagina();
    } ) ;
  </script>

<?php
session_start();
$_SESSION['rfid'] = 'XL';
$_SESSION['llenar'] = 'si';
$txtRFID=(isset($_POST['txtRFID']) ) ?$_POST['txtRFID']:"" ;
$accion=(isset($_POST['accion']) ) ? $_POST['accion']:"" ;

include("administrador/config/db.php");

?>

<div class="jumbotron">
    <h1 class="display-3">Buscar equipos</h1>
    <p class="lead">Esta sección permitirá buscar equipos escaneando su código RFID</p>
    <hr class="my-2">
</div>

<div class="col-md-5">

    <div class="card">
        <div class="card-header">
            Código RFID a buscar
        </div>
        
        <div class="card-body">
            
            <form method="POST" enctype="multipart/form-data" >
            <div class = "form-group">
            <label for="txtID">RFID: </label>
            <input type="text" readonly class="form-control"  value="<?php echo $txtRFID?>" name="txtRFID" id="txtRFID" placeholder="RFID">
            </div>

            <?php if(isset($mensaje) ){?>
                        <div class="alert alert-danger" role="alert">
                            <?php echo $mensaje; ?>
                        </div>
            <?php }?>

            </br>
            
        </div>

            <div class="btn-group" role="group" aria-label="">
                <button type="submit" name="accion"  value="Buscar" class="btn btn-success">Buscar</button>
            </div>
            </form>
        </div>

    </div>

    
    
    
</div>



<?php 
switch($accion){
    
    case "Buscar":
        if($txtRFID == ""){
            /*no se puede agregar porque no existe*/
            $mensaje = "Debe ingresar el RFID a buscar";
            break;
        }

        $sentenciaSQL = $conexion ->prepare("SELECT * FROM medidasrfid");
        $sentenciaSQL -> execute();
        $listaEquipo = $sentenciaSQL -> fetchAll(PDO::FETCH_ASSOC);

        foreach($listaEquipo as $equipo){
            if($equipo['RFID'] == $txtRFID){
                /*se encontro uno existente agregar*/
                ?>
                <div class="col-md-7">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>RFID</th>
                            <th>Nombre</th>
                            <th>Serie</th>
                            <th>Imagen</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?php echo $equipo['RFID'];?></td>
                            <td><?php echo $equipo['Nombre'];?></td>
                            <td><?php echo $equipo['Serie'];?></td>
                            <td>
                                
                                <img class= "img-thumbnail rounded" src="img/<?php echo $equipo['imagen'];?>" width="50" alt="" srcset="">
                            
                            </td>
                        </tr>
                    </tbody>
                </table>
                </div>
                <?php
                $accion = "encontro";
                break;
            }
        }

        if($accion != "encontro"){
            //no se encontró nada, se debe mandar un mensaje de error
            
            $mensaje = "No se encontró dicho código RFID";
        }
        break;

}

include("template/pie.php"); ?>