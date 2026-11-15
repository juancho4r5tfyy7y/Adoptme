<?php
/** Funciones auxiliares usadas por las vistas. */
function e($s) { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }          // Escapa HTML (anti-XSS)
function url($r = 'home', $p = []) { return BASE_URL . '/index.php?' . http_build_query(array_merge(['r' => $r], $p)); }
function asset($f) { return BASE_URL . '/assets/' . $f; }

/** Insignia de estado (disponible, en proceso, aprobada...). */
function badge($estado) {
    return '<span class="badge badge-estado badge-' . e(str_replace(' ', '-', $estado)) . '">' . e($estado) . '</span>';
}
/** Ilustración de mascota: fondo de color + huella (se reemplazará por la foto real del backend). */
function mascota_img($m) {
    // Si la mascota tiene foto real, se muestra la imagen; si no, la ilustración de respaldo.
    if (!empty($m['foto'])) {
        return '<img class="pet-art-img" src="' . asset('img/mascotas/' . e($m['foto'])) . '" alt="Foto de ' . e($m['nombre']) . ' (' . e($m['raza']) . ')" loading="lazy">';
    }
    $c = e($m['color'] ?? '#FBE4D8');
    return '<div class="pet-art" style="background:' . $c . '" role="img" aria-label="Ilustración de ' . e($m['nombre']) . '">
      <svg viewBox="0 0 100 100" width="46%" fill="#2E1D14" opacity=".75"><ellipse cx="30" cy="38" rx="9" ry="12"/><ellipse cx="50" cy="26" rx="9.5" ry="12.5"/><ellipse cx="70" cy="38" rx="9" ry="12"/><path d="M50 46c-14 0-24 10-24 21 0 9 7 13 13 9 4-2 7-3 11-3s7 1 11 3c6 4 13 0 13-9 0-11-10-21-24-21z"/></svg></div>';
}
/** Menú lateral según el rol: [ruta, etiqueta, ícono Bootstrap Icons]. */
function menu_por_rol($rol) {
    $m = [
        'usuario' => [['dashboard','Panel principal','house'],['catalogo','Catálogo de mascotas','search'],['favoritos','Favoritos','heart'],['seguimiento','Mis solicitudes','file-earmark-text'],['perfil','Mi perfil','person'],['notificaciones','Notificaciones','bell']],
        'refugio' => [['refugio/dashboard','Panel principal','house'],['refugio/mascotas','Gestión de mascotas','bandaid'],['refugio/solicitudes','Solicitudes recibidas','file-earmark-text'],['refugio/perfil','Perfil del refugio','building'],['notificaciones','Notificaciones','bell']],
        'admin'   => [['admin/dashboard','Panel general','house'],['admin/usuarios','Usuarios','people'],['admin/refugios','Refugios','building'],['admin/solicitudes','Solicitudes','file-earmark-text'],['admin/reportes','Reportes y estadísticas','bar-chart'],['notificaciones','Notificaciones','bell']],
    ];
    return $m[$rol] ?? [];
}
