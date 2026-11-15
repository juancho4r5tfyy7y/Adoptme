<?php /** Panel general del administrador. */ $pend = array_filter($solicitudes, fn($s) => $s['estado'] === 'pendiente'); $c = fn($e) => count(array_filter($mascotas, fn($m) => $m['estado'] === $e)); ?>
<div class="page-head"><div><h1>Panel de administración</h1><p class="text-taupe mb-0">Estadísticas generales de la plataforma</p></div></div>
<div class="row g-3 mb-4"><?php foreach ([[count($mascotas),'Mascotas registradas',''],[$c('disponible'),'Disponibles',''],[$c('adoptado'),'Adoptadas',''],[count($pend),'Solicitudes pendientes','accent']] as [$n,$l,$a]): ?>
  <div class="col-6 col-lg-3"><div class="stat-card <?= $a ?>"><div class="num"><?= $n ?></div><div class="lbl"><?= $l ?></div></div></div><?php endforeach; ?></div>
<div class="row g-3">
  <div class="col-lg-6"><div class="card-adopt"><div class="section-title-sm">Usuarios y refugios</div>
    <div class="list-line"><span>Usuarios registrados</span><b><?= $nUsuarios ?></b></div><div class="list-line"><span>Refugios activos</span><b><?= $nRefugios ?></b></div><div class="list-line"><span>Solicitudes totales</span><b><?= count($solicitudes) ?></b></div>
    <a class="btn btn-sm btn-outline-dark mt-3" href="<?= url('admin/reportes') ?>">Ver reportes</a></div></div>
  <div class="col-lg-6"><div class="card-adopt"><div class="section-title-sm">Pendientes por revisar</div>
    <?php foreach ($pend as $s): $p = Mascota::buscar($s['mascota_id']); ?><div class="list-line"><span><?= e($s['solicitante']) ?> → <?= e($p['nombre']) ?></span><a class="fw-bold small" href="<?= url('admin/solicitudes') ?>">Revisar</a></div><?php endforeach; ?></div></div>
</div>
