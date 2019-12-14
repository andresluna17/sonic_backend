<?php
session_start();

if (isset($_SESSION['mensaje'])) {
    echo $_SESSION['mensaje'];
    unset($_SESSION['mensaje']);
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Agregar Registro</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body>
        <div>
            <h1>EJERCICIO PARA APROPIAR FUNDAMENTOS DEL LENGUAJE PHP</h1>
            <form action="controlador.php" method="GET">
                <table border="1">
                    <tr><td>Cédula:</td>
                        <td >
                            <input name="cedula" type="number" id="cedula" pattern="^[0-9]{5,11}$" 
                                   title="De 5 a 11 dígitos"   
                                   oninvalid="setCustomValidity('Debe contener solo números, de 5 a 11 digitos')"
                                   oninput="setCustomValidity('')"/>
                        </td>
                    </tr>
                    <tr><td>Nombre:</td><td><input type="text" name="nombre" value="" /></td></tr>
                    <tr><td>Correo:</td><td><input type="email" name="correo" value="" /></td></tr>
                    <tr><td>Teléfono fijo:</td><td><input type="number" name="telfij" value="" /></td></tr>
                    <tr><td>Teléfono móvil:</td><td><input type="number" name="telmov" value="" /></td></tr>
                    <tr><td colspan="2" align="right">
                            <button type="submit" name="ruta" value="cancelar">CANCELAR</button>&nbsp;&nbsp;&nbsp;
                            <button type="submit" name="ruta" value="agregar">AGREGAR</button>
                        </td>
                    </tr>
                </table>
            </form>
            <a href="principal.php">IR AL MENU PRINCIPAL</a> 
        </div>
    </body>
</html>
