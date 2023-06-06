<?php include('template/cabecera.php'); ?>

            <div class="col-md-12">
                <div class="jumbotron">
                    <h1 class="display-3">Bienvenido <?php echo $nombreUsuario;?></h1>
                    <p class="lead">Esta página es para verificar los productos ingresados al sistema y administrar al personal con acceso de parte del hospital</p>
                    <hr class="my-2">
                    <p>Más información</p>
                    <p class="lead">
                        <a class="btn btn-primary btn-lg" href="seccion/productos.php" role="button">Administrar productos</a>
                    </p>
                    <p class="lead">
                        <a class="btn btn-primary btn-lg" href="seccion/admin.php" role="button">Administrar personal</a>
                    </p>
                </div>
            </div>
            
<?php include('template/pie.php'); ?>