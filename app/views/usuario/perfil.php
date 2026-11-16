<?php /** Perfil del adoptante: datos, seguridad y cuenta. */ ?>
<div class="d-flex justify-content-between align-items-start gap-3 flex-wrap mb-4"><div><h1 class="h2 mb-1">Mi perfil</h1><p class="text-secondary mb-0">Gestiona tu información personal y tu cuenta</p></div></div>
<ul class="nav nav-tabs mb-4"><?php foreach (['info' => 'Información personal', 'seg' => 'Seguridad', 'cta' => 'Cuenta'] as $k => $l): ?><li class="nav-item"><button class="nav-link <?= $k === 'info' ? 'active' : '' ?>" data-bs-toggle="tab" data-bs-target="#p-<?= $k ?>"><?= $l ?></button></li><?php endforeach; ?></ul>
<div class="tab-content content-max-600">
  <div class="tab-pane fade show active" id="p-info"><form class="card border-0 shadow-sm rounded-4 p-4" method="post" action="<?= url('perfil') ?>"><div class="row g-3">
    <div class="col-sm-6"><label class="form-label fw-bold small">Nombre</label><input class="form-control" value="María" required><div class="invalid-feedback">El nombre es obligatorio.</div></div>
    <div class="col-sm-6"><label class="form-label fw-bold small">Apellido</label><input class="form-control" value="Gómez" required><div class="invalid-feedback">El apellido es obligatorio.</div></div>
    <div class="col-sm-6"><label class="form-label fw-bold small">Correo</label><input class="form-control" value="<?= e($user['email']) ?>" disabled></div>
    <div class="col-sm-6"><label class="form-label fw-bold small">Teléfono</label><input class="form-control" value="(300) 111 2233" required><div class="invalid-feedback">El teléfono es obligatorio.</div></div>
    <div class="col-12"><label class="form-label fw-bold small">Dirección</label><input class="form-control" value="Calle 10 # 45-20, Medellín" required><div class="invalid-feedback">La dirección es obligatoria.</div></div>
    <div class="col-12"><button class="btn btn-primary">Guardar cambios</button></div></div></form></div>
  <div class="tab-pane fade" id="p-seg"><form class="card border-0 shadow-sm rounded-4 p-4" method="post" action="<?= url('perfil') ?>">
    <div class="mb-3"><label class="form-label fw-bold small">Contraseña actual</label><input type="password" class="form-control" required><div class="invalid-feedback">Ingresa tu contraseña actual.</div></div>
    <div class="mb-3"><label class="form-label fw-bold small">Nueva contraseña</label><input type="password" class="form-control" data-match required minlength="8"><div class="invalid-feedback">Mínimo 8 caracteres.</div></div>
    <div class="mb-3"><label class="form-label fw-bold small">Confirmar nueva contraseña</label><input type="password" class="form-control" data-match required><div class="invalid-feedback">No coinciden.</div></div>
    <button class="btn btn-primary">Actualizar contraseña</button></form></div>
  <div class="tab-pane fade" id="p-cta"><div class="card border-0 shadow-sm rounded-4 p-4">
    <div class="small text-uppercase fw-bold text-secondary mb-3">Desactivar cuenta</div><p class="small text-secondary">No podrás iniciar sesión ni enviar solicitudes hasta reactivarla.</p>
    <button class="btn btn-outline-dark" data-confirm="No podrás iniciar sesión hasta reactivar tu cuenta. ¿Deseas continuar?" data-confirm-title="Desactivar cuenta" data-toast="Cuenta desactivada temporalmente">Desactivar mi cuenta</button><hr class="my-4">
    <div class="small text-uppercase fw-bold text-secondary mb-3">Eliminar cuenta</div><p class="small text-secondary">Acción permanente. Tu historial de adopciones se conserva.</p>
    <button class="btn btn-dark" data-confirm="Esta acción no se puede deshacer." data-confirm-title="Eliminar cuenta permanentemente" data-toast="Cuenta eliminada" data-href="<?= url('logout') ?>"><i class="bi bi-trash me-1"></i>Eliminar mi cuenta</button></div></div>
</div>
