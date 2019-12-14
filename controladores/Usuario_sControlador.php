<?php
require_once PATH . 'controladores/ManejoSesiones/ClaseSesion.php';
require_once PATH . 'modelos/modeloUsuario_s/Usuario_sDAO.php';
require_once PATH . 'modelos/modeloPersona/PersonaDAO.php';
require_once PATH . 'modelos/modeloRol/RolDAO.php';
require_once PATH . 'modelos/modeloUsuario_s_roles/Usuario_s_rolesDAO.php';

class Usuario_sControlador
{
    private $datos = array();

    public function __construct()
    {
        if (!empty($_POST)) {
            $this->datos = $_POST;
        }
        if (!empty($_GET)) {
            $this->datos = $_GET;
        }
        //$this->usuario_sControlador();
    }

    public function crearpersona($rol){
                $gestarUsuario_s = new Usuario_sDAO(SERVIDOR, BASE, USUARIO_BD, CONTRASENIA_BD);
//                $insertarUsuario = new Usuario_sVO();
                if(!isset($this->datos['email'])){
                    $this->datos['email']=substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 15);
                    $this->datos['password']=md5("123");
                }
                $existeUsuario_s = $gestarUsuario_s->seleccionarId(array($this->datos["documento"], $this->datos['email'])); //Se revisa si existe la persona en la base
                if (0 == $existeUsuario_s['exitoSeleccionId']) {//Si no existe la persona en la base se procede a insertar
                    $this->datos['password'] = md5($this->datos['password']); //se encripta la contraseña que viene
                    $insertoUsuario_s = $gestarUsuario_s->insertar($this->datos); //inserción de los campos en la tabla usuario_s
                    $exitoInsercionUsuario_s = $insertoUsuario_s['inserto']; //indica si se logró inserción de los campos en la tabla usuario_s
                    $resultadoInsercionUsuario_s = $insertoUsuario_s['resultado']; //Traer el id con que quedó el usuario de lo contrario la excepción o fallo
//                    if (1 == $exitoInsercionUsuario_s) {//si se logró la inserción de los campos en la tabla usuario_s insertar datos en tabla persona
                    $gestarPersona = new PersonaDAO(SERVIDOR, BASE, USUARIO_BD, CONTRASENIA_BD);
                    $this->datos['perId'] = $resultadoInsercionUsuario_s; //Id 'usuID' con quedó insertado el usuario, con el fin que quede el mismo en la tabla 'persona'
                    $insertoPersona = $gestarPersona->insertar($this->datos); //inserción de los campos en la tabla persona
//                    echo __FILE__ . "-----" . __LINE__;
//                    exit(1);
                    $exitoInsercionPersona = $insertoPersona['inserto']; //indica si se logró inserción de los campos en la tabla persona
                    $resultadoInsercionPersona = $insertoPersona['resultado']; //***Si logró insertar trae el id con que quedó la persona de lo contrario la excepción o fallo
                    //FALTA AQUÍ IMPLEMENTAR LA VALIDACIÓN EN CASO DE NO INSERTAR EN LA TABLA persona
                    //
                    // SE ASIGNA UN ROL GENÉRICO (en este ejemplo 1) AL USUARIO REGISTRADO//
                    $asignarRol = new Usuario_s_rolesDAO(SERVIDOR, BASE, USUARIO_BD, CONTRASENIA_BD);
                    $rolAsignado=$asignarRol->insertar(array($resultadoInsercionUsuario_s,$rol));//Se envía el id con que quedó el usuario_s y el id del rol 
                    return $resultadoInsercionUsuario_s;
        }
    }

    public function usuario_sControlador()
    {
        $res = [
            "Acceso" => false
        ];
        $gestarUsuario_s = new Usuario_sDAO(
            SERVIDOR,
            BASE,
            USUARIO_BD,
            CONTRASENIA_BD
        );

        $this->datos["password"] = md5($this->datos["password"]); //Encriptamos password para que coincida con la base de datos
        $this->datos["documento"] = ""; //Para logueo crear ésta variable límpia por cuanto se utiliza el mismo método de registrarse a continuación
        $existeUsuario_s = $gestarUsuario_s->seleccionarId(array(
            $this->datos["documento"],
            $this->datos['username'],
            $this->datos["password"]
        ));
        //Se revisa si existe la persona en la base
        if (
            0 != $existeUsuario_s['exitoSeleccionId'] &&
            $existeUsuario_s['registroEncontrado']["usuLogin"] ==
                $this->datos['username']
        ) {
            //Consultamos los roles de la persona logueada
            $consultaRoles = new RolDAO(
                SERVIDOR,
                BASE,
                USUARIO_BD,
                CONTRASENIA_BD
            );
            $rolesUsuario = $consultaRoles->seleccionarRolPorPersona(array(
                $existeUsuario_s['registroEncontrado']["perDocumento"]
            ));
            $cantidadRoles = count($rolesUsuario['registroEncontrado']);
            $rolesEnSesion = array();
            for ($i = 0; $i < $cantidadRoles; $i++) {
                array_push(
                    $rolesEnSesion,
                    $rolesUsuario['registroEncontrado'][$i]["rolId"]
                );
            }
            $res = [
                "Acceso" => true,
                "usuario" => $existeUsuario_s['registroEncontrado'],
                "roles" => $rolesEnSesion
            ];
            echo json_encode($res);
        } else {
            echo json_encode($res);
        }
    }
}
