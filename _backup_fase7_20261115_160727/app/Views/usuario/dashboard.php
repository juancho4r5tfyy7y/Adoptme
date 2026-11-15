<?php /** Resumen del adoptante. */ $pend = count(array_filter($solicitudes, fn($s) => $s['estado'] === 'pendiente')); $ok = count(array_filter($solicitudes, fn($s) => $s['estado'] === 'aprobada')); ?>
<div class="d-flex justify-content-between align-items-start gap-3 flex-wrap mb-4"><div><h1 class="h2 mb-1">Hola, <?= e(explode(' ', $user['nombre'])[0]) ?></h1><p class="text-secondary mb-0">Resumen de tu actividad en ADOPT ME</p></div><a class="btn btn-primary" href="<?= url('catalogo') ?>">Buscar mascotas</a></div>
<div class="row g-3 mb-4">
  <?php foreach ([['2','Favoritos guardados',''],[count($solicitudes),'Solicitudes enviadas',''],[$pend,'En revisión',''],[$ok,'Adopciones logradas','accent']] as [$n,$l,$c]): ?>
  <div class="col-6 col-lg-3"><div class="card border-0 shadow-sm rounded-4 p-3 <?= $c === 'accent' ? 'bg-dark text-white' : '' ?>"><div class="display-6 fw-bold mb-1"><?= $n ?></div><div class="small fw-bold <?= $c === 'accent' ? 'text-white-50' : 'text-secondary' ?>"><?= $l ?></div></div></div><?php endforeach; ?>
</div>
<div class="row g-3">
  <div class="col-lg-6"><div class="card border-0 shadow-sm rounded-4 p-4"><div class="small text-uppercase fw-bold text-secondary mb-3">Estado de tus solicitudes</div>
    <?php foreach ($solicitudes as $s): $p = Mascota::buscar($s['mascota_id']); ?><div class="d-flex justify-content-between align-items-center py-2 border-bottom"><span><?= e($p['nombre'] ?? '—') ?></span><?= badge($s['estado']) ?></div><?php endforeach; ?>
    <a class="btn btn-sm btn-outline-dark mt-3" href="<?= url('seguimiento') ?>">Ver todas</a></div></div>
  <div class="col-lg-6"><div class="card border-0 shadow-sm rounded-4 p-4"><div class="small text-uppercase fw-bold text-secondary mb-3">Recomendadas para ti</div>
    <?php foreach ($recomendadas as $p): ?><div class="d-flex justify-content-between align-items-center py-2 border-bottom"><span><?= e($p['nombre']) ?> · <?= e($p['raza']) ?></span><a class="fw-bold small text-decoration-none" href="<?= url('mascota', ['id' => $p['id']]) ?>">Ver</a></div><?php endforeach; ?></div></div>
</div>
