<?php

include_once '../modelos/ConstantesConexion.php';
include_once PATH.'modelos/ConBdMysql.php';
include_once PATH.'modelos/modeloLibros/LibroDAO.php';


$eliminarLibro = new LibroDAO(SERVIDOR,BASE,USUARIO_BD,CONTRASENIA_BD);

$libroEliminado=$eliminarLibro->eliminar(array(2));

echo "<pre>";
print_r($libroEliminado);
echo "</pre>";

