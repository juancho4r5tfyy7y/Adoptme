<?php /** Perfil institucional del refugio. */ ?>
<div class="page-head"><div><h1>Perfil del refugio</h1><p class="text-taupe mb-0">Información institucional y de contacto</p></div></div>
<div class="row g-3">
  <div class="col-lg-6"><form class="card-adopt" data-demo data-toast="Información institucional actualizada" novalidate><div class="section-title-sm">Datos institucionales</div><div class="row g-3">
    <div class="col-12"><label class="form-label">Nombre de la organización</label><input class="form-control" required value="<?= e($r['nombre']) ?>"></div>
    <div class="col-sm-6"><label class="form-label">Tipo</label><select class="form-select"><?php foreach (['Fundación', 'Albergue', 'Rescatista independiente'] as $t): ?><option <?= $r['tipo'] === $t ? 'selected' : '' ?>><?= $t ?></option><?php endforeach; ?></select></div>
    <div class="col-sm-6"><label class="form-label">Año de fundación</label><input class="form-control" value="<?= e($r['anio']) ?>"></div>
    <div class="col-sm-6"><label class="form-label">Ciudad</label><input class="form-control" required value="<?= e($r['ciudad']) ?>"></div>
    <div class="col-sm-6"><label class="form-label">Teléfono</label><input class="form-control" required value="<?= e($r['telefono']) ?>"></div>
    <div class="col-12"><button class="btn btn-primary">Guardar cambios</button></div></div></form></div>
  <div class="col-lg-6"><div class="card-adopt"><form data-demo data-toast="Contacto actualizado" novalidate><div class="section-title-sm">Persona de contacto</div>
    <div class="mb-3"><label class="form-label">Nombre</label><input class="form-control" required value="<?= e($r['contacto']) ?>"></div>
    <div class="mb-3"><label class="form-label">Cargo</label><input class="form-control" value="<?= e($r['cargo']) ?>"></div>
    <div class="mb-3"><label class="form-label">Correo</label><input type="email" class="form-control" required value="<?= e($r['correo']) ?>"></div>
    <button class="btn btn-outline-dark">Actualizar contacto</button></form><hr class="my-4">
    <div class="section-title-sm">Eliminar cuenta del refugio</div><p class="small text-taupe">No podrás eliminarla si tienes solicitudes pendientes.</p>
    <button class="btn btn-dark" data-msg="No puedes eliminar la cuenta: tienes solicitudes pendientes"><i class="bi bi-trash me-1"></i>Eliminar cuenta</button></div></div>
</div>
