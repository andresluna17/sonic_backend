<?php //1. Subir archivos haciendo uso de formularios HTML y la variable global $_FILES de PHP

//echo "<pre>";
//print_r($_POST);
//echo "</pre>";exit();

?>
<html>
	<head>
		<title>Verificar las caracter�sticas del archivo recibido</title>
	</head>
	<body text="white">
            <?php
                $a=$_POST['destino'];
                $b=$_POST['mensaje'];
//                echo $a;
//                echo $b;
            ?>
		<table align="center" border="1" bgcolor="#553399">
			<tr>
				<td colspan="2"> <hl align="center">Archivo recibido</hl> </td>
			</tr>
			<tr>
				<td>Nombre del archivo recibido:</td>
				<td>
					<?php
						echo $_FILES['adjunto']['name'];
					?>
				</td>
			</tr>
			<tr>
				<td>Tama�o del archivo:</td>
				<td>
					<?php
						echo $_FILES['adjunto']['size']. "bytes";
					?>
				</td>
			</tr>
			<tr>
				<td>Tipo de archivo (MIME):</td>
				<td>
					<?php
						echo $_FILES['adjunto']['type'];
					?>
				</td>
			</tr>
			<tr>
				<td>Nombre temporal en el servidor:</td>
				<td>
					<?php
						echo $_FILES['adjunto']['tmp_name'];
					?>
				</td>
			</tr>
			<tr>
				<td>Direccion actual:</td>
				<td>
					<?php
						$ruta_destino = './'; //guarda en el mismo directorio donde esta el archivo PHP
						move_uploaded_file($_FILES['adjunto']['tmp_name'],$ruta_destino.$_FILES['adjunto']['name']);
						echo $ruta_destino.$_FILES['adjunto']['name'];
					?>
				</td>
			</tr>
		</table>
            <?php
                $a=$_POST['destino'];
                $b=$_POST['mensaje'];
                echo $a;
                echo $b;
            ?>
	</body>
</html>
<!--https://www.lawebdelprogramador.com/codigo/PHP/3029-Formulario-de-contacto-con-adjuntos.html-->