<?php

include_once PATH . 'modelos/ConBdMysql.php';

class EventosDAO extends ConBdMySql {

    public function __construct($servidor, $base, $loginBD, $passwordBD) {

        parent::__construct($servidor, $base, $loginBD, $passwordBD);
    }

    public function seleccionarTodos() {

        $planConsulta = " SELECT * ";
        $planConsulta .= " FROM eventos";

        $registrosCategoriaLibro = $this->conexion->prepare($planConsulta);

        $registrosCategoriaLibro->execute();

        $listadoRegistrosCategoriasLibros = array();

        while ($registro = $registrosCategoriaLibro->fetch(PDO::FETCH_ASSOC)) {
            $listadoRegistrosCategoriasLibros[] = $registro;
        }
        $this->cierreBd();
        return $listadoRegistrosCategoriasLibros;
    }

    public function insertar($registro) {

        try {
            $query = "INSERT INTO eventos";
            $query .= "(eveNombre, eveFecha, eveUbicacion,eveCategoria,Idcliente,persona_perId)";
            $query .= " VALUES";
            $query .= "(:nombre,:fecha,:ubicacion,:categoria,:cliente,:lider); ";
            $inserta = $this->conexion->prepare($query);
            $inserta->bindParam(":nombre", $registro['eventonombre']);
            $inserta->bindParam(":fecha", $registro['fecha']);
            $inserta->bindParam(":ubicacion", $registro['ubicacion']);
            $inserta->bindParam(":categoria", $registro['categoria']);
            $inserta->bindParam(":cliente", $registro['cliente']);
            $inserta->bindParam(":lider", $registro['lider']);

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
            $id = $registro['id'];

            if (!empty($id)) {
                $actualizar = "UPDATE eventos SET eveNombre= :nombre ,  eveUbicacion =:ubicaion, eveCategoria =:categoria,persona_perId =:lider WHERE eveId= :id ; ";
                $actualizacion = $this->conexion->prepare($actualizar);
                $actualizacion->bindParam(":nombre", $registro["nombre"]);
                $actualizacion->bindParam(":ubicaion", $registro["ubicaion"]);
                $actualizacion->bindParam(":categoria", $registro["categoria"]);
                $actualizacion->bindParam(":lider", $registro["lider"]);
                $actualizacion->bindParam(":id", $registro["id"]);
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
                $planConsulta = "delete from eventos ";
                $planConsulta .= "where  eveId= :id ;";
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
