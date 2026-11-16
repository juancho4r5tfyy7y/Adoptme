<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard del refugio</title>
  <link href="<?= asset('vendor/bootstrap-5.3.9-dist/css/bootstrap.min.css') ?>" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link href="<?= asset('css/adoptme.css') ?>" rel="stylesheet">
  <link href="<?= asset('css/components.css') ?>" rel="stylesheet">
  <link href="<?= asset('css/pages.css') ?>" rel="stylesheet">
</head>
<body>
<?php /** Resumen del refugio. */ $c = fn($e) => count(array_filter($mascotas, fn($m) => $m['estado'] === $e)); ?>
<div class="d-flex justify-content-between align-items-start gap-3 flex-wrap mb-4"><div><h1 class="h2 mb-1">Panel del refugio</h1><p class="text-secondary mb-0">Resumen de actividad</p></div><a class="btn btn-primary" href="<?= url('refugio/mascota-form') ?>"><i class="bi bi-plus-lg me-1"></i>Registrar mascota</a></div>
<div class="row g-3 mb-4"><?php foreach ([[count($mascotas),'Mascotas registradas',''],[$c('disponible'),'Disponibles',''],[count(array_filter($solicitudes, fn($s) => $s['estado'] === 'pendiente')),'Solicitudes pendientes',''],[$c('adoptado'),'Adopciones logradas','accent']] as [$n,$l,$a]): ?>
  <div class="col-6 col-lg-3"><div class="card border-0 shadow-sm rounded-4 p-3 <?= $a === 'accent' ? 'bg-dark text-white' : '' ?>"><div class="display-6 fw-bold mb-1"><?= $n ?></div><div class="small fw-bold <?= $a === 'accent' ? 'text-white-50' : 'text-secondary' ?>"><?= $l ?></div></div></div><?php endforeach; ?></div>
<div class="row g-3">
  <div class="col-lg-6"><div class="card border-0 shadow-sm rounded-4 p-4"><div class="small text-uppercase fw-bold text-secondary mb-3">Últimas solicitudes</div>
    <?php foreach ($solicitudes as $s): $p = Mascota::buscar($s['mascota_id']); ?><div class="d-flex justify-content-between align-items-center py-2 border-bottom"><span><?= e($s['solicitante']) ?> → <?= e($p['nombre'] ?? 'Mascota no encontrada') ?></span><?= badge($s['estado']) ?></div><?php endforeach; ?>
    <a class="btn btn-sm btn-outline-dark mt-3" href="<?= url('refugio/solicitudes') ?>">Ver todas</a></div></div>
  <div class="col-lg-6"><div class="card border-0 shadow-sm rounded-4 p-4"><div class="small text-uppercase fw-bold text-secondary mb-3">Mis mascotas</div>
    <?php foreach (array_slice($mascotas, 0, 4) as $m): ?><div class="d-flex justify-content-between align-items-center py-2 border-bottom"><span><?= e($m['nombre']) ?></span><?= badge($m['estado']) ?></div><?php endforeach; ?>
    <a class="btn btn-sm btn-outline-dark mt-3" href="<?= url('refugio/mascotas') ?>">Gestionar</a></div></div>
</div>
</body>
</html>
