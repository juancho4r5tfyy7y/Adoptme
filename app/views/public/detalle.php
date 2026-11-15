<?php /** Detalle de una mascota. $m = mascota, $refugio = refugio responsable. */
$disp = $m['estado'] === 'disponible'; ?>
<div class="<?= $user ? '' : 'container-xl pt-4' ?>">
  <nav class="small fw-bold text-secondary mb-3"><a href="<?= url('catalogo') ?>">Catálogo</a> / <span class="text-dark"><?= e($m['nombre']) ?></span></nav>
  <div class="row g-4">
    <div class="col-lg-6"><div class="detail-photo"><?= mascota_img($m) ?></div></div>
    <div class="col-lg-6">
      <div class="d-flex justify-content-between align-items-start"><div><h1><?= e($m['nombre']) ?></h1><p class="text-secondary"><?= e($m['raza']) ?> · <?= e($refugio['nombre'] ?? '') ?> · <?= e($refugio['ciudad'] ?? '') ?></p></div>
        <button class="icon-btn pet-fav position-static" data-id="<?= $m['id'] ?>" <?= !$user ? 'data-login="' . url('login') . '"' : '' ?> aria-label="Favorito"><i class="bi bi-heart"></i></button></div>
      <?= badge($m['estado']) ?><p class="mt-3"><?= e($m['desc']) ?></p>
      <div class="row g-2 my-3"><?php foreach (['Edad' => $m['edad'], 'Tamaño' => $m['tamano'], 'Sexo' => $m['sexo'], 'Estado de salud' => $m['salud']] as $k => $v): ?>
        <div class="col-6"><div class="bg-body-secondary rounded-3 p-3"><div class="small fw-bold text-uppercase text-secondary text-micro"><?= $k ?></div><div class="fw-bold"><?= e($v) ?></div></div></div><?php endforeach; ?></div>
      <div class="d-flex flex-wrap gap-2">
        <?php foreach (['Vacunado' => 'vacunado', 'Esterilizado' => 'esterilizado', 'Niños' => 'ninos', 'Perros' => 'perros', 'Gatos' => 'gatos'] as $et => $k): ?>
          <span class="badge rounded-pill <?= $m[$k] ? 'text-bg-warning-subtle text-primary-emphasis' : 'text-bg-secondary' ?> px-3 py-2 fw-bold d-inline-flex align-items-center gap-1"><i class="bi bi-<?= $m[$k] ? 'check' : 'x' ?>-lg"></i><?= $et ?></span><?php endforeach; ?></div>
      <div class="d-flex gap-2 flex-wrap mt-4">
        <?php if ($disp): ?><a class="btn btn-primary" href="<?= $user && $user['rol'] === 'usuario' ? url('solicitud', ['id' => $m['id']]) : url('login') ?>">Solicitar adopción</a>
        <?php else: ?><button class="btn btn-outline-dark" disabled>No disponible actualmente</button><?php endif; ?>
        <button class="btn btn-outline-dark" data-bs-toggle="modal" data-bs-target="#contacto"><i class="bi bi-chat-dots me-1"></i>Contactar refugio</button>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="contacto" tabindex="-1"><div class="modal-dialog modal-dialog-centered"><form class="modal-content border-0 shadow-lg rounded-4" data-demo data-toast="Mensaje enviado al refugio" novalidate>
  <div class="modal-body p-4"><h3 class="h5">Contactar a <?= e($refugio['nombre'] ?? 'el refugio') ?></h3>
    <textarea class="form-control my-3" rows="4" required placeholder="Hola, quisiera más información sobre <?= e($m['nombre']) ?>..."></textarea>
    <div class="d-flex justify-content-end gap-2"><button type="button" class="btn btn-outline-dark" data-bs-dismiss="modal">Cancelar</button><button class="btn btn-primary" data-bs-dismiss="modal">Enviar mensaje</button></div></div></form></div></div>
