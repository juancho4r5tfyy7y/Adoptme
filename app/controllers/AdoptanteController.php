<?php
/** Panel del adoptante: resumen, favoritos, solicitud, seguimiento y perfil. */
class AdoptanteController extends Controller
{
    private function misSolicitudes(): array
    {
        $usuario = $_SESSION['user'] ?? null;

        if (!is_array($usuario) || empty($usuario['id'])) {
            return [];
        }

        return SolicitudAdopcion::porUsuario((int) $usuario['id']);
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

    public function guardarSolicitud(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . url('catalogo'));
            exit;
        }

        $usuario = $_SESSION['user'] ?? null;

        if (!is_array($usuario) || ($usuario['rol'] ?? '') !== 'usuario') {
            header('Location: ' . url('login'));
            exit;
        }

        $idMascota = (int) ($_POST['id_mascota'] ?? 0);
        $mascota = Mascota::buscar($idMascota);

        if (!$mascota || ($mascota['pub'] ?? '') !== 'publicado' || ($mascota['estado'] ?? '') !== 'disponible') {
            $_SESSION['error'] = 'La mascota no está disponible para adopción.';
            header('Location: ' . url('catalogo'));
            exit;
        }

        $motivo = trim((string) ($_POST['motivo'] ?? ''));
        $tipoVivienda = trim((string) ($_POST['tipo_vivienda'] ?? ''));
        $tienePatio = !empty($_POST['tiene_patio']) ? 1 : 0;
        $compromisos = !empty($_POST['compromisos_aceptados']) ? 1 : 0;

        if ($motivo === '' || strlen($motivo) < 20 || $tipoVivienda === '' || $compromisos !== 1) {
            $_SESSION['error'] = 'La solicitud está incompleta. Revisa el motivo, vivienda y compromisos.';
            header('Location: ' . url('solicitud', ['id' => $idMascota]));
            exit;
        }

        if (SolicitudAdopcion::activa((int) $usuario['id'], $idMascota)) {
            $_SESSION['error'] = 'Ya tienes una solicitud activa para esta mascota.';
            header('Location: ' . url('seguimiento'));
            exit;
        }

        $idSolicitud = SolicitudAdopcion::crear([
            'id_usuario' => (int) $usuario['id'],
            'id_mascota' => $idMascota,
            'id_refugio' => (int) ($mascota['refugio_id'] ?? 0),
            'motivo' => $motivo,
            'tipo_vivienda' => $tipoVivienda,
            'tiene_patio' => $tienePatio,
            'compromisos_aceptados' => $compromisos,
        ]);

        if ($idSolicitud === null) {
            $_SESSION['error'] = 'No fue posible registrar la solicitud.';
            header('Location: ' . url('solicitud', ['id' => $idMascota]));
            exit;
        }

        $_SESSION['success'] = 'Solicitud enviada correctamente.';
        header('Location: ' . url('seguimiento'));
        exit;
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
        $usuario = $_SESSION['user'] ?? null;
        $perfil = is_array($usuario) && !empty($usuario['id'])
            ? Usuario::buscarPorId((int) $usuario['id'])
            : null;

        if (!is_array($perfil)) {
            $_SESSION['error'] = 'No fue posible cargar la información del perfil.';
            header('Location: ' . url('login'));
            exit;
        }

        $this->render('usuario/perfil', [
            'titulo' => 'Mi perfil',
            'perfil' => $perfil,
        ]);
    }
}
