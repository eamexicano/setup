<!DOCTYPE html>
<html>
	<head>
		<meta charset='utf-8' />
		<title>setup - search</title>
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
        <form action='buscador.php' method='get'>
          <?php if (isset(\$_GET['q'])) { ?>
					<input type='text' name='q' value='<?php echo \$_GET["q"]?>'><br />
          <?php } else { ?>
            <input type='text' name='q'><br>    
          <?php } ?> 
					<input type='submit' value='Buscar' />
				</form>
				<!-- Formulario de búsqueda -->
        
  			<!-- Consulta  -->
  			<?php
        
        if (isset(\$_GET['q'])) {
          \$q = \$_GET['q'];          
          \$query = "SELECT * FROM $tabla WHERE $atributo LIKE ?";
          if (\$stmt = \$conexion->prepare(\$query)) {
            \$search_term = "%$q%";
            \$stmt->bind_param("s", \$search_term);
            \$stmt->execute();
            \$resultados = \$stmt->get_result();

    				while (\$resultado = \$resultados->fetch_array()) { 
              echo "<a href='#'>" . \$resultado['$atributo'] . "</a><br />";
    				} 
            \$stmt->close();
          }
        } else {
          \$query = "SELECT * FROM $tabla";          
          if (\$stmt = \$conexion->prepare(\$query)) {
            \$stmt->execute();
            \$resultados = \$stmt->get_result();

    				while (\$resultado = \$resultados->fetch_array()) { 
              echo "<a href='#'>" . \$resultado['$atributo'] . "</a><br />";
    				} 
            \$stmt->close();
          }
        }
        \$conexion->close();

  			?>
  			<!-- Consulta  -->        
        
			</div>
			<div class='footer'>
				<p>
					&copy;
				</p>
			</div>
		</div>
	</body>
</html>
SOURCE;
	$archivo = fopen("../buscador.php", 'w') or die("No se pudo crear el archivo busqueda.php");
	fwrite($archivo, $setup_file);
	fclose($archivo);

?> 
	<h1>¡Hecho!</h1>
	<p>Se creó el siguiente archivo:</p>
	<ul>
		<li>
			<em>buscador.php</em>
			<p>
        En este archivo se encuentra tanto el formulario de búsqueda como las consultas que muestran el resultado.<br>
        Si no se realiza una búsqueda (que no exista término de búsqueda) se muestran todos los registros de la tabla. <br>
        Si existe un término de búsqueda, la búsqueda se realiza en la columna especificada. Se espera que la columna<br>
        contenga el término, es decir, no importa cuántos caracteres existan antes o después del término y qué caracteres sean,<br>
        mientras el término exista.
      </p>

		</li>
	</ul>

<?php 
} else {
?>
	<h1>Buscador</h1> 
	
	<form action='buscador.php' method='post'>
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