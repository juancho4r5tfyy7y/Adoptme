<?php
/** Páginas públicas: inicio, catálogo, detalle y 404. */
class PublicController extends Controller
{
    public function inicio(): void
    {
        $destacadas = array_filter(
            Mascota::publicadas(),
            static fn(array $mascota): bool => $mascota['estado'] === 'disponible'
        );

        $this->render('public/home', [
            'titulo' => 'Inicio',
            'destacadas' => array_slice($destacadas, 0, 3),
        ], 'main');
    }

    public function catalogo(): void
    {
        $this->render('public/catalogo', [
            'titulo' => 'Catálogo',
            'mascotas' => Mascota::publicadas(),
        ], 'auto');
    }

    public function detalle(): void
    {
        $mascota = Mascota::buscar((int) ($_GET['id'] ?? 0));

        // Las mascotas en borrador no deben abrirse desde una ruta pública.
        if (!$mascota || $mascota['pub'] !== 'publicado') {
            $this->noEncontrado();
            return;
        }

        $consulta = Database::conectar()->prepare(
            'SELECT nombre_refugio AS nombre, direccion, telefono, correo, tipo_refugio AS tipo
             FROM refugios
             WHERE id_refugio = :id_refugio
             LIMIT 1'
        );
        $consulta->execute([':id_refugio' => $mascota['refugio_id']]);
        $refugio = $consulta->fetch() ?: null;

        $this->render('public/detalle', [
            'titulo' => $mascota['nombre'],
            'm' => $mascota,
            'refugio' => $refugio,
        ], 'auto');
    }

    public function noEncontrado(): void
    {
        http_response_code(404);
        $this->render('public/404', ['titulo' => 'Página no encontrada'], 'main');
    }
}
