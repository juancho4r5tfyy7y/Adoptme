<?php
/** Panel del refugio: mascotas, solicitudes recibidas y perfil institucional. */
class RefugioController extends Controller
{
    private const MAX_TAMANO_IMAGEN = 5242880;

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
        $idMascota = (int) ($_POST['id_mascota'] ?? 0);

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

        try {
            $imagenes = $this->guardarImagenesSubidas($idMascota > 0 ? 0 : 2);
        } catch (InvalidArgumentException $error) {
            $_SESSION['error'] = $error->getMessage();
            header('Location: ' . url('refugio/mascota-form', $idMascota > 0 ? ['id' => $idMascota] : []));
            exit;
        } catch (RuntimeException $error) {
            error_log('ADOPT ME - No fue posible almacenar las fotos: ' . $error->getMessage());
            $_SESSION['error'] = 'No fue posible guardar las fotos. Inténtalo nuevamente.';
            header('Location: ' . url('refugio/mascota-form', $idMascota > 0 ? ['id' => $idMascota] : []));
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

        try {
            $resultado = $idMascota > 0
                ? Mascota::actualizar($idMascota, $data, $idRefugio, $imagenes)
                : Mascota::guardar($data, $idRefugio, $imagenes);
        } catch (Throwable $error) {
            $this->eliminarImagenesSubidas($imagenes);
            error_log('ADOPT ME - No fue posible guardar la mascota: ' . $error->getMessage());
            $_SESSION['error'] = 'No fue posible guardar la mascota y sus fotos.';
            header('Location: ' . url('refugio/mascota-form', $idMascota > 0 ? ['id' => $idMascota] : []));
            exit;
        }

        if ($resultado === null || $resultado === false) {
            $this->eliminarImagenesSubidas($imagenes);
            $_SESSION['error'] = 'No fue posible guardar la mascota.';
            header('Location: ' . url('refugio/mascota-form', $idMascota > 0 ? ['id' => $idMascota] : []));
            exit;
        }

        $_SESSION['success'] = 'Mascota guardada correctamente.';
        header('Location: ' . url('refugio/mascotas'));
        exit;
    }

    private function guardarImagenesSubidas(int $minimo): array
    {
        $carga = $_FILES['imagen'] ?? null;

        if (!is_array($carga)) {
            if ($minimo > 0) {
                throw new InvalidArgumentException('Debes subir al menos 2 fotos de la mascota.');
            }

            return [];
        }

        $errores = $carga['error'] ?? null;
        $temporales = $carga['tmp_name'] ?? null;
        $tamanos = $carga['size'] ?? null;

        if (!is_array($errores) || !is_array($temporales) || !is_array($tamanos)) {
            throw new InvalidArgumentException('Selecciona fotos válidas para la mascota.');
        }

        $extensionesPermitidas = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp',
        ];
        $imagenes = [];

        foreach ($errores as $indice => $errorCarga) {
            if ($errorCarga === UPLOAD_ERR_NO_FILE) {
                continue;
            }

            if ($errorCarga !== UPLOAD_ERR_OK) {
                throw new InvalidArgumentException('Una de las fotos no se pudo cargar. Inténtalo nuevamente.');
            }

            $temporal = $temporales[$indice] ?? null;
            $tamano = (int) ($tamanos[$indice] ?? 0);

            if (!is_string($temporal) || !is_uploaded_file($temporal) || $tamano <= 0) {
                throw new InvalidArgumentException('Selecciona fotos válidas para la mascota.');
            }

            if ($tamano > self::MAX_TAMANO_IMAGEN) {
                throw new InvalidArgumentException('Cada foto debe pesar máximo 5 MB.');
            }

            $detallesImagen = getimagesize($temporal);
            $mime = is_array($detallesImagen) ? ($detallesImagen['mime'] ?? '') : '';

            if (!isset($extensionesPermitidas[$mime])) {
                throw new InvalidArgumentException('Las fotos deben estar en formato JPG, PNG o WEBP.');
            }

            try {
                $nombreArchivo = bin2hex(random_bytes(16)) . '.' . $extensionesPermitidas[$mime];
            } catch (Exception $error) {
                throw new RuntimeException('No se pudo generar un nombre seguro para la foto.', 0, $error);
            }

            $imagenes[] = [
                'temporal' => $temporal,
                'nombre' => $nombreArchivo,
            ];
        }

