<?php /** Resumen del refugio. */ $c = fn($e) => count(array_filter($mascotas, fn($m) => $m['estado'] === $e)); ?>
<div class="page-head"><div><h1>Panel del refugio</h1><p class="text-taupe mb-0">Resumen de actividad</p></div><a class="btn btn-primary" href="<?= url('refugio/mascota-form') ?>"><i class="bi bi-plus-lg me-1"></i>Registrar mascota</a></div>
<div class="row g-3 mb-4"><?php foreach ([[count($mascotas),'Mascotas registradas',''],[$c('disponible'),'Disponibles',''],[count(array_filter($solicitudes, fn($s) => $s['estado'] === 'pendiente')),'Solicitudes pendientes',''],[$c('adoptado'),'Adopciones logradas','accent']] as [$n,$l,$a]): ?>
  <div class="col-6 col-lg-3"><div class="stat-card <?= $a ?>"><div class="num"><?= $n ?></div><div class="lbl"><?= $l ?></div></div></div><?php endforeach; ?></div>
<div class="row g-3">
  <div class="col-lg-6"><div class="card-adopt"><div class="section-title-sm">Últimas solicitudes</div>
    <?php foreach ($solicitudes as $s): $p = Mascota::buscar($s['mascota_id']); ?><div class="list-line"><span><?= e($s['solicitante']) ?> → <?= e($p['nombre']) ?></span><?= badge($s['estado']) ?></div><?php endforeach; ?>
    <a class="btn btn-sm btn-outline-dark mt-3" href="<?= url('refugio/solicitudes') ?>">Ver todas</a></div></div>
  <div class="col-lg-6"><div class="card-adopt"><div class="section-title-sm">Mis mascotas</div>
    <?php foreach (array_slice($mascotas, 0, 4) as $m): ?><div class="list-line"><span><?= e($m['nombre']) ?></span><?= badge($m['estado']) ?></div><?php endforeach; ?>
    <a class="btn btn-sm btn-outline-dark mt-3" href="<?= url('refugio/mascotas') ?>">Gestionar</a></div></div>
</div>
