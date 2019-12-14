<?php
echo "<pre>";
print_r($_GET);
echo "</pre>";

if (isset($_GET['ruta'])) {   //***********capturar en local lo que venga por GET
    $ruta = $_GET['ruta'];
}
if (isset($_POST['ruta'])) {   //***********capturar en local lo que venga por POST
    $ruta = $_POST['ruta'];
}

switch ($ruta) {
    case "listar":
        listar();
        break;
    case "cancelar":
        cancelar();
        break;
    case "agregar":
        agregar();
        break;
    case "eliminar":
        eliminar();
        break;
    case "actualizar":
        actualizar();
        break;
    case "vistaAgregar":
        vistaAgregar();
        break;
    case "vistaActualizar":
        vistaActualizar();
        break;
    case "confirmarActualizar":
        confirmarActualizar();
        break;
}

////******************************/////FUNCIONES*******/////////
function confirmarActualizar() {
    if (isset($_GET['cedula'])) {
        $cedula = $_GET['cedula'];
        $nombre = $_GET['nombre'];
        $correo = $_GET['correo'];
        $telfij = $_GET['telfij'];
        $telmov = $_GET['telmov'];

        $resultadoBuscar = (buscar($_GET['cedula']));
        $posicion = $resultadoBuscar['posicion'];
    }
    if (isset($_POST['cedula'])) {
        $cedula = $_POST['cedula'];
        $nombre = $_POST['nombre'];
        $correo = $_POST['correo'];
        $telfij = $_POST['telfij'];
        $telmov = $_POST['telmov'];

        $resultadoBuscar = (buscar($_GET['cedula']));
        $posicion = $resultadoBuscar['posicion'];
    }

    $datosNuevos = strtoupper($cedula . ":" . $nombre . ":" . $correo . ":" . $telfij . ":" . $telmov . ":" . "\n");
    $registros = file("archivo.txt");
    $registros[$posicion] = $datosNuevos;
    $totalReg = count($registros);

    $recurso = fopen("archivo.txt", "w+");

    for ($i = 0; $i < $totalReg; $i++) {
        fwrite($recurso, $registros[$i]);
    }

    fclose($recurso);

    header("Location:controlador.php?ruta=listar");
}

function vistaActualizar() {

    header("Location:vistaActualizar.php");
}

function actualizar() {
    $resultadoBuscar = buscar($_GET['cedula']);

    $posicion = $resultadoBuscar['posicion']; //Posición del registro a actualizar

    $registros = file("archivo.txt");

    $registroActualizar = $registros[$posicion];

    session_start();
    $_SESSION['registroActualizar'] = $registroActualizar;
    $_SESSION['posicion'] = $posicion;

    header("Location:controlador.php?ruta=vistaActualizar");
}

function eliminar() {
    $resultadoBuscar = (buscar($_GET['cedula']));

    $registros = file("archivo.txt");

    $registros[$resultadoBuscar['posicion']] = NULL;
    $totalReg = count($registros);

    $recurso = fopen("archivo.txt", "w+");

    for ($i = 0; $i < $totalReg; $i++) {
        fwrite($recurso, $registros[$i]);
    }

    fclose($recurso);

    header("Location:controlador.php?ruta=listar");
}

function agregar() {

    $resultadoBuscar = (buscar($_GET['cedula']));

    if (!$resultadoBuscar['hallado']) {

        $cedula = $_GET['cedula'];
        $nombre = $_GET['nombre'];
        $correo = $_GET['correo'];
        $telfij = $_GET['telfij'];
        $telmov = $_GET['telmov'];

        $registro = strtoupper($cedula . ":" . $nombre . ":" . $correo . ":" . $telfij . ":" . $telmov . ":" . "\n");
//    echo $registro; exit();
        $recurso = fopen("archivo.txt", "a+");
        fwrite($recurso, $registro);
        fclose($recurso);
        $mensencont1 = "<script language=\"JavaScript\">alert(\"Registro Insertado!\");location.href=\"principal.php\"</script>";
        session_start();
        $_SESSION['mensaje'] = $mensencont1;

        header("Location:vistaListar.php");
    } else {
        $mensencont1 = "<script language=\"JavaScript\">alert(\"Cédula ya registrada!\");location.href=\"vistaAgregar.php\"</script>";
        session_start();
        $_SESSION['mensaje'] = $mensencont1;

        header("Location:vistaAgregar.php");
    }
}

function cancelar() {
    header("location:vistaAgregar.php");
}

function vistaAgregar() {
    header("location:vistaAgregar.php");
}

function listar() {
    $registros = file("archivo.txt");

    $totalReg = count($registros);

    $pintarRegistros = array();

    for ($i = 0; $i < $totalReg; $i++) {
        list($ced, $nom, $correo, $telf, $telm) = explode(":", $registros[$i]);

        $pintarRegistros[$i]['ced'] = $ced;
        $pintarRegistros[$i]['nom'] = $nom;
//        $pintarRegistros[$i]['apell'] = $apell;
        $pintarRegistros[$i]['correo'] = $correo;
        $pintarRegistros[$i]['telf'] = $telf;
        $pintarRegistros[$i]['telm'] = $telm;
    }

    session_start();

    $_SESSION['pintarRegistros'] = $pintarRegistros;

    header("Location:vistaListar.php");
}

function buscar($buscar) {
    $hallado = FALSE;
    $posicion = "";
    if ($recurso = fopen("archivo.txt", "r")) { //Archivo existe!!!!!
        $vect = file("archivo.txt");
        $cantreg = count($vect);
        for ($i = 0; $i < $cantreg; $i++) {
            list($ced, $nom, $correo, $telf, $telmov) = explode(":", $vect[$i]);

            if ($ced == $buscar) {
                $hallado = TRUE;
                $posicion = $i;
                break;
            }
        }
    }
    return array("hallado" => $hallado, "posicion" => $posicion);
}
?>


<!--
NOTAS:
Mensajes JavaScript:
https://www.anerbarrena.com/javascript-confirm-js-5508/
https://developer.mozilla.org/es/docs/Web/JavaScript/Referencia/Operadores/Conditional_Operator
-->

