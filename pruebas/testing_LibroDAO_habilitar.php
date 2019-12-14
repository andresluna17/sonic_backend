<?php

include_once '../modelos/ConstantesConexion.php';
include_once PATH.'modelos/ConBdMysql.php';
include_once PATH.'modelos/modeloLibros/LibroDAO.php';

$cambiarEstadoLibro = new LibroDAO(SERVIDOR,BASE,USUARIO_BD,CONTRASENIA_BD);

$habilitarLibro=$cambiarEstadoLibro->habilitar(array(3));


echo "<pre>";
print_r($habilitarLibro);
echo "</pre>";