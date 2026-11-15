<?php /** Alta/edición de mascota. $m = null cuando es nueva. */
$v = fn($k, $d = '') => e($m[$k] ?? $d); $sel = fn($k, $o) => ($m[$k] ?? '') === $o ? 'selected' : ''; $chk = fn($k) => !empty($m[$k]) ? 'checked' : ''; ?>
<nav class="small fw-bold text-secondary mb-3"><a href="<?= url('refugio/mascotas') ?>" class="text-decoration-none">Gestión de mascotas</a> / <span class="text-dark"><?= $m ? 'Editar' : 'Nueva mascota' ?></span></nav>
<div class="d-flex justify-content-between align-items-start gap-3 flex-wrap mb-4"><div><h1 class="h2 mb-1"><?= $m ? 'Editar mascota' : 'Registrar nueva mascota' ?></h1><p class="text-secondary mb-0">Completa los campos obligatorios antes de guardar</p></div></div>
<form data-demo data-toast="Mascota guardada" data-redirect="<?= url('refugio/mascotas') ?>" novalidate><div class="row g-3">
  <div class="col-lg-6"><div class="card border-0 shadow-sm rounded-4 p-4 h-100"><div class="small text-uppercase fw-bold text-secondary mb-3">Información básica</div><div class="row g-3">
    <div class="col-12"><label class="form-label fw-bold small">Nombre</label><input class="form-control" required value="<?= $v('nombre') ?>"><div class="invalid-feedback">El nombre es obligatorio.</div></div>
    <div class="col-sm-6"><label class="form-label fw-bold small">Especie</label><select class="form-select"><?php foreach (['Perro', 'Gato', 'Conejo'] as $o): ?><option <?= $sel('especie', $o) ?>><?= $o ?></option><?php endforeach; ?></select></div>
    <div class="col-sm-6"><label class="form-label fw-bold small">Raza</label><input class="form-control" required value="<?= $v('raza') ?>"><div class="invalid-feedback">La raza es obligatoria.</div></div>
    <div class="col-sm-6"><label class="form-label fw-bold small">Edad</label><input class="form-control" required placeholder="Ej. 2 años" value="<?= $v('edad') ?>"><div class="invalid-feedback">La edad es obligatoria.</div></div>
    <div class="col-sm-6"><label class="form-label fw-bold small">Sexo</label><select class="form-select"><?php foreach (['Macho', 'Hembra'] as $o): ?><option <?= $sel('sexo', $o) ?>><?= $o ?></option><?php endforeach; ?></select></div>
    <div class="col-12"><label class="form-label fw-bold small">Tamaño</label><select class="form-select"><?php foreach (['Pequeño', 'Mediano', 'Grande'] as $o): ?><option <?= $sel('tamano', $o) ?>><?= $o ?></option><?php endforeach; ?></select></div>
    <div class="col-12"><label class="form-label fw-bold small">Descripción</label><textarea class="form-control" rows="3" required><?= $v('desc') ?></textarea><div class="invalid-feedback">Agrega una descripción para la mascota.</div></div>
    <div class="col-12"><label class="form-label fw-bold small">Fotografías</label><div class="rounded-3 p-4 text-center text-secondary" style="border:2px dashed var(--bs-border-color)"><i class="bi bi-image fs-4"></i><div>Imágenes JPG o PNG (máx. 5 MB c/u)</div><input type="file" class="form-control mt-2" accept="image/jpeg,image/png" multiple></div></div>
  </div></div></div>
  <div class="col-lg-6"><div class="card border-0 shadow-sm rounded-4 p-4 h-100"><div class="small text-uppercase fw-bold text-secondary mb-3">Salud</div>
    <label class="form-check mb-2"><input type="checkbox" class="form-check-input" <?= $chk('vacunado') ?>> Vacunado</label>
    <label class="form-check mb-3"><input type="checkbox" class="form-check-input" <?= $chk('esterilizado') ?>> Esterilizado</label>
    <label class="form-label fw-bold small">Estado de salud general</label><input class="form-control mb-4" value="<?= $v('salud') ?>" placeholder="Ej. Excelente">
    <div class="small text-uppercase fw-bold text-secondary mb-3">Compatibilidad</div>
    <?php foreach (['ninos' => 'niños', 'perros' => 'perros', 'gatos' => 'gatos'] as $k => $l): ?><label class="form-check mb-2"><input type="checkbox" class="form-check-input" <?= $chk($k) ?>> Compatible con <?= $l ?></label><?php endforeach; ?>
    <div class="d-grid gap-2 mt-4"><button type="button" class="btn btn-outline-dark" data-msg="Borrador guardado">Guardar como borrador</button><button class="btn btn-primary"><?= $m ? 'Guardar cambios' : 'Publicar mascota' ?></button></div>
  </div></div>
</div></form>
