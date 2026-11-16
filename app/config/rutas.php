<?php
/**
 * Tabla de rutas: ruta => [controlador, método, roles permitidos].
 * Un valor null en roles indica que la ruta es pública.
 */
$rolUsuarios = ['usuario'];
$rolRefugio = ['refugio'];
$rolAdmin = ['admin'];
$rolesRegistrados = ['usuario', 'refugio', 'admin'];

return [
    'home' => ['PublicController', 'inicio', null],
    'nosotros' => ['PublicController', 'inicio', null],
    'catalogo' => ['PublicController', 'catalogo', null],
    'mascota' => ['PublicController', 'detalle', null],
    'login' => ['AuthController', 'login', null],
    'login-post' => ['AuthController', 'procesarLogin', null],
    'registro' => ['AuthController', 'registro', null],
    'registro-post' => ['AuthController', 'procesarRegistro', null],
    'recuperar' => ['AuthController', 'recuperar', null],
    'logout' => ['AuthController', 'logout', null],
    'notificaciones' => ['CuentaController', 'notificaciones', $rolesRegistrados],

    // Adoptante.
    'dashboard' => ['AdoptanteController', 'dashboard', $rolUsuarios],
    'favoritos' => ['AdoptanteController', 'favoritos', $rolUsuarios],
    'solicitud' => ['AdoptanteController', 'solicitud', $rolUsuarios],
    'solicitud-post' => ['AdoptanteController', 'guardarSolicitud', $rolUsuarios],
    'seguimiento' => ['AdoptanteController', 'seguimiento', $rolUsuarios],
    'perfil' => ['AdoptanteController', 'perfil', $rolUsuarios],

    // Refugio.
    'refugio/dashboard' => ['RefugioController', 'dashboard', $rolRefugio],
    'refugio/mascotas' => ['RefugioController', 'mascotas', $rolRefugio],
    'refugio/mascota-form' => ['RefugioController', 'formMascota', $rolRefugio],
    'refugio/mascota-post' => ['RefugioController', 'guardarMascota', $rolRefugio],
    'refugio/solicitudes' => ['RefugioController', 'solicitudes', $rolRefugio],
    'refugio/solicitud-post' => ['RefugioController', 'procesarSolicitud', $rolRefugio],
    'refugio/perfil' => ['RefugioController', 'perfil', $rolRefugio],
    'refugio/perfil-admin-post' => ['RefugioController', 'guardarAdministrador', $rolRefugio],

    // Administrador.
    'admin/dashboard' => ['AdminController', 'dashboard', $rolAdmin],
    'admin/usuarios' => ['AdminController', 'usuarios', $rolAdmin],
    'admin/refugios' => ['AdminController', 'refugios', $rolAdmin],
    'admin/solicitudes' => ['AdminController', 'solicitudes', $rolAdmin],
    'admin/reportes' => ['AdminController', 'reportes', $rolAdmin],
];
