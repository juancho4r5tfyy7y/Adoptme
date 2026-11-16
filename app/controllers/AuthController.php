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
        $_SESSION['user'] = [
            'id' => (int) $usuario['id_usuario'],
            'nombre' => trim((string) $usuario['nombres']) . ' ' . trim((string) $usuario['apellidos']),
            'rol' => 'usuario',
            'email' => (string) $usuario['correo'],
            'refugio_id' => null,
        ];

        $refugio = Refugio::buscarPorUsuario((int) $usuario['id_usuario']);
        if (is_array($refugio)) {
            $_SESSION['user']['rol'] = 'refugio';
            $_SESSION['user']['refugio_id'] = (int) $refugio['id_refugio'];
        }

        $destino = 'dashboard';

        if ($_SESSION['user']['rol'] === 'refugio') {
            $destino = 'refugio/dashboard';
        }

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
        $password = (string) ($_POST['password'] ?? '');
        $confirmar = (string) ($_POST['confirm_password'] ?? '');
        $terminos = !empty($_POST['terminos']);

        if ($nombres === '' || $documento === '' || $telefono === '' || $correo === '' || $direccion === '' || $password === '' || $confirmar === '') {
            $_SESSION['error'] = 'Todos los campos obligatorios deben completarse.';
            header('Location: ' . url('registro'));
            exit;
        }

        if ($password !== $confirmar) {
            $_SESSION['error'] = 'Las contraseñas no coinciden.';
            header('Location: ' . url('registro'));
            exit;
        }

        if (!filter_var($correo, FILTER_VALIDATE_EMAIL) || strlen($password) < 8) {
            $_SESSION['error'] = 'Ingresa un correo válido y una contraseña de al menos 8 caracteres.';
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
                ], $usuarioId);

                if ($refugioId === null) {
                    throw new RuntimeException('No fue posible crear el refugio.');
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
