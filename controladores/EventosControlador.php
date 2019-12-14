<?php
header('Access-Control-Allow-Origin: *');
require_once PATH . 'modelos/modeloPersona/PersonaDAO.php';
require_once PATH . 'modelos/modeloRol/RolDAO.php';
require_once PATH . 'modelos/modeloEventos/EventosDAO.php';
require_once PATH . 'modelos/modeloUsuario_s_roles/Usuario_s_rolesDAO.php';
require_once PATH . 'modelos/modeloCatEvento/CatEventoDAO.php';
require_once PATH . 'controladores/Usuario_sControlador.php';
require_once PATH . 'modelos/modeloInvitados/InvitadosDAO.php';
require_once PATH . 'modelos/modeloEmpleados/empleadosDAO.php';

class EventosControlador
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
        $this->EventosControlador();
    }


    public function EventosControlador()
    {
        switch ($this->datos["ruta"]) {
            case 'listarEventos':
                $gestionarcategoria= new CatEventoDAO(SERVIDOR,BASE,USUARIO_BD,CONTRASENIA_BD);
                $gestionarEventos = new EventosDAO(SERVIDOR,BASE,USUARIO_BD,CONTRASENIA_BD);
                $eventos=$gestionarEventos->seleccionarTodos();
                $contador=count($eventos);
                $gestionarpersonas= new PersonaDAO(SERVIDOR,BASE,USUARIO_BD,CONTRASENIA_BD);
                
                for($i=0;$i < $contador ; $i++){
                    //$categoria= $gestionarcategoria->seleccionarTodos();
                    $categoria= $gestionarcategoria->seleccionarId(array($eventos[$i]["eveCategoria"]));
                    $ev=["nombreCategoria" => $categoria[0]["catNombre"]];
                    $clientes=$gestionarpersonas->seleccionarId(array($eventos[$i]["Idcliente"]));
                    $lider=$gestionarpersonas->seleccionarId(array($eventos[$i]["persona_perId"]));
                    $l=["lider"=>$lider[0]];
                    $c=["cliente" => $clientes[0]];
                    $eventos[$i]+=$l;
                    $eventos[$i]+=$c;
                    $eventos[$i]+=$ev;
                }
                echo json_encode($eventos);
        
                break;
            case "formularios":
                $gestionarcategoria= new CatEventoDAO(SERVIDOR,BASE,USUARIO_BD,CONTRASENIA_BD);
                $categorias= $gestionarcategoria->seleccionarTodos();
                $gestionarlideres= new personaDAO(SERVIDOR,BASE,USUARIO_BD,CONTRASENIA_BD);
                $lideres= $gestionarlideres->seleccionarRol(5);
                $respuesta=[
                    "categorias" => $categorias ,
                    "lideres" => $lideres
                ];
                echo json_encode($respuesta);
            break;
            case "insertarEvento":
                $cliente = new Usuario_sControlador();
                $idcliente=$cliente->crearpersona(3);
                $this->datos["cliente"]=$idcliente;
                $gestionarEventos = new EventosDAO(SERVIDOR,BASE,USUARIO_BD,CONTRASENIA_BD);
                $respuesta =$gestionarEventos->insertar($this->datos);
                echo json_encode($respuesta);
            break;
            case 'listardestalles':
                $gestionarempleado=new empleadosDAO(SERVIDOR,BASE,USUARIO_BD,CONTRASENIA_BD);
                $empleados=$gestionarempleado->seleccionarTodos($this->datos["id"]);
                $gestionarinvitados=new InvitadosDAO(SERVIDOR,BASE,USUARIO_BD,CONTRASENIA_BD);
                $invitados=$gestionarinvitados->seleccionarTodos($this->datos["id"]);
                $gestionarEventos = new EventosDAO(SERVIDOR,BASE,USUARIO_BD,CONTRASENIA_BD);
                $evento=$gestionarEventos->seleccionarId($this->datos["id"]);
                $respuesta=[
                    "empleados" => $empleados,
                    "invitados" => $invitados,
                    "evento" => $evento["registroEncontrado"][0]
                ];
                echo json_encode($respuesta); 
            break;
            case 'actualizarevento':
                $gestionarEventos = new EventosDAO(SERVIDOR,BASE,USUARIO_BD,CONTRASENIA_BD);
                $respuesta=$gestionarEventos->actualizar($this->datos);
                echo json_encode($respuesta); 
            break;
            case 'eliminarInvitado':
                $gestionarinvitados=new InvitadosDAO(SERVIDOR,BASE,USUARIO_BD,CONTRASENIA_BD);
                $respuesta=$gestionarinvitados->eliminar($this->datos["id"]);
                echo json_encode($respuesta); 
            break;
            case 'invitadonuevo':
                $cliente = new Usuario_sControlador();
                $idcliente=$cliente->crearpersona(4);
                if(!empty($idcliente)){
                    $this->datos["invitado"]=$idcliente;
                    $gestionarinvitados=new InvitadosDAO(SERVIDOR,BASE,USUARIO_BD,CONTRASENIA_BD);
                    $respuesta=$gestionarinvitados->insertar($this->datos);
                    echo json_encode($respuesta);
                }else{
                    echo json_encode(['inserto' => 0]);
                }
            break;
            case 'actualizarinvitado':
                $gestionarpersonas= new PersonaDAO(SERVIDOR,BASE,USUARIO_BD,CONTRASENIA_BD);
                $actualizacion=$gestionarpersonas->actualizar($this->datos);
                if($actualizacion["inserto"]){
                    $gestionarinvitados=new InvitadosDAO(SERVIDOR,BASE,USUARIO_BD,CONTRASENIA_BD);
                    $actualizar=$gestionarinvitados->actualizar($this->datos);
                    echo json_encode($actualizar);
                }
            break;
            case 'listarempleadosnoasignados':
                $gestionarempleado=new empleadosDAO(SERVIDOR,BASE,USUARIO_BD,CONTRASENIA_BD);
                $empleadosevento=$gestionarempleado->seleccionardiferente($this->datos["id"]);
                $gestionarlideres= new personaDAO(SERVIDOR,BASE,USUARIO_BD,CONTRASENIA_BD);
                $empleados= $gestionarlideres->seleccionarRol(2);
                $contador= count($empleadosevento);
                $contador1= count($empleados);
                $respuesta=array();
                for ($j=0; $j < $contador1 ; $j++) { 
                    for ($i=0; $i < $contador ; $i++) {                         
                            if ($empleados[$j]["perId"]==$empleadosevento[$i]["perId"]) {
                                break 2;                      
                            }else{
                                $i=0;
                                $respuesta[]=$empleados[$j];
                                break;
                            }
                    }
                }
                echo json_encode($respuesta);
                break;
            case 'asignarempleado':
                $gestionarempleado=new empleadosDAO(SERVIDOR,BASE,USUARIO_BD,CONTRASENIA_BD);
                $respuesta=$gestionarempleado->insertar($this->datos);
                echo json_encode($respuesta);
            break;
            case 'eliminarasigempleado':
                $gestionarempleado=new empleadosDAO(SERVIDOR,BASE,USUARIO_BD,CONTRASENIA_BD);
                $respuesta=$gestionarempleado->eliminar($this->datos["id"]);
                echo json_encode($respuesta);
            break;
            case 'eliminarevento':
                $gestionarempleado=new empleadosDAO(SERVIDOR,BASE,USUARIO_BD,CONTRASENIA_BD);
                $empleados=$gestionarempleado->seleccionarTodos($this->datos["id"]);
                $contador=count($empleados);
                for ($i=0; $i < $contador ; $i++) { 
                        $gestionarempleado->eliminar($empleados[$i]["inteId"]);
                }
                $gestionarinvitados=new InvitadosDAO(SERVIDOR,BASE,USUARIO_BD,CONTRASENIA_BD);
                $invitados=$gestionarinvitados->seleccionarTodos($this->datos["id"]);
                $contador2=count($invitados);
                for ($i=0; $i < $contador2 ; $i++) { 
                        $gestionarinvitados->eliminar($invitados[$i]["invId"]);
                }
                $gestionarEventos = new EventosDAO(SERVIDOR,BASE,USUARIO_BD,CONTRASENIA_BD);
                $evento=$gestionarEventos->eliminar($this->datos["id"]);
                echo json_encode($evento);
            break;
            case 'personal':
                $gestionarlideres= new personaDAO(SERVIDOR,BASE,USUARIO_BD,CONTRASENIA_BD);
                $lideres= $gestionarlideres->seleccionarRol(5);
                $Empleados=$gestionarlideres->seleccionarRol(2);
                $respuesta=[
                    "empleados" => $Empleados,
                    "lideres" => $lideres
                ];    
                echo json_encode($respuesta);
            break;
            case 'nuevoempleado':
                $cliente = new Usuario_sControlador();
                $idcliente=$cliente->crearpersona($this->datos["rol"]);
                echo json_encode(["inserto" => true]);
            break;
            case 'actualizarempleado':
                $gestionarpersonas= new PersonaDAO(SERVIDOR,BASE,USUARIO_BD,CONTRASENIA_BD);
                $actualizacion=$gestionarpersonas->actualizar($this->datos);
                echo json_encode($actualizacion);
            break;
            default:
                break;
        }
    }
}
