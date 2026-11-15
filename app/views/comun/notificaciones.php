<?php /** Notificaciones del rol actual. */ ?>
<div class="d-flex justify-content-between align-items-start gap-3 flex-wrap mb-4"><div><h1 class="h2 mb-1">Notificaciones</h1><p class="text-secondary mb-0">Mantente al día con tu actividad</p></div><button class="btn btn-outline-dark btn-sm" id="leer-todas">Marcar todas como leídas</button></div>
<div class="card border-0 shadow-sm rounded-4 p-3"><?php foreach ($lista as [$t, $b, $f, $leida, $ic]): ?>
  <div class="notif <?= $leida ? '' : 'unread' ?>"><div class="ic"><i class="bi bi-<?= $ic ?>"></i></div><div><b><?= e($t) ?></b><p class="small text-secondary mb-0"><?= e($b) ?></p><small class="text-secondary"><?= e($f) ?></small></div></div>
<?php endforeach; if (!$lista): ?><p class="text-center text-secondary py-4 mb-0">No tienes notificaciones.</p><?php endif; ?></div>
