<?php /** Gestión de refugios. */ ?>
<div class="page-head"><div><h1>Gestión de refugios</h1><p class="text-taupe mb-0">Refugios y rescatistas registrados</p></div></div>
<div class="table-adopt table-responsive"><table class="table mb-0 align-middle"><thead><tr><th>Refugio</th><th>Ciudad</th><th>Tipo</th><th>Publicadas</th><th>Estado</th><th>Acción</th></tr></thead><tbody>
<?php foreach ($refugios as $r): $n = count(array_filter($mascotas, fn($m) => $m['refugio_id'] == $r['id'] && $m['pub'] === 'publicado')); ?>
<tr><td><b><?= e($r['nombre']) ?></b></td><td><?= e($r['ciudad']) ?></td><td><?= e($r['tipo']) ?></td><td><?= $n ?></td><td><?= badge($r['estado']) ?></td>
  <td><button class="btn btn-sm btn-outline-dark" data-confirm="¿Suspender o activar a <?= e($r['nombre']) ?>?" data-confirm-title="Cambiar estado del refugio" data-toast="Estado del refugio actualizado">Suspender</button></td></tr><?php endforeach; ?></tbody></table></div>
