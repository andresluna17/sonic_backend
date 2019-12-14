<?php

include_once PATH . 'modelos/ConBdMysql.php';
/* http://www.mustbebuilt.co.uk/php/insert-update-and-delete-with-pdo */

class LibroDAO extends ConBdMySql {

    private $cantidadTotalRegistros;

    public function __construct($servidor, $base, $loginBD, $passwordBD) {

        parent::__construct($servidor, $base, $loginBD, $passwordBD);
    }

    public function seleccionarTodos() {
        $planConsulta = "SELECT l.isbn,l.titulo,l.autor,l.precio,cl.catLibId,cl.catLibNombre";
        $planConsulta .= " FROM libros l";
        $planConsulta .= " JOIN categorialibro cl ON l.categoriaLibro_catLibId=cl.catLibId ";
        $planConsulta .= " ORDER BY l.isbn ASC ";

        $registrosLibros = $this->conexion->prepare($planConsulta);
        $registrosLibros->execute(); //Ejecución de la consulta 

        $listadoRegistrosLibro = array();

        while ($registro = $registrosLibros->fetch(PDO::FETCH_OBJ)) {
            $listadoRegistrosLibro[] = $registro;
        }

        $this->cierreBd();

        return $listadoRegistrosLibro;
    }

    public function seleccionarId($sId = array()) {
        $planConsulta = "select * from libros l ";
        $planConsulta .= " where l.isbn= ? ;";

        $listar = $this->conexion->prepare($planConsulta);
        $listar->execute(array($sId[0]));

        $registroEncontrado = array();

        while ($registro = $listar->fetch(PDO::FETCH_OBJ)) {
            $registroEncontrado[] = $registro;
        }

        $this->cierreBd();
//Retorna si fue exitoso o no hallar el registro con la llave primaria y sus datos o vacío       
        if (!empty($registroEncontrado)) {
            return ['exitoSeleccionId' => TRUE, 'registroEncontrado' => $registroEncontrado];
        } else {
            return ['exitoSeleccionId' => FALSE, 'registroEncontrado' => $registroEncontrado];
        }
    }

    public function insertar($registro) {
        try {

            $query = "INSERT INTO libros";
            $query .= "(isbn, titulo, autor, precio, categoriaLibro_catLibId) ";
            $query .= " VALUES";
            $query .= "(:isbn , :titulo , :autor , :precio , :categoriaLibro_catLibId ); ";

            $inserta = $this->conexion->prepare($query);

            $inserta->bindParam(":isbn", $registro['isbn']);
            $inserta->bindParam(":titulo", $registro['titulo']);
            $inserta->bindParam(":autor", $registro['autor']);
            $inserta->bindParam(":precio", $registro['precio']);
            $inserta->bindParam(":categoriaLibro_catLibId", $registro['categoriaLibro_catLibId']);

            $insercion = $inserta->execute();

            $clavePrimariaConQueInserto = $this->conexion->lastInsertId();

            return ['inserto' => 1, 'resultado' => $clavePrimariaConQueInserto];
        } catch (PDOException $pdoExc) {

            return ['inserto' => 0, 'resultado' => $pdoExc];
        }
    }

    public function actualizar($registro) {
        try {
            $autor = $registro[0]['autor'];
            $titulo = $registro[0]['titulo'];
            $precio = $registro[0]['precio'];
            $categoria = $registro[0]['categoriaLibro_catLibId'];
            $isbn = $registro[0]['isbn'];

            if (isset($isbn)) {
                $actualizar = "UPDATE libros SET autor= ? , titulo = ? , precio = ? , categoriaLibro_catLibId = ? WHERE isbn= ? ;";
                $actualizacion = $this->conexion->prepare($actualizar);
                $actualizacion = $actualizacion->execute(array($autor, $titulo, $precio, $categoria, $isbn));
                return ['actualizacion' => $actualizacion, 'mensaje' => "Actualización realizada."];
            }
        } catch (PDOException $pdoExc) {
            return ['actualizacion' => $actualizacion, 'mensaje' => $pdoExc];
        }
    }

