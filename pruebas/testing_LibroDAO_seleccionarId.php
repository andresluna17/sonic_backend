<?php

include_once '../modelos/ConstantesConexion.php';
include_once PATH.'modelos/ConBdMysql.php';
include_once PATH.'modelos/modeloLibros/LibroDAO.php';


$hallarLibro = new LibroDAO(SERVIDOR,BASE,USUARIO_BD,CONTRASENIA_BD);

$libroHallado=$hallarLibro->seleccionarId(array(5));


echo "<pre>";
print_r($libroHallado);
echo "</pre>";