<?php
/** Funciones auxiliares utilizadas por controladores y vistas. */

/** Escapa texto para mostrarlo dentro de HTML. */
function e($valor): string
{
    return htmlspecialchars((string) $valor, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/** Genera una URL interna conservando la ruta y sus parámetros. */
function url(string $ruta = 'home', array $parametros = []): string
{
    return BASE_URL . '/index.php?' . http_build_query(
        array_merge(['r' => $ruta], $parametros)
    );
}

/** Genera la ruta de un archivo dentro de public/assets. */
function asset(string $archivo): string
{
    return BASE_URL . '/assets/' . ltrim($archivo, '/');
}

/** Devuelve la inicial de un nombre sin depender de la extensión mbstring. */
function inicial(string $nombre): string
{
    $nombre = trim($nombre);

    if ($nombre === '') {
        return '';
    }

    if (function_exists('mb_substr')) {
        $letra = mb_substr($nombre, 0, 1, 'UTF-8');
    } elseif (function_exists('iconv_substr')) {
        $letra = iconv_substr($nombre, 0, 1, 'UTF-8');
    } else {
        $letra = substr($nombre, 0, 1);
    }

    if ($letra === false || $letra === '') {
        return '';
    }

    if (function_exists('mb_strtoupper')) {
        return mb_strtoupper($letra, 'UTF-8');
    }

    return strtr(strtoupper($letra), [
        'á' => 'Á',
        'é' => 'É',
        'í' => 'Í',
        'ó' => 'Ó',
        'ú' => 'Ú',
        'ñ' => 'Ñ',
    ]);
}

/** Genera una insignia visual para un estado. */
function badge(string $estado): string
{
    $clase = str_replace(' ', '-', $estado);

    return '<span class="badge badge-estado badge-' . e($clase) . '">'
        . e($estado)
        . '</span>';
}

/** Muestra una foto o la ilustración de respaldo de una mascota. */
function mascota_img(array $mascota): string
{
    $nombre = e($mascota['nombre'] ?? 'mascota');

    if (!empty($mascota['foto'])) {
        $archivo = asset('img/mascotas/' . $mascota['foto']);
        $raza = e($mascota['raza'] ?? '');

        return '<img class="pet-art-img" src="' . e($archivo)
            . '" alt="Foto de ' . $nombre . ' (' . $raza . ')" loading="lazy">';
    }

    // La paleta es cerrada para evitar insertar valores CSS procedentes de datos externos.
    $colores = [
        '#FBE4D8' => 'pet-art--orange',
        '#F1EAE0' => 'pet-art--cream',
        '#EAE6E1' => 'pet-art--taupe',
    ];
    $color = strtoupper((string) ($mascota['color'] ?? '#FBE4D8'));
    $claseColor = $colores[$color] ?? 'pet-art--orange';

    return '<div class="pet-art ' . $claseColor . '" role="img" aria-label="Ilustración de '
        . $nombre . '">
        <svg viewBox="0 0 100 100" width="46%" fill="#2E1D14" opacity=".75" aria-hidden="true">
            <ellipse cx="30" cy="38" rx="9" ry="12" />
            <ellipse cx="50" cy="26" rx="9.5" ry="12.5" />
            <ellipse cx="70" cy="38" rx="9" ry="12" />
            <path d="M50 46c-14 0-24 10-24 21 0 9 7 13 13 9 4-2 7-3 11-3s7 1 11 3c6 4 13 0 13-9 0-11-10-21-24-21z" />
        </svg>
    </div>';
}

/** Opciones del menú lateral según el rol. */
function menu_por_rol(string $rol): array
{
    $menus = [
        'usuario' => [
            ['dashboard', 'Panel principal', 'house'],
            ['catalogo', 'Catálogo de mascotas', 'search'],
            ['favoritos', 'Favoritos', 'heart'],
            ['seguimiento', 'Mis solicitudes', 'file-earmark-text'],
            ['perfil', 'Mi perfil', 'person'],
            ['notificaciones', 'Notificaciones', 'bell'],
        ],
        'refugio' => [
            ['refugio/dashboard', 'Panel principal', 'house'],
            ['refugio/mascotas', 'Gestión de mascotas', 'bandaid'],
            ['refugio/solicitudes', 'Solicitudes recibidas', 'file-earmark-text'],
            ['refugio/perfil', 'Perfil del refugio', 'building'],
            ['notificaciones', 'Notificaciones', 'bell'],
        ],
        'admin' => [
            ['admin/dashboard', 'Panel general', 'house'],
            ['admin/usuarios', 'Usuarios', 'people'],
            ['admin/refugios', 'Refugios', 'building'],
            ['admin/solicitudes', 'Solicitudes', 'file-earmark-text'],
            ['admin/reportes', 'Reportes y estadísticas', 'bar-chart'],
            ['notificaciones', 'Notificaciones', 'bell'],
        ],
    ];

    return $menus[$rol] ?? [];
}
