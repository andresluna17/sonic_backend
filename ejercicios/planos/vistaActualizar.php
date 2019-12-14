<?php
session_start();
if (isset($_SESSION['registroActualizar'])) {

    $registroActualizar = $_SESSION['registroActualizar'];

    list($cedula, $nombre, $correo, $telfij, $telmovil) = explode(":", $registroActualizar);
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Actualizar Registro</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body>
        <div>
            <h1>EJERCICIO PARA APROPIAR FUNDAMENTOS DEL LENGUAJE PHP</h1>
            <h2>Formulario de Actualización de Registro.</h2>
            <form action="controlador.php" method="GET">
                <table border="1">
                    <tr><td>Cédula:</td><td><input type="number" name="cedula" value="<?php
                            if (isset($cedula)) {
                                echo $cedula;
                            }
                            ?>" readonly="readonly" /></td></tr>
                    <tr><td>Nombre:</td><td><input type="text" name="nombre" value="<?php
                            if (isset($nombre)) {
                                echo $nombre;
                            }
                            ?>" /></td></tr>
                    <tr><td>Correo:</td><td><input type="email" name="correo" value="<?php
                            if (isset($correo)) {
                                echo $correo;
                            }
                            ?>" /></td></tr>
                    <tr><td>Teléfono fijo:</td><td><input type="number" name="telfij" value="<?php
                            if (isset($telfij)) {
                                echo $telfij;
                            }
                            ?>" /></td></tr>
                    <tr><td>Teléfono móvil:</td><td><input type="number" name="telmov" value="<?php
                            if (isset($telmovil)) {
                                echo $telmovil;
                            }
                            ?>" /></td></tr>
                    <tr><td colspan="2" align="right">
                            <button type="submit" name="ruta" value="cancelarActualizar">CANCELAR</button>&nbsp;&nbsp;&nbsp;
                            <button type="submit" name="ruta" value="confirmarActualizar">ACTUALIZAR</button>
                        </td>
                    </tr>
                </table>
            </form>
            <a href="principal.php">IR AL MENU PRINCIPAL</a> 
        </div>
    </body>
</html>
