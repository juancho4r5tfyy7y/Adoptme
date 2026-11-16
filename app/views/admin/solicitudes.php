<?php /** Vista de consulta para el administrador de la plataforma. */ ?>
<div class="d-flex justify-content-between align-items-start gap-3 flex-wrap mb-4"><div><h1 class="h2 mb-1">Solicitudes de adopción</h1><p class="text-secondary mb-0">El administrador de cada refugio toma la decisión sobre sus solicitudes</p></div></div>
<div class="card border-0 shadow-sm rounded-4 overflow-hidden"><div class="table-responsive"><table class="table table-hover mb-0 align-middle"><thead><tr><th>Solicitante</th><th>Mascota</th><th>Refugio</th><th>Fecha</th><th>Estado</th></tr></thead><tbody>
<?php foreach ($solicitudes as $s): ?>
<tr><td><?= e($s['solicitante']) ?></td><td><?= e($s['mascota_nombre']) ?></td><td><?= e($s['refugio_nombre']) ?></td><td><?= e($s['fecha']) ?></td><td><?= badge($s['estado']) ?></td>
  </tr><?php endforeach; ?></tbody></table></div></div>
