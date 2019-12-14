<?php

include_once PATH . 'modelos/modeloLibros/LibroDAO.php';
include_once PATH . 'modelos/modeloCategoriaLibro/CategoriaLibroDAO.php';

class LibrosControlador {

    private $datos;

    public function __construct($datos) {
        $this->datos = $datos;
        $this->librosControlador();
    }

    public function librosControlador() {

        switch ($this->datos['ruta']) {
            case 'mostrarInsertarLibros':
                    //Se alistan datos a desplegar en los campos select con relación a otras tablas
                    $gestarCategoriasLibros = new CategoriaLibroDAO(SERVIDOR, BASE, USUARIO_BD, CONTRASENIA_BD);
                    $registroCategoriasLibros = $gestarCategoriasLibros->seleccionarTodos(); /*                     * *********** */

                    session_start();
                    $_SESSION['registroCategoriasLibros'] = $registroCategoriasLibros;
                    $gestarCategoriasLibros = null;

                    header("location:principal.php?contenido=vistas/vistasLibros/vistaInsertarLibro.php");

                break;
            case 'insertarLibro':

                $buscarLibro = new LibroDAO(SERVIDOR, BASE, USUARIO_BD, CONTRASENIA_BD); //Se instancia LibroDAO para insertar
                $libroHallado = $buscarLibro->seleccionarId(array($this->datos['isbn'])); //Se consulta si existe ya el registro
                echo "<pre>";
                print_r($libroHallado);
                echo "</pre>";
                if (!$libroHallado['exitoSeleccionId']) {//Si no existe el libro en la base se procede a insertar
                    $insertarLibro = new LibroDAO(SERVIDOR, BASE, USUARIO_BD, CONTRASENIA_BD);
                    $insertoLibro = $insertarLibro->insertar($this->datos); //inserción de los campos en la tabla libros    
                    $exitoInsercionLibro = $insertoLibro['inserto']; //indica si se logró inserción de los campos en la tabla libros
                    $resultadoInsercionLibro = $insertoLibro['resultado']; //Traer el id con que quedó el libro de lo contrario la excepción o fallo
                    session_start();
                    $_SESSION['mensaje'] = "Registrado " . $this->datos['isbn'] . " con èxito. Agregado Nuevo Libro: " . $resultadoInsercionLibro . " "; //mensaje de inserción 

                    header("location:Controlador.php?ruta=listarLibros");
                } else {
                    session_start();
                    $_SESSION['isbn'] = $this->datos['isbn'];
                    $_SESSION['titulo'] = $this->datos['titulo'];
                    $_SESSION['autor'] = $this->datos['autor'];
                    $_SESSION['precio'] = $this->datos['precio'];
                    $_SESSION['categoriaLibro_catLibId'] = $this->datos['categoriaLibro_catLibId'];
                    $_SESSION['mensaje'] = "   El código " . $this->datos['isbn'] . " ya existe en el sistema.";

                    header("location:Controlador.php?ruta=mostrarInsertarLibros");
                }

                break;
            case "listarLibros":

                session_start();
                // PARA LA PAGINACIÒN SE VERIFICA Y VALIDA QUE EL LIMIT Y EL OFFSET ESTÈN EN LOS RANGOS QUE CORRESPONDAN//
                $limit = (isset($_GET['limit'])) ? $_GET['limit'] : 2;
                $offset = (isset($_GET['pag'])) ? $_GET['pag'] : 0;
                $offset = ($offset < 0 || !isset($_GET['pag'])) ? 0 : $_GET['pag'];

                // SE REALIZA LA CONSERVACIÓN Y CONSTRUCCIÒN DE FILTROS O BUSQUEDA DE LA CONSULTA
                $filtarBuscar = "";
                $this->conservarFiltroYBuscar();

                $filtrarBuscar = $this->armarFiltradoYBusqueda();

                // SE HACE LA CONSULTA A LA BASE PARA TRAER LA CANTIDAD DE REGISTROS SOLICITADOS Y EL TOTAL PARA PAGINARLOS//
                $gestarLibros = new LibroDAO(SERVIDOR, BASE, USUARIO_BD, CONTRASENIA_BD);
                $resultadoConsultaPaginada = $gestarLibros->consultaPaginada($limit, $offset, $filtrarBuscar);

                $totalRegistrosLibros = $resultadoConsultaPaginada[0];
                $listaDeLibros = $resultadoConsultaPaginada[1];
                //SE CONSTRUYEN LOS ENLACES PARA LA PAGINACIÓN QUE SE MOSTRARÀ EN LA VISTA//
                $totalEnlacesPaginacion = (isset($_GET['limit'])) ? $_GET['limit'] : 2;
                $paginacionVinculos = $this->enlacesPaginacion($totalRegistrosLibros, $limit, $offset, $totalEnlacesPaginacion); //Se obtienen los enlaces de paginación
                //SE ALISTA LA CONSULTA DE CATEGORIAS DE LIBROS PARA FUTURO FORMULARIO DE FILTRAR//
                $gestarCategoriasLibros = new CategoriaLibroDAO(SERVIDOR, BASE, USUARIO_BD, CONTRASENIA_BD);
                $registroCategoriasLibros = $gestarCategoriasLibros->seleccionarTodos();

                //SE SUBEN A SESION LOS DATOS NECESARIOS PARA QUE LA VISTA LOS IMPRIMA O UTILICE//
                $_SESSION['listaDeLibros'] = $listaDeLibros;
                $_SESSION['paginacionVinculos'] = $paginacionVinculos;
                $_SESSION['totalRegistrosLibros'] = $totalRegistrosLibros;
                $_SESSION['registroCategoriasLibros'] = $registroCategoriasLibros;
                $gestarLibros = null; //CIERRE DE LA CONEXIÓN CON LA BASE DE DATOS//
                header("location:principal.php?contenido=vistas/vistasLibros/listarRegistrosLibros.php");
                break;

            case "actualizarLibro":

                $gestarLibros = new LibroDAO(SERVIDOR, BASE, USUARIO_BD, CONTRASENIA_BD);
//                $consultaLibro = new LibroVO();
                $consultaDeLibro = $gestarLibros->seleccionarId(array($this->datos["idAct"])); //Se consulta el libro para traer los datos.

                $actualizarDatosLibro = $consultaDeLibro['registroEncontrado'][0];

                session_start();
                $_SESSION['actualizarDatosLibro'] = $actualizarDatosLibro;

                header("location:principal.php?contenido=vistas/vistasLibros/vistaActualizarLibro.php");
                break;
            case "confirmaActualizarLibro":
                $gestarLibros = new LibroDAO(SERVIDOR, BASE, USUARIO_BD, CONTRASENIA_BD);
//                $consultaLibro = new LibroVO();
                $actualizarLibro = $gestarLibros->actualizar(array($this->datos)); //Se envía datos del libro para actualizar.

                $actualizarLibro = $consultaDeLibro['registroEncontrado'][0];

                session_start();
                $_SESSION['mensaje'] = "Actualización realizada.";
                header("location:Controlador.php?ruta=listarLibros");
                break;
        }
    }

