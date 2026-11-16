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
            'SELECT * FROM refugios WHERE id_usuario = :id_usuario LIMIT 1'
        );
        $consulta->execute([':id_usuario' => $idUsuario]);

        $registro = $consulta->fetch();
        return is_array($registro) ? $registro : null;
    }

    public static function crear(array $datos, int $idUsuario): ?int
    {
        $nombre = trim((string) ($datos['nombre'] ?? ''));
        $direccion = trim((string) ($datos['direccion'] ?? ''));
        $telefono = trim((string) ($datos['telefono'] ?? ''));
        $correo = strtolower(trim((string) ($datos['correo'] ?? '')));
        $documento = trim((string) ($datos['documento'] ?? ''));
        $tipo = in_array((string) ($datos['tipo'] ?? ''), ['fundación', 'albergue', 'rescatista_independiente'], true)
            ? (string) $datos['tipo']
            : 'fundación';

        if ($idUsuario <= 0 || $nombre === '' || $direccion === '' || $telefono === '' || $correo === '' || $documento === '') {
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
            ':nombre_contacto' => $nombre,
            ':correo_contacto' => $correo,
        ]);

        return (int) Database::conectar()->lastInsertId();
    }
}
