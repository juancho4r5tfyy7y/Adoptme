<?php
/**
 * ADOPT ME — Front controller (único punto de entrada).
 * Lee ?r=ruta, valida el rol de la sesión demo y delega al controlador.
 */
session_start();

define('BASE_URL', rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/'));
define('APP_PATH', dirname(__DIR__) . '/app');

require APP_PATH . '/config/config.php';
require APP_PATH . '/core/helpers.php';

// Autocarga de clases del núcleo, controladores y modelos.
spl_autoload_register(static function (string $clase): void {
    foreach (['core', 'controllers', 'models'] as $directorio) {
        $archivo = APP_PATH . "/{$directorio}/{$clase}.php";

        if (is_file($archivo)) {
            require $archivo;
            return;
        }
    }
});

$rutas = require APP_PATH . '/config/rutas.php';
$ruta = $_GET['r'] ?? 'home';
$usuario = $_SESSION['user'] ?? null;

// Evita errores si se envía una ruta como arreglo (?r[]=home).
if (!is_string($ruta) || !isset($rutas[$ruta])) {
    http_response_code(404);
    (new PublicController())->noEncontrado();
    exit;
}

[$controlador, $metodo, $rolesPermitidos] = $rutas[$ruta];

// Control visual por rol. La autenticación real debe implementarse en el backend.
if ($rolesPermitidos !== null) {
    if (!is_array($usuario)) {
        header('Location: ' . url('login'));
        exit;
    }

    $rolUsuario = $usuario['rol'] ?? '';

    if (!in_array($rolUsuario, $rolesPermitidos, true)) {
        http_response_code(404);
        (new PublicController())->noEncontrado();
        exit;
    }
}

(new $controlador())->$metodo();
