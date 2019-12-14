<?php

$cedula = "123456789";
$nombre = "ciudadano";
$apellido = "DelUniverso";
$correo = "notengo@no.com";
$telefonoFijo = "456789";
$telefonoMovil = "456789";
/* * ********************* */
$registro = strtoupper($cedula . ":" . $nombre . ":" . $apellido . ":" . $correo . ":" . $telefonoFijo . ":" . "\n");
$recurso = fopen("archivo.txt", "a+");
fwrite($recurso, $registro);
fclose($recurso);
/* * ********************* */
$verarch = file("archivo.txt");
$totalreg = count($verarch);

for ($i = 0; $i < $totalreg; $i++) {
    list($ced, $nom, $correo, $telf, $telmov) = explode(":", $verarch[$i]);
    echo $ced . "<br/>";
    echo $nom . "<br/>";
    echo $correo . "<br/>";
    echo $telf . "<br/>";
    echo $telmov . "<br/>";
}
/* * ********************* */
?>
