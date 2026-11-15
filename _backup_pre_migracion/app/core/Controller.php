<?php
/** Controlador base: renderiza una vista dentro de un layout. */
abstract class Controller {
    /**
     * @param string $vista  ruta relativa en app/views (sin .php)
     * @param array  $datos  variables disponibles en la vista
     * @param string $layout 'main' | 'panel' | 'auth' | 'auto' (panel si hay sesión, main si no)
     */
    protected function render($vista, $datos = [], $layout = 'panel') {
        $user = $_SESSION['user'] ?? null;
        if ($layout === 'auto') $layout = $user ? 'panel' : 'main';
        extract($datos);
        $titulo = $titulo ?? APP_NOMBRE;
        ob_start();
        require APP_PATH . "/views/$vista.php";
        $contenido = ob_get_clean();
        require APP_PATH . "/views/layouts/$layout.php";
    }
}
