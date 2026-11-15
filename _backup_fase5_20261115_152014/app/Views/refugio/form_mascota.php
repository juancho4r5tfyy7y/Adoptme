<?php /** Alta/edición de mascota. $m = null cuando es nueva. */
$v = fn($k, $d = '') => e($m[$k] ?? $d); $sel = fn($k, $o) => ($m[$k] ?? '') === $o ? 'selected' : ''; $chk = fn($k) => !empty($m[$k]) ? 'checked' : ''; ?>
<nav class="small fw-bold text-taupe mb-3"><a href="<?= url('refugio/mascotas') ?>">Gestión de mascotas</a> / <span class="text-dark"><?= $m ? 'Editar' : 'Nueva mascota' ?></span></nav>
<div class="page-head"><div><h1><?= $m ? 'Editar mascota' : 'Registrar nueva mascota' ?></h1><p class="text-taupe mb-0">Completa los campos obligatorios antes de guardar</p></div></div>
<form data-demo data-toast="Mascota guardada" data-redirect="<?= url('refugio/mascotas') ?>" novalidate><div class="row g-3">
  <div class="col-lg-6"><div class="card-adopt h-100"><div class="section-title-sm">Información básica</div><div class="row g-3">
    <div class="col-12"><label class="form-label">Nombre</label><input class="form-control" required value="<?= $v('nombre') ?>"></div>
    <div class="col-sm-6"><label class="form-label">Especie</label><select class="form-select"><?php foreach (['Perro', 'Gato', 'Conejo'] as $o): ?><option <?= $sel('especie', $o) ?>><?= $o ?></option><?php endforeach; ?></select></div>
    <div class="col-sm-6"><label class="form-label">Raza</label><input class="form-control" required value="<?= $v('raza') ?>"></div>
    <div class="col-sm-6"><label class="form-label">Edad</label><input class="form-control" required placeholder="Ej. 2 años" value="<?= $v('edad') ?>"></div>
    <div class="col-sm-6"><label class="form-label">Sexo</label><select class="form-select"><?php foreach (['Macho', 'Hembra'] as $o): ?><option <?= $sel('sexo', $o) ?>><?= $o ?></option><?php endforeach; ?></select></div>
    <div class="col-12"><label class="form-label">Tamaño</label><select class="form-select"><?php foreach (['Pequeño', 'Mediano', 'Grande'] as $o): ?><option <?= $sel('tamano', $o) ?>><?= $o ?></option><?php endforeach; ?></select></div>
    <div class="col-12"><label class="form-label">Descripción</label><textarea class="form-control" rows="3" required><?= $v('desc') ?></textarea></div>
    <div class="col-12"><label class="form-label">Fotografías</label><div class="dashed-drop"><i class="bi bi-image fs-4"></i><div>Imágenes JPG o PNG (máx. 5 MB c/u)</div><input type="file" class="form-control mt-2" accept="image/jpeg,image/png" multiple></div></div>
  </div></div></div>
  <div class="col-lg-6"><div class="card-adopt h-100"><div class="section-title-sm">Salud</div>
    <label class="form-check mb-2"><input type="checkbox" class="form-check-input" <?= $chk('vacunado') ?>> Vacunado</label>
    <label class="form-check mb-3"><input type="checkbox" class="form-check-input" <?= $chk('esterilizado') ?>> Esterilizado</label>
    <label class="form-label">Estado de salud general</label><input class="form-control mb-4" value="<?= $v('salud') ?>" placeholder="Ej. Excelente">
    <div class="section-title-sm">Compatibilidad</div>
    <?php foreach (['ninos' => 'niños', 'perros' => 'perros', 'gatos' => 'gatos'] as $k => $l): ?><label class="form-check mb-2"><input type="checkbox" class="form-check-input" <?= $chk($k) ?>> Compatible con <?= $l ?></label><?php endforeach; ?>
    <div class="d-grid gap-2 mt-4"><button type="button" class="btn btn-outline-dark" data-msg="Borrador guardado">Guardar como borrador</button><button class="btn btn-primary"><?= $m ? 'Guardar cambios' : 'Publicar mascota' ?></button></div>
  </div></div>
</div></form>
