<?php /** Aprobar o rechazar solicitudes (el rechazo exige motivo). */ ?>
<div class="d-flex justify-content-between align-items-start gap-3 flex-wrap mb-4"><div><h1 class="h2 mb-1">Gestión de solicitudes</h1><p class="text-secondary mb-0">Aprueba o rechaza las solicitudes de adopción</p></div></div>
<div class="card border-0 shadow-sm rounded-4 overflow-hidden"><div class="table-responsive"><table class="table table-hover mb-0 align-middle"><thead><tr><th>Solicitante</th><th>Mascota</th><th>Refugio</th><th>Fecha</th><th>Estado</th><th>Acciones</th></tr></thead><tbody>
<?php foreach ($solicitudes as $s): $p = Mascota::buscar($s['mascota_id']); $r = DatosDemo::porId(DatosDemo::refugios(), $s['refugio_id']); ?>
<tr><td><?= e($s['solicitante']) ?></td><td><?= e($p['nombre']) ?></td><td><?= e($r['nombre']) ?></td><td><?= e($s['fecha']) ?></td><td><?= badge($s['estado']) ?></td>
  <td><div class="d-flex gap-2">
    <button class="icon-btn" title="Ver motivo" data-msg="<?= e($s['motivo']) ?>"><i class="bi bi-eye"></i></button>
    <?php if ($s['estado'] === 'pendiente'): ?>
    <button class="icon-btn acciones-pend" title="Aprobar" data-badge="aprobada" data-confirm-title="Aprobar solicitud" data-confirm="La mascota pasará a «adoptado» y las demás solicitudes pendientes se rechazarán." data-toast="Solicitud aprobada y adopción registrada"><i class="bi bi-check-lg"></i></button>
    <button class="icon-btn acciones-pend" title="Rechazar" data-badge="rechazada" data-confirm-title="Rechazar solicitud" data-confirm="Indica el motivo. El solicitante será notificado." data-confirm-input="Ej. Espacio insuficiente para la mascota..." data-toast="Solicitud rechazada"><i class="bi bi-x-lg"></i></button><?php endif; ?>
  </div></td></tr><?php endforeach; ?></tbody></table></div></div>
