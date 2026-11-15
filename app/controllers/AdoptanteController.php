<?php
/** Panel del adoptante: resumen, favoritos, solicitud, seguimiento y perfil. */
class AdoptanteController extends Controller
{
    private function misSolicitudes(): array
    {
        return array_values(array_filter(
            DatosDemo::solicitudes(),
            static fn(array $solicitud): bool => $solicitud['solicitante'] === 'María Gómez'
        ));
    }

    public function dashboard(): void
    {
        $this->render('usuario/dashboard', [
            'titulo' => 'Mi panel',
            'solicitudes' => $this->misSolicitudes(),
            'recomendadas' => array_slice(Mascota::publicadas(), 0, 3),
        ]);
    }

    public function favoritos(): void
    {
        $this->render('usuario/favoritos', [
            'titulo' => 'Favoritos',
            'mascotas' => Mascota::publicadas(),
        ]);
    }

    public function solicitud(): void
    {
        $mascota = Mascota::buscar((int) ($_GET['id'] ?? 0));

        if (!$mascota || $mascota['pub'] !== 'publicado' || $mascota['estado'] !== 'disponible') {
            (new PublicController())->noEncontrado();
            return;
        }

        $this->render('usuario/solicitud', [
            'titulo' => 'Solicitud de adopción',
            'm' => $mascota,
        ]);
    }

    public function seguimiento(): void
    {
        $this->render('usuario/seguimiento', [
            'titulo' => 'Mis solicitudes',
            'solicitudes' => $this->misSolicitudes(),
        ]);
    }

    public function perfil(): void
    {
        $this->render('usuario/perfil', ['titulo' => 'Mi perfil']);
    }
}