    public function enlacesPaginacion($totalRegistros = NULL, $limit = 2, $offset = 0, $totalEnlacesPaginacion = 2) {

        $ruta = "listarLibros";

        if (isset($offset) && (int) $offset <= 0) {
            $offset = 0;
        }
        if (isset($offset) && ((int) $offset > ($totalRegistros - $limit))) {
            $offset = ($totalRegistros - $limit) + 1;
        }
        $anterior = $offset - $totalEnlacesPaginacion; /*         * **** */
        $siguiente = $offset + $totalEnlacesPaginacion; /*         * **** */

        $mostrar = array();
        $enlacesProvisional = array();
        $conteoEnlaces = 0;

        $mostrar['inicio'] = "Controlador.php?ruta=" . $ruta . "&pag=0"; //Enlace a enviar para páginas Iniciales
        $mostrar['anterior'] = "Controlador.php?ruta=" . $ruta . "&pag=" . (($anterior)); //Enlace a enviar para páginas anteriores

        for ($i = $offset; $i < ($offset + $limit) && $i < $totalRegistros && $conteoEnlaces < $totalEnlacesPaginacion; $i++) {

            $mostrar[$i + 1] = "Controlador.php?ruta=" . $ruta . "&pag=$i";
            $enlacesProvisional[$i] = "Controlador.php?ruta=" . $ruta . "&pag=$i";
            $conteoEnlaces++;
            $siguiente = $i;
        }

        $cantidadProvisional = count($enlacesProvisional);

        if ($offset < $totalRegistros) {
            $mostrar['siguiente'] = "Controlador.php?ruta=" . $ruta . "&pag=" . ($siguiente + 1);
//            $mostrar.="<a href='controladores/ControladorPrincipal.php?ruta=listarLibros&pag=" . ($totalPag - $totalEnlacesPaginacion) . "'>..::BLOQUE FINAL::..</a><br></center>";
            $mostrar ['final'] = "Controlador.php?ruta=" . $ruta . "&pag=" . ($totalRegistros - $totalEnlacesPaginacion);
        }

        if ($offset >= $totalRegistros) {
            $mostrar[$siguiente + 1] = "Controlador.php?ruta=" . $ruta . "&pag=" . ($siguiente + 1);
            for ($j = 0; $j < $cantidadProvisional; $j++) {
                $mostrar [] = $enlacesProvisional[$j];
            }
            $mostrar [$totalRegistros - $offset] = "Controlador.php?ruta=" . $ruta . "&pag=" . ($totalRegistros - $offset);
        }

        return $mostrar;
    }

