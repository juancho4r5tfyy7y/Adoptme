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
                    s.tiene_patio,
                    s.estado_solicitud AS estado,
                    s.observaciones AS motivo_rechazo,
                    m.nombre AS mascota_nombre,
                    u.correo AS solicitante_correo,
                    u.telefono AS solicitante_telefono,
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

    public static function resolver(
        int $idSolicitud,
        int $idRefugio,
        string $decision,
        string $observaciones = ''
    ): bool {
        $observaciones = trim($observaciones);

        if ($idSolicitud <= 0
            || $idRefugio <= 0
            || !in_array($decision, ['aprobar', 'rechazar'], true)
            || ($decision === 'rechazar' && $observaciones === '')) {
            return false;
        }

        $pdo = Database::conectar();
        $pdo->beginTransaction();

        try {
            $consulta = $pdo->prepare(
                'SELECT s.id_usuario, s.id_mascota, s.id_refugio,
                        s.estado_solicitud, m.estado_adopcion
                 FROM solicitudes_adopcion s
                 INNER JOIN mascotas m ON m.id_mascota = s.id_mascota
                                       AND m.id_refugio = s.id_refugio
                 WHERE s.id_solicitud = :id_solicitud
                   AND s.id_refugio = :id_refugio
                 FOR UPDATE'
            );
            $consulta->execute([
                ':id_solicitud' => $idSolicitud,
                ':id_refugio' => $idRefugio,
            ]);
            $solicitud = $consulta->fetch();

            if (!is_array($solicitud) || $solicitud['estado_solicitud'] !== 'pendiente') {
                $pdo->rollBack();
                return false;
            }

            if ($decision === 'aprobar') {
                if ($solicitud['estado_adopcion'] !== 'disponible') {
                    $pdo->rollBack();
                    return false;
                }

                $actualizarOtras = $pdo->prepare(
                    "UPDATE solicitudes_adopcion
                     SET estado_solicitud = 'rechazada',
                         fecha_respuesta = NOW(),
                         observaciones = 'La mascota fue asignada a otra solicitud.'
                     WHERE id_refugio = :id_refugio
                       AND id_mascota = :id_mascota
                       AND id_solicitud <> :id_solicitud
                       AND estado_solicitud = 'pendiente'"
                );
                $actualizarOtras->execute([
                    ':id_refugio' => $idRefugio,
                    ':id_mascota' => (int) $solicitud['id_mascota'],
                    ':id_solicitud' => $idSolicitud,
                ]);
            }

            $estado = $decision === 'aprobar' ? 'aprobada' : 'rechazada';
            $actualizar = $pdo->prepare(
                'UPDATE solicitudes_adopcion
                 SET estado_solicitud = :estado,
                     fecha_respuesta = NOW(),
                     observaciones = :observaciones
                 WHERE id_solicitud = :id_solicitud
                   AND id_refugio = :id_refugio
                   AND estado_solicitud = :estado_pendiente'
            );
            $actualizar->execute([
                ':estado' => $estado,
                ':observaciones' => $decision === 'aprobar' ? null : $observaciones,
                ':id_solicitud' => $idSolicitud,
                ':id_refugio' => $idRefugio,
                ':estado_pendiente' => 'pendiente',
            ]);

            if ($actualizar->rowCount() !== 1) {
                $pdo->rollBack();
                return false;
            }

            if ($decision === 'aprobar') {
                $adopcion = $pdo->prepare(
                    'INSERT INTO adopciones (
                        id_solicitud,
                        id_mascota,
                        id_usuario,
                        id_refugio,
                        observaciones
                     ) VALUES (
                        :id_solicitud,
                        :id_mascota,
                        :id_usuario,
                        :id_refugio,
                        NULL
                     )'
                );
                $adopcion->execute([
                    ':id_solicitud' => $idSolicitud,
                    ':id_mascota' => (int) $solicitud['id_mascota'],
                    ':id_usuario' => (int) $solicitud['id_usuario'],
                    ':id_refugio' => $idRefugio,
                ]);
            }

            $pdo->commit();
            return true;
        } catch (Throwable $error) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }

            throw $error;
        }
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
