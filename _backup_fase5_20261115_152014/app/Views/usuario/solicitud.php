<?php /** Formulario de adopción responsable (validación visual). */ ?>
<nav class="small fw-bold text-taupe mb-3"><a href="<?= url('mascota', ['id' => $m['id']]) ?>"><?= e($m['nombre']) ?></a> / <span class="text-dark">Solicitud de adopción</span></nav>
<div class="page-head"><div><h1>Adoptar a <?= e($m['nombre']) ?></h1><p class="text-taupe mb-0">Completa el formulario de adopción responsable.</p></div></div>
<form class="card-adopt" style="max-width:660px" data-demo data-toast="¡Solicitud enviada con éxito!" data-redirect="<?= url('seguimiento') ?>" novalidate>
  <div class="section-title-sm">Sobre tu vivienda</div>
  <div class="row g-3 mb-2">
    <div class="col-sm-6"><label class="form-label">Tipo de vivienda</label><select class="form-select" required><option>Casa</option><option>Apartamento</option></select></div>
    <div class="col-sm-6"><label class="form-label">Tamaño aproximado</label><input class="form-control" placeholder="Ej. 80 m²" required></div>
    <div class="col-12"><label class="form-check"><input type="checkbox" class="form-check-input"> Mi vivienda tiene patio o espacio exterior</label></div></div>
  <div class="section-title-sm mt-4">Motivo de adopción</div>
  <textarea class="form-control mb-2" rows="4" required minlength="20" placeholder="Cuéntale al refugio por qué quieres adoptar y qué experiencia tienes (mín. 20 caracteres)"></textarea><div class="invalid-feedback">Cuéntanos tu motivo (mínimo 20 caracteres).</div>
  <div class="section-title-sm mt-4">Compromisos de cuidado responsable</div>
  <?php foreach (['Me comprometo a brindar alimentación, atención veterinaria y espacio adecuado.', 'Entiendo que la adopción es una responsabilidad a largo plazo.', 'Autorizo una visita de seguimiento del refugio si es necesario.'] as $c): ?>
    <label class="form-check mb-2"><input type="checkbox" class="form-check-input" required> <?= $c ?></label><?php endforeach; ?>
  <div class="d-flex gap-2 mt-4"><a class="btn btn-outline-dark" href="<?= url('mascota', ['id' => $m['id']]) ?>">Cancelar</a><button class="btn btn-primary">Enviar solicitud</button></div>
</form>
