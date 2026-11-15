<?php
/** Vistas comunes a todos los roles. */
class CuentaController extends Controller
{
    public function notificaciones(): void
    {
        $rol = $_SESSION['user']['rol'] ?? 'usuario';

        $this->render('comun/notificaciones', [
            'titulo' => 'Notificaciones',
            'lista' => DatosDemo::notificaciones($rol),
        ]);
    }
}
