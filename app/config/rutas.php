<?php
/**
 * Tabla de rutas: ruta => [controlador, método, roles permitidos].
 * Un valor null en roles indica que la ruta es pública.
 */
$rolUsuario = ['usuario'];
$rolRefugio = ['refugio'];
$rolAdmin = ['admin'];
$rolesRegistrados = ['usuario', 'refugio', 'admin'];

return [
    'home' => ['PublicController', 'inicio', null],
    'nosotros' => ['PublicController', 'inicio', null],
    'catalogo' => ['PublicController', 'catalogo', null],
    'mascota' => ['PublicController', 'detalle', null],
    'login' => ['AuthController', 'login', null],
    'registro' => ['AuthController', 'registro', null],
    'recuperar' => ['AuthController', 'recuperar', null],
    'demo' => ['AuthController', 'demo', null],
    'logout' => ['AuthController', 'logout', null],
    'notificaciones' => ['CuentaController', 'notificaciones', $rolesRegistrados],

    // Adoptante.
    'dashboard' => ['AdoptanteController', 'dashboard', $rolUsuario],
    'favoritos' => ['AdoptanteController', 'favoritos', $rolUsuario],
    'solicitud' => ['AdoptanteController', 'solicitud', $rolUsuario],
    'seguimiento' => ['AdoptanteController', 'seguimiento', $rolUsuario],
    'perfil' => ['AdoptanteController', 'perfil', $rolUsuario],

    // Refugio.
    'refugio/dashboard' => ['RefugioController', 'dashboard', $rolRefugio],
    'refugio/mascotas' => ['RefugioController', 'mascotas', $rolRefugio],
    'refugio/mascota-form' => ['RefugioController', 'formMascota', $rolRefugio],
    'refugio/solicitudes' => ['RefugioController', 'solicitudes', $rolRefugio],
    'refugio/perfil' => ['RefugioController', 'perfil', $rolRefugio],

    // Administrador.
    'admin/dashboard' => ['AdminController', 'dashboard', $rolAdmin],
    'admin/usuarios' => ['AdminController', 'usuarios', $rolAdmin],
    'admin/refugios' => ['AdminController', 'refugios', $rolAdmin],
    'admin/solicitudes' => ['AdminController', 'solicitudes', $rolAdmin],
    'admin/reportes' => ['AdminController', 'reportes', $rolAdmin],
];
