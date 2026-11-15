<?php /** Solicitudes recibidas (solo lectura: la decisión la toma el administrador). */ ?>
<div class="page-head"><div><h1>Solicitudes recibidas</h1><p class="text-taupe mb-0">Adoptantes interesados en tus mascotas</p></div></div>
<div class="table-adopt table-responsive"><table class="table mb-0 align-middle"><thead><tr><th>Solicitante</th><th>Mascota</th><th>Fecha</th><th>Estado</th><th>Acciones</th></tr></thead><tbody>
<?php foreach ($solicitudes as $s): $p = Mascota::buscar($s['mascota_id']); ?><tr><td><?= e($s['solicitante']) ?></td><td><?= e($p['nombre']) ?></td><td><?= e($s['fecha']) ?></td><td><?= badge($s['estado']) ?></td>
  <td><button class="icon-btn" title="Ver detalle" data-bs-toggle="modal" data-bs-target="#det-<?= $s['id'] ?>"><i class="bi bi-eye"></i></button></td></tr><?php endforeach; ?></tbody></table></div>
<p class="small text-taupe mt-3">La aprobación o el rechazo lo gestiona el administrador de la plataforma.</p>
<?php foreach ($solicitudes as $s): $p = Mascota::buscar($s['mascota_id']); ?>
<div class="modal fade" id="det-<?= $s['id'] ?>" tabindex="-1"><div class="modal-dialog modal-dialog-centered"><div class="modal-content" style="border-radius:var(--radius)"><div class="modal-body p-4">
  <h3 class="h5">Solicitud #<?= $s['id'] ?> — <?= e($p['nombre']) ?></h3>
  <div class="row g-2 my-3"><div class="col-6"><div class="info-item"><div class="k">Solicitante</div><div class="v"><?= e($s['solicitante']) ?></div></div></div><div class="col-6"><div class="info-item"><div class="k">Vivienda</div><div class="v"><?= e($s['vivienda']) ?></div></div></div></div>
  <p class="small"><b>Motivo:</b> <?= e($s['motivo']) ?></p><div class="text-end"><button class="btn btn-outline-dark" data-bs-dismiss="modal">Cerrar</button></div></div></div></div></div>
<?php endforeach; ?>
