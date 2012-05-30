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
				Join table
				<form action='system.php' method='post'>
<?php
	require "../config/conexion.php";
	$projectName = basename(dirname(dirname(__FILE__)));		
			if (isset($_POST['join_table'])) {
				// Crear la tabla join
				$t1 = $_POST['table_1'];
				$t2 = $_POST['table_2'];
				$table_name = $t1 . "_" . $t2;
				$table_name_array = $table_name . "[]";
				$form_action = "update_" . $table_name . ".php";
				$t1_id = $t1 . "_id";
				$t2_id = $t2 . "_id";

$setup_file = <<<SOURCE
<!DOCTYPE html>
<html>
	<head>
		<meta charset='utf8' />
	</head>
	<body>
		<form action='$form_action' method='post'>
		<?php
		  require '../config/conexion.php';
		 \$checkboxes = "";
		 \$index = 0;
		 \$ids = array();
		\$id = \$_GET['\$id'];
		
		\$select_checked = "SELECT $t2_id FROM $table_name WHERE $t1_id = '\$id'";
		\$checked = mysql_query(\$select_checked) or die ("No se pudo realizar la consulta. " . mysql_error());

		while(\$id = mysql_fetch_row(\$checked)) {
			\$ids[\$index] = \$id[0];
			++\$index;
		}      
		
		\$query = "SELECT * FROM $t2";
 		\$resultado = mysql_query(\$query) or die ("No se pudo realizar la consulta. " . mysql_error());

		while (\$mostrar = mysql_fetch_row(\$resultado)) {
			\$checkboxes .= "<input type='checkbox' name='$table_name_array' value='\$mostrar[0]'"
			for (\$i = 0; \$i < count(\$ids); \$i++) {
				if (\$ids[\$i] == \$mostrar[0]) {
					\$checkboxes .= "checked";
				}
			}
			\$checkboxes .= ">\$mostrar[1]"; 							
		} 
		echo \$checkboxes;		
?>                 
		<input type='hidden' name='id' value='<?php echo $_GET['id'] ?>' />
		<input type='submit'value='Actualizar' />
		</form>
	</body>
</html>
SOURCE;
	$archivo = fopen("../$t1/join.php", 'w') or die("No se pudo crear el archivo join.php");
	fwrite($archivo, $setup_file);
	fclose($archivo);       

$setup_file = <<<SOURCE
	<?php
	require "../config/conexion.php";
	\$id = \$_POST['id'];
	\$query = "DELETE FROM $table_name WHERE $t1_id = '\$id'";
	\$completado = mysql_query(\$query) or die ("No se pudo realizar la consulta. " . mysql_error());		

	if (isset(\$_POST['$table_name'])) {
		for(\$i=0; \$i < count(\$_POST['$table_name']); \$i++) {
			\$query = "INSERT INTO $table_name ($t2_id, $t1_id ) VALUES (" . \$_POST['$t2'][\$i] . ", \$id)";
			\$completado = mysql_query(\$query) or die ("No se pudo realizar la consulta. " . mysql_error());
		}
	}
	if (\$completado) {
		header("location: ./index.php");
	} else {
		echo "Problema con el query.";
	}
	?>
SOURCE;
		$archivo = fopen("../$t1/update_join.php", 'w') or die("No se pudo crear el archivo update_join.php");
		fwrite($archivo, $setup_file);
		fclose($archivo);       
		$sql_table = "USE $projectName;\n";          
		$sql_table .= "CREATE TABLE IF NOT EXISTS $table_name (\n";
		$sql_table .= $t1 . "_id int(11) NOT NULL,\n";
		$sql_table .= $t2 . "_id int(11) NOT NULL,\n";
		$sql_table .= "creado datetime, \n";
		$sql_table .= "actualizado datetime, \n";
		$sql_table .= "PRIMARY KEY (" . $t1 . "_id," . $t2 . "_id) \n";
		$sql_table .= ") ENGINE=MyISAM DEFAULT CHARSET=UTF8;";
		$archivo = fopen("../db/$table_name.sql", 'w') or die("No se pudo crear el archivo $recurso.sql");
		fwrite($archivo, $sql_table);
		fclose($archivo);       
		if (exec('mysql -u root < ../db/$recurso.sql')) {
			echo "<b>$table_name</b>";
			echo "<p>Listo para utilizar.</p>";
		}  else {
			echo "<b>$table_name</b>";
			echo "<p>Importa $proyecto/db/$table_name.sql a MySQL.</p>";
		}
	}
	else {
		$query = "SHOW TABLES";
		$available_tables = "";
		$resultados = mysql_query($query) or die ("No se pudo realizar la consulta. " . mysql_error());
		while ($resultado = mysql_fetch_array($resultados)) { 				
		 	$available_tables .= "<option value='" . $resultado['0'] . "'>" . $resultado['0'] . '</option>';
		}   
		echo "<select id='t1' name='table_1'>";  
		echo $available_tables;
		echo "</select>";
		echo "<select id='t2' name='table_2'>";
		echo $available_tables;
		echo "</select>";
		}
?>                
		<input type='submit' name='join_table' value='Crear Tabla' />
	</form>
		</div>
		<div class='footer'>
			&nbsp;	
		</div> 
	</div>    
	</body>
</html>