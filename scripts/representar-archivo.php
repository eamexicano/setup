<!DOCTYPE html>
<html>
	<head>
		<meta charset='utf-8' />
		<title>setup - representar</title>
	</head>
	<style>
	body {font-family: 'helvetica neue', helvetica, sans-serif; font-size: 12px; line-height: 1.5; width: 980px; margin: 0 auto; color: #333;}
	.container {width: 860px; margin: auto; }
	.header {height: 40px; border-bottom: 1px solid #ccc;}
	.content {padding: 1em 0;}
	.footer {height: 40px; border-top: 1px solid #ccc; text-align: right; color: #777;} 
	ul {list-style: none;}	
	</style>
	<body>
		<div class='container'>
			<div class='header'>
				<h1>setup</h1>
			</div>
			<div class='content'>  
<?php
	require('pluralize.php');
	$projectName = basename(dirname(dirname(__FILE__)));
	if (isset($_POST['recurso'])) {
	$recurso = $_POST['recurso'];    
	/* Asignar valores del formulario a variables y remover para generar el SQL bien */
	if (isset($_POST['responsive']) && ($_POST['responsive'] == '1' || $_POST['responsive'] == 'on')) {		
		$css = "<link rel='stylesheet' href='../assets/css/$projectName.css' type='text/css' />\n";		
		$css .= "<link rel='stylesheet' href='../assets/css/responsive.css' type='text/css' />";
	} else {		
		$css = "<link rel='stylesheet' href='../assets/css/$projectName.css' type='text/css' />";
	}  
	
	$script = "<script src='http://code.jquery.com/jquery-1.7.2.min.js'></script>\n";
	$script .= "<script src='../assets/js/$projectName.js'></script>";
	
	/* htmlContent */
	if (isset($_POST['htmlContent']) && ($_POST['htmlContent'] == '1' || $_POST['htmlContent'] == 'on')) {
		$htmlContent = true;
	} else {
		$htmlContent = false;
	}

	unset($_POST['responsive']);
	unset($_POST['htmlContent']);
	
	echo "Creando directorios para $recurso <br />\n";
 	mkdir("../$recurso", 0777, true);
	chmod("../$recurso", 0777);
	mkdir("../assets/$recurso", 0777, true);
	chmod("../assets/$recurso", 0777);	

	$elem = $_POST;
	$show="";
	$new_input="";
	$sent_params="";
	$insert_attrs="";
	$insert_vals="";
	$edit_input="";
	$update_attrs = "";
	for($i=1;$i<count($elem);$i++) {
		$key = "attr_" . $i;
		$value = "type_". $i;
		echo $elem[$key] . " " . $elem[$value] . "<br />";					
		if ($elem[$key] != '') {
			if ($elem[$value] == 'text') {
		   		$new_input .= "<label>$elem[$key]</label><br />\n<textarea name='$elem[$key]' placeholder='$elem[$key]'></textarea><br />\n";
		   		$edit_input .= "echo \"<label>$elem[$key]</label><br />\n<textarea name='$elem[$key]'>\" . stripslashes(\$resultado['$elem[$key]']) . \"</textarea><br />\";\n";					
			} elseif(preg_match("/_id+$/i", $elem[$key])) {
				$attr_id = $elem[$key];
				$sustantivo = str_replace("_id", "", $elem[$key]);
				$tabla = pluralize($sustantivo);
				/* new input*/
$new_input = <<<SOURCE
\n<?php
				require('../config/conexion.php');
				\$query = "SELECT * FROM $tabla";
		 		\$resultado = mysql_query(\$query) or die ("No se pudo realizar la consulta. " . mysql_error());	
				echo "<label>$sustantivo</label><br />\n";
				echo "<select name='$elem[$key]'>\n";
				echo "<option value='0' selected> - Selecciona - </option>\n";
				while (\$mostrar = mysql_fetch_array(\$resultado)) { 
					echo "<option value='\$mostrar[0]'>\$mostrar[1]</option>";\n
				} 
				echo "</select><br />\n";
?>\n
SOURCE;
                /* new input*/
$edit_input = <<<SOURCE
				 \$attr_id = \$resultado['$attr_id'];
				\$query = "SELECT * FROM $tabla";
				\$select = mysql_query(\$query) or die ("No se pudo realizar la consulta. " . mysql_error());	
				echo "<label>$sustantivo</label><br />";
				echo "<select name='$elem[$key]'>";
				echo "<option value='0'> - Selecciona - </option>";
				while (\$selected = mysql_fetch_row(\$select)) { 
					echo "<option value='\$selected[0]'";     
/*					
Se necesita hacer una consulta para obtener el valor almacenado en: $elem[$key]
Ya sea utilizar ese o almacenarlo en otra variable y sustituirla en el condicional
para que cuando se mande llamar el formulario de edición aparezca como seleccionada la opción.
*/
				if (\$attr_id == \$selected[0]) { echo "selected='selected'"; }
					echo ">\$selected[1]</option>";
				} 
				echo "</select><br />";
SOURCE;
				
			} else {
		   		$new_input .= "<label>$elem[$key]</label><br />\n<input type='text' name='$elem[$key]' placeholder='$elem[$key]' /><br />\n";
		   		$edit_input .= "echo \"<label>$elem[$key]</label><br />\n<input type='text' name='$elem[$key]' value='\" . \$resultado['$elem[$key]'] . \"' /><br />\";\n";					
			}							

			if (isset($htmlContent) && ($htmlContent == true)) {
				$show .= "echo stripslashes(\$resultado['$elem[$key]']) . '<br />';\n";
			} else {
				$show .= "echo htmlentities(stripslashes(\$resultado['$elem[$key]'])) . '<br />';\n";				
			}
	   		$sent_params .= "\$$elem[$key] = mysql_real_escape_string(\$_POST['$elem[$key]']);\n";
	   		$insert_attrs .= "$elem[$key],";
	   		$insert_vals .= "'\$$elem[$key]',";
			$update_attrs .= "$elem[$key] = '\$$elem[$key]',";
		}	   		
	}	
$setup_file = <<<SOURCE
<!DOCTYPE html>
<html>
	<head>
		<meta charset='utf-8' />
		$css
	</head>
	<body>
		<?php require '../config/conexion.php'; ?>
		<div class='container'>
			<div class='header'>
				<h1><a href='../index.php'>$recurso</a></h1>
			</div>
			<a href="new.php">Agregar $recurso</a>
			<div class='content'>
				<?php
				\$query = "SELECT * FROM $recurso";
				\$resultados = mysql_query(\$query) or die ("No se pudo realizar la consulta. " . mysql_error());
					while (\$resultado = mysql_fetch_array(\$resultados)) { 
  					$show
					echo "<a href='show.php?id=" . \$resultado['id'] . "'>Ver</a>";
					echo "<a href='edit.php?id=" . \$resultado['id'] . "'>Editar</a>";
					echo "<form action='destroy.php' method='post' class='linkDisplay'><input type='hidden' name='id' value='" . \$resultado['id'] . "'/><input type='submit' value='Eliminar' class='linkDisplay' /></form>";
					echo "<br />";
				}				
				?>
			</div>
			<div class='footer'>
				<p>
					&copy; $projectName
				</p>
			</div>
		</div>
			$script	
	</body>
</html>
SOURCE;
	$archivo = fopen("../$recurso/index.php", 'w') or die("No se pudo crear el archivo index.php");
	fwrite($archivo, $setup_file);
	fclose($archivo);       

$setup_file = <<<SOURCE
<!DOCTYPE html>
<html>
	<head>
		<meta charset='utf-8' />		
		$css
	</head>
	<body>
		<?php require '../config/conexion.php'; ?>
		<div class='container'>
			<div class='header'>
				<h1><a href='../index.php'>$recurso</a></h1>
			</div>
			<a href="index.php">Ver todos</a>
			<div class='content'>
				<?php				
				\$id = \$_GET['id'];
				\$query = sprintf("SELECT * FROM $recurso WHERE id = '%s'", mysql_real_escape_string(\$id));
				\$resultados = mysql_query(\$query) or die ("No se pudo realizar la consulta. " . mysql_error());
					while (\$resultado = mysql_fetch_array(\$resultados)) { 
				 	$show;
				}				
				?>
			</div>
			<div class='footer'>
				<p>
					&copy; $projectName
				</p>
			</div>
		</div>
		$script
	</body>
</html>
SOURCE;
	$archivo = fopen("../$recurso/show.php", 'w') or die("No se pudo crear el archivo show.php");
	fwrite($archivo, $setup_file);
	fclose($archivo);       

$setup_file = <<<SOURCE
<!DOCTYPE html>
<html>
	<head>
		<meta charset='utf-8' />		
		$css
	</head>
	<body>
		<div class='container'>
			<div class='header'>
				<h1><a href='../index.php'>$recurso</a></h1>
			</div>
			<a href="index.php">Ver todos</a>
			<div class='content'>
			<form action='create.php' enctype="multipart/form-data" method='post'>
  			 	$new_input
				<label>Archivo:</label>
				<input type="file" name="uploadFile" id="uploadFile">
				<input type='submit' value='Crear' />
			</form>
			</div>
			<div class='footer'>
				<p>
					&copy; $projectName
				</p>
			</div>
		</div>
		$script
	</body>
</html>
SOURCE;
	$archivo = fopen("../$recurso/new.php", 'w') or die("No se pudo crear el archivo new.php");
	fwrite($archivo, $setup_file);
	fclose($archivo);
$setup_file = <<<SOURCE
<?php
require '../config/conexion.php';
$sent_params                 

\$nombre_temporal = \$_FILES['uploadFile']['tmp_name'];
\$file_name = \$_FILES['uploadFile']['name'];
\$file_size = \$_FILES['uploadFile']['size'];
\$file_type = \$_FILES['uploadFile']['type'];
\$date = date('Y-m-d H:i:s'); 

if (move_uploaded_file(\$nombre_temporal, "../assets/$recurso/\$file_name")) {
	\$query = "INSERT INTO $recurso($insert_attrs file_name, file_type, file_size, creado, actualizado) VALUES ($insert_vals '\$file_name', '\$file_type', '\$file_size', '\$date', '\$date')";	
	\$completado = mysql_query(\$query) or die ("No se pudo realizar la consulta. " . mysql_error());
	if (\$completado) {
		header("location: ./index.php");
	} else {
		echo "Problema con el query.";
	}
} else {
	echo "No se guardó el archivo";
}
?>
SOURCE;
	$archivo = fopen("../$recurso/create.php", 'w') or die("No se pudo crear el archivo create.php");
	fwrite($archivo, $setup_file);
	fclose($archivo);
	
$setup_file = <<<SOURCE
<!DOCTYPE html>
<html>
	<head>
		<meta charset='utf-8' />
		$css
	</head>
	<body>
		<div class='container'>
			<div class='header'>
				<h1><a href='../index.php'>$recurso</a></h1>
			</div>
			<a href="index.php">Ver todos</a>
			<div class='content'>
			<?php require '../config/conexion.php'; ?>
			<form action='update.php' enctype="multipart/form-data" method='post'>
			<?php
			\$id = \$_GET['id'];
			\$query = "SELECT * FROM $recurso WHERE id = '\$id'";
			\$resultados = mysql_query(\$query) or die ("No se pudo realizar la consulta. " . mysql_error());
				while (\$resultado = mysql_fetch_array(\$resultados)) { 
				$edit_input
			 	echo "<input type='hidden' name='id' value='\$id'>";  
				echo "<input type='hidden' name='eliminar' value='" . \$resultado['file_name'] . "'>";
			    echo "<label>Archivo:</label>";
				echo \$resultado['file_name'] . "<br>";
				echo "<input type='file' name='uploadFile' id='uploadFile'>";				
			}				
			?>
				<input type='submit' value='Actualizar' />
			</form>
			</div>
			<div class='footer'>
				<p>
					&copy; $projectName
				</p>
			</div>
		</div>
		$script
	</body>
</html>  
SOURCE;
	$archivo = fopen("../$recurso/edit.php", 'w') or die("No se pudo crear el archivo edit.php");
	fwrite($archivo, $setup_file);
	fclose($archivo);       

$setup_file = <<<SOURCE
<?php
require '../config/conexion.php';
\$id = \$_POST['id'];
\$eliminar = \$_POST['eliminar'];      
$sent_params

\$uploadFile = \$_FILES['uploadFile']['tmp_name'];
\$file_name = \$_FILES['uploadFile']['name'];
\$file_size = \$_FILES['uploadFile']['size'];
\$file_type = \$_FILES['uploadFile']['type'];

\$date = date('Y-m-d H:i:s');


if (move_uploaded_file(\$uploadFile, "../assets/$recurso/\$file_name") && unlink("../assets/$recurso/" . \$eliminar)) {
	\$query = "UPDATE $recurso SET  $update_attrs file_name = '\$file_name', file_size = '\$file_size', file_type = '\$file_type', actualizado = '\$date' WHERE id = '\$id'";
	\$completado = mysql_query(\$query) or die ("No se pudo realizar la consulta. " . mysql_error());
	if (\$completado) {
		header("location: ./index.php");
	} else {
		echo "Hubo un error en la consulta.";
	}
} else {
	\$query = "UPDATE $recurso SET  $update_attrs actualizado = '\$date' WHERE id = '\$id'"; 
	\$completado = mysql_query(\$query) or die ("No se pudo realizar la consulta. " . mysql_error());
	if (\$completado) {
		header("location: ./index.php");
	} else {
		echo "Hubo un error en la consulta.";
	}

}

?>
SOURCE;

$archivo = fopen("../$recurso/update.php", 'w') or die("No se pudo crear el archivo update.php");
fwrite($archivo, $setup_file);
fclose($archivo);       


$setup_file = <<<SOURCE
<?php
require '../config/conexion.php';
\$id = \$_POST['id'];
\$query = sprintf("SELECT * FROM $recurso WHERE id = '%s'", mysql_real_escape_string(\$id));
\$resultados = mysql_query(\$query) or die ("No se pudo realizar la consulta. " . mysql_error());
	while (\$resultado = mysql_fetch_array(\$resultados)) { 
		unlink("../assets/$recurso/" .  \$resultado['file_name']);
	}				
\$query = "DELETE FROM $recurso WHERE id = '\$id'";  
\$completado = mysql_query(\$query) or die ("No se pudo realizar la consulta. " . mysql_error());
if (\$completado) {
	header("location: ./index.php");
} else {
	echo "Problema con el query.";
}
?>
SOURCE;
	$archivo = fopen("../$recurso/destroy.php", 'w') or die("No se pudo crear el archivo destroy.php");
	fwrite($archivo, $setup_file);
	fclose($archivo);       

if (isset($_POST['responsive']) && $_POST['responsive'] > 0) {
$setup_file = <<<SOURCE
SOURCE;
}

$sql_table = "USE $projectName;\n";          
$sql_table .= "CREATE TABLE IF NOT EXISTS $recurso (\n";
$sql_table .= "id int(11) NOT NULL AUTO_INCREMENT,\n";
$i = 0;
foreach ($elem as $key => $value) { 
	$i++;
   if ($value != $recurso) {
		if ($i % 2 == 0) {
			$sql_table .= $value . " ";
		} else {
			$sql_table .= $value . ", \n";
		}
	}
}
$sql_table .= "file_name varchar(255), \n";
$sql_table .= "file_type varchar(255), \n";
$sql_table .= "file_size int(11), \n";
$sql_table .= "creado datetime, \n";
$sql_table .= "actualizado datetime, \n";
$sql_table .= "PRIMARY KEY (id) \n";
$sql_table .= ") ENGINE=MyISAM DEFAULT CHARSET=UTF8;";

	$archivo = fopen("../db/$recurso.sql", 'w') or die("No se pudo crear el archivo $recurso.sql");
	fwrite($archivo, $sql_table);
	fclose($archivo);       
	if (exec('mysql -u root < ../db/$recurso.sql')) {
		echo "<b>$recurso</b>";
		echo "<p>Listo para utilizar.</p>";
	}  else {
		echo "<b>$recurso</b>";
		echo "<p>Importa $proyecto/db/$recurso.sql a MySQL.</p>";
	}
  } else { ?>
		<h1>Configuración</h1>
		<p>
			Este script va a generar:
		</p>
		<ul>
			<li>Un archivo sql para crear una tabla en MySQL.</li>
			<li>Una carpeta con el nombre de la tabla dentro de assets. Ahí se van a almacenar los archivos adjuntos.</li>
			<li>Una carpeta con el nombre de la tabla la cual va a contener los siguientes archivos para administrar los registros de esa tabla.</li>
		</ul>
		<ul>
			<li><em>index</em>: Muestra todos los registros que están en la tabla.</li>
			<li><em>show</em>: Muestra un registro en particular.</li>
			<li><em>new</em>: Formulario para crear un registro.</li>
			<li><em>create</em>: Crea un registro en la BD y regresa a index.</li>
			<li><em>edit</em>: Formulario para editar un registro.</li>
			<li><em>update</em>: Actualiza un registro en la BD y regresa a index.</li>
			<li><em>destroy</em>: Elimina un registro.</li>
		</ul>

		<p>
			Dentro de la carpeta <?php echo $projectName; ?>
		</p>
		<form action='representar-archivo.php' method='post'>
			<label>Tabla</label><br />
			<input type='text' name='recurso' placeholder='Nombre de la tabla'><br />
			<div id='customAttributes'>
			<input type='text' name='attr_1' placeholder='Atributo' />
			<select name='type_1'>
				<option value='int(11)'>entero / integer</option>
				<option value='varchar(255)'>texto (menor a 255 caracteres) / varchar (less than 255 characters)</option>
				<option value='text'>texto / text</option>
				<option value='decimal(10,2)'>decimal / decimal</option>
				<option value='datetime'>fecha / hora</option>
			</select><br />       			
			</div>
			<input type='button' id='addAttribute' value='Agregar atributo' /><br />
			<input type='checkbox' name='htmlContent' />Seleccionar si se quiere almacenar contenido en HTML / CSS / JS<br />			
			<input type='checkbox' name='responsive' />Vincular con responsive.css - Documentos html sensibles al tamaño de la pantalla (ajustar reglas por tamaño).<br />
			<input type='submit' value='Crear archivos' />
		</form>
<?php
}
?>
		</div>
		<div class='footer'>
			&nbsp;	
		</div> 
	</div>
		<script src='http://code.jquery.com/jquery-1.7.2.min.js'></script>
		<script>
		  jQuery(document).ready(function($) {
			var items = 1; 
			$('#addAttribute').click(function() {
				items += 1;
				input = "<input type='text' name='attr_" + items + "' placeholder='Atributo' />";
				input += "<select name='type_" + items + "'><option value='int(11)'>entero / integer</option><option value='varchar(255)'>texto (menor a 255 caracteres) / varchar (less than 255 characters)</option><option value='text'>texto / text</option><option value='decimal(10,2)'>decimal / decimal</option><option value='datetime'>fecha / hora</option></select><br />";
				$('#customAttributes').append(input);
			});
		  });
		</script>
	</body>
</html>