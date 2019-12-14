<?php

class BloqueDeSeguridad {

    public function seguridad($ubicacion) {
        
        $estado_session = session_status();
        if ($estado_session == PHP_SESSION_DISABLED) {
            session_start();
        }

        //Comprobar que el usuario este autenticado
        if ($_SESSION["autenticado"] != "1") {
            //si no existe , va para l apágina de login
            header("Location: " . $ubicacion);
            //salimos de este script
            exit(1);
        }
    }

}
?>

