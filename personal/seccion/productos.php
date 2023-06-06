<?php include("../template/cabecera.php");?>

<script>
    $(document).ready(function() {
      console.log("El script se está ejecutando...");
      // Función para realizar la solicitud Ajax y actualizar la página
      function actualizarPagina() {
        console.log("Ejecutando la función actualizarPagina");
        $.ajax({
            url: '../../obtener_datos.php', // Ruta al archivo PHP que procesará los datos
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

<?php /*esta pagina servirá para administrar los productos*/
$_SESSION['rfid'] = 'XL';
$_SESSION['llenar'] = 'si';

$txtRFID=(isset($_POST['txtRFID']) ) ?$_POST['txtRFID']:"" ;
/* La variable txtID se verifica si es que está vacia (isset - ?), de lo contrario
retornamos el valor de donde se envió a (:) "" */
$txtNombre=(isset($_POST['txtNombre']) ) ?$_POST['txtNombre']:"" ;
$txtSerie=(isset($_POST['txtSerie']) ) ?$_POST['txtSerie']:"" ;
$txtImagen=(isset($_FILES['txtImagen']['name']) ) ?$_FILES['txtImagen']['name']:"" ;
$accion=(isset($_POST['accion']) ) ? $_POST['accion']:"" ;

//echo $txtID."<br/>";
//echo $txtNombre."<br/>";
//echo $txtCargo."<br/>";
//echo $accion."<br/>";

include("../config/db.php");

switch($accion){
    
    case "Agregar":
        if($txtRFID == "" || $txtNombre == "" || $txtSerie== "" || $txtImagen ==""){
            /*no se puede agregar porque no existe*/
            $mensaje = "Debe llenar todos los campos";
            break;
        }

        $sentenciaSQL = $conexion ->prepare("SELECT * FROM medidasrfid");
        $sentenciaSQL -> execute();
        $listaEquipo = $sentenciaSQL -> fetchAll(PDO::FETCH_ASSOC);

        foreach($listaEquipo as $equipo){
            if($equipo['RFID'] == $txtRFID){
                /*no se puede agregar*/
                $mensaje = "El RFID del equipo ya se encuentra en uso";
                $accion = "";
                break;
            }
        }

        if($accion ==""){
            break;
        }

        $sentenciaSQL = $conexion ->prepare("INSERT INTO medidasrfid VALUES (:RFID,:nombre,:serie,:imagen);");
        $sentenciaSQL -> bindParam(":RFID",$txtRFID);
        $sentenciaSQL -> bindParam(":nombre",$txtNombre);
        $sentenciaSQL -> bindParam(":serie",$txtSerie);
        
        $fecha = new DateTime();
        //para la imagen
        $nombreArchivo = ($txtImagen!="")?$fecha->getTimeStamp()."_".$_FILES["txtImagen"]["name"]:"imagen.jpg";
        $tmpImagen = $_FILES["txtImagen"]["tmp_name"];

        if($tmpImagen != ""){
            move_uploaded_file($tmpImagen,"../../img/".$nombreArchivo);
        }

        $sentenciaSQL -> bindParam(":imagen",$nombreArchivo);
        $sentenciaSQL -> execute();
        
        header("Location:productos.php");
        break;

    case "Modificar":
        $sentenciaSQL = $conexion ->prepare("UPDATE medidasrfid SET Nombre=:nombre WHERE RFID=:RFID");
        $sentenciaSQL -> bindParam(":nombre",$txtNombre);
        $sentenciaSQL -> bindParam(":RFID",$_SESSION['anterior']);
        $sentenciaSQL -> execute();

        $sentenciaSQL = $conexion ->prepare("UPDATE medidasrfid SET Serie=:serie WHERE RFID=:RFID");
        $sentenciaSQL -> bindParam(":serie",$txtSerie);
        $sentenciaSQL -> bindParam(":RFID",$_SESSION['anterior']);
        $sentenciaSQL -> execute();

        $sentenciaSQL = $conexion ->prepare("UPDATE medidasrfid SET RFID=:nuevo WHERE Nombre=:nombre");
        $sentenciaSQL -> bindParam(":nuevo",$txtRFID);
        $sentenciaSQL -> bindParam(":nombre",$txtNombre);
        $sentenciaSQL -> execute();

        if($txtImagen != ""){
            $fecha = new DateTime();
            //para la imagen
            $nombreArchivo = ($txtImagen!="")?$fecha->getTimeStamp()."_".$_FILES["txtImagen"]["name"]:"imagen.jpg";
            $tmpImagen = $_FILES["txtImagen"]["tmp_name"];
            move_uploaded_file($tmpImagen,"../../img/".$nombreArchivo);

            $sentenciaSQL = $conexion ->prepare("SELECT imagen FROM medidasrfid WHERE RFID=:RFID");
            $sentenciaSQL -> bindParam(":RFID",$_SESSION['anterior']);
            $sentenciaSQL -> execute();
            $libro = $sentenciaSQL -> fetch(PDO::FETCH_LAZY);
            if(isset($libro["imagen"]) && ($libro["imagen"]!="imagen.jpg") ){

                if(file_exists("../../img/".$libro["imagen"])){
                    unlink("../../img/".$libro["imagen"]);
                }

            }

            $sentenciaSQL = $conexion ->prepare("UPDATE medidasrfid SET imagen=:imagen WHERE RFID=:RFID");
            $sentenciaSQL -> bindParam(":imagen",$nombreArchivo);
            $sentenciaSQL -> bindParam(":RFID",$_SESSION['anterior']);
            $sentenciaSQL -> execute();
        }

        else{
            
        }

        header("Location:productos.php");
        break;

    case "Cancelar":
        header("Location:productos.php");
        break;

    case "Seleccionar":
        $txtRFID = $_POST['txtRFID'];
        $_SESSION['anterior'] = $txtRFID;
        $sentenciaSQL = $conexion ->prepare("INSERT INTO biodi (rfid) VALUES ($txtRFID)");
        $sentenciaSQL -> execute();
        $sentenciaSQL = $conexion ->prepare("SELECT * FROM medidasrfid WHERE RFID=:rfid");
        $sentenciaSQL -> bindParam(":rfid",$txtRFID);
        $sentenciaSQL -> execute();
        $equipo = $sentenciaSQL -> fetch(PDO::FETCH_LAZY);
            //permite cargar los datos uno a uno y rellenarlos
        $txtNombre = $equipo['Nombre'];
        $txtSerie = $equipo['Serie'];
        $txtImagen = $equipo['imagen'];
            //$_SESSION['llenar'] = 'no';
        
        break;

    case "Borrar":
        $sentenciaSQL = $conexion ->prepare("SELECT imagen FROM medidasrfid WHERE RFID=:RFID");
        $sentenciaSQL -> bindParam(":RFID",$txtRFID);
        $sentenciaSQL -> execute();
        $libro = $sentenciaSQL -> fetch(PDO::FETCH_LAZY);
        if(isset($libro["imagen"]) && ($libro["imagen"]!="imagen.jpg") ){

            if(file_exists("../../img/".$libro["imagen"])){
                unlink("../../img/".$libro["imagen"]);
            }

        }

        //echo "Presionado botón Borrar";
        $sentenciaSQL = $conexion ->prepare("DELETE FROM medidasrfid WHERE RFID=:rfid");
        $sentenciaSQL -> bindParam(":rfid",$txtRFID);
        $sentenciaSQL -> execute();
        header("Location:productos.php");
        break;
}

$sentenciaSQL = $conexion ->prepare("SELECT * FROM medidasrfid");
$sentenciaSQL -> execute();
$listaEquipo = $sentenciaSQL -> fetchAll(PDO::FETCH_ASSOC);
//el método fetchall recupera todos los registros para mostrarlos en la nueva variable.


?>


<div class="col-md-5">

    <div class="card">
        <div class="card-header">
            Datos del Equipo
        </div>
        
        <div class="card-body">
            
            <form method="POST" enctype="multipart/form-data" >
            <div class = "form-group">
            <label for="txtID">RFID:</label>
            <input type="text" <?php echo($accion!="Seleccionar")?"readonly":"" ?> class="form-control"  value="<?php echo $txtRFID?>" name="txtRFID" id="txtRFID" placeholder="RFID">
            </div>

            <div class = "form-group">
            <label for="txtNombre">Nombre:</label>
            <input type="text" class="form-control" value="<?php echo $txtNombre?>" name="txtNombre" id="txtNombre" placeholder="Nombre del equipo">
            </div>

            <div class = "form-group">
            <label for="txtNombre">Serie:</label>
            <input type="text" class="form-control" value="<?php echo $txtSerie?>" name="txtSerie" id="txtSerie" placeholder="Serie del equipo">
            </div>

            <?php if(isset($mensaje) ){?>
                        <div class="alert alert-danger" role="alert">
                            <?php echo $mensaje; ?>
                        </div>
            <?php }?>

            </br>
            
            <label for="txtImagen">Imagen:</label>
            
            <br/>
            <?php if($txtImagen!=""){ ?>
                
                <img class= "img-thumbnail rounded" src="../../img/<?php echo $txtImagen?>" width="100" alt="" srcset="">
            
            <?php } ?>

            <input type="file" class="form-control" name="txtImagen" id="txtImagen" placeholder="Nombre">
            </div>

            <div class="btn-group" role="group" aria-label="">
                <button type="submit" name="accion" <?php echo($accion=="Seleccionar" || $accion=="Borrar")?"disabled":"" ?> value="Agregar" class="btn btn-success">Agregar</button>
                <button type="submit" name="accion" <?php echo($accion!="Seleccionar")?"disabled":"" ?> value="Modificar" class="btn btn-warning">Modificar</button>
                <button type="submit" name="accion" <?php echo($accion!="Seleccionar")?"disabled":"" ?> value="Cancelar"  class="btn btn-info">Cancelar</button>
            </div>
            </form>
        </div>

    </div>

    
    
    
</div>

<br/>

<div class="col-md-7">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>RFID</th>
                <th>Nombre</th>
                <th>Serie</th>
                <th>Imagen</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach($listaEquipo as $Equipo){
        ?>
            <tr>
                <td><?php echo $Equipo['RFID'];?></td>
                <td><?php echo $Equipo['Nombre'];?></td>
                <td><?php echo $Equipo['Serie'];?></td>
                <td>
                    
                    <img class= "img-thumbnail rounded" src="../../img/<?php echo $Equipo['imagen'];?>" width="50" alt="" srcset="">
                
                </td>

                <td>
                    <form method="post">
                        <input type="hidden" name="txtRFID" id="txtRFID" value="<?php echo $Equipo['RFID'];?>" />

                        <input type="submit" name="accion" value="Seleccionar" class="btn btn-primary" />

                        <input type="submit" name="accion" value="Borrar" class="btn btn-danger" />

                    </form>
                
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>

<?php include("../template/pie.php");?>