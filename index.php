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
if (isset($_POST['proyecto'])) {
	$proyecto = $_POST['proyecto'];
	echo "Creando directorios para $proyecto <br />";
	mkdir("$proyecto", 0777, true);
	mkdir("$proyecto/config", 0777, true);
	mkdir("$proyecto/scripts", 0777, true);
	mkdir("$proyecto/db", 0777, true);
	mkdir("$proyecto/lib", 0777, true);  
	mkdir("$proyecto/assets", 0777, true);
	mkdir("$proyecto/assets/css", 0777, true);
	mkdir("$proyecto/assets/js", 0777, true);
	mkdir("$proyecto/assets/img", 0777, true);
	// Cambia los directorios a +rwx para que el servidor web pueda escribir los archivos. 
	chmod("$proyecto", 0777);
	chmod("$proyecto/config", 0777);
	chmod("$proyecto/scripts", 0777);
	chmod("$proyecto/db", 0777);
	chmod("$proyecto/lib", 0777);  
	chmod("$proyecto/assets", 0777);
	chmod("$proyecto/assets/css", 0777);
	chmod("$proyecto/assets/js", 0777);
	chmod("$proyecto/assets/img", 0777);

$setup_file = <<<SETUP_FILE
<?php
\$base_dir  = __DIR__;
\$doc_root  = preg_replace("!{\$_SERVER['SCRIPT_NAME']}\$!", '', \$_SERVER['SCRIPT_FILENAME']);
\$base_url  = preg_replace("!^{\$doc_root}!", '', \$base_dir);
define('ROOT_PATH', \$base_url);
?>
SETUP_FILE;
	$archivo = fopen("$proyecto/root.php", 'w') or die('No se pudo crear el archivo root.php');
	fwrite($archivo, $setup_file);
	fclose($archivo);
// root
	// HTML
$setup_file = <<<SETUP_FILE
<!DOCTYPE html>
<html>
	<head>
		<meta charset='utf-8' />
		<link rel="stylesheet" href="assets/css/$proyecto.css" type="text/css" />
	</head>
	<body>
		<div class='container'>
			<div class='header'>
				<h1>$proyecto</h1>
			</div>
			<div class='content'>
			<h1>Principal</h1>
			<p>Archivo principal</p>
			</div>
			<div class='footer'>
				<p>
					&copy; $proyecto
				</p>
			</div>
		</div>
		<script src='assets/js/$proyecto.js'></script>
	</body>
</html>     
SETUP_FILE;
	$archivo = fopen("$proyecto/index.php", 'w') or die('No se pudo crear el archivo index.php');
	fwrite($archivo, $setup_file);
	fclose($archivo);
// HTML
$setup_file = <<<SETUP_FILE
<!DOCTYPE html>
<html>
	<head>
		<meta charset='utf-8' />
		<link rel="stylesheet" href="assets/css/$proyecto.css" type="text/css" />
	</head>
	<body>
		<div class='container'>
			<div class='header'>
				<h1>$proyecto</h1>
			</div>
			<div class='content'>
			<h1>Leame</h1>
			<p>
				Este archivo es generado automáticamente.
				El documento html cuenta con 3 secciones dentro de un contenedor general.
			</p>                                                       
			<ul>
				<li>container</li>
				<li>header</li>
				<li>content</li>
				<li>footer</li>
			</ul>
			<p>
				También se generó una hoja de estilos y un archivo js que se encuentran dentro de assets/css/$proyecto.css y assets/js/$proyecto.js respectivamente.<br />
				Revisarlos y modificarlos para ajustar la visualización y funcionamiento sitio.
			</p>
			<p>
				Si se quiere crear un sitio sin conexión a BD, se pueden eliminar las carpetas <em>db</em> y <em>config</em> para ir agregando documentos html según se requiera.
			</p>
			<p>
				Si se quiere crear una aplicación o un sitio conectado a una BD en la carpeta db está el sql para generar la BD.<br />
				En config se encuentran los datos de conexión en php a la BD. <br />
				Se presupone lo siguiente: 
			</p>                           
			<ul>
				<li>Se está utilizando MySQL</li>
				<li>Se quiere almacenar el contenido en UTF-8</li>
				<li>Los datos de ingreso a MySQL son <em>root</em> sin contraseña (utilizando una versión de XAMPP)</li>    
				<li>Si MySQL se encuentra activo y se puede invocar desde consola, es probable que la BD se haya generado automáticamente.</li>
			</ul>
			</div>
			<div class='footer'>
				<p>
					&copy; $proyecto
				</p>
			</div>
		</div>
		<script src='assets/js/$proyecto.js'></script>
	</body>
</html>     
SETUP_FILE;
	$archivo = fopen("$proyecto/index.html", 'w') or die('No se pudo crear el archivo index.html'); 
	fwrite($archivo, $setup_file);
	fclose($archivo);

$setup_file = <<<SETUP_FILE
<?php

class Paginador {
  public \$consulta_total;  
  const REGISTROS_POR_PAGINA = 5;

  function limit() {
    return self::REGISTROS_POR_PAGINA;
  }

  function offset(\$pagina) {  
    return self::REGISTROS_POR_PAGINA * (\$this->pagina_actual(\$pagina) - 1);
  }  

  function pagina_actual(\$pagina_actual) {
    if (isset(\$pagina_actual)) {
      \$pagina = \$pagina_actual; 
    } else {
      \$pagina = 1; 
    }  
    return \$pagina;
  }

  function paginas_totales(\$con) {
    \$resultados = \$con->query(\$this->consulta_total);
    \$numero = \$resultados->fetch_array();
    return ceil(\$numero['total'] / self::REGISTROS_POR_PAGINA);  
  }

  function paginar(\$con, \$pagina) {
    \$paginacion = "";
    \$paginacion .= "<ul class='paginacion'>";
    \$total = \$this->paginas_totales(\$con);
    for (\$i=1; \$i < \$total + 1; \$i++) {
      if (\$i == \$this->pagina_actual(\$pagina)) {
        \$paginacion .= "<li><a class='actual' href='index.php?pagina=" . \$i. "'>" . \$i . "</a></li>";
      } else {
        \$paginacion .= "<li><a href='index.php?pagina=" . \$i. "'>" . \$i . "</a></li>";
      }
    }  
    \$paginacion .= "</ul>";    
    echo \$paginacion;
  }

}
?>
SETUP_FILE;
	$archivo = fopen("$proyecto/lib/paginador.php", 'w') or die('No se pudo crear el archivo paginador.php');
	fwrite($archivo, $setup_file);
	fclose($archivo);
// root
  
// CSS
$setup_file = <<< SETUP_FILE
body {font-family: 'helvetica neue', helvetica, sans-serif; font-size: 12px; line-height: 1.5; width: 980px; margin: 0 auto; color: #333;}
.container {width: 860px; margin: auto; }
.header {height: 40px; border-bottom: 1px solid #ccc;}
.content {padding: 1em 0;}
.footer {height: 40px; border-top: 1px solid #ccc; text-align: right; color: #777;}
/* Muestra los botones del formulario como vínculos */
.linkDisplay {border: none; padding: 0; margin: 0; color: #00E; font-size: inherit; font-family: inherit; text-decoration: underline; background: transparent; display: inline;}

/* Paginación */
ul.paginacion { text-align:center; display: block; clear: both; float: none; height: 20px; line-height: 20px;}
ul.paginacion li { display:inline-block; padding: 5px; float: left;  height: 20px; width: 20px; line-height: 20px;}
ul.paginacion a { display:block; text-decoration:none; width: 100%; height: 100%;}
ul.paginacion a:hover, ul.paginacion a.actual { text-decoration: underline; }

SETUP_FILE;
	$archivo = fopen("$proyecto/assets/css/$proyecto.css", 'w') or die("No se pudo crear el archivo $proyecto.css");
	fwrite($archivo, $setup_file);
	fclose($archivo);
// JS	
$setup_file = <<< SETUP_FILE
/* AQUÍ VA EL CÓDIGO JS */
SETUP_FILE;
	$archivo = fopen("$proyecto/assets/js/$proyecto.js", 'w') or die("No se pudo crear el archivo $proyecto.js");
	fwrite($archivo, $setup_file);
	fclose($archivo);       
// CONEXIÓN
$setup_file = <<< SETUP_FILE
<?php
  date_default_timezone_set('America/Mexico_City');
  \$conexion = new mysqli("127.0.0.1", "root","", "$proyecto");
  if (mysqli_connect_errno()) {
      printf("Error de conexión: %s\\n", mysqli_connect_error());
      exit();
  }  
  \$conexion->query("SET NAMES utf8mb4");
  \$conexion->query("SET CHARACTER SET utf8mb4");
?>
SETUP_FILE;
	$archivo = fopen("$proyecto/config/conexion.php", 'w') or die("No se pudo crear el archivo conexion.php");
	fwrite($archivo, $setup_file);
	fclose($archivo);       
// CREAR BD
$setup_file = <<< SETUP_FILE
CREATE DATABASE $proyecto 
DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
SETUP_FILE;
	$archivo = fopen("$proyecto/db/$proyecto.sql", 'w') or die("No se pudo crear el archivo $proyecto.sql");
	fwrite($archivo, $setup_file);
	fclose($archivo);
	// Copiar scripts al proyecto generado
		copy("scripts/autorizacion.php", "$proyecto/scripts/autorizacion.php");
		copy("scripts/buscador.php", "$proyecto/scripts/buscador.php");
		copy("scripts/contacto.php", "$proyecto/scripts/contacto.php");
		copy("scripts/index.html", "$proyecto/scripts/index.html");    
		copy("scripts/pluralize.php", "$proyecto/scripts/pluralize.php");
		copy("scripts/representar.php", "$proyecto/scripts/representar.php");
		copy("scripts/representar-archivo.php", "$proyecto/scripts/representar-archivo.php");
		copy("scripts/union.php", "$proyecto/scripts/union.php");
	// Copiar scripts al proyecto generado
	if (exec("mysql -u root < db/$proyecto.sql")) {
		echo "Listo para utilizar.<br />";
	}  else {
		echo "Importa $proyecto/db/$proyecto.sql a MySQL.<br />";
	}
	echo "¡Hecho!"; 
	echo "Después de importar '$proyecto/db/$proyecto.sql' revisa los <a href='$proyecto/scripts/index.html' target='_blank'>scripts</a> para continuar.";
} else { ?>
	<h2>¿Qué es?</h2>
	<p>
	Generador de archivos (php / sql) desde una interfaz web.<br>
	Permite trabajar con las operaciones básicas (crear, leer, actualizar, eliminar) de datos en las tablas.
	</p>
	<h2>Recomendaciones de uso</h2>
	<p>
		Las relaciones entre tablas están basadas en las asociaciones de <a href='http://api.rubyonrails.org/classes/ActiveRecord/Associations/ClassMethods.html' target='_blank'>ActiveRecord</a> de <a href='http://www.rubyonrails.org'>Ruby On Rails</a>.<br>
		<ul>
			<li>Nombre de la tabla en español, plural (usuarios, categorias, productos, documentos, etiquetas).</li>
			<li>Para nombrar tablas utilizar solo letras minúsculas [a-z], sin acentos o ñ, espacios o guiones.</li>
			<li>
				Cuando se quiera una clave foránea (FK) para relacionar dos tablas utilizar el nombre de la tabla "independiente" en singular seguido de _id.<br> 
				Ej: Relacionar productos con categorias.<br>
				Al crear la tabla "productos", agregar una columna llamada categoria_id.
			</li>
		</ul>
	</p>
	<h2>Requerimientos</h2>
	<p>
		Instalación LAMP (XAMPP, MAMP, WAMP o instalación independiente de Apache, MySQL, PHP).
	</p>
	<h2>Instalación</h2>
	<p>
	Descargar el archivo .zip<br>
	Descomprimir dentro de htdocs o carpeta donde se encuentren los proyectos web (como /var/www/http).  <br>
	Renombrar la carpeta con un nombre corto (setup).<br>
	Verificar que la carpeta tenga permisos para escritura (0777) para poder generar el proyecto.<br>
	Iniciar Apache, MySQL y visitar: http://localhost/setup/setup.php<br>
	</p>
	<h2>Notas</h2>
	<p>
	La dirección (URL) puede variar si la instalación de XAMPP utiliza otro puerto (8080, 8888).   <br>
	Es recomendable aumentar la seguridad cuando el proyecto se encuentre en un servidor que sea accesible a través de internet (cambiar los permisos de escritura a (0755)).  <br>
	Si mysql es accesible desde la línea de comandos, algunos scripts va a tratar de generar la BD / tablas con el usuario root y sin contraseña (valores predeterminados en xampp).<br>
	</p>
		<p>
			También se va a almacenar el script de creación de la BD dentro de la carpeta db por si no se creó la BD o si se quiere crear en otro lugar. <br />			
		</p> 
		<ul>
			<li><em>index.html</em>: Archivo base.</li>
			<li><em>assets</em>: Contiene las carpetas css, img, js para almacenar los archivos correspondientes.</li>
			<li><em>config/conexion.php</em>: Archivo de configuración para conectar PHP a MySQL preconfigurado para trabajar en XAMPP  (127.0.0.1, root sin contraseña)<br />
					La zona horaria predeterminada es la de la Ciudad de México. Dentro de este archivo viene un vínculo para revisar las zonas horarias disponibles en PHP y ajustar si así se requiere.
				</li>
			<li><em>db</em>: Carpeta que tiene el archivo sql para crear la BD. Si se utiliza resource.php ahí se van a guardar los scripts para crear las tablas.</li>
			<li><em>scripts</em>: Scripts en PHP que agregan funcionalidad</li>
		</ul>
		<form action='index.php' method='post'>
			<label>Proyecto</label><br />
			<input type='text' name='proyecto' placeholder='Nombre del proyecto'><br />
			<input type='submit' value='Crear proyecto'>
		</form>
<?php
}
?>
		</div>
		<div class='footer'>
			&nbsp;  			
		</div>
	</div>
	</body>
</html>