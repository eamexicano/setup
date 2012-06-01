<!DOCTYPE html>
<html>
	<head>
		<meta charset='utf-8' />
		<title>setup</title>
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
	$projectName = basename(dirname(dirname(__FILE__)));
if (isset($_POST['tabla']) && isset($_POST['atributo'])) {
	$tabla = $_POST['tabla'];
	$atributo = $_POST['atributo'];

$setup_file = <<<SOURCE
<!DOCTYPE html>
<html>
	<head>
		<meta charset='utf-8' />
		<link rel="stylesheet" href="assets/css/$projectName.css" type="text/css" />
	</head>
	<body>
		<?php require 'config/conexion.php'; ?>
		<div class='container'>
			<div class='header'>
				<h1>búsqueda</h1>
			</div>
			<div class='content'>
				<!-- Formulario de búsqueda -->
                 	<form action='resultados.php' method='get'>
					<input type='text' name='q' /><br />
					<input type='submit' value='Buscar' />
				</form>
				<!-- Formulario de búsqueda -->
			</div>
			<div class='footer'>
				<p>
					&copy;
				</p>
			</div>
		</div>
		<script src='http://code.jquery.com/jquery-1.7.2.min.js'></script>
		<script src='../assets/js/$proyectName.js'></script>
	</body>
</html>
SOURCE;
	$archivo = fopen("../busqueda.php", 'w') or die("No se pudo crear el archivo busqueda.php");
	fwrite($archivo, $setup_file);
	fclose($archivo);

$setup_file = <<<SOURCE
<!DOCTYPE html>
<html>
	<head>
		<meta charset='utf-8' />
		<link rel="stylesheet" href="assets/css/$projectName.css" type="text/css" />
	</head>
	<body>
		<?php require 'config/conexion.php'; ?>
		<div class='container'>
			<div class='header'>
				<h1>Resultados de Búsqueda</h1>
			</div>
			<div class='content'>
			<!-- Resultados de Búsqueda -->
			<?php
			\$q = mysql_real_escape_string(\$_GET['q']);
			\$query = "SELECT * FROM $tabla WHERE $atributo LIKE '%\$q%'";
			\$resultados = mysql_query(\$query) or die ("No se pudo realizar la consulta. " . mysql_error());
			while (\$resultado = mysql_fetch_array(\$resultados)) { 
				echo "<a href='#'>" . \$resultado['$atributo'] . "</a>";
				echo "<br />";
			}				
			?>
			<!-- Resultados de Búsqueda -->
			</div>
			<div class='footer'>
				<p>
					&copy;
				</p>
			</div>
		</div>
		<script src='http://code.jquery.com/jquery-1.7.2.min.js'></script>
		<script src='../assets/js/$proyectName.js'></script>
	</body>
</html>
SOURCE;
	$archivo = fopen("../resultados.php", 'w') or die("No se pudo crear el archivo resultados.php");
	fwrite($archivo, $setup_file);
	fclose($archivo);   
?> 
	<h1>¡Hecho!</h1>
	<p>Se crearon dos archivos:</p>
	<ul>
		<li>
			<em>busquedas.php</em>
			<p>Formulario de búsquedas (dentro de una página), si solo se quiere el formulario se puede recortar del código lo que está dentro de las siguientes etiquetas.</p>
			<p>&lt;!-- Formulario de búsqueda --&gt;</p>
		</li>
		<li>
			<em>resultados.php</em>
			<p>
				Archivo que realiza una búsqueda en la BD y muestra los resultados en forma de vínculo.<br />
				El vínculo <b>NO</b> está asociado a una acción o página para visualizar el contenido de un resultado en particular. 
				(eso se tiene que hacer).
			</p>
		 </li>
	</ul>

<?php 
} else {
?>
	<h1>Buscador</h1>
	<form action='search.php' method='post'>
		<label>Tabla en la que se va a buscar</label><br />
		<input type='text' name='tabla' placeholder='tabla'><br />

		<div id='customAttributes'>
			<label>Columna en la que se va a realizar la búsqueda</label><br />
			<input type='text' name='atributo' placeholder='atributo' />
		</div>
		<input type='submit' value='Crear recurso' />
	</form>
				
<?php } ?>
		</div>
		<div class='footer'>
			&nbsp;	
		</div> 
	</div>    
	<script src='http://code.jquery.com/jquery-1.7.2.min.js'></script>
	</body>
</html>