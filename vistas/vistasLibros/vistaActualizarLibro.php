<?php
if (isset($_SESSION['mensaje'])) {
    $mensaje = $_SESSION['mensaje'];
    echo "<script languaje='javascript'>alert('$mensaje')</script>";
    unset($_SESSION['mensaje']);
}
if (isset($_SESSION['actualizarDatosLibro'])) {
    $actualizarDatosLibro = $_SESSION['actualizarDatosLibro'];
    unset($_SESSION['actualizarLibro']);
}
if (isset($_SESSION['registroCategoriasLibros'])) { /* * ************************ */
    $registroCategoriasLibros = $_SESSION['registroCategoriasLibros'];
    $cantCategorias = count($registroCategoriasLibros);
}
if (isset($_SESSION['erroresValidacion'])) {
    $erroresValidacion = $_SESSION['erroresValidacion'];
    unset($_SESSION['erroresValidacion']);
}
?>
<div class="panel-heading">
    <h2 class="panel-title">Gestión de libros</h2>
    <h3 class="panel-title">Actualización de Libro.</h3>
</div>
<div>
    <fieldset>
        <form role="form" method="POST" action="Controlador.php" id="formRegistro">
            <table>
                <tr>
                    <td>
                        <input class="form-control" placeholder="ISBN" name="isbn" type="number" pattern="" required="required" autofocus readonly="readonly" 
                               value="<?php
                           if (isset($actualizarDatosLibro->isbn))
                               echo $actualizarDatosLibro->isbn;
                           if (isset($erroresValidacion['datosViejos']['isbn']))
                               echo $erroresValidacion['datosViejos']['isbn'];
                           if (isset($_SESSION['isbn']))
                               echo $_SESSION['isbn'];
                           unset($_SESSION['isbn']);
                           ?>">
                            <?php if (isset($erroresValidacion['marcaCampo']['isnb'])) echo "<font color='red'>" . $erroresValidacion['marcaCampo']['isnb'] . "</font>"; ?>
                            <?php if (isset($erroresValidacion['mensajesError']['isnb'])) echo "<font color='red'>" . $erroresValidacion['mensajesError']['isnb'] . "</font>"; ?>                                        
                    <!--<p class="help-block">Example block-level help text here.</p>-->
                    </td>
                </tr>
                <tr>
                    <td>                
                        <input class="form-control" placeholder="TITULO" name="titulo" type="text"   required="required" 
                               value="<?php
                           if (isset($actualizarDatosLibro->titulo))
                               echo $actualizarDatosLibro->titulo;                           
                           if (isset($erroresValidacion['datosViejos']['titulo']))
                               echo $erroresValidacion['datosViejos']['titulo'];
                           if (isset($_SESSION['titulo']))
                               echo $_SESSION['titulo'];
                           unset($_SESSION['titulo']);
                           ?>">
                            <?php if (isset($erroresValidacion['marcaCampo']['titulo'])) echo "<font color='red'>" . $erroresValidacion['marcaCampo']['titulo'] . "</font>"; ?>                                        
                            <?php if (isset($erroresValidacion['mensajesError']['titulo'])) echo "<font color='red'>" . $erroresValidacion['mensajesError']['titulo'] . "</font>"; ?>                                        
                    <!--<p class="help-block">Example block-level help text here.</p>-->                      
                    </td>
                </tr>
                <tr>
                    <td>                  
                        <input class="form-control" placeholder="AUTOR" name="autor" type="text"  required="required" 
                               value="<?php
                           if (isset($actualizarDatosLibro->autor))
                               echo $actualizarDatosLibro->autor;                            
                           if (isset($erroresValidacion['datosViejos']['autor']))
                               echo $erroresValidacion['datosViejos']['autor'];
                           if (isset($_SESSION['autor']))
                               echo $_SESSION['autor'];
                           unset($_SESSION['autor']);
                           ?>">
                           <?php if (isset($erroresValidacion['marcaCampo']['autor'])) echo "<font color='red'>" . $erroresValidacion['marcaCampo']['autor'] . "</font>"; ?>                                        
                           <?php if (isset($erroresValidacion['mensajesError']['autor'])) echo "<font color='red'>" . $erroresValidacion['mensajesError']['autor'] . "</font>"; ?>
                                <!--<p class="help-block">Example block-level help text here.</p>-->                                               
                    </td>
                </tr>                  
                <tr>
                    <td>                  
                        <input class="form-control" placeholder="PRECIO" name="precio" type="number"  required="required" 
                               value="<?php
                           if (isset($actualizarDatosLibro->precio))
                               echo $actualizarDatosLibro->precio;                            
                           if (isset($erroresValidacion['datosViejos']['precio']))
                               echo $erroresValidacion['datosViejos']['precio'];
                           if (isset($_SESSION['precio']))
                               echo $_SESSION['precio'];
                           unset($_SESSION['precio']);
                           ?>">
                           <?php if (isset($erroresValidacion['marcaCampo']['precio'])) echo "<font color='red'>" . $erroresValidacion['marcaCampo']['precio'] . "</font>"; ?>                                        
                           <?php if (isset($erroresValidacion['mensajesError']['precio'])) echo "<font color='red'>" . $erroresValidacion['mensajesError']['precio'] . "</font>"; ?>                                                           
                                <!--<p class="help-block">Example block-level help text here.</p>-->                          
                    </td>
                </tr>  
            <tr>
                <td>
                    <select id="categoriaLibro_catLibId" name="categoriaLibro_catLibId">                    
                        <?php
                        for ($j = 0; $j < $cantCategorias; $j++) {
                            ?>
                            <option value = "<?php echo $registroCategoriasLibros[$j]->catLibId; ?>" 
                            <?php
                            if (isset($registroCategoriasLibros[$j]->catLibId) && isset($actualizarDatosLibro->categoriaLibro_catLibId) && ($registroCategoriasLibros[$j]->catLibId == $actualizarDatosLibro->categoriaLibro_catLibId)) {
                                echo "selected";
                            }
                            ?>
                                    ><?php echo $registroCategoriasLibros[$j]->catLibId . " - " . $registroCategoriasLibros[$j]->catLibNombre; ?></option>             
                            <?php
                        }
                        ?>
                    </select> 
                </td>                       
            </tr>             
                <tr>            
                    <td>            
                        <button type="reset" name="ruta" value="cancelarActualizarLibro">Cancelar</button>&nbsp;&nbsp;||&nbsp;&nbsp;
                        <button type="submit" name="ruta" value="confirmaActualizarLibro">Actualizar Libro</button>
                    </td>
                </tr>             
            </table>
            <?php if (isset($erroresValidacion)) $erroresValidacion = NULL; ?>
        </form>
    </fieldset>
</div>