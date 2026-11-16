<?php
/**
 * Gestión de solicitudes de adopción reales.
 */
class SolicitudAdopcion
{
    public static function crear(array $datos): ?int
    {
        $idUsuario = (int) ($datos['id_usuario'] ?? 0);
        $idMascota = (int) ($datos['id_mascota'] ?? 0);
        $idRefugio = (int) ($datos['id_refugio'] ?? 0);
        $motivo = trim((string) ($datos['motivo'] ?? ''));
        $tipoVivienda = trim((string) ($datos['tipo_vivienda'] ?? ''));
        $tienePatio = !empty($datos['tiene_patio']) ? 1 : 0;
        $compromisosAceptados = !empty($datos['compromisos_aceptados']) ? 1 : 0;

        if ($idUsuario <= 0 || $idMascota <= 0 || $idRefugio <= 0 || $motivo === '' || $compromisosAceptados !== 1) {
            return null;
        }

        $consulta = Database::conectar()->prepare(
            'INSERT INTO solicitudes_adopcion (
                id_usuario,
                id_mascota,
                id_refugio,
                motivo_adopcion,
                tipo_vivienda,
                tiene_patio,
                compromisos_aceptados,
                estado_solicitud
            ) VALUES (
                :id_usuario,
                :id_mascota,
                :id_refugio,
                :motivo,
                :tipo_vivienda,
                :tiene_patio,
                :compromisos,
                :estado
            )'
        );

        $resultado = $consulta->execute([
            ':id_usuario' => $idUsuario,
            ':id_mascota' => $idMascota,
            ':id_refugio' => $idRefugio,
            ':motivo' => $motivo,
            ':tipo_vivienda' => $tipoVivienda,
            ':tiene_patio' => $tienePatio,
            ':compromisos' => $compromisosAceptados,
            ':estado' => 'pendiente',
        ]);

        if (!$resultado) {
            return null;
        }

        return (int) Database::conectar()->lastInsertId();
    }

    public static function porUsuario(int $idUsuario): array
    {
        if ($idUsuario <= 0) {
            return [];
        }

        $consulta = Database::conectar()->prepare(
            'SELECT s.id_solicitud AS id,
                    s.id_mascota AS mascota_id,
                    s.id_refugio AS refugio_id,
                    s.fecha_solicitud AS fecha,
                    s.motivo_adopcion AS motivo,
                    s.tipo_vivienda AS vivienda,
                    s.tiene_patio,
                    s.estado_solicitud AS estado,
                    s.observaciones AS motivo_rechazo,
                    m.nombre AS mascota_nombre,
                    m.estado_adopcion,
                    m.estado_publicacion
             FROM solicitudes_adopcion s
             INNER JOIN mascotas m ON m.id_mascota = s.id_mascota
             WHERE s.id_usuario = :id_usuario
             ORDER BY s.fecha_solicitud DESC'
        );
        $consulta->execute([':id_usuario' => $idUsuario]);

        return $consulta->fetchAll();
    }

    public static function porRefugio(int $idRefugio): array
    {
        if ($idRefugio <= 0) {
            return [];
        }

        $consulta = Database::conectar()->prepare(
            'SELECT s.id_solicitud AS id,
                    s.id_mascota AS mascota_id,
                    s.id_refugio AS refugio_id,
                    s.fecha_solicitud AS fecha,
                    s.motivo_adopcion AS motivo,
                    s.tipo_vivienda AS vivienda,
                    s.estado_solicitud AS estado,
                    m.nombre AS mascota_nombre,
                    CONCAT(u.nombres, " ", u.apellidos) AS solicitante
             FROM solicitudes_adopcion s
             INNER JOIN mascotas m ON m.id_mascota = s.id_mascota
             INNER JOIN usuarios u ON u.id_usuario = s.id_usuario
             WHERE s.id_refugio = :id_refugio
             ORDER BY s.fecha_solicitud DESC'
        );
        $consulta->execute([':id_refugio' => $idRefugio]);

        return $consulta->fetchAll();
    }

    public static function activa(int $idUsuario, int $idMascota): bool
    {
        $consulta = Database::conectar()->prepare(
            'SELECT 1 FROM solicitudes_adopcion
             WHERE id_usuario = :id_usuario
               AND id_mascota = :id_mascota
               AND estado_solicitud = :estado
             LIMIT 1'
        );
        $consulta->execute([
            ':id_usuario' => $idUsuario,
            ':id_mascota' => $idMascota,
            ':estado' => 'pendiente',
        ]);

        return $consulta->fetchColumn() !== false;
    }
}