    public function armarFiltradoYBusqueda() {

        $planConsulta = "";

        if (!empty($_SESSION['isbnF'])) {
            $planConsulta .= " where l.isbn='" . $_SESSION['isbnF'] . "'";
            $filtros = 0;  // cantidad de filtros/condiciones o criterios de búsqueda al comenzar la consulta        
        } else {
            $where = false; // inicializar $where a falso ( al comenzar la consulta NO HAY condiciones o criterios de búsqueda)
            $filtros = 0;  // cantidad de filtros/condiciones o criterios de búsqueda al comenzar la consulta            
            if (!empty($_SESSION['titulof'])) {
                $where = true; // inicializar $where a verdadero ( hay condiciones o criterios de búsqueda)
                $planConsulta .= (($where && !$filtros) ? " where " : " and ") . "l.titulo like upper('%" . $_SESSION['titulof'] . "%')"; // con tipo de búsqueda aproximada sin importar mayúsculas ni minúsculas
                $filtros++; //cantidad de filtros/condiciones o criterios de búsqueda
            }
            if (!empty($_SESSION['autorF'])) {
                $where = true;  // inicializar $where a verdadero ( hay condiciones o criterios de búsqueda)
                $planConsulta .= (($where && !$filtros) ? " where " : " and ") . " l.autor like upper('%" . $_SESSION['autorF'] . "%')"; // con tipo de búsqueda aproximada sin importar mayúsculas ni minúsculas
                $filtros++; //cantidad de filtros/condiciones o criterios de búsqueda
            }
            if (!empty($_SESSION['precioF'])) {
                $where = true;  // inicializar $where a verdadero ( hay condiciones o criterios de búsqueda)
                $planConsulta .= (($where && !$filtros) ? " where " : " and ") . " l.precio = " . $_SESSION['precioF'];
                $filtros++; //cantidad de filtros/condiciones o criterios de búsqueda
            }
            if (!empty($_SESSION['categoriaLibro_catLibIdF'])) {
                $where = true;  // inicializar $where a verdadero ( hay condiciones o criterios de búsqueda)
                $planConsulta .= (($where && !$filtros) ? " where " : " and ") . " l.categoriaLibro_catLibId like upper('%" . $_SESSION['categoriaLibro_catLibIdF'] . "%')";
                $filtros++; //cantidad de filtros/condiciones o criterios de búsqueda
            }
            if (!empty($_SESSION['catLibNombreF'])) {
                $where = true;  // inicializar $where a verdadero ( hay condiciones o criterios de búsqueda)
                $planConsulta .= (($where && !$filtros) ? " where " : " and ") . " cl.catLibNombre like upper('%" . $_SESSION['catLibNombreF'] . "%')";
                $filtros++; //cantidad de filtros/condiciones o criterios de búsqueda
            }
        }
        if (!empty($_SESSION['buscarF'])) {
            $where = TRUE;
            $condicionBuscar = (($where && !$filtros == 0) ? " or " : " where ");
            $filtros++;
            $planConsulta .= $condicionBuscar;
            $planConsulta .= "( isbn like '%" . $_SESSION['buscarF'] . "%'";
            $planConsulta .= " or titulo like '%" . $_SESSION['buscarF'] . "%'";
            $planConsulta .= " or autor like '%" . $_SESSION['buscarF'] . "%'";
            $planConsulta .= " or precio like '%" . $_SESSION['buscarF'] . "%'";
            $planConsulta .= " or catLibId like '%" . $_SESSION['buscarF'] . "%'";
            $planConsulta .= " or catLibNombre like '%" . $_SESSION['buscarF'] . "%'";
            $planConsulta .= " ) ";
        }
        return $planConsulta;
    }

