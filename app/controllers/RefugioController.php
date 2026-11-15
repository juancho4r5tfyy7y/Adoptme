<?php
/** Panel del refugio: mascotas, solicitudes recibidas y perfil institucional. */
class RefugioController extends Controller
{
    private function id(): int
    {
        return (int) ($_SESSION['user']['refugio_id'] ?? 1);
    }

    private function mias(): array
    {
        return array_values(array_filter(
            Mascota::todas(),
            fn(array $mascota): bool => (int) $mascota['refugio_id'] === $this->id()
        ));
    }

    private function recibidas(): array
    {
        return array_values(array_filter(
            DatosDemo::solicitudes(),
            fn(array $solicitud): bool => (int) $solicitud['refugio_id'] === $this->id()
        ));
    }

    public function dashboard(): void
    {
        $this->render('refugio/dashboard', [
            'titulo' => 'Panel del refugio',
            'mascotas' => $this->mias(),
            'solicitudes' => $this->recibidas(),
        ]);
    }

    public function mascotas(): void
    {
        $this->render('refugio/mascotas', [
            'titulo' => 'Gestión de mascotas',
            'mascotas' => $this->mias(),
        ]);
    }

    public function formMascota(): void
    {
        $id = (int) ($_GET['id'] ?? 0);
        $mascota = $id > 0 ? Mascota::buscar($id) : null;

        $this->render('refugio/form_mascota', [
            'titulo' => $mascota ? 'Editar mascota' : 'Nueva mascota',
            'm' => $mascota,
        ]);
    }

    public function solicitudes(): void
    {
        $this->render('refugio/solicitudes', [
            'titulo' => 'Solicitudes recibidas',
            'solicitudes' => $this->recibidas(),
        ]);
    }

    public function perfil(): void
    {
        $refugio = DatosDemo::porId(DatosDemo::refugios(), $this->id());

        $this->render('refugio/perfil', [
            'titulo' => 'Perfil del refugio',
            'r' => $refugio,
        ]);
    }
}
