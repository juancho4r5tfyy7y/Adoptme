<?php /** Seguimiento de solicitudes con línea de estado. */ ?>
<div class="d-flex justify-content-between align-items-start gap-3 flex-wrap mb-4"><div><h1 class="h2 mb-1">Seguimiento de solicitudes</h1><p class="text-secondary mb-0">Estado de cada solicitud que has enviado</p></div></div>
<?php if (!$solicitudes): ?><div class="text-center py-5"><h3 class="h5">No tienes solicitudes aún</h3><a class="btn btn-primary" href="<?= url('catalogo') ?>">Explorar mascotas</a></div><?php endif; ?>
<?php foreach ($solicitudes as $s):
    $p = Mascota::buscar($s['mascota_id']) ?? [
        'id' => $s['mascota_id'],
        'nombre' => 'Mascota no encontrada',
        'raza' => '',
        'color' => '#FBE4D8',
    ];
    $rev = $s['estado'] !== 'pendiente';
?>
<div class="card border-0 shadow-sm rounded-4 p-4 mb-3" data-solicitud>
  <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
    <div class="d-flex align-items-center gap-3"><div class="pet-thumbnail-lg"><?= mascota_img($p) ?></div>
      <div><h3 class="h6 mb-0"><?= e($p['nombre']) ?></h3><small class="text-secondary">Solicitud #<?= $s['id'] ?> · Enviada el <?= e($s['fecha']) ?></small></div></div><?= badge($s['estado']) ?></div>
  <div class="track">
    <div class="track-step done"><div class="track-dot"><i class="bi bi-check-lg"></i></div>Enviada</div>
    <div class="track-step <?= $rev ? 'done' : '' ?>"><div class="track-dot"><?= $rev ? '<i class="bi bi-check-lg"></i>' : '2' ?></div>En revisión</div>
    <div class="track-step <?= $s['estado'] === 'aprobada' ? 'done' : ($s['estado'] === 'rechazada' ? 'bad' : '') ?>"><div class="track-dot"><?= $s['estado'] === 'aprobada' ? '<i class="bi bi-check-lg"></i>' : ($s['estado'] === 'rechazada' ? '<i class="bi bi-x-lg"></i>' : '3') ?></div><?= $s['estado'] === 'rechazada' ? 'Rechazada' : 'Aprobada' ?></div>
  </div>
  <?php if ($s['estado'] === 'rechazada'): ?><p class="small text-secondary mb-0"><b>Motivo:</b> <?= e($s['motivo_rechazo'] ?? 'No especificado') ?></p><?php endif; ?>
  <?php if ($s['estado'] === 'aprobada'): ?><button class="btn btn-outline-dark btn-sm mt-2" data-msg="Certificado de adopción descargado"><i class="bi bi-download me-1"></i>Descargar certificado</button><?php endif; ?>
  <?php if ($s['estado'] === 'pendiente'): ?><button class="btn btn-outline-dark btn-sm mt-2" data-confirm="¿Confirmas que deseas cancelar esta solicitud? Esta acción no se puede deshacer." data-confirm-title="Cancelar solicitud" data-remove="[data-solicitud]" data-toast="Solicitud cancelada">Cancelar solicitud</button><?php endif; ?>
</div><?php endforeach; ?>
