# ADOPT ME

Aplicación de demostración desarrollada con PHP, estructura MVC y Bootstrap 5. El proyecto contiene vistas para visitantes, adoptantes, refugios y administradores.

## Cómo ejecutar

1. Instala PHP 7.4 o superior.
2. Abre una terminal en la carpeta del proyecto.
3. Ejecuta `php -S localhost:8000 -t public`.
4. Abre `http://localhost:8000` en el navegador.

En XAMPP o Laragon también puedes colocar la carpeta del proyecto dentro de `htdocs` o `www` y abrir la ruta `.../adoptme5/public`.

## Organización

- `app/config/`: configuración y tabla de rutas.
- `app/core/`: controlador base y funciones auxiliares.
- `app/controllers/`: controladores de cada módulo.
- `app/models/`: conexión PDO, modelo de mascotas y datos de demostración.
- `app/views/`: pantallas, layouts y componentes reutilizables.
- `public/assets/css/`: estilos del tema, componentes y páginas.
- `public/assets/js/`: interacciones de la interfaz.
- `public/assets/vendor/`: Bootstrap incluido en el proyecto.
- `database/schema.sql`: estructura de MySQL.

## Datos y conexión MySQL

Por defecto, `USAR_BD` está en `false` y la aplicación trabaja con registros de demostración.

El esquema SQL crea la base de datos `adopt_me`. Para activar la lectura de mascotas desde MySQL, importa `database/schema.sql`, configura las credenciales en `app/config/config.php`, establece `USAR_BD = true` y asegúrate de tener habilitado `PDO MySQL`.

En esta versión, el modelo `Mascota` lee y adapta los registros de MySQL. Los usuarios, refugios, solicitudes, autenticación y formularios siguen siendo demostrativos: sus acciones no se guardan en la base de datos.

## Hojas de estilos

- `adoptme.css`: colores, tipografía y variables de Bootstrap.
- `components.css`: navegación, tarjetas, formularios y componentes reutilizables.
- `pages.css`: ajustes específicos de páginas y estilos que no tienen una utilidad equivalente en Bootstrap.
