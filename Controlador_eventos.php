<?php
header('Access-Control-Allow-Origin: *');
/****20190621_1902**/

include_once 'modelos/ConstantesConexion.php';
include_once PATH . 'controladores/EventosControlador.php';

//include_once PATH.'modelos/ConBdMysql.php';


//echo __FILE__ . "<br/>" . __CLASS__ . "<br/>" . __METHOD__ . "<br/>" . __LINE__ . "<br/><br/>";

$acceso = new EventosControlador();


?>

