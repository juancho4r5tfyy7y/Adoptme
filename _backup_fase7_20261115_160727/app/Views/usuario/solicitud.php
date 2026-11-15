<?php /** Formulario de adopción responsable (validación visual). */ ?>
<nav class="small fw-bold text-secondary mb-3"><a href="<?= url('mascota', ['id' => $m['id']]) ?>" class="text-decoration-none"><?= e($m['nombre']) ?></a> / <span class="text-dark">Solicitud de adopción</span></nav>
<div class="d-flex justify-content-between align-items-start gap-3 flex-wrap mb-4"><div><h1 class="h2 mb-1">Adoptar a <?= e($m['nombre']) ?></h1><p class="text-secondary mb-0">Completa el formulario de adopción responsable.</p></div></div>
<form class="card border-0 shadow-sm rounded-4 p-4" style="max-width:660px" data-demo data-toast="¡Solicitud enviada con éxito!" data-redirect="<?= url('seguimiento') ?>" novalidate>
  <div class="small text-uppercase fw-bold text-secondary mb-3">Sobre tu vivienda</div>
  <div class="row g-3 mb-2">
    <div class="col-sm-6"><label class="form-label fw-bold small">Tipo de vivienda</label><select class="form-select" required><option>Casa</option><option>Apartamento</option></select></div>
    <div class="col-sm-6"><label class="form-label fw-bold small">Tamaño aproximado</label><input class="form-control" placeholder="Ej. 80 m²" required><div class="invalid-feedback">Indica el tamaño aproximado.</div></div>
    <div class="col-12"><label class="form-check"><input type="checkbox" class="form-check-input"> Mi vivienda tiene patio o espacio exterior</label></div></div>
  <div class="small text-uppercase fw-bold text-secondary mb-3 mt-4">Motivo de adopción</div>
  <textarea class="form-control mb-2" rows="4" required minlength="20" placeholder="Cuéntale al refugio por qué quieres adoptar y qué experiencia tienes (mín. 20 caracteres)"></textarea><div class="invalid-feedback">Cuéntanos tu motivo (mínimo 20 caracteres).</div>
  <div class="small text-uppercase fw-bold text-secondary mb-3 mt-4">Compromisos de cuidado responsable</div>
  <?php foreach (['Me comprometo a brindar alimentación, atención veterinaria y espacio adecuado.', 'Entiendo que la adopción es una responsabilidad a largo plazo.', 'Autorizo una visita de seguimiento del refugio si es necesario.'] as $c): ?>
    <label class="form-check mb-2"><input type="checkbox" class="form-check-input" required> <?= $c ?></label><?php endforeach; ?>
  <div class="d-flex gap-2 mt-4"><a class="btn btn-outline-dark" href="<?= url('mascota', ['id' => $m['id']]) ?>">Cancelar</a><button class="btn btn-primary">Enviar solicitud</button></div>
</form>
