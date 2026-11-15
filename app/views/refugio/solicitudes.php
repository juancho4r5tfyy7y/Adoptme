<?php /** Solicitudes recibidas (solo lectura: la decisión la toma el administrador). */ ?>
<div class="d-flex justify-content-between align-items-start gap-3 flex-wrap mb-4"><div><h1 class="h2 mb-1">Solicitudes recibidas</h1><p class="text-secondary mb-0">Adoptantes interesados en tus mascotas</p></div></div>
<div class="card border-0 shadow-sm rounded-4 overflow-hidden"><div class="table-responsive"><table class="table table-hover mb-0 align-middle"><thead><tr><th>Solicitante</th><th>Mascota</th><th>Fecha</th><th>Estado</th><th>Acciones</th></tr></thead><tbody>
<?php foreach ($solicitudes as $s): $p = Mascota::buscar($s['mascota_id']); ?><tr><td><?= e($s['solicitante']) ?></td><td><?= e($p['nombre'] ?? 'Mascota no encontrada') ?></td><td><?= e($s['fecha']) ?></td><td><?= badge($s['estado']) ?></td>
  <td><button class="icon-btn" title="Ver detalle" data-bs-toggle="modal" data-bs-target="#det-<?= $s['id'] ?>"><i class="bi bi-eye"></i></button></td></tr><?php endforeach; ?></tbody></table></div></div>
<p class="small text-secondary mt-3">La aprobación o el rechazo lo gestiona el administrador de la plataforma.</p>
<?php foreach ($solicitudes as $s): $p = Mascota::buscar($s['mascota_id']); ?>
<div class="modal fade" id="det-<?= $s['id'] ?>" tabindex="-1"><div class="modal-dialog modal-dialog-centered"><div class="modal-content border-0 shadow-lg rounded-4"><div class="modal-body p-4">
  <h3 class="h5">Solicitud #<?= $s['id'] ?> — <?= e($p['nombre'] ?? 'Mascota no encontrada') ?></h3>
  <div class="row g-2 my-3"><div class="col-6"><div class="bg-body-secondary rounded-3 p-3"><div class="small fw-bold text-uppercase text-secondary text-micro">Solicitante</div><div class="fw-bold"><?= e($s['solicitante']) ?></div></div></div><div class="col-6"><div class="bg-body-secondary rounded-3 p-3"><div class="small fw-bold text-uppercase text-secondary text-micro">Vivienda</div><div class="fw-bold"><?= e($s['vivienda']) ?></div></div></div></div>
  <p class="small"><b>Motivo:</b> <?= e($s['motivo']) ?></p><div class="text-end"><button class="btn btn-outline-dark" data-bs-dismiss="modal">Cerrar</button></div></div></div></div></div>
<?php endforeach; ?>