    public function eliminar($sId = array()) {//Recibe llave primaria a eliminar
        $planConsulta = "delete from libros ";
        $planConsulta .= " where isbn= :isbn ;";
        $eliminar = $this->conexion->prepare($planConsulta);
        $eliminar->bindParam(':isbn', $sId[0], PDO::PARAM_INT);
        $eliminar->execute();

        $this->cierreBd();

        if (!empty($resultado)) {
            return ['eliminar' => TRUE, 'registroEliminado' => array($sId[0])];
        } else {
            return ['eliminar' => FALSE, 'registroEliminado' => array($sId[0])];
        }
    }

    public function eliminarLogico($sId = array()) {// Se deshabilita un registro cambiando su estado a 0
        try {

            $cambiarEstado = 0;

            if (isset($sId[0])) {
                $actualizar = "UPDATE libros SET estado = ? WHERE isbn= ? ;";
                $actualizacion = $this->conexion->prepare($actualizar);
                $actualizacion = $actualizacion->execute(array($cambiarEstado, $sId[0]));
                return ['actualizacion' => $actualizacion, 'mensaje' => "Registro Inactivado."];
            }
        } catch (PDOException $pdoExc) {
            return ['actualizacion' => $actualizacion, 'mensaje' => $pdoExc];
        }
    }

    public function habilitar($sId = array()) {// Se habilita un registro cambiando su estado a 1
        try {

            $cambiarEstado = 1;

            if (isset($sId[0])) {
                $actualizar = "UPDATE libros SET estado = ? WHERE isbn= ? ;";
                $actualizacion = $this->conexion->prepare($actualizar);
                $actualizacion = $actualizacion->execute(array($cambiarEstado, $sId[0]));
                return ['actualizacion' => $actualizacion, 'mensaje' => "Registro Inactivado."];
            }
        } catch (PDOException $pdoExc) {
            return ['actualizacion' => $actualizacion, 'mensaje' => $pdoExc];
        }
    }

    public function consultaPaginada($limit = NULL, $offset = NULL, $filtrarBuscar = "") {

        $planConsulta = "select SQL_CALC_FOUND_ROWS l.isbn,l.titulo,l.autor,l.precio,cl.catLibId,cl.catLibNombre ";
        $planConsulta .= " from libros l ";
        $planConsulta .= " join categorialibro cl ";
        $planConsulta .= " ON  l.categoriaLibro_catLibId=cl.catLibId  ";

        $planConsulta .= $filtrarBuscar;

        $planConsulta .= "  order by l.isbn asc";
        $planConsulta .= " LIMIT " . $limit . " OFFSET " . $offset . " ; ";
     
        $listar = $this->conexion->prepare($planConsulta);
        $listar->execute();

        $listadoLibros = array();

        while ($registro = $listar->fetch(PDO::FETCH_OBJ)) {
            $listadoLibros[] = $registro;
        }

        $listar2 = $this->conexion->prepare("SELECT FOUND_ROWS() as total;");
        $listar2->execute();
        while ($registro = $listar2->fetch(PDO::FETCH_OBJ)) {
            $totalRegistros = $registro->total;
        }
        $this->cantidadTotalRegistros = $totalRegistros;

        return array($totalRegistros, $listadoLibros);

    }

    public function totalRegistros() {

        $planConsulta = "SELECT count(*) as total from libros; ";

        $cantidadLibros = $this->conexion->prepare($planConsulta);
        $cantidadLibros->execute(); //Ejecución de la consulta 

        $totalRegistrosLibros = "";

        $totalRegistrosLibros = $cantidadLibros->fetch(PDO::FETCH_OBJ);

        $this->cierreBd();

        return $totalRegistrosLibros;
    }

}
?>

