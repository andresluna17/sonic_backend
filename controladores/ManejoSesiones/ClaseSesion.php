<?php

class ClaseSesion {

    function crearSesion($usuario_s) {

        $_SESSION['autenticado'] = "1";

        $estado_session = session_status();
        if ($estado_session == PHP_SESSION_DISABLED) {
            session_start();
        }

        list($datosUsuario, $rolesUsuario, $rolesEnSesion) = $usuario_s;

        $_SESSION['datosUsuario'] = $datosUsuario;
        $_SESSION['rolesUsuario'] = $rolesUsuario;
        $_SESSION['rolesEnSesion'] = $rolesEnSesion;
    }

    function cerrarSesion() {
        session_start();
        session_destroy();
        header("Location: login.php");
    }

}
?>

