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
	mkdir("$proyecto", 0777);
	mkdir("$proyecto/config", 0777, true);
	mkdir("$proyecto/scripts", 0777, true);
	mkdir("$proyecto/db", 0777, true);
	mkdir("$proyecto/assets", 0777, true);
	mkdir("$proyecto/assets/css", 0777, true);
	mkdir("$proyecto/assets/js", 0777, true);
	mkdir("$proyecto/assets/img", 0777, true);
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
		<script src='http://code.jquery.com/jquery-1.7.2.min.js'></script>
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
		<script src='http://code.jquery.com/jquery-1.7.2.min.js'></script>
		<script src='assets/js/$proyecto.js'></script>
	</body>
</html>     
SETUP_FILE;
	$archivo = fopen("$proyecto/index.html", 'w') or die('No se pudo crear el archivo index.html'); 
	fwrite($archivo, $setup_file);
	fclose($archivo);
// CSS
$setup_file = <<< SETUP_FILE
body {font-family: 'helvetica neue', helvetica, sans-serif; font-size: 12px; line-height: 1.5; width: 980px; margin: 0 auto; color: #333;}
.container {width: 860px; margin: auto; }
.header {height: 40px; border-bottom: 1px solid #ccc;}
.content {padding: 1em 0;}
.footer {height: 40px; border-top: 1px solid #ccc; text-align: right; color: #777;}
/* Muestra los botones del formulario como vínculos */
.linkDisplay {border: none; padding: 0; margin: 0; color: #00E; font-size: inherit; font-family: inherit; text-decoration: underline; background: transparent; display: inline;}
SETUP_FILE;
	$archivo = fopen("$proyecto/assets/css/$proyecto.css", 'w') or die("No se pudo crear el archivo $proyecto.css");
	fwrite($archivo, $setup_file);
	fclose($archivo);
// CSS
$setup_file = <<< SETUP_FILE
/* 
   Responsive CSS
   1. Utilizar la hoja de estilos del sitio / aplicación normalmente.
   2. Mandar llamar esta hoja de estilos después de que se cargue la hoja de estilos del sitio.
   Agregar dentro de cada tamaño de anchura las reglas en CSS que van a modificar valores establecidos en la hoja de estilos inicial.
   por ejemplo:
	@media (min-width:1200px){
		body {width: 1000px; }
	}
*/
@media (min-width:1200px){
}

@media (min-width:940px){
}

@media (max-width:940px){
}

@media (max-width:768px){ 
}

@media (max-width:480px){
}                    			
SETUP_FILE;
	$archivo = fopen("$proyecto/assets/css/responsive.css", 'w') or die("No se pudo crear el archivo responsive.css");
	fwrite($archivo, $setup_file);
	fclose($archivo);
// JS	
$setup_file = <<< SETUP_FILE
/*
AQUÍ VA EL CÓDIGO JS
jQuery(document).ready(function($) { 
});
*/
SETUP_FILE;
	$archivo = fopen("$proyecto/assets/js/$proyecto.js", 'w') or die("No se pudo crear el archivo $proyecto.js");
	fwrite($archivo, $setup_file);
	fclose($archivo);       
// CONEXIÓN
$setup_file = <<< SETUP_FILE
<?php
	/* http://www.php.net/manual/en/timezones.php */
	date_default_timezone_set('America/Mexico_City');
	\$conexion = mysql_connect("127.0.0.1", "root","") or die ("Revisa host, usuario y password. " . mysql_error());
	\$db = mysql_select_db("$proyecto") or die("Revisa el nombre de tu BD. " . mysql_error());
	mysql_query("SET NAMES UTF8");
	mysql_query("SET CHARACTER SET utf8");
?>
SETUP_FILE;
	$archivo = fopen("$proyecto/config/conexion.php", 'w') or die("No se pudo crear el archivo conexion.php");
	fwrite($archivo, $setup_file);
	fclose($archivo);       
// CREAR BD
$setup_file = <<< SETUP_FILE
CREATE DATABASE $proyecto 
DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
SETUP_FILE;
	$archivo = fopen("$proyecto/db/$proyecto.sql", 'w') or die("No se pudo crear el archivo $proyecto.sql");
	fwrite($archivo, $setup_file);
	fclose($archivo);
	// Copiar scripts al proyecto generado
		exec("cp scripts/index.html $proyecto/scripts/index.html");
		exec("cp scripts/autorizacion.php $proyecto/scripts/autorizacion.php");
		exec("cp scripts/join.php $proyecto/scripts/join.php");
		exec("cp scripts/pluralize.php $proyecto/scripts/pluralize.php");
		exec("cp scripts/resource.php $proyecto/scripts/resource.php");
		exec("cp scripts/search.php $proyecto/scripts/search.php");
	// Copiar scripts al proyecto generado
	if (exec("mysql -u root < db/$proyecto.sql")) {
		echo "Listo para utilizar.<br />";
	}  else {
		echo "Importa $proyecto/db/$proyecto.sql a MySQL.<br />";
	}
	echo "¡Hecho!"; 
	echo "Después de importar '$proyecto/db/$proyecto.sql' revisa los <a href='$proyecto/scripts/index.html' target='_blank'>scripts</a> para continuar.";
} else { ?>
		<h1>Requerimientos</h1>
		<p>
			Se necesita una instalación de Apache, PHP, MySQL (XAMPP, LAMP, WAMP o intalaciones independientes).
		</p>
		<h1>Configuración</h1>
		<p>
			setup tiene que estar dentro de htdocs y tiene que ser accesible a través de una dirección (URL) similar a:<br />
			La carpeta que contiene este archivo (setup) debe de contar con permisos para escritura (0777) para poder generar la carpeta con el proyecto.<br />
			La dirección (URL) puede variar si la instalación de XAMPP utiliza otro puerto (8080, 8888).<br />
		</p>
		<p>			
			Solo es recomendable para generar los archivos en un ambiente de desarrollo local (localhost) pero <b>NO</b> en un servidor que sea accesible a través de internet.
		</p>
		<p>
			Si mysql es accesible desde la terminal / consola el script va a tratar de generar la BD con el usuario root y sin contraseña (valores predeterminados en xampp).<br />
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
		<form action='setup.php' method='post'>
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