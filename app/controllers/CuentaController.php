<?php
/** Vistas comunes a todos los roles. */
class CuentaController extends Controller
{
    public function notificaciones(): void
    {
        $this->render('comun/notificaciones', [
            'titulo' => 'Notificaciones',
            'lista' => [],
        ]);
    }
}
