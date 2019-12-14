<?php

include_once '../modelos/ConstantesConexion.php';
include_once PATH.'modelos/ConBdMysql.php';
include_once PATH.'modelos/modeloLibros/LibroDAO.php';

$libros = new LibroDAO(SERVIDOR, BASE, USUARIO_BD, CONTRASENIA_BD);
$listadoLibros=$libros->seleccionarTodos();

//echo json_encode($listadoLibros);
echo "<pre>";
print_r($listadoLibros);
echo "</pre>";

