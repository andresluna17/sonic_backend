<?php

/****20190621_1902**/
header('Access-Control-Allow-Origin: *');
include_once 'modelos/ConstantesConexion.php';
include_once PATH . 'controladores/Usuario_sControlador.php';

//include_once PATH.'modelos/ConBdMysql.php';


//echo __FILE__ . "<br/>" . __CLASS__ . "<br/>" . __METHOD__ . "<br/>" . __LINE__ . "<br/><br/>";

$acceso = new Usuario_sControlador();
$acceso->usuario_sControlador();


?>



