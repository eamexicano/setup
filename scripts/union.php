<!DOCTYPE html>
<html>
	<head>
		<meta charset='utf-8' />
		<title>setup - join </title>
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
				<h1>Union</h1>
				<p>
					Representación de una relación M:M entre dos tablas.<br>
					Se va a crear un sql que tiene una tabla de unión - join_table - donde van a estar registrados los ids de la primer y segunda tabla.<br>
					En la carpeta de la primer tabla se van a crear un arrchivo join_table.html<br>
          Este archivo va a tener código para modificar los siguientes archivos: <br>
          new.php, create.php, edit.php, update.php, destroy.php
				</p>
				<form action='union.php' method='post'>
<?php
	require "../config/conexion.php";
	$projectName = basename(dirname(dirname(__FILE__)));		
			if (isset($_POST['join_table'])) {
      	$css = "<link rel='stylesheet' href='../assets/css/$projectName.css' type='text/css' />";

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
      $css
  	</head>
  	<body>
    <h1>¡Alto!</h1>
    <p>
    Este archivo no se utiliza directamente. Es necesario incluir el código presentado aquí en diferentes archivos. 
    Y después eliminarlo ( a menos que se quiera conservar como referencia). <br>
    También se creo un archivo sql con el nombre de las tablas que va a crear una tabla de union - JOIN TABLE - entre las tablas 
    mencionadas. <br>
    </p>
    
    <p>
      Incluir el siguiente código en el archivo new.php<br>
      Va a mostrar todos los registros de la tabla dos como checkbox para que se puedan asociar. 
    </p>
    <pre>
      <code>
&lt;?php
 require '../config/conexion.php';
 \$checkboxes = "";
 \$query = "SELECT * FROM $t2";
 \$resultado = \$conexion->query(\$query);
	while (\$mostrar = \$resultado->fetch_array()) {
		\$checkboxes .= "&lt;label&gt;&lt;input type='checkbox' name='$table_name_array' value='\$mostrar[0]' &gt; \$mostrar[1] &lt;/label&gt;";
	}
echo \$checkboxes;		
?&gt;
      </code>
    </pre>
    
    <p>
    Incluir el siguiente código en el archivo create.php<br>
    Recibe los parámetros de los checkeboxes asociados al registro recién creado para crear<br>
    nuevos registros en la tabla de unión.
    </p>
    
    <pre>
      <code>
    	\$id = \$conexion->insert_id;;
    	\$query = "DELETE FROM $table_name WHERE $t1_id = '\$id'";
    	\$completado = \$conexion->query(\$query);

    	if (isset(\$_POST['$table_name'])) {
    		for(\$i=0; \$i < count(\$_POST['$table_name']); \$i++) {
    			\$query = "INSERT INTO $table_name ($t2_id, $t1_id, creado, actualizado) VALUES ('" . \$_POST['$table_name'][\$i] . "', '\$id', '\$date', '\$date')";
    			\$completado = \$conexion->query(\$query);
    		}
    	}

      </code>
    </pre>
    
    <p>
      Incluir en edit.php<br>
    
    </p>
    
    <pre>
      <code>
&lt;?php

\$checkboxes = "";
\$index = 0;
\$ids = array();

\$select_checked = "SELECT $t2_id FROM $table_name WHERE $t1_id = '\$id'";
\$checked = \$conexion->query(\$select_checked);

while(\$related = \$checked->fetch_array()) {
  \$ids[\$index] = \$related[0];
  \$index++;
}      

\$query = "SELECT * FROM $t2";
\$resultado = \$conexion->query(\$query);

while (\$mostrar = \$resultado->fetch_array()) {
\$checkboxes .= "&lt;label&gt;&lt;input type='checkbox' name='$table_name_array' value='\$mostrar[0]'";
for (\$i = 0; \$i < count(\$ids); \$i++) {
	if (\$ids[\$i] == \$mostrar[0]) {
		\$checkboxes .= "checked";
	}
}
\$checkboxes .= "&gt;\$mostrar[1] &lt;/label&gt;";
} 
echo \$checkboxes;		
?&gt;    
      </code>
    </pre>
    
    <p>
      Incluir en update.php<br>    
    </p>
    
    <pre>
      <code>

\$query = "DELETE FROM $table_name WHERE $t1_id = '\$id'";
\$completado = \$conexion->query(\$query);

if (isset(\$_POST['$table_name'])) {
	for(\$i=0; \$i < count(\$_POST['$table_name']); \$i++) {
		\$query = "INSERT INTO $table_name ($t2_id, $t1_id, creado, actualizado) VALUES ('" . \$_POST['$table_name'][\$i] . "', '\$id', '\$date', '\$date')";
		\$completado = \$conexion->query(\$query);
	}
}


      </code>
    </pre>
    
    
    <p>
      Incluir en destroy.php<br>    
    </p>
    
    <pre>
      <code>

\$query = "DELETE FROM $table_name WHERE $t1_id = '\$id'";
\$completado = \$conexion->query(\$query);

      </code>
    </pre>
    
    
  	</body>
  </html>


SOURCE;
$archivo = fopen("../$t1/join_table.html", 'w') or die("No se pudo crear el archivo update_join.php");
fwrite($archivo, $setup_file);
fclose($archivo);       


		$sql_table = "USE $projectName;\n";          
		$sql_table .= "CREATE TABLE IF NOT EXISTS $table_name (\n";
		$sql_table .= $t1 . "_id int(11) NOT NULL,\n";
		$sql_table .= $t2 . "_id int(11) NOT NULL,\n";
		$sql_table .= "creado datetime, \n";
		$sql_table .= "actualizado datetime, \n";
		$sql_table .= "PRIMARY KEY (" . $t1 . "_id," . $t2 . "_id) \n";
		$sql_table .= ") ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
		$archivo = fopen("../db/$table_name.sql", 'w') or die("No se pudo crear el archivo $recurso.sql");
		fwrite($archivo, $sql_table);
		fclose($archivo);       
		if (exec('mysql -u root < ../db/$recurso.sql')) {
			echo "<b>$table_name</b>";
			echo "<p>Listo para utilizar.</p>";
      echo "<h4>Importante</h4>";
      echo "<p>Lee el archivo join_table.html que se encuentra en la carpeta $t1</p>";
		}  else {
			echo "<b>$table_name</b>";
			echo "<p>Importa $proyecto/db/$table_name.sql a MySQL.</p>";
      echo "<h4>Importante</h4>";
      echo "<p>Lee el archivo join_table.html que se encuentra en la carpeta $t1</p>";      
		}
	}
	else {
		$query = "SHOW TABLES";
		$available_tables = "";
		$resultados = $conexion->query($query);
		while ($resultado = $resultados->fetch_array()) { 				
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