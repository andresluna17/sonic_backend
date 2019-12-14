<?php

include_once '../modelos/ConstantesConexion.php';
require_once PATH . 'modelos/modeloPersona/PersonaDAO.php';
$gestionarcategoria= new personaDAO(SERVIDOR,BASE,USUARIO_BD,CONTRASENIA_BD);
//$categoria= $gestionarcategoria->seleccionarTodos();
$categoria= $gestionarcategoria->seleccionarRol(5);
echo "<pre>";
print_r($categoria);
echo "</pre>";
?>