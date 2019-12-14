<?php

include_once '../modelos/ConstantesConexion.php';
include_once PATH.'modelos/ConBdMysql.php';
include_once PATH.'modelos/modeloLibros/LibroDAO.php';


$registro=array();/**Array para capturar datos de un formulario***/
/******SIMULAMOS DATOS QUE VIENEN DE UN FORMULARIO CON MÉTODO POST******/
$_POST['isbn']=2;
$_POST['titulo']="FICHA 1804901 APRENDIENDO PHP";
$_POST['autor']="ESTUDIANTES";
$_POST['precio']=105000;
$_POST['categoriaLibro_catLibId']=2;
/********************************************************************/
/******SIMULAMOS CAPTURAR LOS DATOS QUE VIENEN DESDE UN FORMULARIO CON MÉTODO POST*/
$registro['isbn']=$_POST['isbn'];
$registro['titulo']=$_POST['titulo'];
$registro['autor']=$_POST['autor'];
$registro['precio']=$_POST['precio'];
$registro['categoriaLibro_catLibId']=$_POST['categoriaLibro_catLibId'];
/*******************************************************************/

$actualizar = new LibroDAO(SERVIDOR,BASE,USUARIO_BD,CONTRASENIA_BD);

$actualizaLibro=$actualizar->actualizar($registro);


echo "<pre>";
print_r($actualizaLibro);
echo "</pre>";