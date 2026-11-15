<?php
/** Acceso de demostración. No valida credenciales ni reemplaza la autenticación del backend. */
class AuthController extends Controller
{
    public function login(): void
    {
        $this->render('auth/login', ['titulo' => 'Iniciar sesión'], 'auth');
    }

    public function registro(): void
    {
        $this->render('auth/registro', ['titulo' => 'Crear cuenta'], 'auth');
    }

    public function recuperar(): void
    {
        $this->render('auth/recuperar', ['titulo' => 'Recuperar contraseña'], 'auth');
    }

    public function demo(): void
    {
        $rol = $_GET['rol'] ?? 'usuario';
        $nombres = [
            'usuario' => 'María Gómez',
            'refugio' => 'Huellas Felices',
            'admin' => 'Admin ADOPT ME',
        ];

        if (!is_string($rol) || !isset($nombres[$rol])) {
            $rol = 'usuario';
        }

        $_SESSION['user'] = [
            'nombre' => $nombres[$rol],
            'rol' => $rol,
            'email' => 'maria@ejemplo.com',
            'refugio_id' => 1,
        ];

        $destinos = [
            'usuario' => 'dashboard',
            'refugio' => 'refugio/dashboard',
            'admin' => 'admin/dashboard',
        ];

        header('Location: ' . url($destinos[$rol]));
        exit;
    }

    public function logout(): void
    {
        $_SESSION = [];
        session_destroy();

        header('Location: ' . url('home'));
        exit;
    }
}
