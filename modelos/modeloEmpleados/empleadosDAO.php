<?php

include_once PATH . 'modelos/ConBdMysql.php';

class empleadosDAO extends ConBdMySql {

    public function __construct($servidor, $base, $loginBD, $passwordBD) {

        parent::__construct($servidor, $base, $loginBD, $passwordBD);
    }

    public function seleccionarTodos($id) {

        $planConsulta = " SELECT persona.perDocumento,persona.perId,persona.perNombre,persona.perApellido,rol.rolNombre,integrantes_equipo.inteId FROM ((((usuario_s JOIN usuario_s_roles ON usuario_s_roles.id_usuario_s=usuario_s.usuId) RIGHT JOIN rol ON rol.rolId=usuario_s_roles.id_rol) RIGHT JOIN persona ON persona.usuario_s_usuId=usuario_s.usuId) RIGHT JOIN integrantes_equipo ON integrantes_equipo.inteIdpersona=persona.perId) RIGHT JOIN eventos ON eventos.eveId=integrantes_equipo.eventos_eveId WHERE integrantes_equipo.eventos_eveId= ? ";

        $registrosCategoriaLibro = $this->conexion->prepare($planConsulta);

        $registrosCategoriaLibro->execute(array($id));

        $listadoRegistrosCategoriasLibros = array();

        while ($registro = $registrosCategoriaLibro->fetch(PDO::FETCH_ASSOC)) {
            $listadoRegistrosCategoriasLibros[] = $registro;
        }
        return $listadoRegistrosCategoriasLibros;
    }

    public function seleccionardiferente($id) {

        $planConsulta = " SELECT persona.perDocumento,persona.perId,persona.perNombre,persona.perApellido FROM ((((usuario_s JOIN usuario_s_roles ON usuario_s_roles.id_usuario_s=usuario_s.usuId) RIGHT JOIN rol ON rol.rolId=usuario_s_roles.id_rol) RIGHT JOIN persona ON persona.usuario_s_usuId=usuario_s.usuId) RIGHT JOIN integrantes_equipo ON integrantes_equipo.inteIdpersona=persona.perId) RIGHT JOIN eventos ON eventos.eveId=integrantes_equipo.eventos_eveId WHERE integrantes_equipo.eventos_eveId = ?";

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
            $query = "INSERT INTO integrantes_equipo";
            $query .= "(inteIdpersona, eventos_eveId)";
            $query .= " VALUES";
            $query .= "(:Empleado,:evento); ";
            $inserta = $this->conexion->prepare($query);
            $inserta->bindParam(":Empleado", $registro['empleado']);
            $inserta->bindParam(":evento", $registro['evento']);

            $insercion = $inserta->execute();

            $respuesta = $this->conexion->lastInsertId();

            return ['inserto' => $insercion, 'resultado' => $respuesta];
        } catch (PDOException $pdoExc) {

            return ['inserto' => 0, 'resultado' => $pdoExc];
        }
    }


    public function eliminar($id) {//Recibe llave primaria a eliminar
        try {
            if (!empty($id)) {
                $planConsulta = "delete from integrantes_equipo ";
                $planConsulta .= "where  inteId= :id ;";
                $eliminar = $this->conexion->prepare($planConsulta);
                $eliminar->bindParam(':id', $id, PDO::PARAM_INT);
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
