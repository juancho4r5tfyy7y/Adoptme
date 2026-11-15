<?php /** Perfil del adoptante: datos, seguridad y cuenta. */ ?>
<div class="page-head"><div><h1>Mi perfil</h1><p class="text-taupe mb-0">Gestiona tu información personal y tu cuenta</p></div></div>
<ul class="nav nav-tabs mb-4"><?php foreach (['info' => 'Información personal', 'seg' => 'Seguridad', 'cta' => 'Cuenta'] as $k => $l): ?><li class="nav-item"><button class="nav-link <?= $k === 'info' ? 'active' : '' ?>" data-bs-toggle="tab" data-bs-target="#p-<?= $k ?>"><?= $l ?></button></li><?php endforeach; ?></ul>
<div class="tab-content" style="max-width:600px">
  <div class="tab-pane fade show active" id="p-info"><form class="card-adopt" data-demo data-toast="Perfil actualizado" novalidate><div class="row g-3">
    <div class="col-sm-6"><label class="form-label">Nombre</label><input class="form-control" value="María" required></div>
    <div class="col-sm-6"><label class="form-label">Apellido</label><input class="form-control" value="Gómez" required></div>
    <div class="col-sm-6"><label class="form-label">Correo</label><input class="form-control" value="<?= e($user['email']) ?>" disabled></div>
    <div class="col-sm-6"><label class="form-label">Teléfono</label><input class="form-control" value="(300) 111 2233" required></div>
    <div class="col-12"><label class="form-label">Dirección</label><input class="form-control" value="Calle 10 # 45-20, Medellín" required></div>
    <div class="col-12"><button class="btn btn-primary">Guardar cambios</button></div></div></form></div>
  <div class="tab-pane fade" id="p-seg"><form class="card-adopt" data-demo data-toast="Contraseña actualizada" novalidate>
    <div class="mb-3"><label class="form-label">Contraseña actual</label><input type="password" class="form-control" required></div>
    <div class="mb-3"><label class="form-label">Nueva contraseña</label><input type="password" class="form-control" data-match required minlength="8"></div>
    <div class="mb-3"><label class="form-label">Confirmar nueva contraseña</label><input type="password" class="form-control" data-match required><div class="invalid-feedback">No coinciden.</div></div>
    <button class="btn btn-primary">Actualizar contraseña</button></form></div>
  <div class="tab-pane fade" id="p-cta"><div class="card-adopt">
    <div class="section-title-sm">Desactivar cuenta</div><p class="small text-taupe">No podrás iniciar sesión ni enviar solicitudes hasta reactivarla.</p>
    <button class="btn btn-outline-dark" data-confirm="No podrás iniciar sesión hasta reactivar tu cuenta. ¿Deseas continuar?" data-confirm-title="Desactivar cuenta" data-toast="Cuenta desactivada temporalmente">Desactivar mi cuenta</button><hr class="my-4">
    <div class="section-title-sm">Eliminar cuenta</div><p class="small text-taupe">Acción permanente. Tu historial de adopciones se conserva.</p>
    <button class="btn btn-dark" data-confirm="Esta acción no se puede deshacer." data-confirm-title="Eliminar cuenta permanentemente" data-toast="Cuenta eliminada" data-href="<?= url('logout') ?>"><i class="bi bi-trash me-1"></i>Eliminar mi cuenta</button></div></div>
</div>