    public function conservarFiltroYBuscar() {
//        se almacenan en sesion las variables del filtro y buscar para conservarlas en el formulario
        $_SESSION['isbnF'] = (isset($_POST['isbn']) && !isset($_SESSION['isbnF'])) ? $_POST['isbn'] : $_SESSION['isbnF'];
        $_SESSION['isbnF'] = (!isset($_POST['isbn']) && isset($_SESSION['isbnF'])) ? $_SESSION['isbnF'] : $_POST['isbn'];

        $_SESSION['tituloF'] = (isset($_POST['titulo']) && !isset($_SESSION['tituloF'])) ? $_POST['titulo'] : $_SESSION['tituloF'];
        $_SESSION['tituloF'] = (!isset($_POST['titulo']) && isset($_SESSION['tituloF'])) ? $_SESSION['tituloF'] : $_POST['titulo'];

        $_SESSION['autorF'] = (isset($_POST['autor']) && !isset($_SESSION['autorF'])) ? $_POST['autor'] : $_SESSION['autorF'];
        $_SESSION['autorF'] = (!isset($_POST['autor']) && isset($_SESSION['autorF'])) ? $_SESSION['autorF'] : $_POST['autor'];

        $_SESSION['precioF'] = (isset($_POST['precio']) && !isset($_SESSION['precioF'])) ? $_POST['precio'] : $_SESSION['precioF'];
        $_SESSION['precioF'] = (!isset($_POST['precio']) && isset($_SESSION['precioF'])) ? $_SESSION['precioF'] : $_POST['precio'];

        $_SESSION['categoriaLibro_catLibIdF'] = (isset($_POST['categoriaLibro_catLibId']) && !isset($_SESSION['categoriaLibro_catLibIdF'])) ? $_POST['categoriaLibro_catLibId'] : $_SESSION['categoriaLibro_catLibIdF'];
        $_SESSION['categoriaLibro_catLibIdF'] = (!isset($_POST['categoriaLibro_catLibId']) && isset($_SESSION['categoriaLibro_catLibIdF'])) ? $_SESSION['categoriaLibro_catLibIdF'] : $_POST['categoriaLibro_catLibId'];

        $_SESSION['buscarF'] = (isset($_POST['buscar']) && !isset($_SESSION['buscarF'])) ? $_POST['buscar'] : $_SESSION['buscarF'];
        $_SESSION['buscarF'] = (!isset($_POST['buscar']) && isset($_SESSION['buscarF'])) ? $_SESSION['buscarF'] : $_POST['buscar'];
    }

}
