<?php
/**
 * Gestión de usuarios reales para autenticación y registro.
 */
class Usuario
{
    public static function buscarPorCorreo(string $correo): ?array
    {
        $correo = strtolower(trim($correo));

        if ($correo === '') {
            return null;
        }

        $consulta = Database::conectar()->prepare(
            'SELECT * FROM usuarios WHERE correo = :correo LIMIT 1'
        );
        $consulta->execute([':correo' => $correo]);

        $registro = $consulta->fetch();
        return is_array($registro) ? $registro : null;
    }

    public static function buscarPorId(int $id): ?array
    {
        if ($id <= 0) {
            return null;
        }

        $consulta = Database::conectar()->prepare(
            'SELECT * FROM usuarios WHERE id_usuario = :id LIMIT 1'
        );
        $consulta->execute([':id' => $id]);

        $registro = $consulta->fetch();
        return is_array($registro) ? $registro : null;
    }

    public static function existeCorreo(string $correo): bool
    {
        return self::buscarPorCorreo($correo) !== null;
    }

    public static function existeDocumento(string $documento): bool
    {
        $documento = trim($documento);

        if ($documento === '') {
            return false;
        }

        $consulta = Database::conectar()->prepare(
            'SELECT 1 FROM usuarios WHERE numero_documento = :documento LIMIT 1'
        );
        $consulta->execute([':documento' => $documento]);

        return $consulta->fetchColumn() !== false;
    }

    public static function autenticar(string $correo, string $password): ?array
    {
        $usuario = self::buscarPorCorreo($correo);

        if (!$usuario) {
            return null;
        }

        if (($usuario['estado_cuenta'] ?? '') !== 'activo') {
            return null;
        }

        if (!password_verify($password, (string) $usuario['contrasena'])) {
            return null;
        }

        return $usuario;
    }

    public static function crear(array $datos): ?int
    {
        $correo = strtolower(trim((string) ($datos['correo'] ?? '')));
        $password = (string) ($datos['password'] ?? '');
        $nombres = trim((string) ($datos['nombres'] ?? ''));
        $apellidos = trim((string) ($datos['apellidos'] ?? ''));
        $documento = trim((string) ($datos['documento'] ?? ''));
        $telefono = trim((string) ($datos['telefono'] ?? ''));
        $direccion = trim((string) ($datos['direccion'] ?? ''));
        $terminos = !empty($datos['terminos']) ? 1 : 0;

        if (!filter_var($correo, FILTER_VALIDATE_EMAIL)
            || strlen($password) < 8
            || $nombres === ''
            || $documento === '') {
            return null;
        }

        if (self::existeCorreo($correo) || self::existeDocumento($documento)) {
            return null;
        }

        $contrasena = password_hash($password, PASSWORD_BCRYPT);
        $apellidos = $apellidos === '' ? 'Usuario' : $apellidos;

        $consulta = Database::conectar()->prepare(
            'INSERT INTO usuarios (
                nombres,
                apellidos,
                numero_documento,
                correo,
                telefono,
                direccion,
                contrasena,
                terminos_aceptados,
                fecha_aceptacion_terminos
            ) VALUES (
                :nombres,
                :apellidos,
                :documento,
                :correo,
                :telefono,
                :direccion,
                :contrasena,
                :terminos,
                NOW()
            )'
        );

        $consulta->execute([
            ':nombres' => $nombres,
            ':apellidos' => $apellidos,
            ':documento' => $documento,
            ':correo' => $correo,
            ':telefono' => $telefono,
            ':direccion' => $direccion,
            ':contrasena' => $contrasena,
            ':terminos' => $terminos,
        ]);

        return (int) Database::conectar()->lastInsertId();
    }
}
