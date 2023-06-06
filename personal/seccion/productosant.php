<?php include("../template/cabecera.php");?>
<?php
$txtID=(isset($_POST['txtID']) ) ?$_POST['txtID']:"" ;
/* La variable txtID se verifica si es que está vacia (isset - ?), de lo contrario
retornamos el valor de donde se envió a (:) "" */
$txtNombre=(isset($_POST['txtNombre']) ) ?$_POST['txtNombre']:"" ;
$txtImagen=(isset($_FILES['txtImagen']['name']) ) ?$_FILES['txtImagen']['name']:"" ;
$accion=(isset($_POST['accion']) ) ? $_POST['accion']:"" ;

//echo $txtID."<br/>";
//echo $txtNombre."<br/>";
//echo $txtImagen."<br/>";
//echo $accion."<br/>";

include("../config/db.php");

switch($accion){
    
    case "Agregar":
        $sentenciaSQL = $conexion ->prepare("INSERT INTO libros (nombre, imagen) VALUES (:nombre,:imagen);");
        $sentenciaSQL -> bindParam(":nombre",$txtNombre);
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
        $sentenciaSQL = $conexion ->prepare("UPDATE libros SET nombre=:nombre WHERE id=:id");
        $sentenciaSQL -> bindParam(":nombre",$txtNombre);
        $sentenciaSQL -> bindParam(":id",$txtID);
        $sentenciaSQL -> execute();
        
        if($txtImagen != ""){
            $fecha = new DateTime();
            //para la imagen
            $nombreArchivo = ($txtImagen!="")?$fecha->getTimeStamp()."_".$_FILES["txtImagen"]["name"]:"imagen.jpg";
            $tmpImagen = $_FILES["txtImagen"]["tmp_name"];
            move_uploaded_file($tmpImagen,"../../img/".$nombreArchivo);

            $sentenciaSQL = $conexion ->prepare("SELECT imagen FROM libros WHERE id=:id");
            $sentenciaSQL -> bindParam(":id",$txtID);
            $sentenciaSQL -> execute();
            $libro = $sentenciaSQL -> fetch(PDO::FETCH_LAZY);
            if(isset($libro["imagen"]) && ($libro["imagen"]!="imagen.jpg") ){

                if(file_exists("../../img/".$libro["imagen"])){
                    unlink("../../img/".$libro["imagen"]);
                }

            }

            $sentenciaSQL = $conexion ->prepare("UPDATE libros SET imagen=:imagen WHERE id=:id");
            $sentenciaSQL -> bindParam(":imagen",$nombreArchivo);
            $sentenciaSQL -> bindParam(":id",$txtID);
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
        $sentenciaSQL = $conexion ->prepare("SELECT * FROM libros WHERE id=:id");
        $sentenciaSQL -> bindParam(":id",$txtID);
        $sentenciaSQL -> execute();
        $libro = $sentenciaSQL -> fetch(PDO::FETCH_LAZY);
        //permite cargar los datos uno a uno y rellenarlos
        $txtNombre = $libro['nombre'];
        $txtImagen = $libro['imagen'];
        break;

    case "Borrar":

        $sentenciaSQL = $conexion ->prepare("SELECT imagen FROM libros WHERE id=:id");
        $sentenciaSQL -> bindParam(":id",$txtID);
        $sentenciaSQL -> execute();
        $libro = $sentenciaSQL -> fetch(PDO::FETCH_LAZY);
        if(isset($libro["imagen"]) && ($libro["imagen"]!="imagen.jpg") ){

            if(file_exists("../../img/".$libro["imagen"])){
                unlink("../../img/".$libro["imagen"]);
            }

        }

        //echo "Presionado botón Borrar";
        $sentenciaSQL = $conexion ->prepare("DELETE FROM libros WHERE id=:id");
        $sentenciaSQL -> bindParam(":id",$txtID);
        $sentenciaSQL -> execute();
        header("Location:productos.php");
        break;
}

$sentenciaSQL = $conexion ->prepare("SELECT * FROM libros");
$sentenciaSQL -> execute();
$listaLibros = $sentenciaSQL -> fetchAll(PDO::FETCH_ASSOC);
//el método fetchall recupera todos los registros para mostrarlos en la nueva variable.


?>


<div class="col-md-5">

    <div class="card">
        <div class="card-header">
            Datos de Libro
        </div>
        
        <div class="card-body">
            
            <form method="POST" enctype="multipart/form-data" >
            <div class = "form-group">
            <label for="txtID">ID:</label>
            <input type="text" required readonly class="form-control" value="<?php echo $txtID?>" name="txtID" id="txtID" placeholder="ID">
            </div>

            <div class = "form-group">
            <label for="txtNombre">Nombre:</label>
            <input type="text" class="form-control" value="<?php echo $txtNombre?>" name="txtNombre" id="txtNombre" placeholder="Nombre del libro">
            </div>

            <div class = "form-group">
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
<div class="col-md-7">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Imagen</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach($listaLibros as $libro){
        ?>
            <tr>
                <td><?php echo $libro['id'];?></td>
                <td><?php echo $libro['nombre'];?></td>
                <td>
                    
                    <img class= "img-thumbnail rounded" src="../../img/<?php echo $libro['imagen'];?>" width="50" alt="" srcset="">
                
                </td>
                <td>
                    <form method="post">
                        <input type="hidden" name="txtID" id="txtID" value="<?php echo $libro['id'];?>" />

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