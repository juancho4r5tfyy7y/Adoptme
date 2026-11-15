<?php
/** Panel de administración: usuarios, refugios, solicitudes y reportes. */
class AdminController extends Controller
{
    public function dashboard(): void
    {
        $this->render('admin/dashboard', [
            'titulo' => 'Panel de administración',
            'mascotas' => Mascota::todas(),
            'solicitudes' => DatosDemo::solicitudes(),
            'nUsuarios' => count(DatosDemo::usuarios()),
            'nRefugios' => count(DatosDemo::refugios()),
        ]);
    }

    public function usuarios(): void
    {
        $this->render('admin/usuarios', [
            'titulo' => 'Usuarios',
            'usuarios' => DatosDemo::usuarios(),
        ]);
    }

    public function refugios(): void
    {
        $this->render('admin/refugios', [
            'titulo' => 'Refugios',
            'refugios' => DatosDemo::refugios(),
            'mascotas' => Mascota::todas(),
        ]);
    }

    public function solicitudes(): void
    {
        $this->render('admin/solicitudes', [
            'titulo' => 'Solicitudes',
            'solicitudes' => DatosDemo::solicitudes(),
        ]);
    }

    public function reportes(): void
    {
        $this->render('admin/reportes', [
            'titulo' => 'Reportes',
            'mascotas' => Mascota::todas(),
            'solicitudes' => DatosDemo::solicitudes(),
        ]);
    }
}
