<!DOCTYPE html>
<html>
	<head>
		<meta charset='utf-8' />
		<title>setup - restablecer contraseña</title>
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
if (isset($_POST['submit'])) {

$setup_file = <<<SOURCE
<?php
require 'config/conexion.php';
\$mensaje = false;
\$hasError = false;

if (isset(\$_POST['enviar'])) {
	if(trim(\$_POST['email']) == '')  {
		\$hasError = true;
	} else {
		\$email = trim(\$_POST['email']);
	}
  
  \$reset_pwd_code =  "rc" . MD5(RAND()) . date("ymdhis");
  
  if (\$hasError === false && isset(\$email)){
    \$query = "UPDATE usuarios SET restablecer_password = '\$reset_pwd_code'  WHERE email = ?";
    if (\$stmt = \$conexion->prepare(\$query)) {
      \$stmt->bind_param("s", \$email);
      \$stmt->execute();
      \$updated_reset_pwd_code = \$conexion->affected_rows;
      \$stmt->close();
    }
  }

	if(\$hasError === false && \$updated_reset_pwd_code === 1) {
    \$mensaje = "Revisa tu correo electrónico para restablecer tu contraseña";
		\$emailTo = '$projectName@example.com';
		\$subject = 'Reestablecer contraseña - $projectName';
    \$body =  "Hola:\\n Alguien solicitó que se restableciera tu contraseña en $projectName.\\n
               Si es así visita: http://$projectName.example.org/recuperar.php?reset-password-code=\$reset_pwd_code\\n
               Si no es así, simplemente ignora este mensaje.\\n
               Saludos,\\n
               $projectName";
		\$headers = 'From: $projectName <'.\$emailTo.'>' . "\r" . 'Reply-To: ' . \$email;
		mail(\$email, \$subject, \$body, \$headers);				           
  } else {
		  \$mensaje = "Revisa la dirección de correo.";
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
				if (isset(\$mensaje) && \$mensaje !== false) {
					echo "<div id='valido'>";
					echo "\$mensaje";
					echo "</div>";
				}
				?>					
				<form action='restablecer.php' method='post' onsubmit='return validar();'>
					<table>
						<tr>
							<td><label class='required'>E-mail</label></td>
							<td><input type='text' id='email' name='email' /></td>
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
			validarCorreo = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}\$/;
			errores = 0;
			mensajeError = "";    			
			enviar = false;
			email = document.getElementById('email');

			if (!validarCorreo.test(email.value)) {
				mensajeError += "Ingresa un correo válido.<br />";
				email.style.border = "1px solid #C66";
				errores += 1;
			} else {
				email.style.border = "1px solid #CCC";
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
	$archivo = fopen("../restablecer.php", 'w') or die("No se pudo crear el archivo restablecer.php");
	fwrite($archivo, $setup_file);
	fclose($archivo);
  
$setup_file = <<<SOURCE
<?php
require 'config/conexion.php';
\$mensaje = false;
\$mostrar_formulario = false;
if (isset(\$_GET['reset-password-code'])) {
  \$rpc = \$_GET['reset-password-code'];
  \$query = "SELECT id FROM usuarios WHERE restablecer_password = ?";
  if (\$stmt = \$conexion->prepare(\$query)) {
    \$stmt->bind_param("s", \$rpc);
    \$stmt->execute();
    \$stmt->store_result();
    \$found = \$stmt->num_rows;
    \$stmt->close();
  } else {
    \$found = -1;
  }
  
}

if(isset(\$found) && \$found == 1) {
  \$mostrar_formulario = true;
  \$mensaje = "<p>Ingresa una nueva contraseña y la confirmación de la contraseña.</p>";
} else if(isset(\$found) && \$found != 1) {
  \$mensaje = "<p>No se encontró ningún usuario con ese código para reestablecer la contraseña.<br>Tal vez copiaste mal el código o no solicitaste que se restableciera la contraseña.</p>";
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
      .error {border-radius: 10px; width: 23em; padding: 1em 2em; color: #000; margin: 2em 0; background: #FDD; border: 1px solid #C66;}      
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
			<div id='mensajeUsuario' class='error' style='display: none;'></div>
			<?php
			if (isset(\$mensaje) && \$mensaje !== false) {
				echo "<div id='valido'>";
				echo "\$mensaje";
				echo "</div>";
			}
			?>					        
				<form action='actualizar_password.php' method='post' onsubmit='return validar();'>
					<table>
						<tr>
							<td><input type='hidden' name='reset-code' value='<?php echo \$rpc; ?>' /></td>
						</tr>
						<tr>
							<td><label class='required'>Contraseña Nueva</label></td>
							<td><input type='password' id='pwd' name='password' /></td>
						</tr>
						<tr>
							<td><label class='required'>Confirmación contraseña</label></td>
							<td><input type='password' id='confirmacion' name='confirmacion' /></td>
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
        var pwd = document.getElementById('pwd').value;
        var conf = document.getElementById('confirmacion').value;
        var mensaje = document.getElementById('mensajeUsuario');
        var enviar = false;
      
        if (pwd.length > 3 && pwd === conf) {
          enviar = true;
        } else {
          enviar = false;
          mensaje.innerHTML = "<p>La contraseña debe tener al menos 3 caracteres<br> y debe ser igual a la confirmación.</p>";
          mensaje.style.display = '';        
          document.getElementById('valido').style.display = 'none';
        }
      
  			return enviar;				
  		};
  		</script>
	</body>
</html>
SOURCE;
	$archivo = fopen("../recuperar.php", 'w') or die("No se pudo crear el archivo recuperar.php");
	fwrite($archivo, $setup_file);
	fclose($archivo);  
  
$setup_file = <<<SOURCE
<?php
require 'lib/salt.php';
require 'config/conexion.php';
\$prc = \$_POST['reset-code'];
\$password = \$_POST['password'];
\$confirmacion = \$_POST['confirmacion'];


if (\$password == \$confirmacion) {
  \$clean_reset_password_code = "";
  \$date = date('Y-m-d H:i:s');
  \$encrypted_password = hash('sha256', \$password . SALT);  
  \$query = "UPDATE usuarios SET password = ?, restablecer_password = ?, actualizado = ? WHERE restablecer_password = ?";

  if (\$stmt = \$conexion->prepare(\$query)) {
    \$stmt->bind_param("ssss", \$encrypted_password, \$clean_reset_password_code, \$date, \$prc);
    \$stmt->execute();
    \$updated_password = \$conexion->affected_rows;
    \$stmt->close();

  }  
}

if(isset(\$updated_password) && \$updated_password == 1) {
  header('location: iniciar_sesion.php');
} else {
  header('location: restablecer.php');
}			

?>  
SOURCE;
	$archivo = fopen("../actualizar_password.php", 'w') or die("No se pudo crear el archivo actualizar_password.php");
	fwrite($archivo, $setup_file);
	fclose($archivo);  

// usuarios.sql
$setup_file = <<<SOURCE
USE $projectName;
ALTER TABLE usuarios 
ADD COLUMN restablecer_password varchar(255)
SOURCE;
$archivo = fopen("../db/actualizar_usuarios.sql", 'w') or die("No se pudo crear el actualizar_usuarios.sql");
fwrite($archivo, $setup_file);
fclose($archivo);   

?> 
	<h1>¡Hecho!</h1>
	<p>Se crearon los archivos: restablecer.php, recuperar.php, actualizar_password.php</p>

<?php 
} else {
?>
	<h1>Restablecer contraseña</h1>
	<form action='restablecer.php' method='post'>
		<p>
		</p>
		<input type='submit' name='submit' value='Crear archivos para restablecer contraseña'>
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