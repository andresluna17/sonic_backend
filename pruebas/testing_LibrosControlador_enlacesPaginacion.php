<!DOCTYPE html>
<html>
    <head>
        <title>Actualización de Libro.</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!--Para utilizar Bootstrap sin descargarlo:   https://getbootstrap.com/   Require conexión a internet-->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    </head>
    <body>
        <?php
        include_once '../modelos/ConstantesConexion.php';
        include_once PATH . 'modelos/ConBdMysql.php';
        include_once PATH . 'modelos/modeloLibros/LibroDAO.php';
        include_once PATH . 'controladores/LibrosControlador.php';

        $_GET['pag'] = 0;

//$construirPaginacionLibros = new LibrosControlador(array());
        $construirPaginacionLibros = new LibrosControlador($_GET['pag']);
        $paginacionVinculos = $construirPaginacionLibros->enlacesPaginacion($totalRegistros = NULL, $limit = 2, $offset = 50, $totalEnlacesPaginacion = 2);

//echo json_encode($listadoLibros);
        echo "<pre>";
        print_r($paginacionVinculos);
        echo "</pre>";
        ?>

        <nav aria-label="Page navigation example">
            <?php $i = 0; ?>
            <ul class="pagination justify-content-center">
                <?php foreach ($paginacionVinculos as $key => $value) { ?>    
                    <li class="page-item"><a class="page-link" href="<?php echo $value; ?>"><?php echo $key; ?></a></li>
                    <?php } ?>
            </ul>
        </nav>

    </body>
</html>



