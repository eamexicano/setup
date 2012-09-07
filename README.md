setup
======

¿Qué es?
--------

Generador de archivos (php / sql) desde una interfaz web.  
Permite trabajar con las operaciones básicas (crear, leer, actualizar, eliminar) de datos en las tablas.  

Recomendaciones de uso
----------------------

Las relaciones entre tablas están basadas en las asociaciones de <a href='http://api.rubyonrails.org/classes/ActiveRecord/Associations/ClassMethods.html' target='_blank'>ActiveRecord</a> de <a href='http://www.rubyonrails.org'>Ruby On Rails</a>.  
  
Nombre de la tabla en español, plural (usuarios, categorias, productos, documentos, etiquetas).  
Para nombrar tablas utilizar solo letras minúsculas [a-z], sin acentos o ñ, espacios o guiones.  
  
Cuando se quiera una clave foránea (FK) para relacionar dos tablas utilizar el nombre de la tabla "independiente" en singular seguido de _id.  
Ej: Relacionar productos con categorias.  
Al crear la tabla "productos", agregar una columna llamada categoria\_id.  


Requerimientos
--------------

Instalación LAMP (XAMPP, MAMP, WAMP o instalación independiente de Apache, MySQL, PHP).

Instalación
--------------

Descargar el archivo .zip  
Descomprimir dentro de htdocs o carpeta donde se encuentren los proyectos web (como /var/www/http).  
Renombrar la carpeta con un nombre corto (setup).  
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

