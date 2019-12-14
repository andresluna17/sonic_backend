<?php

include_once PATH . 'modelos/ConBdMysql.php';

class InvitadosDAO extends ConBdMySql {

    public function __construct($servidor, $base, $loginBD, $passwordBD) {

        parent::__construct($servidor, $base, $loginBD, $passwordBD);
    }

    public function seleccionarTodos($id) {

        $planConsulta = "SELECT persona.perId,persona.perNombre,persona.perApellido,persona.perDocumento,invitaciones.invMesa,invitaciones.invId,invitaciones.invEstado FROM ((((usuario_s JOIN usuario_s_roles ON usuario_s_roles.id_usuario_s=usuario_s.usuId) RIGHT JOIN rol ON rol.rolId=usuario_s_roles.id_rol) RIGHT JOIN persona ON persona.usuario_s_usuId=usuario_s.usuId) RIGHT JOIN invitaciones ON invitaciones.persona_perId=persona.perId) RIGHT JOIN eventos ON eventos.eveId=invitaciones.eventos_eveId WHERE invitaciones.eventos_eveId= ?";

        $registrosCategoriaLibro = $this->conexion->prepare($planConsulta);

        $registrosCategoriaLibro->execute(array($id));

        $listadoRegistrosCategoriasLibros = array();

        while ($registro = $registrosCategoriaLibro->fetch(PDO::FETCH_ASSOC)) {
            $listadoRegistrosCategoriasLibros[] = $registro;
        }
        return $listadoRegistrosCategoriasLibros;
    }

    public function insertar($registro) {
        try {
            $query = "INSERT INTO invitaciones ( `invMesa`, `persona_perId`, `eventos_eveId`) VALUES (:mesa,:invitado,:evento)";
            $inserta = $this->conexion->prepare($query);
            $inserta->bindParam(":mesa", $registro['mesa']);
            $inserta->bindParam(":evento", $registro['evento']);
            $inserta->bindParam(":invitado", $registro['invitado']);
            $insercion = $inserta->execute();
            $respuesta = $this->conexion->lastInsertId();

            return ['inserto' => $insercion, 'resultado' => $respuesta];
        } catch (PDOException $pdoExc) {

            return ['inserto' => 0, 'resultado' => $pdoExc];
        }
    }

    public function seleccionarId($Id) {

        try {

            $aqui_sql = "SELECT * FROM eventos WHERE eventos.eveId= :id ;";
            $consulta = $this->conexion->prepare($aqui_sql);
            $consulta->bindParam(":id", $Id);
            $consulta->execute();

            $registroEncontrado = array();

            while ($registro = $consulta->fetch(PDO::FETCH_ASSOC)) {
                $registroEncontrado[] = $registro;
            }
            return ['consultaexitosa' => TRUE, 'registroEncontrado' => $registroEncontrado];
        } catch (PDOException $pdoExc) {

            return ['exitoSeleccionId' => FALSE, 'registroEncontrado' => $pdoExc];
        }
    }

    public function actualizar($registro) {
        try {
            $id = $registro['idp'];

            if (!empty($id)) {
                $actualizar = "UPDATE invitaciones SET invEstado= :estado  WHERE invId= :id ; ";
                $actualizacion = $this->conexion->prepare($actualizar);
                $actualizacion->bindParam(":estado", $registro["estado"]);
                $actualizacion->bindParam(":id", $registro["idp"]);
                $actualizacion = $actualizacion->execute();
                return ['actualizacion' => $actualizacion, 'mensaje' => "Actualización realizada."];
            }
        } catch (PDOException $pdoExc) {
            return ['actualizacion' => $actualizacion, 'mensaje' => $pdoExc];
        }
    }

    public function eliminar($id) {//Recibe llave primaria a eliminar
        try {
            if (!empty($id)) {
                $planConsulta = "delete from invitaciones ";
                $planConsulta .= "where  invId= :id ;";
                $eliminar = $this->conexion->prepare($planConsulta);
                $eliminar->bindParam(':id', $id);
                $resultado = $eliminar->execute();
                return ['eliminacion' => $resultado, 'mensaje' => "eliminacion exitosa."];
            }
        } catch (PDOException $pdoExc) {
            return ['eliminacion' => $actualizacion, 'mensaje' => $pdoExc];
        }
    }

    public function eliminarLogico($id) {// Se deshabilita un registro cambiando su estado a 0
        try {
            $cambiarEstado = 0;
            $id1 = $id["id"];
            if (!empty($id1)) {
                $actualizar = "UPDATE categoria_eventos SET catEstado =:estado WHERE catId= :id ;";
                $actualizacion = $this->conexion->prepare($actualizar);
                $actualizacion->bindParam(':id', $id["id"], PDO::PARAM_INT);
                $actualizacion->bindParam(':estado', $cambiarEstado);
                $actualizacion = $actualizacion->execute();
                return ['actualizacion' => $actualizacion, 'mensaje' => "Registro Inactivado."];
            }
        } catch (PDOException $pdoExc) {
            return ['actualizacion' => $actualizacion, 'mensaje' => $pdoExc];
        }
    }

    public function habilitar($id) {// Se habilita un registro cambiando su estado a 1
        try {

            $cambiarEstado = 1;

            if (!empty($id)) {
                $actualizar = "UPDATE categoria_eventos SET catEstado =:estado WHERE catId= :id ;";
                $actualizacion = $this->conexion->prepare($actualizar);
                $actualizacion->bindParam(':id', $id, PDO::PARAM_INT);
                $actualizacion->bindParam(':estado', $cambiarEstado);
                $actualizacion = $actualizacion->execute();
                return ['actualizacion' => $actualizacion, 'mensaje' => "Registro Activado."];
            }
        } catch (PDOException $pdoExc) {
            return ['actualizacion' => $actualizacion, 'mensaje' => $pdoExc];
        }
    }

}

?>
