<?php

include_once PATH . 'modelos/ConBdMysql.php';

class PersonaDAO extends ConBdMySql
{
    public function __construct($servidor, $base, $loginBD, $passwordBD)
    {
        parent::__construct($servidor, $base, $loginBD, $passwordBD);
    }
    public function seleccionarRol($rol)
    {
        $planConsulta ="SELECT usuario_s.usuId,persona.perNombre,persona.perApellido,persona.perId,persona.perDocumento,rol.rolNombre FROM (((usuario_s JOIN usuario_s_roles ON usuario_s.usuId=usuario_s_roles.id_usuario_s) RIGHT JOIN rol ON usuario_s_roles.id_rol=rol.rolId) RIGHT JOIN persona ON usuario_s.usuId=persona.usuario_s_usuId)WHERE usuario_s_roles.id_rol= ? ";
        $listar = $this->conexion->prepare($planConsulta);
        $listar->execute(array($rol));

        $registroEncontrado = array();
        while ($registro = $listar->fetch(PDO::FETCH_ASSOC)) {
            $registroEncontrado[] = $registro;
        }
        return $registroEncontrado;
    }
    public function seleccionarId($Id)
    {
        //
        //        $resultadoConsulta = FALSE;
        $planConsulta ="select * from persona p join usuario_s u on p.perId=u.usuId ";
        $planConsulta .= " where u.usuId = ? ;";
        $listar = $this->conexion->prepare($planConsulta);
        $listar->execute(array($Id[0]));

        $registroEncontrado = array();
        while ($registro = $listar->fetch(PDO::FETCH_ASSOC)) {
            $registroEncontrado[] = $registro;
        }
        return $registroEncontrado;
    }

    public function insertar($registro)
    {
        try {
            $inserta = $this->conexion->prepare(
                'INSERT INTO persona (perId, perDocumento, perNombre, perApellido, usuario_s_usuId) VALUES ( :perId, :perDocumento, :perNombre, :perApellido, :usuario_s_usuId );'
            );
            $inserta->bindParam(":perId", $registro['perId']);
            $inserta->bindParam(":perDocumento", $registro['documento']);
            $inserta->bindParam(":perNombre", $registro['nombre']);
            $inserta->bindParam(":perApellido", $registro['apellidos']);
            $inserta->bindParam(":usuario_s_usuId", $registro['perId']);
            $insercion = $inserta->execute();
            $clavePrimariaConQueInserto = $this->conexion->lastInsertId();

            return ['inserto' => 1, 'resultado' => $clavePrimariaConQueInserto];
        }/* catch (Exception $exc) {
          return ['inserto' => FALSE, 'resultado' => $exc->getTraceAsString()];
          } */  catch (PDOException $pdoExc) {
            return ['inserto' => 0, 'resultado' => $pdoExc];
        }
    }

    public function seleccionarTodos()
    {
        $planConsulta = "select * from persona order by perApellido ASC;"; //Se prepara la consulta

        $registrosPersona = $this->conexion->prepare($planConsulta); //Se envia la consulta
        $registrosPersona->execute(); //Ejecución de la consulta
        $listadoRegistrosPersona = array();
        @$listadoRegistrosPersona[0]->usuId = "";
        @$listadoRegistrosPersona[0]->usuLogin = "Seleccione";

        while ($registro = $registrosPersona->fetch(PDO::FETCH_OBJ)) {
            $listadoRegistrosPersona[] = $registro;
        }

        return $listadoRegistrosPersona;
    }
    public function actualizar($registro)
    {
        try {
            $inserta = $this->conexion->prepare(
                'UPDATE persona SET  perNombre = :perNombre, perApellido = :perApellido WHERE persona.perId= :id ;'
            );
            $inserta->bindParam(":perNombre", $registro['nombre']);
            $inserta->bindParam(":perApellido", $registro['apellidos']);
            $inserta->bindParam(":id", $registro['id']);
            $insercion = $inserta->execute();

            return ['inserto' => $insercion];
        }/* catch (Exception $exc) {
          return ['inserto' => FALSE, 'resultado' => $exc->getTraceAsString()];
          } */  catch (PDOException $pdoExc) {
            return ['inserto' => 0, 'resultado' => $pdoExc];
        }
    }
}
