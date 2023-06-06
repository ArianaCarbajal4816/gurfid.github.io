<?php
session_start();
include("../administrador/config/db.php");

if($_POST){
    $sentenciaSQL = $conexion ->prepare("SELECT * FROM usuariosadmin");
    $sentenciaSQL -> execute();
    $listaAdmin = $sentenciaSQL -> fetchAll(PDO::FETCH_ASSOC);
    /*deberiamos usar el SELECT FROM de la base de datos*/
    foreach($listaAdmin as $perso){
        if(($perso['usuario'] ==$_POST ['usuario']) && ($perso['contrasena'] ==$_POST ['contrasena'])){
            $_SESSION['usuario']="ok";
            $_SESSION['nombreUsuario']="Rosaditos";
            header('Location:../administrador/inicio.php');
        }
    }
    
    /*if(($_POST['usuario']=="rosaditos") && ($_POST['contrasena']=="rosaditos") ){
        
        $_SESSION['usuario']="ok";
        $_SESSION['nombreUsuario']="Rosaditos";
        header('Location:../administrador/inicio.php');
    }*/
    
    $mensaje = "El usuario o contraseña son incorrectos";
    
}

?>

<!doctype html>
<html lang="en">
<head>
    <title>Administrador</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  </head>
  <body>
    <div class="container">
        <div class="row">

        <div class="col-md-4">
            
        </div>
            <div class="col-md-4">
                <br/> <br/> <br/>
                <div class="card">
                    <div class="card-header">
                        Login
                    </div>
                    <div class="card-body">
                    <?php if(isset($mensaje) ){?>
                        <div class="alert alert-danger" role="alert">
                            <?php echo $mensaje; ?>
                        </div>
                    <?php }?>
                        <form method="POST">
                        <div class = "form-group">
                        <label>Usuario</label>
                        <input type="text" required class="form-control" name="usuario" placeholder="Escribe tu usuario">
                        </div>
                        <div class="form-group">
                        <label>Contraseña</label>
                        <input type="password" required class="form-control" name="contrasena" placeholder="Escribe tu contraseña">
                        </div>
                        <div class="form-check">
                        </div>
                        <button type="submit" class="btn btn-primary">Entrar al sistema</button>
                        </form>
                            
                    </div>
 
                </div>
            </div>
            
        </div>
    </div>
    
  </body>
</html>