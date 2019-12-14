<?php

class ValidadorLibros {

    public function validarFormularioLibro($datos) {
        $mensajesError = NULL;
        $datosViejos = NULL;
        $marcaCampo = NULL;

        /*         * ****Validar datos ingresados************************ */
        foreach ($datos as $key => $value) {

            $datosViejos[$key] = $value;

            switch ($key) {

                case 'isbn':
                    $patronDocumento = "/^[[:digit:]]+$/";
                    if (!preg_match($patronDocumento, $value)) {
                        $mensajesError['isbn'] = "*1-Formato/Dato incorrecto";
                        $marcaCampo['isbn'] = "*1";
                    }
                    break;
                case 'titulo':
//                    $patronDocumento = "/^[^ ][0-9a-zA-ZÁáÀàÉéÈèÍíÌìÓóÒòÚúÙùÑñüÜ- ]*$/";
                    $patronDocumento = "//";
                    if (!preg_match($patronDocumento, $value)) {
                        $mensajesError['titulo'] = "*2-Formato/Dato incorrecto";
                        $marcaCampo['titulo'] = "*2";
                    }
                    break;
                case 'autor':
//                    $patronDocumento = "/^[^ ][a-zA-ZÁáÀàÉéÈèÍíÌìÓóÒòÚúÙùÑñüÜ ]*$/";
                    $patronDocumento = "//";
                    if (!preg_match($patronDocumento, $value)) {
                        $mensajesError['autor'] = "*3-Formato/Dato incorrecto";
                        $marcaCampo['autor'] = "*3";
                    }
                    break;
                case 'precio':
                    $patronDocumento = "/^[[:digit:]]+$/";
                    if (!preg_match($patronDocumento, $value)) {
                        $mensajesError['precio'] = "*4-Formato/Dato incorrecto";
                        $marcaCampo['precio'] = "*4";
                    }
                    break;
            }
        }
        if (!is_null($mensajesError)) {
            return array('datosViejos' => $datosViejos, 'mensajesError' => $mensajesError, 'marcaCampo' => $marcaCampo);
        } else {
            $datosViejos = NULL;
            return FALSE;
        }
    }

}

