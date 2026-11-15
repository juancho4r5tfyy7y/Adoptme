<?php /** Notificaciones del rol actual. */ ?>
<div class="page-head"><div><h1>Notificaciones</h1><p class="text-taupe mb-0">Mantente al día con tu actividad</p></div><button class="btn btn-outline-dark btn-sm" id="leer-todas">Marcar todas como leídas</button></div>
<div class="card-adopt p-2"><?php foreach ($lista as [$t, $b, $f, $leida, $ic]): ?>
  <div class="notif <?= $leida ? '' : 'unread' ?>"><div class="ic"><i class="bi bi-<?= $ic ?>"></i></div><div><b><?= e($t) ?></b><p class="small text-taupe mb-0"><?= e($b) ?></p><small class="text-taupe"><?= e($f) ?></small></div></div>
<?php endforeach; if (!$lista): ?><p class="text-center text-taupe py-4 mb-0">No tienes notificaciones.</p><?php endif; ?></div>