        if (count($imagenes) < $minimo) {
            throw new InvalidArgumentException('Debes subir al menos 2 fotos de la mascota.');
        }

        if ($imagenes === []) {
            return [];
        }

        $directorio = dirname(APP_PATH) . DIRECTORY_SEPARATOR . 'public'
            . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'img'
            . DIRECTORY_SEPARATOR . 'mascotas';

        if (!is_dir($directorio) || !is_writable($directorio)) {
            throw new RuntimeException('El directorio de fotos no está disponible.');
        }

        $guardadas = [];

        try {
            foreach ($imagenes as $imagen) {
                $destino = $directorio . DIRECTORY_SEPARATOR . $imagen['nombre'];

                if (!move_uploaded_file($imagen['temporal'], $destino)) {
                    throw new RuntimeException('No se pudo mover una foto al directorio de imágenes.');
                }

                $guardadas[] = $imagen['nombre'];
            }
        } catch (Throwable $error) {
            $this->eliminarImagenesSubidas($guardadas);
            throw $error;
        }

        return $guardadas;
    }

    private function eliminarImagenesSubidas(array $imagenes): void
    {
        $directorio = dirname(APP_PATH) . DIRECTORY_SEPARATOR . 'public'
            . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'img'
            . DIRECTORY_SEPARATOR . 'mascotas';

        foreach ($imagenes as $imagen) {
            if (!is_string($imagen) || basename($imagen) !== $imagen) {
                continue;
            }

            $archivo = $directorio . DIRECTORY_SEPARATOR . $imagen;

            if (is_file($archivo) && !unlink($archivo)) {
                error_log('ADOPT ME - No fue posible eliminar la foto temporal: ' . $archivo);
            }
        }
    }

    public function solicitudes(): void
    {
        $usuario = $_SESSION['user'] ?? null;
        $puedeResolver = is_array($usuario)
            && Refugio::esAdministrador($this->id(), (int) ($usuario['id'] ?? 0));

        $this->render('refugio/solicitudes', [
            'titulo' => 'Solicitudes recibidas',
            'solicitudes' => $this->recibidas(),
            'puedeResolver' => $puedeResolver,
        ]);
    }

    public function procesarSolicitud(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . url('refugio/solicitudes'));
            exit;
        }

        $usuario = $_SESSION['user'] ?? null;
        $idUsuario = is_array($usuario) ? (int) ($usuario['id'] ?? 0) : 0;
        $idRefugio = $this->id();

        if ($idUsuario <= 0 || !is_array($usuario) || ($usuario['rol'] ?? '') !== 'refugio') {
            header('Location: ' . url('login'));
            exit;
        }

        if (!Refugio::esAdministrador($idRefugio, $idUsuario)) {
            $_SESSION['error'] = 'Solo el administrador de este refugio puede resolver solicitudes.';
            header('Location: ' . url('refugio/solicitudes'));
            exit;
        }

        $idSolicitud = (int) ($_POST['id_solicitud'] ?? 0);
        $decision = (string) ($_POST['decision'] ?? '');
        $observaciones = trim((string) ($_POST['observaciones'] ?? ''));

        if (!in_array($decision, ['aprobar', 'rechazar'], true)
            || ($decision === 'rechazar' && ($observaciones === '' || strlen($observaciones) > 2000))) {
            $_SESSION['error'] = 'Selecciona una decisión válida y, al rechazar, escribe un motivo de hasta 2000 caracteres.';
            header('Location: ' . url('refugio/solicitudes'));
            exit;
        }

        try {
            $procesada = SolicitudAdopcion::resolver(
                $idSolicitud,
                $idRefugio,
                $decision,
                $observaciones
            );
        } catch (Throwable $error) {
            error_log('ADOPT ME - No fue posible resolver la solicitud de adopción: ' . $error->getMessage());
            $_SESSION['error'] = 'No fue posible guardar la decisión. Inténtalo nuevamente.';
            header('Location: ' . url('refugio/solicitudes'));
            exit;
        }

        if (!$procesada) {
            $_SESSION['error'] = 'La solicitud ya fue atendida o la mascota dejó de estar disponible.';
            header('Location: ' . url('refugio/solicitudes'));
            exit;
        }

        $_SESSION['success'] = $decision === 'aprobar'
            ? 'Solicitud aprobada y adopción registrada. Las demás solicitudes pendientes de esta mascota fueron rechazadas.'
            : 'Solicitud rechazada y motivo guardado.';
        header('Location: ' . url('refugio/solicitudes'));
        exit;
    }

    public function perfil(): void
    {
        $consulta = Database::conectar()->prepare(
            'SELECT nombre_refugio AS nombre, direccion AS ciudad, telefono,
                    correo_contacto AS correo,
                    tipo_refugio AS tipo, anio_fundacion AS anio,
                    nombre_contacto AS contacto, cargo_contacto AS cargo
             FROM refugios
             WHERE id_refugio = :id_refugio
             LIMIT 1'
        );
        $consulta->execute([':id_refugio' => $this->id()]);
        $refugio = $consulta->fetch() ?: [];
        $administrador = Refugio::administradorPorRefugio($this->id());

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
            'administrador' => $administrador,
        ]);
    }

    public function guardarAdministrador(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . url('refugio/perfil'));
            exit;
        }

        $nombre = trim((string) ($_POST['nombre_contacto'] ?? ''));
        $correo = strtolower(trim((string) ($_POST['correo_contacto'] ?? '')));
        $documento = trim((string) ($_POST['documento_admin'] ?? ''));
        $password = (string) ($_POST['password_admin'] ?? '');
        $confirmarPassword = (string) ($_POST['confirm_password_admin'] ?? '');

        if ($nombre === ''
            || !preg_match('/^\d{6,12}$/', $documento)
            || !filter_var($correo, FILTER_VALIDATE_EMAIL)
            || strlen($password) < 8
            || $password !== $confirmarPassword) {
            $_SESSION['error'] = 'Completa los datos, ingresa un documento de 6 a 12 dígitos y confirma una contraseña de al menos 8 caracteres.';
            header('Location: ' . url('refugio/perfil'));
            exit;
        }

        $idRefugio = $this->id();
        $pdo = Database::conectar();

        try {
            $pdo->beginTransaction();

            if (Refugio::administradorPorRefugio($idRefugio) !== null) {
                $pdo->rollBack();
                $_SESSION['error'] = 'Este refugio ya tiene un administrador con cuenta de acceso.';
                header('Location: ' . url('refugio/perfil'));
                exit;
            }

            $idAdministrador = Usuario::crear([
                'nombres' => $nombre,
                'apellidos' => 'Administrador',
                'documento' => $documento,
                'correo' => $correo,
                'password' => $password,
                'terminos' => 0,
            ]);

            if ($idAdministrador === null) {
                $pdo->rollBack();
                $_SESSION['error'] = 'El correo o documento del administrador ya están registrados.';
                header('Location: ' . url('refugio/perfil'));
                exit;
            }

            if (!Refugio::agregarAdministrador($idRefugio, $idAdministrador, $correo)
                || !Refugio::actualizarAdministrador($idRefugio, $nombre, $correo)) {
                throw new RuntimeException('No fue posible guardar el administrador del refugio.');
            }

            $pdo->commit();
        } catch (Throwable $error) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }

            error_log('ADOPT ME - No fue posible crear el administrador del refugio: ' . $error->getMessage());
            $_SESSION['error'] = 'No fue posible crear la cuenta del administrador.';
            header('Location: ' . url('refugio/perfil'));
            exit;
        }

        $_SESSION['success'] = 'Cuenta del administrador creada correctamente. Ya puede iniciar sesión con su correo y contraseña inicial.';
        header('Location: ' . url('refugio/perfil'));
        exit;
    }
}
