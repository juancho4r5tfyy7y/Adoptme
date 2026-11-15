<?php /** Resumen del adoptante. */ $pend = count(array_filter($solicitudes, fn($s) => $s['estado'] === 'pendiente')); $ok = count(array_filter($solicitudes, fn($s) => $s['estado'] === 'aprobada')); ?>
<div class="page-head"><div><h1>Hola, <?= e(explode(' ', $user['nombre'])[0]) ?></h1><p class="text-taupe mb-0">Resumen de tu actividad en ADOPT ME</p></div><a class="btn btn-primary" href="<?= url('catalogo') ?>">Buscar mascotas</a></div>
<div class="row g-3 mb-4">
  <?php foreach ([['2','Favoritos guardados',''],[count($solicitudes),'Solicitudes enviadas',''],[$pend,'En revisión',''],[$ok,'Adopciones logradas','accent']] as [$n,$l,$c]): ?>
  <div class="col-6 col-lg-3"><div class="stat-card <?= $c ?>"><div class="num"><?= $n ?></div><div class="lbl"><?= $l ?></div></div></div><?php endforeach; ?>
</div>
<div class="row g-3">
  <div class="col-lg-6"><div class="card-adopt"><div class="section-title-sm">Estado de tus solicitudes</div>
    <?php foreach ($solicitudes as $s): $p = Mascota::buscar($s['mascota_id']); ?><div class="list-line"><span><?= e($p['nombre'] ?? '—') ?></span><?= badge($s['estado']) ?></div><?php endforeach; ?>
    <a class="btn btn-sm btn-outline-dark mt-3" href="<?= url('seguimiento') ?>">Ver todas</a></div></div>
  <div class="col-lg-6"><div class="card-adopt"><div class="section-title-sm">Recomendadas para ti</div>
    <?php foreach ($recomendadas as $p): ?><div class="list-line"><span><?= e($p['nombre']) ?> · <?= e($p['raza']) ?></span><a class="fw-bold small" href="<?= url('mascota', ['id' => $p['id']]) ?>">Ver</a></div><?php endforeach; ?></div></div>
</div>
