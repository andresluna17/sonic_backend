<?php

include_once '../modelos/ConstantesConexion.php';
include_once PATH.'modelos/ConBdMysql.php';
include_once PATH.'modelos/modeloLibros/LibroDAO.php';


$consultarCantidadRegistros= new LibroDAO(SERVIDOR,BASE,USUARIO_BD,CONTRASENIA_BD);

$paginacionVinculos=$consultarCantidadRegistros->totalRegistros();


echo "<pre>";
print_r($paginacionVinculos);
echo "</pre>";