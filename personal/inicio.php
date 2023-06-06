<?php include('template/cabecera.php'); ?>

            <div class="col-md-12">
                <div class="jumbotron">
                    <h1 class="display-3">Bienvenido <?php echo $nombreUsuario;?></h1>
                    <p class="lead">Esta página es para administrar los productos y los accesorios de los equipos biomédicos de la institución.</p>
                    <hr class="my-2">
                    <p>Más información</p>
                    <p class="lead">
                        <a class="btn btn-primary btn-lg" href="seccion/productos.php" role="button">Administrar productos</a>
                    </p>
                    <?php /* <p class="lead">
                        <a class="btn btn-primary btn-lg" href="seccion/admin.php" role="button">Administrar </a>
                    </p> */ ?>
                </div>
            </div>
            
<?php include('template/pie.php'); ?>