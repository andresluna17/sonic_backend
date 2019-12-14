<?php

if (isset($_SESSION['mensaje'])) {
    $mensaje = $_SESSION['mensaje'];
    echo "<script languaje='javascript'>alert('$mensaje')</script>";
    unset($_SESSION['mensaje']);
}

if (isset($_SESSION['listaDeLibros'])) {
    $listaDeLibros = $_SESSION['listaDeLibros'];
}

if (isset($_SESSION['paginacionVinculos'])) {
    $paginacionVinculos = $_SESSION['paginacionVinculos'];
}
if (isset($_SESSION['totalRegistrosLibros'])) {
    $totalRegistrosLibros = $_SESSION['totalRegistrosLibros'];
}
if (isset($_SESSION['registroCategoriasLibros'])) { /* * ************************ */
    $registroCategoriasLibros = $_SESSION['registroCategoriasLibros'];
    $cantCategorias = count($registroCategoriasLibros);
}
?>
<style type="text/css">
    .derecha   { float: right; }
    .izquierdo { float: left;  }
    fieldset.scheduler-border {
        border: 1px groove #ddd !important;
        padding: 0 1.4em 1.4em 1.4em !important;
        margin: 0 0 1.5em 0 !important;
        -webkit-box-shadow:  0px 0px 0px 0px #000;
        box-shadow:  0px 0px 0px 0px #000;
    }

    legend.scheduler-border {
        font-size: 1.2em !important;
        font-weight: bold !important;
        text-align: left !important;

    }  
    table th {
        text-align: center;
    }
    table tr {
        text-align: center;
    }
    thead th{
        color: #79008E;
        font-weight: normal;
    }
</style>
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Gestión de Libros.</h1>
        </div>                        
    </div>
</div>
<div>
    <fieldset class="scheduler-border"><legend class="scheduler-border">FILTRO</legend>

        <form name="formBuscarLibros" action="Controlador.php" method="POST">
            <input type="hidden" name="ruta" value="listarLibros"/>
            <table> 
                <tr><td>ISBN:</td>
                    <td><input type="number" name="isbn" onclick="" value="<?php
                        if (isset($_SESSION['isbnF'])) {
                            echo $_SESSION['isbnF'];
                        } ?>"/>
                    </td>                      
                </tr> 
                <tr><td>TITULO:</td>
                    <td><input type="text" name="titulo" onclick="" value="<?php
                        if (isset($_SESSION['tituloF'])) {
                            echo $_SESSION['tituloF'];
                        } ?>"/>
                    </td>                       
                </tr> 
                <tr><td>AUTOR:</td>
                    <td><input type="text" onclick="" name="autor" value="<?php
                        if (isset($_SESSION['autorF'])) {
                            echo $_SESSION['autorF'];
                        } ?>"/>
                    </td>
                    <td>                         
                </tr> 
                <tr><td>PRECIO:</td>
                    <td><input type="number" onclick=""  name="precio" value="<?php
                        if (isset($_SESSION['precioF'])) {
                            echo $_SESSION['precioF'];
                        } ?>"/>
                    </td>                          
                </tr>                   
                <tr><td>CATEGORIA </td>
                    <td>
                        <select id="categoriaLibro_catLibId" name="categoriaLibro_catLibId">
                            <option value = "">Seleccionar</option>
                        <?php
                            for ($j = 0; $j < $cantCategorias; $j++) {
                        ?>
                                <option value = "<?php echo $registroCategoriasLibros[$j]->catLibId; ?>" <?php
                                if (isset($_SESSION['categoriaLibro_catLibIdF']) && $_SESSION['categoriaLibro_catLibIdF'] == $registroCategoriasLibros[$j]->catLibId) {
                                    echo " selected";
                                }
                                ?> > <?php echo $registroCategoriasLibros[$j]->catLibId . " - " . $registroCategoriasLibros[$j]->catLibNombre; ?></option>             
                                <?php
                                    }
                                ?>
                        </select> 
                    </td>
                    <td></td>                          
                </tr>               
                <tr><td><input type="submit" value="Filtrar" name="enviar" title="Si es necesario limpie 'Buscar'"/></td>
                    <td><input type="reset" value="limpiar" onclick="
                            javascript:document.formBuscarLibros.isbn.value = '';
                            javascript:document.formBuscarLibros.titulo.value = '';
                            javascript:document.formBuscarLibros.autor.value = '';
                            javascript:document.formBuscarLibros.precio.value = '';
                            javascript:document.formBuscarLibros.categoriaLibro_catLibId.value = '';
                            javascript:document.formBuscarLibros.buscar.value = '';
                            javascript:document.formBuscarLibros.submit();"/>
                    </td>
                    <td></td>
                </tr> 
            </table>
            <fieldset class="scheduler-border"><legend class="scheduler-border">BUSCAR</legend>
                <div style="width: 800">
                        <!--BOTÓN PARA BUSCAR*************************-->
                            <input type="text" name="buscar" placeholder="Término a Buscar" value="<?php
                            if (isset($_SESSION['buscarF'])) {
                                echo $_SESSION['buscarF'];
                            }
                            ?>">
                </div>        
            </fieldset>             
        </form>
    </fieldset>
