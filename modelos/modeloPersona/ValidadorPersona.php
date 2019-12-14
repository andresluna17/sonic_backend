<?php

class ValidadorPersona {

    public function validarFormularioRegistrarse($datos) {
        $mensajesError = NULL;
        $datosViejos = NULL;
        $marcaCampo = NULL;
        /*         * ****Validar datos ingresados************************ */
        foreach ($datos as $key => $value) {

            $datosViejos[$key] = $value;

            switch ($key) {
                case 'documento':
                    $patronDocumento = "/^[[:digit:]]+$/";
                    if (!preg_match($patronDocumento, $value)) {
                        $mensajesError['documento'] = "*1-Formato o valor del Dato incorrecto";
                        $marcaCampo['documento'] = "*1";
                    }
                    break;
                case 'nombre':
                    $patronNombre = "/^[^ ][a-zA-ZÁáÀàÉéÈèÍíÌìÓóÒòÚúÙùÑñüÜ ]*$/";
                    if (!preg_match($patronNombre, $value)) {
                        $mensajesError['nombre'] = "*2-Formato o valor del Dato incorrecto";
                        $marcaCampo['nombre'] = "*2";
                    }
                    break;
                case 'apellido':
                    $patronApellido = "/^[^ ][a-zA-ZÁáÀàÉéÈèÍíÌìÓóÒòÚúÙùÑñüÜ ]*$/";
                    if (!preg_match($patronApellido, $value)) {
                        $mensajesError['apellido'] = "*3-Formato o valor del Dato incorrecto";
                        $marcaCampo['apellido'] = "*3";
                    }
                    break;
                case 'email':
                    $patronEmail = "/^\w+([\.=]?\w+)*@\w+([\.=]?\w+)*(\.\w{2,3})+$/";
                    if (!preg_match($patronEmail, $value)) {
                        $mensajesError['email'] = "*4-Formato o valor del Dato incorrecto";
                        $marcaCampo['email'] = "*4";
                    }
                    break;
                case 'password':
                    $patronPassword = "/^\s*$/";//si está vacío
                    if (preg_match($patronPassword, $value)) {
                        $mensajesError['password'] = "*7-Formato o valor del Dato incorrecto";
                        $marcaCampo['password'] = "*8";
                    }

                    break;
            }
        }
        if (!is_null($mensajesError)) {
            $datosViejos['password'] = "";
            $datosViejos['password2'] = "";
            $datosViejos['ruta'] = "";
            
            return array('datosViejos' => $datosViejos, 'mensajesError' => $mensajesError, 'marcaCampo' => $marcaCampo);
            
        } else {
            $datosViejos = NULL;
            return FALSE;
        }
    }
    
}

