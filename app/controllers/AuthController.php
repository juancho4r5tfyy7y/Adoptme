<?php
/** Controlador de autenticación real con validación y sesiones persistentes. */
class AuthController extends Controller
{
    public function login(): void
    {
        $this->render('auth/login', ['titulo' => 'Iniciar sesión'], 'auth');
    }

    public function procesarLogin(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . url('login'));
            exit;
        }

        $correo = strtolower(trim((string) ($_POST['email'] ?? '')));
        $password = (string) ($_POST['password'] ?? '');

        if ($correo === '' || $password === '') {
            $_SESSION['error'] = 'Debes ingresar tu correo y contraseña.';
            header('Location: ' . url('login'));
            exit;
        }

        $usuario = Usuario::autenticar($correo, $password);

        if ($usuario === null) {
            $_SESSION['error'] = 'Credenciales inválidas. Verifica tus datos.';
            header('Location: ' . url('login'));
            exit;
        }

        session_regenerate_id(true);
        $idUsuario = (int) $usuario['id_usuario'];
        $esAdministrador = Usuario::tieneRolAdministrador($idUsuario);
        $refugio = Refugio::buscarPorUsuario($idUsuario);
        $rol = $esAdministrador ? 'admin' : (is_array($refugio) ? 'refugio' : 'usuario');

        $_SESSION['user'] = [
            'id' => $idUsuario,
            'nombre' => trim((string) $usuario['nombres']) . ' ' . trim((string) $usuario['apellidos']),
            'rol' => $rol,
            'email' => (string) $usuario['correo'],
            'refugio_id' => is_array($refugio) ? (int) $refugio['id_refugio'] : null,
        ];

        $destino = [
            'usuario' => 'dashboard',
            'refugio' => 'refugio/dashboard',
            'admin' => 'admin/dashboard',
        ][$rol];

        header('Location: ' . url($destino));
        exit;
    }

    public function registro(): void
    {
        $this->render('auth/registro', ['titulo' => 'Crear cuenta'], 'auth');
    }

    public function procesarRegistro(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . url('registro'));
            exit;
        }

        $tipo = (string) ($_POST['tipo'] ?? 'usuarios');
        $tipo = $tipo === 'refugio' ? 'refugio' : 'usuarios';

        $nombres = trim((string) ($_POST['nombre'] ?? ''));
        $documento = trim((string) ($_POST['documento'] ?? ''));
        $telefono = trim((string) ($_POST['telefono'] ?? ''));
        $correo = strtolower(trim((string) ($_POST['email'] ?? '')));
        $direccion = trim((string) ($_POST['direccion'] ?? ''));
        $nombreAdmin = trim((string) ($_POST['nombre_admin'] ?? ''));
        $correoAdmin = strtolower(trim((string) ($_POST['correo_admin'] ?? '')));
        $documentoAdmin = trim((string) ($_POST['documento_admin'] ?? ''));
        $passwordAdmin = (string) ($_POST['password_admin'] ?? '');
        $confirmarPasswordAdmin = (string) ($_POST['confirm_password_admin'] ?? '');
        $password = (string) ($_POST['password'] ?? '');
        $confirmar = (string) ($_POST['confirm_password'] ?? '');
        $terminos = !empty($_POST['terminos']);

        if ($nombres === ''
            || $documento === ''
            || $telefono === ''
            || $correo === ''
            || $direccion === ''
            || $password === ''
            || $confirmar === ''
            || ($tipo === 'refugio'
                && ($nombreAdmin === ''
                    || $correoAdmin === ''
                    || $documentoAdmin === ''
                    || $passwordAdmin === ''
                    || $confirmarPasswordAdmin === ''))) {
            $_SESSION['error'] = 'Todos los campos obligatorios deben completarse.';
            header('Location: ' . url('registro'));
            exit;
        }

        if ($password !== $confirmar) {
            $_SESSION['error'] = 'Las contraseñas no coinciden.';
            header('Location: ' . url('registro'));
            exit;
        }

        if ($tipo === 'refugio' && $passwordAdmin !== $confirmarPasswordAdmin) {
            $_SESSION['error'] = 'La contraseña inicial del administrador no coincide.';
            header('Location: ' . url('registro'));
            exit;
        }

        if (!filter_var($correo, FILTER_VALIDATE_EMAIL)
            || ($tipo === 'refugio' && !filter_var($correoAdmin, FILTER_VALIDATE_EMAIL))
            || strlen($password) < 8
            || ($tipo === 'refugio'
                && (strlen($passwordAdmin) < 8 || !preg_match('/^\d{6,12}$/', $documentoAdmin)))) {
            $_SESSION['error'] = 'Ingresa correos válidos, un documento de administrador de 6 a 12 dígitos y contraseñas de al menos 8 caracteres.';
            header('Location: ' . url('registro'));
            exit;
        }

        if (!$terminos) {
            $_SESSION['error'] = 'Debes aceptar los términos y condiciones.';
            header('Location: ' . url('registro'));
            exit;
        }

        $pdo = Database::conectar();
        $usuarioId = null;
        $refugioId = null;

        try {
            $pdo->beginTransaction();
            $usuarioId = Usuario::crear([
                'nombres' => $nombres,
                'apellidos' => $tipo === 'refugio' ? 'Refugio' : 'Usuario',
                'documento' => $documento,
                'telefono' => $telefono,
                'direccion' => $direccion,
                'correo' => $correo,
                'password' => $password,
                'terminos' => 1,
            ]);

            if ($usuarioId === null) {
                $pdo->rollBack();
                $_SESSION['error'] = 'El correo o documento ya están registrados.';
                header('Location: ' . url('registro'));
                exit;
            }

            if ($tipo === 'refugio') {
                $refugioId = Refugio::crear([
                    'nombre' => $nombres,
                    'direccion' => $direccion,
                    'telefono' => $telefono,
                    'correo' => $correo,
                    'documento' => $documento,
                    'tipo' => 'fundación',
                    'nombre_contacto' => $nombreAdmin,
                    'cargo_contacto' => 'Administrador',
                    'correo_contacto' => $correoAdmin,
                ], $usuarioId);

                if ($refugioId === null) {
                    throw new RuntimeException('No fue posible crear el refugio.');
                }

                $administradorId = Usuario::crear([
                    'nombres' => $nombreAdmin,
                    'apellidos' => 'Administrador',
                    'documento' => $documentoAdmin,
                    'correo' => $correoAdmin,
                    'password' => $passwordAdmin,
                    'terminos' => 0,
                ]);

                if ($administradorId === null) {
                    $pdo->rollBack();
                    $_SESSION['error'] = 'El correo o documento del administrador ya están registrados.';
                    header('Location: ' . url('registro'));
                    exit;
                }

                if (!Refugio::agregarAdministrador($refugioId, $administradorId, $correoAdmin)) {
                    throw new RuntimeException('No fue posible vincular al administrador con el refugio.');
                }
            }

            $pdo->commit();
        } catch (Throwable $error) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }

            error_log('ADOPT ME - No fue posible completar el registro: ' . $error->getMessage());
            $_SESSION['error'] = 'No fue posible completar el registro. Revisa si el correo o documento ya están registrados.';
            header('Location: ' . url('registro'));
            exit;
        }

        session_regenerate_id(true);
        $_SESSION['user'] = [
            'id' => $usuarioId,
            'nombre' => $nombres,
            'rol' => $tipo === 'refugio' ? 'refugio' : 'usuario',
            'email' => $correo,
            'refugio_id' => $refugioId,
        ];

        $destino = $_SESSION['user']['rol'] === 'refugio' ? 'refugio/dashboard' : 'dashboard';
        header('Location: ' . url($destino));
        exit;
    }

    public function recuperar(): void
    {
        $this->render('auth/recuperar', ['titulo' => 'Recuperar contraseña'], 'auth');
    }

    public function logout(): void
    {
        $_SESSION = [];
        session_destroy();

        header('Location: ' . url('home'));
        exit;
    }
}
