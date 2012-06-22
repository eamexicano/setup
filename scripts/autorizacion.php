<!DOCTYPE html>
<html>
	<head>
		<meta charset='utf-8' />
		<title>setup - autorización</title>
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
				<h1>Autorización</h1>
			</div>
			<div class='content'>  

<?php         
$msg = "";
if (isset($_POST['autorizacion'])) {
$projectName = basename(dirname(dirname(__FILE__)));
// Autenticación
$setup_file = <<<SOURCE
<?php
	session_start();
	if (empty(\$_SESSION['uid'])) { header("location: index.php"); } 
?>
SOURCE;
	$archivo = fopen("../autenticacion.php", 'w') or die("No se pudo crear el archivo destroy.php");
	fwrite($archivo, $setup_file);
	fclose($archivo);
// Autorización
$setup_file = <<<SOURCE
<?php
	session_start();
	if ((\$_SESSION['admin'] == null) || (\$_SESSION['admin'] <> 1)) {
		header("location: ../index.php");
	}
?>
SOURCE;
	$archivo = fopen("../autorizacion.php", 'w') or die("No se pudo crear el archivo destroy.php");
	fwrite($archivo, $setup_file);
	fclose($archivo);
// Cerrar Sesión
$setup_file = <<<SOURCE
<?php
	session_start();
	session_destroy();
	header("location: index.php");
?>
SOURCE;
	$archivo = fopen("../cerrar_sesion.php", 'w') or die("No se pudo crear el archivo destroy.php");
	fwrite($archivo, $setup_file);
	fclose($archivo);
// Cuenta
$setup_file = <<<SOURCE
<?php
session_start();
require "config/conexion.php";

\$nombre = \$_POST['nombre'];
\$email = \$_POST['email'];
\$password = \$_POST['password'];
\$confirmacion = \$_POST['confirmacion'];
\$date = date('Y-m-d H:i:s');

if (\$password == \$confirmacion) {
	\$query = "INSERT INTO usuarios (nombre, email, password, creado, actualizado) VALUES ('\$nombre', '\$email', md5('\$password'), '\$date', '\$date')";
	\$completado = mysql_query(\$query) or die ("No se pudo realizar la consulta. " . mysql_error());

	\$query = "SELECT id FROM usuarios WHERE email = '\$email' AND password = md5('\$password')";
	\$resultado = mysql_query(\$query) or die ("No se pudo realizar la consulta. " . mysql_error());	

	while (\$usuario = mysql_fetch_array(\$resultado)) {
		\$session =  \$usuario['id'];
		}

		\$_SESSION['uid'] = \$session;			
		header("location: home.php");
} else {
		header("location: index.php");
}                                      

?> 
SOURCE;
	$archivo = fopen("../cuenta.php", 'w') or die("No se pudo crear el archivo destroy.php");
	fwrite($archivo, $setup_file);
	fclose($archivo);
// Home
$setup_file = <<<SOURCE
<?php require 'autenticacion.php' ?>
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
				<h1><a href='index.php'>$projectName</a></h1>
			</div>

			<div class='content'>
				<?php if (isset(\$_SESSION['admin']) && \$_SESSION['admin'] == 1) { ?>
					 <!-- Código solo para administradores -->
				<?php } ?>
					<a href='cerrar_sesion.php'>Cerrar Sesión</a><br />
			</div>
			<div class='footer'>
				<p>
					&copy; $projectName
				</p>
			</div>
		</div>
		<script src='http://code.jquery.com/jquery-1.7.2.min.js'></script>
		<script src='assets/js/$proyectName.js'></script>
	</body>
</html> 
SOURCE;
	$archivo = fopen("../home.php", 'w') or die("No se pudo crear el archivo destroy.php");
	fwrite($archivo, $setup_file);
	fclose($archivo);
// Sesión
$setup_file = <<<SOURCE
<?php
session_start();
\$msg = "";
require "config/conexion.php";

if (isset(\$_POST['sesion'])) {
		\$email = \$_POST['email'];
		\$password = \$_POST['password'];

		\$query = "SELECT id, admin FROM usuarios WHERE email = '\$email' AND password = md5('\$password')";
		\$resultado = mysql_query(\$query) or die ("No se pudo realizar la consulta. " . mysql_error());	

		while (\$usuario = mysql_fetch_array(\$resultado)) {
			if (isset(\$usuario)) {
				\$usuario_id =  \$usuario['id']; 
				\$rol_id = \$usuario['admin']; // Si es administrador nos va a dar un 1, si es usuario normal es 0
			} 
		} 	

		if (\$usuario_id) {
				\$_SESSION['uid'] = \$usuario_id;
				\$_SESSION['admin'] = \$rol_id;
				header("location: home.php");
		} else {
			\$msg = "El usuario o la contraseña no son correctas. Intenta de nuevo.";
		}
}                                               

?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset='utf-8' />
		<link rel="stylesheet" href="assets/css/$projectName.css" type="text/css" />
	</head>
	<body>
		<div class='container'>
			<div class='header'>
				<h1><a href='index.php'>$projectName</a></h1>
			</div>

			<div class='content'>
				<h3>Autorización</h3>
				<fieldset>
					<legend>Iniciar sesión</legend>
				<form action="iniciar_sesion.php" method="post" accept-charset="utf-8">
					<?php
						if (\$msg <> "")  {
							echo "<div style='width: 100%; display: block; height: 50px; color: red'>";
								echo \$msg;
							echo "</div>";
						}
					?>
					<table>
						<tr>
							<td><label>email</label></td>
							<td><input type="text" name="email"></td>
						</tr>
						<tr>
							<td><label>password</label></td>
							<td><input type="password" name="password"></td>
						</tr>
					</table>
					<input type="submit" value="Iniciar sesión" name='sesion' />
				</form>
				</fieldset>
				<fieldset>
					<legend>Crear cuenta</legend>
				<form action="cuenta.php" method="post" accept-charset="utf-8">
					<table>
						<tr>
							<td><label>Nombre Completo:</label></td>
							<td><input type="text" name="nombre"></td>
						</tr>
						<tr>
							<td><label>Email:</label></td>
							<td><input type="text" name="email"></td>
						</tr>
						<tr>
							<td><label>Password:</label></td>
							<td><input type="password" name="password"></td>
						</tr>
						<tr>
							<td><label>Confirmación de Password:</label></td>
							<td><input type="password" name="confirmacion"></td>
						</tr>
				</table>
								<input type="submit" value="Crear cuenta">
				</form>
				</fieldset>
			</div>
			<div class='footer'>
				<p>
					&copy; $projectName
				</p>
			</div>
		</div>
		<script src='http://code.jquery.com/jquery-1.7.2.min.js'></script>
		<script src='assets/js/$proyectName.js'></script>
	</body>
</html>
SOURCE;
	$archivo = fopen("../iniciar_sesion.php", 'w') or die("No se pudo crear el archivo destroy.php");
	fwrite($archivo, $setup_file);
	fclose($archivo);
// usuarios.sql
$setup_file = <<<SOURCE
USE $projectName;
CREATE TABLE IF NOT EXISTS usuarios (
id int(11) NOT NULL AUTO_INCREMENT,
nombre varchar(255) NOT NULL,
email varchar(255) NOT NULL,
password varchar(255) NOT NULL,
admin int(11) NOT NULL,
creado datetime,
actualizado datetime,
PRIMARY KEY (id)
) ENGINE=MyISAM DEFAULT CHARSET=UTF8;
INSERT INTO usuarios (nombre, email, `password`, admin, creado, actualizado) VALUES ('admin', 'admin@example.com', md5('admin'), 1, NOW(), NOW());
SOURCE;
	$archivo = fopen("../db/usuarios.sql", 'w') or die("No se pudo crear el archivo destroy.php");
	fwrite($archivo, $setup_file);
	fclose($archivo);
	$msg = "<div style='color: red;'>¡Hecho!</div>";
}
?>            
	<form action='autorizacion.php' method='post'>
		<?php echo $msg; ?>
		<p>
			Este archivo se encarga de generar un autorización básico que consta de 7 archivos:
		</p>
		<ul>
			<li>autenticacion.php: Verifica que exista un valor establecido en la sesión uid. Si no hay valor establecido redirige el usuario a index.php</li>
			<li>autorizacion.php: Verifica que exista un valor establecido en la sesión admin. Si no hay valor establecido redirige el usuario a index.php</li>
			<li>cerrar_sesion.php: Destruye las sesiones del usuario que mande llamar el archivo y redirige al usuario a index.php</li>
			<li>cuenta.php: Crea una cuenta en el sistema. Si la logra crear redirige al usuario a home.php - si no lo redirige a index.php</li>
			<li>home.php: Archivo que funciona como panel de control para los usuarios (en específico para el administrador).</li>
			<li>iniciar_sesion.php: Formularios para crear usuarios o iniciar sesión (se pueden copiar e incluir en otro archivo si se quiere modificar la funcionalidad).</li>
			<li>usuarios.sql: Archivo sql para crear la tabla de usuarios.</li>                                                                                 
		</ul>
		   <input type='submit' name='autorizacion' value='Crear Archivos' />
	</form>
		</div>
		<div class='footer'>
			&nbsp;	
		</div> 
	</div>
	</body>
</html>