<!DOCTYPE html>
<html>
	<head>
		<meta charset='utf-8' />
		<title>setup - contacto</title>
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
if (isset($_POST['submit']) && isset($_POST['email'])) {

$email_receiver = $_POST['email'];

$setup_file = <<<SOURCE
    <?php
		\$mensaje = false;
		if (isset(\$_POST['enviar'])) {

			if(trim(\$_POST['nombre']) == '') {
				\$hasError = true;
			} else {
				\$nombre = trim(\$_POST['nombre']);
			}

			if(trim(\$_POST['email']) == '')  {
				\$hasError = true;
			} else {
				\$email = trim(\$_POST['email']);
			}

			if(trim(\$_POST['mensaje']) == '') {
				\$hasError = true;
			} else {
				\$mensaje = trim(\$_POST['mensaje']);
			}

				if(!isset(\$hasError)) {
					\$emailTo = '$email_receiver';
					\$subject = 'Información de contacto';
					\$body = "Nombre: \$nombre Email: \$email Asunto: \$subject Mensaje: \$mensaje ";
					\$headers = 'From: $projectName <'.\$emailTo.'>' . "\r" . 'Reply-To: ' . \$email;

					mail(\$emailTo, \$subject, \$body, \$headers);				           
			}			
		}

	?>
	<!DOCTYPE html>
	<html>
		<head>
			<meta charset='utf-8' />
			<link rel='stylesheet' href='assets/css/$projectName.css' type='text/css' />\n
			<style>
				.mensaje {float:left; margin-left: 4em; margin-right: 1em;}
				form {margin-top: 3em;}
				#mensajeUsuario {border-radius: 10px; width: 23em; padding: .5em 2em; color: #000; margin: .5em 0;}
				#valido {border-radius: 10px; width: 23em; padding: 1em 2em; color: #000; margin: 2em 0; background: #DFD; border: 1px solid #6C6;}
				input, textarea {display: inline-block; border: 1px solid #ccc; border-radius: 5px; padding: 5px; width: 19em;}
				label {width: 6em; display: inline-block; padding-top: 1em;}
				td {vertical-align: top;}
				.required:after {content: " * "; color: red;}
			</style>
		</head>
		<body> 
			<div class='container'>
				<div class='header'>
					<h1>$projectName</h1>
				</div>
				<div class='content'>
					<div id='mensajeUsuario' style='display: none;'></div>
					<?php
					if (isset(\$mensaje) && \$mensaje == true) {
						echo "<div id='valido'>";
						echo "¡Gracias! en breve nos comunicaremos contigo.";
						echo "</div>";
					}
					?>					
					<form action='contacto.php' method='post' onsubmit='return validar();'>
						<table>
							<tr>
								<td><label class='required'>Nombre</label></td>
								<td><input type='text' id='nombre' name='nombre' /></td>
							</tr>
							<tr>
								<td><label class='required'>E-mail</label></td>
								<td><input type='text' id='email' name='email' /></td>
							</tr>
							<tr>
								<td><label class='required'>Mensaje</label></td>
								<td><textarea id='mensaje' name='mensaje' rows='13'></textarea></td>
						   </tr>
						   <tr>
								<td>&nbsp;</td>
								<td><input type='submit' name='enviar' class='enviar' value='Enviar'/></td>
						   </tr>
						</table>
					</form> 
					</div>
					<div class='footer'>
						<p>
							&copy; $projectName
						</p>
					</div>					
				</div>
			<script>
			function validar() {
				validarNombreCompleto =  /^[a-zA-Z -]{2,}\$/;
				validarCorreo = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}\$/;
				errores = 0;
				mensajeError = "";    			
				enviar = false;
				nombre = document.getElementById('nombre');
				email = document.getElementById('email');
				mensaje = document.getElementById('mensaje');

				if (!validarNombreCompleto.test(nombre.value)) {
					mensajeError += "Ingresa tu nombre.<br />"; 
					nombre.style.border = "1px solid #C66";
					errores += 1;
				} else {
					nombre.style.border = "1px solid #CCC";
				}

				if (!validarCorreo.test(email.value)) {
					mensajeError += "Ingresa un correo válido.<br />";
					email.style.border = "1px solid #C66";
					errores += 1;
				} else {
					email.style.border = "1px solid #CCC";
				}

				if (mensaje.value == "" ) {
					mensajeError += "Escribe un mensaje.<br />";
					mensaje.style.border = "1px solid #C66";
					errores += 1;				
				} else {
					mensaje.style.border = "1px solid #CCC";
				}

				if (errores > 0) {
					mensajeUsuario.innerHTML = "<p>" + mensajeError + "</p>";
				   	mensajeUsuario.style.backgroundColor =  "#FDD";
				   	mensajeUsuario.style.border =  "solid 1px #C66";	
					mensajeUsuario.style.display = "";   
				} else {
					formulario.reset();			   
					enviar = true;
				}

				return enviar;				
			};
			</script>
		</body>
	</html>
SOURCE;
	$archivo = fopen("../contacto.php", 'w') or die("No se pudo crear el archivo contact.php");
	fwrite($archivo, $setup_file);
	fclose($archivo);
?> 
	<h1>¡Hecho!</h1>
	<p>Se creó el archivo contacto.php</p>

<?php 
} else {
?>
	<h1>Contacto</h1>
	<form action='contacto.php' method='post'>
		<p>
			Se va a crear un archivo (contacto.php) en el cual se le va a pedir al usuario sus datos
			de contacto * (nombre, empresa, teléfono, email, mensaje). <br>
			Para enviarlos a un correo electrónico (al correo que quieres que te llegue el mensaje).<br >
			* El nombre, correo y mensaje son obligatorios. <br>
			El archivo que se va a generar cuenta con una validación en javascript (sin framework, para que se pueda utilizar con jQuery, Prototype u otro framework de js) que no permite enviar el formulario 
			si se encuentran vacíos esos campos o la dirección de correo no está bien formada.
		</p>
		<label>Dirección de correo electrónico donde quieres recibir los mensajes de contacto</label><br>
		<input type='text' name='email' value='' /><br>
		<input type='submit' name='submit' value='Crear formulario de contacto' />
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