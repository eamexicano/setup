setup
======

Requerimientos
--------------

Se necesita una instalación de Apache, PHP, MySQL (XAMPP, LAMP, WAMP o intalaciones independientes).

Configuración
--------------

setup tiene que estar dentro de htdocs y tiene que ser accesible a través de una dirección (URL) similar a: http://localhost/setupPHP/setup.php  
La carpeta que contiene este archivo (setup) debe de contar con permisos para escritura (0777) para poder generar la carpeta con el proyecto.  
La dirección (URL) puede variar si la instalación de XAMPP utiliza otro puerto (8080, 8888).   
Solo es recomendable para generar los archivos en un ambiente de desarrollo local (localhost) pero NO en un servidor que sea accesible a través de internet.

Si mysql es accesible desde la terminal / consola el script va a tratar de generar la BD con el usuario root y sin contraseña (valores predeterminados en xampp).  
También se va a almacenar el script de creación de la BD dentro de la carpeta db por si no se creó la BD o si se quiere crear en otro lugar.  

Este está liberado bajo una <a href='www.opensource.org/licenses/MIT' target='_blank'>licencia MIT</a>.