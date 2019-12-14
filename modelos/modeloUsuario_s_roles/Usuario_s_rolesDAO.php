<?php

include_once PATH . 'modelos/ConBdMysql.php';
/* http://www.mustbebuilt.co.uk/php/insert-update-and-delete-with-pdo */

class Usuario_s_rolesDAO extends ConBdMySql {

    private $cantidadTotalRegistros;

    public function __construct($servidor, $base, $loginBD, $passwordBD) {

        parent::__construct($servidor, $base, $loginBD, $passwordBD);
    }

        public function insertar($registro) {
        try {

            $query = "INSERT INTO usuario_s_roles ";
            $query .= "(id_usuario_s, id_rol) ";
            $query .= " VALUES ";
            $query .= "(:id_usuario_s , :id_rol ); ";

            $inserta = $this->conexion->prepare($query);

            $inserta->bindParam(":id_usuario_s", $registro[0]);
            $inserta->bindParam(":id_rol", $registro[1]);

            $insercion = $inserta->execute();

            $clavePrimariaConQueInserto = $this->conexion->lastInsertId();

            return ['inserto' => 1, 'resultado' => $clavePrimariaConQueInserto];
        } catch (PDOException $pdoExc) {

            return ['inserto' => 0, 'resultado' => $pdoExc];
        }
    }
    
}
