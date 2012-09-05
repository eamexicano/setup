setup
======

Requerimientos
--------------

Instalación LAMP (XAMPP, MAMP, WAMP o instalación independiente de Apache, MySQL, PHP).

Instalación
--------------

Descargar el archivo .zip  
Descomprimir dentro de htdocs (o carpeta donde se encuentren los proyectos web).  
Renombrar la carpeta con un nombre corto (como setup).  
Verificar que la carpeta tenga permisos para escritura (0777) para poder generar el proyecto.  
Iniciar Apache, MySQL y visitar: http://localhost/setup/setup.php  

Notas
-----

La dirección (URL) puede variar si la instalación de XAMPP utiliza otro puerto (8080, 8888).   
Es recomendable aumentar la seguridad cuando el proyecto se encuentre en un servidor que sea accesible a través de internet (cambiar los permisos de escritura a (0755)).  
Si mysql es accesible desde la línea de comandos, algunos scripts va a tratar de generar la BD / tablas con el usuario root y sin contraseña (valores predeterminados en xampp).  

Licencia
--------

Setup utiliza una <a href='http://www.opensource.org/licenses/MIT' target='_blank'>licencia MIT</a>.