</div>
<br>
<div style="width: 800">
    <span class="izquierdo">
        <input type="button" onclick="javascript:location.href = 'Controlador.php?ruta=mostrarInsertarLibros'" value="Nuevo Libro">
    </span>
</div>
<br>
<a name="listaDeLibros" id="a"></a>
<div style="width: 800">
    <p>Total de Registros: <?php if (isset($totalRegistrosLibros)) echo $totalRegistrosLibros; ?></p>
    <table border='1'>
        <thead>
            <tr>
                <td style="width: 100">ISBN</td>
                <td style="width: 100">TITULO</td>
                <td style="width: 100">AUTOR</td>
                <td style="width: 100">PRECIO</td>
                <td style="width: 100">ID CATEGORIA</td>
                <td style="width: 100">NOMBRE CATEGORIA</td>
                <td style="width: 100"  colspan="2"> ACCIONES </td>
            </tr>
        </thead> 
        <tbody>
            <?php
            $i = 0;
            foreach ($listaDeLibros as $key => $value) {
            ?>
            <tr>
                <td style="width: 100"><?php echo $listaDeLibros[$i]->isbn; ?></td>
                <td style="width: 100"><?php echo strtoupper($listaDeLibros[$i]->titulo); ?></td>
                <td style="width: 100"><?php echo strtoupper($listaDeLibros[$i]->autor); ?></td>
                <td style="width: 100"><?php echo strtoupper($listaDeLibros[$i]->precio); ?></td>
                <td style="width: 100"><?php echo $listaDeLibros[$i]->catLibId; ?></td>
                <td style="width: 100"><?php echo $listaDeLibros[$i]->catLibNombre; ?></td>
                <td style="width: 100"><?php if (in_array(1, $_SESSION['rolesEnSesion'])) { ?><a href="Controlador.php?ruta=actualizarLibro&idAct=<?php echo $listaDeLibros[$i]->isbn; ?>" >Actualizar</a><?php } ?></td>
                <td style="width: 100"><?php if (in_array(1, $_SESSION['rolesEnSesion'])) { ?>  <a href="Controlador.php?ruta=eliminarLibro&idAct=<?php echo $listaDeLibros[$i]->isbn; ?>">Eliminar</a><?php } ?>  </td>
            <?php
            $i++;
            ?>
            <tr>
            <?php
            }
            ?>
        </tbody>
        <tfoot> 
            <tr>
                <td colspan="8">
                    <nav aria-label="Page navigation example">
                        <?php $i = 0; ?>
                        <ul class="pagination justify-content-center">
                        <?php foreach ($paginacionVinculos as $key => $value) { ?>    
                                <li class="page-item"><a class="page-link" href="<?php echo $value; ?>"><?php echo ($key); ?></a></li>
                            <?php } ?>
                        </ul>
                    </nav>
                </td>
            </tr>
        </tfoot>
    </table>
</div>





