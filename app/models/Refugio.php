<?php
/**
 * Registro y consulta de refugios en la base de datos.
 */
class Refugio
{
    public static function buscarPorUsuario(int $idUsuario): ?array
    {
        if ($idUsuario <= 0) {
            return null;
        }

        $consulta = Database::conectar()->prepare(
            "SELECT r.*
             FROM refugios r
             WHERE r.id_usuario = :id_propietario
                OR EXISTS (
                    SELECT 1
                    FROM refugio_miembros rm
                    WHERE rm.id_refugio = r.id_refugio
                      AND rm.id_usuario = :id_miembro
                      AND rm.rol_miembro = 'Administrador'
                      AND rm.estado_invitacion = 'aceptada'
                )
             LIMIT 1"
        );
        $consulta->execute([
            ':id_propietario' => $idUsuario,
            ':id_miembro' => $idUsuario,
        ]);

        $registro = $consulta->fetch();
        return is_array($registro) ? $registro : null;
    }

    public static function esAdministrador(int $idRefugio, int $idUsuario): bool
    {
        if ($idRefugio <= 0 || $idUsuario <= 0) {
            return false;
        }

        $consulta = Database::conectar()->prepare(
            "SELECT 1
             FROM refugio_miembros
             WHERE id_refugio = :id_refugio
               AND id_usuario = :id_usuario
               AND rol_miembro = 'Administrador'
               AND estado_invitacion = 'aceptada'
             LIMIT 1"
        );
        $consulta->execute([
            ':id_refugio' => $idRefugio,
            ':id_usuario' => $idUsuario,
        ]);

        return $consulta->fetchColumn() !== false;
    }

    public static function crear(array $datos, int $idUsuario): ?int
    {
        $nombre = trim((string) ($datos['nombre'] ?? ''));
        $direccion = trim((string) ($datos['direccion'] ?? ''));
        $telefono = trim((string) ($datos['telefono'] ?? ''));
        $correo = strtolower(trim((string) ($datos['correo'] ?? '')));
        $documento = trim((string) ($datos['documento'] ?? ''));
        $nombreContacto = trim((string) ($datos['nombre_contacto'] ?? ''));
        $correoContacto = strtolower(trim((string) ($datos['correo_contacto'] ?? '')));
        $cargoContacto = trim((string) ($datos['cargo_contacto'] ?? 'Administrador'));
        $tipo = in_array((string) ($datos['tipo'] ?? ''), ['fundación', 'albergue', 'rescatista_independiente'], true)
            ? (string) $datos['tipo']
            : 'fundación';

        if ($idUsuario <= 0
            || $nombre === ''
            || $direccion === ''
            || $telefono === ''
            || $correo === ''
            || $documento === ''
            || $nombreContacto === ''
            || !filter_var($correoContacto, FILTER_VALIDATE_EMAIL)) {
            return null;
        }

        $consulta = Database::conectar()->prepare(
            'INSERT INTO refugios (
                id_usuario,
                nombre_refugio,
                direccion,
                telefono,
                correo,
                documento_legal,
                tipo_refugio,
                nombre_contacto,
                cargo_contacto,
                correo_contacto
            ) VALUES (
                :id_usuario,
                :nombre_refugio,
                :direccion,
                :telefono,
                :correo,
                :documento_legal,
                :tipo_refugio,
                :nombre_contacto,
                :cargo_contacto,
                :correo_contacto
            )'
        );

        $consulta->execute([
            ':id_usuario' => $idUsuario,
            ':nombre_refugio' => $nombre,
            ':direccion' => $direccion,
            ':telefono' => $telefono,
            ':correo' => $correo,
            ':documento_legal' => $documento,
            ':tipo_refugio' => $tipo,
            ':nombre_contacto' => $nombreContacto,
            ':cargo_contacto' => $cargoContacto,
            ':correo_contacto' => $correoContacto,
        ]);

        return (int) Database::conectar()->lastInsertId();
    }

    public static function actualizarAdministrador(int $idRefugio, string $nombre, string $correo): bool
    {
        $nombre = trim($nombre);
        $correo = strtolower(trim($correo));

        if ($idRefugio <= 0 || $nombre === '' || !filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            return false;
        }

        $pdo = Database::conectar();
        $consulta = $pdo->prepare(
            'SELECT 1 FROM refugios WHERE id_refugio = :id_refugio LIMIT 1'
        );
        $consulta->execute([':id_refugio' => $idRefugio]);

        if ($consulta->fetchColumn() === false) {
            return false;
        }

        $consulta = $pdo->prepare(
            "UPDATE refugios
             SET nombre_contacto = :nombre_contacto,
                 cargo_contacto = 'Administrador',
                 correo_contacto = :correo_contacto
             WHERE id_refugio = :id_refugio"
        );

        return $consulta->execute([
            ':nombre_contacto' => $nombre,
            ':correo_contacto' => $correo,
            ':id_refugio' => $idRefugio,
        ]);
    }

    public static function agregarAdministrador(int $idRefugio, int $idUsuario, string $correo): bool
    {
        $correo = strtolower(trim($correo));

        if ($idRefugio <= 0 || $idUsuario <= 0 || !filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            return false;
        }

        $pdo = Database::conectar();
        $consulta = $pdo->prepare(
            "SELECT 1
             FROM refugio_miembros
             WHERE id_refugio = :id_refugio
               AND estado_invitacion = 'aceptada'
               AND rol_miembro = 'Administrador'
             LIMIT 1"
        );
        $consulta->execute([':id_refugio' => $idRefugio]);

        if ($consulta->fetchColumn() !== false) {
            return false;
        }

        $consulta = $pdo->prepare(
            "INSERT INTO refugio_miembros (
                id_refugio,
                correo_invitado,
                id_usuario,
                rol_miembro,
                estado_invitacion
             ) VALUES (
                :id_refugio,
                :correo_invitado,
                :id_usuario,
                'Administrador',
                'aceptada'
             )"
        );

        return $consulta->execute([
            ':id_refugio' => $idRefugio,
            ':correo_invitado' => $correo,
            ':id_usuario' => $idUsuario,
        ]);
    }

    public static function administradorPorRefugio(int $idRefugio): ?array
    {
        if ($idRefugio <= 0) {
            return null;
        }

        $consulta = Database::conectar()->prepare(
            "SELECT u.id_usuario, u.numero_documento AS documento,
                    CONCAT(u.nombres, ' ', u.apellidos) AS nombre, u.correo
             FROM refugio_miembros rm
             INNER JOIN usuarios u ON u.id_usuario = rm.id_usuario
             WHERE rm.id_refugio = :id_refugio
               AND rm.rol_miembro = 'Administrador'
               AND rm.estado_invitacion = 'aceptada'
             ORDER BY rm.id_miembro
             LIMIT 1"
        );
        $consulta->execute([':id_refugio' => $idRefugio]);

        $registro = $consulta->fetch();
        return is_array($registro) ? $registro : null;
    }
}
