<?php /** Perfil institucional del refugio. */ ?>
<div class="d-flex justify-content-between align-items-start gap-3 flex-wrap mb-4"><div><h1 class="h2 mb-1">Perfil del refugio</h1><p class="text-secondary mb-0">Información institucional y de contacto</p></div></div>
<div class="row g-3">
  <div class="col-lg-6"><form class="card border-0 shadow-sm rounded-4 p-4" data-demo data-toast="Información institucional actualizada" novalidate><div class="small text-uppercase fw-bold text-secondary mb-3">Datos institucionales</div><div class="row g-3">
    <div class="col-12"><label class="form-label fw-bold small">Nombre de la organización</label><input class="form-control" required value="<?= e($r['nombre']) ?>"><div class="invalid-feedback">El nombre es obligatorio.</div></div>
    <div class="col-sm-6"><label class="form-label fw-bold small">Tipo</label><select class="form-select"><?php foreach (['Fundación', 'Albergue', 'Rescatista independiente'] as $t): ?><option <?= $r['tipo'] === $t ? 'selected' : '' ?>><?= $t ?></option><?php endforeach; ?></select></div>
    <div class="col-sm-6"><label class="form-label fw-bold small">Año de fundación</label><input class="form-control" value="<?= e($r['anio']) ?>"></div>
    <div class="col-sm-6"><label class="form-label fw-bold small">Ciudad</label><input class="form-control" required value="<?= e($r['ciudad']) ?>"><div class="invalid-feedback">La ciudad es obligatoria.</div></div>
    <div class="col-sm-6"><label class="form-label fw-bold small">Teléfono</label><input class="form-control" required value="<?= e($r['telefono']) ?>"><div class="invalid-feedback">El teléfono es obligatorio.</div></div>
    <div class="col-12"><button class="btn btn-primary">Guardar cambios</button></div></div></form></div>
  <div class="col-lg-6"><div class="card border-0 shadow-sm rounded-4 p-4"><form data-demo data-toast="Contacto actualizado" novalidate><div class="small text-uppercase fw-bold text-secondary mb-3">Persona de contacto</div>
    <div class="mb-3"><label class="form-label fw-bold small">Nombre</label><input class="form-control" required value="<?= e($r['contacto']) ?>"><div class="invalid-feedback">El nombre de contacto es obligatorio.</div></div>
    <div class="mb-3"><label class="form-label fw-bold small">Cargo</label><input class="form-control" value="<?= e($r['cargo']) ?>"></div>
    <div class="mb-3"><label class="form-label fw-bold small">Correo</label><input type="email" class="form-control" required value="<?= e($r['correo']) ?>"><div class="invalid-feedback">Ingresa un correo válido.</div></div>
    <button class="btn btn-outline-dark">Actualizar contacto</button></form><hr class="my-4">
    <div class="small text-uppercase fw-bold text-secondary mb-3">Eliminar cuenta del refugio</div><p class="small text-secondary">No podrás eliminarla si tienes solicitudes pendientes.</p>
    <button class="btn btn-dark" data-msg="No puedes eliminar la cuenta: tienes solicitudes pendientes"><i class="bi bi-trash me-1"></i>Eliminar cuenta</button></div></div>
</div>
