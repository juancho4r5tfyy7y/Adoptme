<?php
/** Controlador base: renderiza una vista dentro de un layout. */
abstract class Controller
{
    /**
     * @param string $vista Ruta relativa dentro de app/views, sin extensión.
     * @param array $datos Variables disponibles en la vista.
     * @param string $layout main, panel, auth o auto.
     */
    protected function render(
        string $vista,
        array $datos = [],
        string $layout = 'panel'
    ): void {
        $user = $_SESSION['user'] ?? null;

        if (!is_array($user)) {
            $user = null;
        }

        if ($layout === 'auto') {
            $layout = $user ? 'panel' : 'main';
        }

        // EXTR_SKIP evita que los datos de una vista sobrescriban variables internas.
        extract($datos, EXTR_SKIP);
        $titulo = $titulo ?? APP_NOMBRE;

        ob_start();
        require APP_PATH . "/views/{$vista}.php";
        $contenido = (string) ob_get_clean();

        require APP_PATH . "/views/layouts/{$layout}.php";
    }
}
