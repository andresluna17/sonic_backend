<?php
include_once PATH . 'modelos/ConBdMysql.php';

class CatEventoDAO extends ConBdMySql {

    public function __construct($servidor, $base, $loginBD, $passwordBD) {

        parent::__construct($servidor, $base, $loginBD, $passwordBD);
    }

    public function seleccionarTodos() {

        $planConsulta = " SELECT catId,catNombre,catCapcidad ";
        $planConsulta .= " FROM categoria_eventos";

        $registrosCategoriaLibro = $this->conexion->prepare($planConsulta);

        $registrosCategoriaLibro->execute();

        $listadoRegistrosCategoriasLibros = array();

        while ($registro = $registrosCategoriaLibro->fetch(PDO::FETCH_OBJ)) {
            $listadoRegistrosCategoriasLibros[] = $registro;
        }
        return $listadoRegistrosCategoriasLibros;
    }

    public function insertar($registro) {

        try {
            $query = "INSERT INTO categoria_eventos";
            $query .= "(catNombre, catCapcidad, catUsuSesion)";
            $query .= " VALUES";
            $query .= "(:catNombre,:catCapcidad,:catUsuSesion); ";
            $inserta = $this->conexion->prepare($query);
            $inserta->bindParam(":catNombre", $registro['nombre']);
            $inserta->bindParam(":catCapcidad", $registro['capacidad']);
            $inserta->bindParam(":catUsuSesion", $registro['usuario']);

            $insercion = $inserta->execute();

            $respuesta = $this->conexion->lastInsertId();

            return ['inserto' => 1, 'resultado' => $respuesta];
        } catch (PDOException $pdoExc) {

            return ['inserto' => 0, 'resultado' => $pdoExc];
        }
    }

    public function seleccionarId($Id) {

        try {

            $sql="select * from categoria_eventos e where e.catId= ? ;";
            $consulta = $this->conexion->prepare($sql);
            $consulta->execute(array($Id[0]));

            $registroEncontrado = array();

            while ($registro = $consulta->fetch(PDO::FETCH_ASSOC)) {
                $registroEncontrado[] = $registro;
            }
            return  $registroEncontrado;
        } catch (PDOException $pdoExc) {
            return ['exitoSeleccionId' => FALSE, 'registroEncontrado' => $pdoExc];
        }
    }

    public function actualizar($registro) {
        try {
            $id = $registro['id'];

            if (!empty($id)) {
                $actualizar = "UPDATE categoria_eventos SET catNombre= :nombre ,  catCapcidad =:capacidad WHERE catId= :id ; ";
                $actualizacion = $this->conexion->prepare($actualizar);
                $actualizacion->bindParam(":nombre", $registro["nombre"]);
                $actualizacion->bindParam(":capacidad", $registro["capacidad"]);
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
            $id1 = $id['id'];

            if (!empty($id)) {
                $planConsulta = "delete from categoria_eventos ";
                $planConsulta .= "where  catId= :id ;";
                $eliminar = $this->conexion->prepare($planConsulta);
                $eliminar->bindParam(':id',  $id1, PDO::PARAM_INT);
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
            $id1=$id["id"];
            if(!empty($id1)){
                $actualizar = "UPDATE categoria_eventos SET catEstado =:estado WHERE catId= :id ;";
                $actualizacion = $this->conexion->prepare($actualizar);
                $actualizacion ->bindParam(':id', $id["id"] , PDO::PARAM_INT);
                $actualizacion ->bindParam(':estado', $cambiarEstado);
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
                $actualizacion ->bindParam(':id', $id , PDO::PARAM_INT);
                $actualizacion ->bindParam(':estado', $cambiarEstado);
                $actualizacion = $actualizacion->execute();
                return ['actualizacion' => $actualizacion, 'mensaje' => "Registro Activado."];
            }
        } catch (PDOException $pdoExc) {
            return ['actualizacion' => $actualizacion, 'mensaje' => $pdoExc];
        }
    }
}
?>


