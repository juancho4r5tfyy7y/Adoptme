<?php
/** Panel de administración: usuarios, refugios, solicitudes y reportes. */
class AdminController extends Controller
{
    private function usuariosReal(): array
    {
        $consulta = Database::conectar()->query(
            'SELECT id_usuario AS id, nombres AS nombre, correo AS email, telefono, fecha_registro AS fecha, estado_cuenta AS estado
             FROM usuarios
             ORDER BY fecha_registro DESC'
        );

        return $consulta->fetchAll();
    }

    private function refugiosReal(): array
    {
        $consulta = Database::conectar()->query(
            'SELECT r.id_refugio AS id, r.nombre_refugio AS nombre, r.direccion AS ciudad, r.tipo_refugio AS tipo, r.estado_cuenta AS estado
             FROM refugios r
             ORDER BY r.fecha_registro DESC'
        );

        return $consulta->fetchAll();
    }

    private function solicitudesReal(): array
    {
        $consulta = Database::conectar()->query(
                'SELECT s.id_solicitud AS id, s.id_mascota AS mascota_id, s.id_refugio AS refugio_id,
                    s.estado_solicitud AS estado, s.fecha_solicitud AS fecha,
                    s.motivo_adopcion AS motivo, CONCAT(u.nombres, " ", u.apellidos) AS solicitante,
                    m.nombre AS mascota_nombre, r.nombre_refugio AS refugio_nombre
             FROM solicitudes_adopcion s
             INNER JOIN usuarios u ON u.id_usuario = s.id_usuario
             INNER JOIN mascotas m ON m.id_mascota = s.id_mascota
                 INNER JOIN refugios r ON r.id_refugio = s.id_refugio
             ORDER BY s.fecha_solicitud DESC'
        );

        return $consulta->fetchAll();
    }

    public function dashboard(): void
    {
        $usuarios = $this->usuariosReal();
        $refugios = $this->refugiosReal();

        $this->render('admin/dashboard', [
            'titulo' => 'Panel de administración',
            'mascotas' => Mascota::todas(),
            'solicitudes' => $this->solicitudesReal(),
            'nUsuarios' => count($usuarios),
            'nRefugios' => count($refugios),
        ]);
    }

    public function usuarios(): void
    {
        $this->render('admin/usuarios', [
            'titulo' => 'Usuarios',
            'usuarios' => $this->usuariosReal(),
        ]);
    }

    public function refugios(): void
    {
        $this->render('admin/refugios', [
            'titulo' => 'Refugios',
            'refugios' => array_map(static fn(array $r): array => [
                'id' => (int) $r['id'],
                'nombre' => (string) $r['nombre'],
                'ciudad' => (string) $r['ciudad'],
                'tipo' => (string) ($r['tipo'] ?? 'Fundación'),
                'estado' => (string) $r['estado'],
            ], $this->refugiosReal()),
            'mascotas' => Mascota::todas(),
        ]);
    }

    public function solicitudes(): void
    {
        $this->render('admin/solicitudes', [
            'titulo' => 'Solicitudes',
            'solicitudes' => $this->solicitudesReal(),
        ]);
    }

    public function reportes(): void
    {
        $this->render('admin/reportes', [
            'titulo' => 'Reportes',
            'mascotas' => Mascota::todas(),
            'solicitudes' => $this->solicitudesReal(),
        ]);
    }
}
