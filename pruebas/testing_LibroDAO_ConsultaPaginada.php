<?php

include_once '../modelos/ConstantesConexion.php';
include_once PATH.'modelos/ConBdMysql.php';
include_once PATH.'modelos/modeloLibros/LibroDAO.php';

$limit=null;
$offset=null;

$consultaLibrosPaginados = new LibroDAO(SERVIDOR, BASE, USUARIO_BD, CONTRASENIA_BD);
$listadoLibrosPaginados=$consultaLibrosPaginados->consultaPaginada($limit, $offset);


echo "<pre>";
print_r($listadoLibrosPaginados);
echo "</pre>";

