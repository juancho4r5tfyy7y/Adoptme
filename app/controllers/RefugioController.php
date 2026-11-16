<?php
/** Panel del refugio: mascotas, solicitudes recibidas y perfil institucional. */
class RefugioController extends Controller
{
    private function id(): int
    {
        $usuario = $_SESSION['user'] ?? null;

        if (!is_array($usuario)) {
            return 0;
        }

        return (int) ($usuario['refugio_id'] ?? 0);
    }

    private function mias(): array
    {
        $idRefugio = $this->id();

        if ($idRefugio <= 0) {
            return [];
        }

        return array_values(array_filter(
            Mascota::todas(),
            static fn(array $mascota): bool => (int) $mascota['refugio_id'] === $idRefugio
        ));
    }

    private function recibidas(): array
    {
        $idRefugio = $this->id();

        if ($idRefugio <= 0) {
            return [];
        }

        return SolicitudAdopcion::porRefugio($idRefugio);
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

        if ($mascota && (int) $mascota['refugio_id'] !== $this->id()) {
            (new PublicController())->noEncontrado();
            return;
        }

        $this->render('refugio/form_mascota', [
            'titulo' => $mascota ? 'Editar mascota' : 'Nueva mascota',
            'm' => $mascota,
        ]);
    }

    public function guardarMascota(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . url('refugio/mascotas'));
            exit;
        }

        $usuario = $_SESSION['user'] ?? null;

        if (!is_array($usuario) || ($usuario['rol'] ?? '') !== 'refugio') {
            header('Location: ' . url('login'));
            exit;
        }

        $idRefugio = (int) ($usuario['refugio_id'] ?? 0);

        if ($idRefugio <= 0) {
            $refugio = Refugio::buscarPorUsuario((int) $usuario['id']);
            $idRefugio = $refugio ? (int) $refugio['id_refugio'] : 0;
        }

        $nombre = trim((string) ($_POST['nombre'] ?? ''));
        $especie = trim((string) ($_POST['especie'] ?? ''));
        $raza = trim((string) ($_POST['raza'] ?? ''));
        $edadMeses = (int) ($_POST['edad_meses'] ?? 0);
        $sexo = trim((string) ($_POST['sexo'] ?? ''));
        $tamano = trim((string) ($_POST['tamano'] ?? ''));
        $descripcion = trim((string) ($_POST['descripcion'] ?? ''));
        $especiesPermitidas = ['perro', 'gato', 'conejo'];
        $sexosPermitidos = ['macho', 'hembra'];
        $tamanosPermitidos = ['pequeño', 'mediano', 'grande'];
        $estadoPublicacion = in_array((string) ($_POST['estado_publicacion'] ?? 'borrador'), ['borrador', 'publicado'], true)
            ? (string) $_POST['estado_publicacion']
            : 'borrador';

        if ($idRefugio <= 0
            || $nombre === ''
            || !in_array(strtolower($especie), $especiesPermitidas, true)
            || $raza === ''
            || $edadMeses < 1
            || $edadMeses > 65535
            || !in_array(strtolower($sexo), $sexosPermitidos, true)
            || !in_array(strtolower($tamano), $tamanosPermitidos, true)
            || $descripcion === '') {
            $_SESSION['error'] = 'Faltan datos obligatorios para guardar la mascota.';
            header('Location: ' . url('refugio/mascota-form'));
            exit;
        }

        $data = [
            'id_refugio' => $idRefugio,
            'nombre' => $nombre,
            'especie' => strtolower($especie),
            'raza' => $raza,
            'edad_meses' => $edadMeses,
            'sexo' => strtolower($sexo),
            'tamanio' => strtolower($tamano),
            'vacunado' => !empty($_POST['vacunado']) ? 1 : 0,
            'esterilizado' => !empty($_POST['esterilizado']) ? 1 : 0,
            'estado_salud_detalle' => trim((string) ($_POST['salud'] ?? '')),
            'compatibilidad_ninos' => !empty($_POST['ninos']) ? 1 : 0,
            'compatibilidad_perros' => !empty($_POST['perros']) ? 1 : 0,
            'compatibilidad_gatos' => !empty($_POST['gatos']) ? 1 : 0,
            'descripcion' => $descripcion,
            'estado_adopcion' => 'disponible',
            'estado_publicacion' => $estadoPublicacion,
        ];

        $idMascota = (int) ($_POST['id_mascota'] ?? 0);
        $resultado = $idMascota > 0
            ? Mascota::actualizar($idMascota, $data, $idRefugio)
            : Mascota::guardar($data, $idRefugio);

        if ($resultado === null || $resultado === false) {
            $_SESSION['error'] = 'No fue posible guardar la mascota.';
            header('Location: ' . url('refugio/mascota-form'));
            exit;
        }

        $_SESSION['success'] = 'Mascota guardada correctamente.';
        header('Location: ' . url('refugio/mascotas'));
        exit;
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
        $consulta = Database::conectar()->prepare(
            'SELECT nombre_refugio AS nombre, direccion AS ciudad, telefono, correo,
                    tipo_refugio AS tipo, anio_fundacion AS anio,
                    nombre_contacto AS contacto, cargo_contacto AS cargo
             FROM refugios
             WHERE id_refugio = :id_refugio
             LIMIT 1'
        );
        $consulta->execute([':id_refugio' => $this->id()]);
        $refugio = $consulta->fetch() ?: [];

        $tiposRefugio = [
            'fundación' => 'Fundación',
            'albergue' => 'Albergue',
            'rescatista_independiente' => 'Rescatista independiente',
        ];
        if ($refugio) {
            $refugio['tipo'] = $tiposRefugio[$refugio['tipo']] ?? 'Fundación';
        }

        $this->render('refugio/perfil', [
            'titulo' => 'Perfil del refugio',
            'r' => $refugio,
        ]);
    }
}
