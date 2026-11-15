<?php /** Registro con pestañas: adoptante o refugio. */ ?>
<h1 class="h3 text-center">Crear cuenta</h1><p class="text-center text-secondary small">Únete a la comunidad ADOPT ME</p>
<ul class="nav nav-tabs nav-fill mb-3" role="tablist">
  <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#t-adop" type="button"><i class="bi bi-person me-1"></i>Soy adoptante</button></li>
  <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#t-ref" type="button"><i class="bi bi-building me-1"></i>Soy un refugio</button></li>
</ul>
<div class="tab-content">
<?php foreach (['t-adop' => 'usuario', 't-ref' => 'refugio'] as $id => $rol): ?>
  <div class="tab-pane fade <?= $rol === 'usuario' ? 'show active' : '' ?>" id="<?= $id ?>">
  <form data-demo data-toast="Cuenta creada correctamente" data-redirect="<?= url('demo', ['rol' => $rol]) ?>" novalidate>
    <div class="row g-3">
      <div class="col-12"><label class="form-label fw-bold small"><?= $rol === 'usuario' ? 'Nombre completo' : 'Nombre de la organización' ?></label><input class="form-control" required placeholder="<?= $rol === 'usuario' ? 'Ej. Ana Pérez' : 'Ej. Refugio Esperanza' ?>"><div class="invalid-feedback">Este campo es obligatorio.</div></div>
      <?php if ($rol === 'usuario'): ?><div class="col-sm-6"><label class="form-label fw-bold small">Documento</label><input class="form-control" required pattern="\d{6,12}" placeholder="Ej. 10203040"><div class="form-text">Debe ser único.</div><div class="invalid-feedback">Ingresa entre 6 y 12 dígitos.</div></div>
      <?php else: ?><div class="col-sm-6"><label class="form-label fw-bold small">NIT / Registro legal</label><input class="form-control" required placeholder="Ej. 900.123.456-7"><div class="invalid-feedback">Ingresa el registro legal o NIT.</div></div><?php endif; ?>
      <div class="col-sm-6"><label class="form-label fw-bold small">Teléfono</label><input type="tel" class="form-control" required placeholder="Ej. 3001234567"><div class="invalid-feedback">Ingresa un número de contacto.</div></div>
      <div class="col-sm-6"><label class="form-label fw-bold small">Correo electrónico</label><input type="email" class="form-control" required placeholder="correo@ejemplo.com"><div class="invalid-feedback">Ingresa un correo válido.</div></div>
      <div class="col-sm-6"><label class="form-label fw-bold small">Dirección</label><input class="form-control" required placeholder="Calle, Carrera, Ciudad..."><div class="invalid-feedback">Ingresa tu dirección.</div></div>
      <div class="col-sm-6"><label class="form-label fw-bold small">Contraseña</label><input type="password" class="form-control" data-match required minlength="8" placeholder="Mínimo 8 caracteres"><div class="invalid-feedback">Mínimo 8 caracteres.</div></div>
      <div class="col-sm-6"><label class="form-label fw-bold small">Confirmar contraseña</label><input type="password" class="form-control" data-match required placeholder="Repite tu contraseña"><div class="invalid-feedback">Las contraseñas no coinciden.</div></div>
      <div class="col-12"><div class="form-check small mb-1"><input type="checkbox" class="form-check-input" required id="chk-term-<?= $id ?>"><label class="form-check-label" for="chk-term-<?= $id ?>">Acepto los términos y condiciones y la política de privacidad.</label><div class="invalid-feedback">Debes aceptar los términos para continuar.</div></div>
        <div class="form-check small"><input type="checkbox" class="form-check-input" id="chk-notif-<?= $id ?>"><label class="form-check-label" for="chk-notif-<?= $id ?>">Quiero recibir notificaciones de mis solicitudes.</label></div></div>
      <div class="col-12"><button class="btn btn-primary w-100">Crear cuenta</button></div>
    </div></form></div>
<?php endforeach; ?></div>
<p class="text-center small text-secondary mt-3 mb-0">¿Ya tienes cuenta? <a class="fw-bold text-decoration-none" href="<?= url('login') ?>">Iniciar sesión</a></p>
