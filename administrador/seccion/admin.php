<?php include("../template/cabecera.php");?>
<?php /*esta pagina servirá para administrar al personal*/
$txtID=(isset($_POST['txtID']) ) ?$_POST['txtID']:"" ;
/* La variable txtID se verifica si es que está vacia (isset - ?), de lo contrario
retornamos el valor de donde se envió a (:) "" */
$txtNombre=(isset($_POST['txtNombre']) ) ?$_POST['txtNombre']:"" ;
$txtCargo=(isset($_POST['txtCargo']) ) ?$_POST['txtCargo']:"" ;
$txtUsuario=(isset($_POST['txtUsuario']) ) ?$_POST['txtUsuario']:"" ;
$txtContrasena=(isset($_POST['txtContrasena']) ) ?$_POST['txtContrasena']:"" ;
$accion=(isset($_POST['accion']) ) ? $_POST['accion']:"" ;

//echo $txtID."<br/>";
//echo $txtNombre."<br/>";
//echo $txtCargo."<br/>";
//echo $accion."<br/>";

include("../config/db.php");

switch($accion){
    
    case "Agregar":
        if($txtID == "" || $txtNombre == "" || $txtCargo== "" || $txtUsuario== "" || $txtContrasena == ""){
            /*no se puede agregar porque no existe*/
            $mensaje = "Debe llenar todos los campos";
            break;
        }

        $sentenciaSQL = $conexion ->prepare("SELECT * FROM personal");
        $sentenciaSQL -> execute();
        $listaPersonal = $sentenciaSQL -> fetchAll(PDO::FETCH_ASSOC);

        foreach($listaPersonal as $perso){
            if($perso['ID'] == $txtID){
                /*no se puede agregar*/
                $mensaje = "El ID del personal ya se encuentra en uso";
                $accion = "";
                break;
            }
            if($perso['usuario'] == $txtUsuario){
                /*no se puede agregar*/
                $mensaje = "El usuario ya se encuentra en uso";
                $accion = "";
                break;
            }

        }

        if($accion ==""){
            break;
        }

        $sentenciaSQL = $conexion ->prepare("INSERT INTO personal (ID,Nombre,Cargo,usuario,contrasena) VALUES (:ID,:nombre,:cargo,:usuario,:contrasena);");
        $sentenciaSQL -> bindParam(":ID",$txtID);
        $sentenciaSQL -> bindParam(":nombre",$txtNombre);
        $sentenciaSQL -> bindParam(":cargo",$txtCargo);
        $sentenciaSQL -> bindParam(":usuario",$txtUsuario);
        $sentenciaSQL -> bindParam(":contrasena",$txtContrasena);
        
        $sentenciaSQL -> execute();
        header("Location:admin.php");
        break;

    case "Modificar":
        $sentenciaSQL = $conexion ->prepare("UPDATE personal SET Nombre=:nombre WHERE ID=:id");
        $sentenciaSQL -> bindParam(":nombre",$txtNombre);
        $sentenciaSQL -> bindParam(":id",$txtID);
        $sentenciaSQL -> execute();

        $sentenciaSQL = $conexion ->prepare("UPDATE personal SET Cargo=:cargo WHERE ID=:id");
        $sentenciaSQL -> bindParam(":cargo",$txtCargo);
        $sentenciaSQL -> bindParam(":id",$txtID);
        $sentenciaSQL -> execute();
        //no dejaremos que cambien el usuario y contraseña

        header("Location:admin.php");
        break;

    case "Cancelar":
        header("Location:admin.php");
        break;

    case "Seleccionar":
        $sentenciaSQL = $conexion ->prepare("SELECT * FROM personal WHERE ID=:id");
        $sentenciaSQL -> bindParam(":id",$txtID);
        $sentenciaSQL -> execute();
        $perso = $sentenciaSQL -> fetch(PDO::FETCH_LAZY);
        //permite cargar los datos uno a uno y rellenarlos
        $txtID = $perso['ID'];
        $txtNombre = $perso['Nombre'];
        $txtCargo = $perso['Cargo'];
        $txtUsuario = $perso['usuario'];
        $txtContrasena = $perso['contrasena'];
        break;

    case "Borrar":

        //echo "Presionado botón Borrar";
        $sentenciaSQL = $conexion ->prepare("DELETE FROM personal WHERE ID=:id");
        $sentenciaSQL -> bindParam(":id",$txtID);
        $sentenciaSQL -> execute();
        header("Location:admin.php");
        break;
}

$sentenciaSQL = $conexion ->prepare("SELECT * FROM personal");
$sentenciaSQL -> execute();
$listaPersonal = $sentenciaSQL -> fetchAll(PDO::FETCH_ASSOC);
//el método fetchall recupera todos los registros para mostrarlos en la nueva variable.


?>
<div class="jumbotron">
    <h1 class="display-3">Administrar Personal</h1>
    <p class="lead">Esta sección permitirá agregar, eliminar o editar información sobre el personal</p>
    <hr class="my-2">
</div>

<div class="col-md-5">

    <div class="card">
        <div class="card-header">
            Datos del Personal
        </div>
        
        <div class="card-body">
            
            <form method="POST" enctype="multipart/form-data" >
            <div class = "form-group">
            <label for="txtID">ID:</label>
            <input type="text" <?php echo($accion=="Seleccionar")?"readonly":"" ?> class="form-control"  value="<?php echo $txtID?>" name="txtID" id="txtID" placeholder="ID">
            </div>

            <div class = "form-group">
            <label for="txtNombre">Nombre:</label>
            <input type="text" class="form-control" value="<?php echo $txtNombre?>" name="txtNombre" id="txtNombre" placeholder="Nombre del personal">
            </div>

            <div class = "form-group">
            <label for="txtNombre">Cargo:</label>
            <input type="text" class="form-control" value="<?php echo $txtCargo?>" name="txtCargo" id="txtCargo" placeholder="Cargo del personal">
            </div>

            <div class = "form-group">
            <label for="txtID">Usuario:</label>
            <input type="text" <?php echo($accion=="Seleccionar")?"readonly":"" ?> class="form-control"  value="<?php echo $txtUsuario?>" name="txtUsuario" id="txtUsuario" placeholder="Usuario">
            </div>

            <div class = "form-group">
            <label for="txtID">Contrasena:</label>
            <input type="text" <?php echo($accion=="Seleccionar")?"readonly":"" ?> class="form-control"  value="<?php echo $txtContrasena?>" name="txtContrasena" id="txtContrasena" placeholder="Contrasena">
            </div>

            <?php if(isset($mensaje) ){?>
                        <div class="alert alert-danger" role="alert">
                            <?php echo $mensaje; ?>
                        </div>
            <?php }?>

            </br>

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
                <th>Cargo</th>
                <th>Usuario</th>
                <th>Contrasena</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach($listaPersonal as $personal){
        ?>
            <tr>
                <td><?php echo $personal['ID'];?></td>
                <td><?php echo $personal['Nombre'];?></td>
                <td><?php echo $personal['Cargo'];?></td>
                <td><?php echo $personal['usuario'];?></td>
                <td><?php echo $personal['contrasena'];?></td>
                <td>
                    <form method="post">
                        <input type="hidden" name="txtID" id="txtID" value="<?php echo $personal['ID'];?>" />

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