<?php /** Registro con pestañas: adoptante o refugio. */ ?>
<h1 class="h3 text-center">Crear cuenta</h1><p class="text-center text-taupe small">Únete a la comunidad ADOPT ME</p>
<ul class="nav nav-tabs nav-fill mb-3" role="tablist">
  <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#t-adop" type="button"><i class="bi bi-person me-1"></i>Soy adoptante</button></li>
  <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#t-ref" type="button"><i class="bi bi-building me-1"></i>Soy un refugio</button></li>
</ul>
<div class="tab-content">
<?php foreach (['t-adop' => 'usuario', 't-ref' => 'refugio'] as $id => $rol): ?>
  <div class="tab-pane fade <?= $rol === 'usuario' ? 'show active' : '' ?>" id="<?= $id ?>">
  <form data-demo data-toast="Cuenta creada correctamente" data-redirect="<?= url('demo', ['rol' => $rol]) ?>" novalidate>
    <div class="row g-3">
      <div class="col-12"><label class="form-label"><?= $rol === 'usuario' ? 'Nombre completo' : 'Nombre de la organización' ?></label><input class="form-control" required></div>
      <?php if ($rol === 'usuario'): ?><div class="col-sm-6"><label class="form-label">Documento</label><input class="form-control" required pattern="\d{6,12}"><div class="form-text">Debe ser único.</div></div>
      <?php else: ?><div class="col-sm-6"><label class="form-label">NIT / Registro legal</label><input class="form-control" required></div><?php endif; ?>
      <div class="col-sm-6"><label class="form-label">Teléfono</label><input type="tel" class="form-control" required></div>
      <div class="col-sm-6"><label class="form-label">Correo</label><input type="email" class="form-control" required></div>
      <div class="col-sm-6"><label class="form-label">Dirección</label><input class="form-control" required></div>
      <div class="col-sm-6"><label class="form-label">Contraseña</label><input type="password" class="form-control" data-match required minlength="8"></div>
      <div class="col-sm-6"><label class="form-label">Confirmar contraseña</label><input type="password" class="form-control" data-match required><div class="invalid-feedback">Las contraseñas no coinciden.</div></div>
      <div class="col-12"><label class="form-check small"><input type="checkbox" class="form-check-input" required> Acepto los términos y condiciones y la política de privacidad.</label>
        <label class="form-check small"><input type="checkbox" class="form-check-input"> Quiero recibir notificaciones de mis solicitudes.</label></div>
      <div class="col-12"><button class="btn btn-primary w-100">Crear cuenta</button></div>
    </div></form></div>
<?php endforeach; ?></div>
<p class="text-center small text-taupe mt-3 mb-0">¿Ya tienes cuenta? <a class="fw-bold" href="<?= url('login') ?>">Iniciar sesión</a></p>
