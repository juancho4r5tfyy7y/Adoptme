<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Solicitudes del refugio</title>
  <link href="<?= asset('vendor/bootstrap-5.3.9-dist/css/bootstrap.min.css') ?>" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link href="<?= asset('css/adoptme.css') ?>" rel="stylesheet">
  <link href="<?= asset('css/components.css') ?>" rel="stylesheet">
  <link href="<?= asset('css/pages.css') ?>" rel="stylesheet">
</head>
<body>
<?php /** Solicitudes recibidas y decisiones del administrador del refugio. */ ?>
<div class="d-flex justify-content-between align-items-start gap-3 flex-wrap mb-4">
  <div>
    <h1 class="h2 mb-1">Solicitudes recibidas</h1>
    <p class="text-secondary mb-0">Revisa y responde las solicitudes para tus mascotas</p>
  </div>
</div>
<?php if (!empty($_SESSION['error'])): ?>
  <div class="alert alert-danger small mb-3"><?= e($_SESSION['error']) ?></div>
  <?php unset($_SESSION['error']); ?>
<?php endif; ?>
<?php if (!empty($_SESSION['success'])): ?>
  <div class="alert alert-success small mb-3"><?= e($_SESSION['success']) ?></div>
  <?php unset($_SESSION['success']); ?>
<?php endif; ?>
<?php if (!$puedeResolver): ?>
  <div class="alert alert-info small">La decisión de estas solicitudes corresponde al administrador de este refugio.</div>
<?php endif; ?>
<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
  <div class="table-responsive">
    <table class="table table-hover mb-0 align-middle">
      <thead><tr><th>Solicitante</th><th>Mascota</th><th>Fecha</th><th>Estado</th><th>Detalle</th></tr></thead>
      <tbody>
      <?php if (!$solicitudes): ?>
        <tr><td colspan="5" class="text-center text-secondary py-4">Aún no hay solicitudes para tus mascotas.</td></tr>
      <?php endif; ?>
      <?php foreach ($solicitudes as $s): ?>
        <tr>
          <td><?= e($s['solicitante']) ?></td>
          <td><?= e($s['mascota_nombre']) ?></td>
          <td><?= e($s['fecha']) ?></td>
          <td><?= badge($s['estado']) ?></td>
          <td><button class="icon-btn" type="button" title="Ver detalle" data-bs-toggle="modal" data-bs-target="#det-<?= (int) $s['id'] ?>"><i class="bi bi-eye"></i></button></td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
<?php foreach ($solicitudes as $s): ?>
  <div class="modal fade" id="det-<?= (int) $s['id'] ?>" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content border-0 shadow-lg rounded-4">
        <div class="modal-body p-4">
          <h3 class="h5">Solicitud #<?= (int) $s['id'] ?> — <?= e($s['mascota_nombre']) ?></h3>
          <div class="row g-2 my-3">
            <div class="col-sm-6"><div class="bg-body-secondary rounded-3 p-3"><div class="small fw-bold text-uppercase text-secondary">Solicitante</div><div class="fw-bold"><?= e($s['solicitante']) ?></div><div class="small"><?= e($s['solicitante_correo']) ?></div><div class="small"><?= e($s['solicitante_telefono'] ?? 'Teléfono no registrado') ?></div></div></div>
            <div class="col-sm-6"><div class="bg-body-secondary rounded-3 p-3"><div class="small fw-bold text-uppercase text-secondary">Vivienda</div><div class="fw-bold"><?= e($s['vivienda'] ?? 'No especificada') ?></div><div class="small"><?= !empty($s['tiene_patio']) ? 'Cuenta con patio' : 'No cuenta con patio' ?></div></div></div>
          </div>
          <p class="small"><b>Motivo de adopción:</b> <?= e($s['motivo']) ?></p>
          <?php if ($s['estado'] === 'rechazada' && !empty($s['motivo_rechazo'])): ?>
            <p class="small text-secondary"><b>Motivo de rechazo:</b> <?= e($s['motivo_rechazo']) ?></p>
          <?php endif; ?>
          <div class="d-flex justify-content-end gap-2">
            <button class="btn btn-outline-dark" type="button" data-bs-dismiss="modal">Cerrar</button>
            <?php if ($puedeResolver && $s['estado'] === 'pendiente'): ?>
              <button class="btn btn-outline-danger" type="button" data-bs-toggle="collapse" data-bs-target="#rechazo-<?= (int) $s['id'] ?>">Rechazar</button>
              <form method="post" action="<?= url('refugio/solicitud-post') ?>" onsubmit="return confirm('¿Confirmas que deseas aprobar esta solicitud? Las demás solicitudes pendientes de esta mascota serán rechazadas.');">
                <input type="hidden" name="id_solicitud" value="<?= (int) $s['id'] ?>">
                <input type="hidden" name="decision" value="aprobar">
                <button class="btn btn-success" type="submit">Aprobar adopción</button>
              </form>
            <?php endif; ?>
          </div>
          <?php if ($puedeResolver && $s['estado'] === 'pendiente'): ?>
            <div class="collapse mt-3" id="rechazo-<?= (int) $s['id'] ?>">
              <form method="post" action="<?= url('refugio/solicitud-post') ?>">
                <input type="hidden" name="id_solicitud" value="<?= (int) $s['id'] ?>">
                <input type="hidden" name="decision" value="rechazar">
                <label class="form-label fw-bold small" for="motivo-rechazo-<?= (int) $s['id'] ?>">Motivo del rechazo</label>
                <textarea class="form-control mb-2" id="motivo-rechazo-<?= (int) $s['id'] ?>" name="observaciones" rows="3" maxlength="2000" required></textarea>
                <button class="btn btn-danger" type="submit">Confirmar rechazo</button>
              </form>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
<?php endforeach; ?>
</body>
</html>
