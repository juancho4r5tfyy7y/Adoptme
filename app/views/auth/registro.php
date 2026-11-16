<?php /** Registro con pestañas: adoptante o refugio. */ ?>
<h1 class="h3 text-center">Crear cuenta</h1>
<p class="text-center text-secondary small">Únete a la comunidad ADOPT ME</p>

<?php if (!empty($_SESSION['error'])): ?>
  <div class="alert alert-danger small mb-3"><?= e($_SESSION['error']) ?></div>
  <?php unset($_SESSION['error']); ?>
<?php endif; ?>

<ul class="nav nav-tabs nav-fill mb-3" role="tablist">
  <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#t-adop" type="button"><i class="bi bi-person me-1"></i>Soy adoptante</button></li>
  <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#t-ref" type="button"><i class="bi bi-building me-1"></i>Soy un refugio</button></li>
</ul>
<div class="tab-content">
<?php foreach (['t-adop' => 'usuarios', 't-ref' => 'refugio'] as $id => $rol): ?>
  <div class="tab-pane fade <?= $rol === 'usuarios' ? 'show active' : '' ?>" id="<?= $id ?>">
    <form method="post" action="<?= url('registro-post') ?>">
      <input type="hidden" name="tipo" value="<?= e($rol) ?>">
      <div class="row g-3">
        <div class="col-12">
          <label class="form-label fw-bold small"><?= $rol === 'usuarios' ? 'Nombre completo' : 'Nombre de la organización' ?></label>
          <input name="nombre" class="form-control" required placeholder="<?= $rol === 'usuarios' ? 'Ej. Ana Pérez' : 'Ej. Refugio Esperanza' ?>">
        </div>
        <?php if ($rol === 'refugio'): ?>
          <div class="col-sm-6">
            <label class="form-label fw-bold small">Nombre del administrador del refugio</label>
            <input name="nombre_admin" class="form-control" required placeholder="Nombre de la persona responsable">
          </div>
          <div class="col-sm-6">
            <label class="form-label fw-bold small">Correo del administrador</label>
            <input name="correo_admin" type="email" class="form-control" required placeholder="admin@refugio.com">
            <div class="form-text">Se usará para iniciar sesión con la contraseña inicial.</div>
          </div>
          <div class="col-sm-6">
            <label class="form-label fw-bold small">Documento del administrador</label>
            <input name="documento_admin" class="form-control" required pattern="\d{6,12}" placeholder="Ej. 10203040">
          </div>
          <div class="col-sm-6">
            <label class="form-label fw-bold small">Contraseña inicial del administrador</label>
            <input name="password_admin" type="password" class="form-control" required minlength="8">
          </div>
          <div class="col-sm-6">
            <label class="form-label fw-bold small">Confirmar contraseña inicial</label>
            <input name="confirm_password_admin" type="password" class="form-control" required minlength="8">
          </div>
        <?php endif; ?>
        <?php if ($rol === 'usuarios'): ?>
          <div class="col-sm-6">
            <label class="form-label fw-bold small">Documento</label>
            <input name="documento" class="form-control" required pattern="\d{6,12}" placeholder="Ej. 10203040">
          </div>
        <?php else: ?>
          <div class="col-sm-6">
            <label class="form-label fw-bold small">NIT / Registro legal</label>
            <input name="documento" class="form-control" required placeholder="Ej. 900.123.456-7">
          </div>
        <?php endif; ?>
        <div class="col-sm-6">
          <label class="form-label fw-bold small">Teléfono</label>
          <input name="telefono" type="tel" class="form-control" required placeholder="Ej. 3001234567">
        </div>
        <div class="col-sm-6">
          <label class="form-label fw-bold small">Correo electrónico</label>
          <input name="email" type="email" class="form-control" required placeholder="correo@ejemplo.com">
        </div>
        <div class="col-sm-6">
          <label class="form-label fw-bold small">Dirección</label>
          <input name="direccion" class="form-control" required placeholder="Calle, Carrera, Ciudad...">
        </div>
        <div class="col-sm-6">
          <label class="form-label fw-bold small">Contraseña</label>
          <input name="password" type="password" class="form-control" required minlength="8" placeholder="Mínimo 8 caracteres">
        </div>
        <div class="col-sm-6">
          <label class="form-label fw-bold small">Confirmar contraseña</label>
          <input name="confirm_password" type="password" class="form-control" required placeholder="Repite tu contraseña">
        </div>
        <div class="col-12">
          <div class="form-check small mb-1">
            <input type="checkbox" name="terminos" class="form-check-input" required id="chk-term-<?= $id ?>">
            <label class="form-check-label" for="chk-term-<?= $id ?>">Acepto los términos y condiciones y la política de privacidad.</label>
          </div>
          <div class="form-check small">
            <input type="checkbox" class="form-check-input" id="chk-notif-<?= $id ?>">
            <label class="form-check-label" for="chk-notif-<?= $id ?>">Quiero recibir notificaciones de mis solicitudes.</label>
          </div>
        </div>
        <div class="col-12">
          <button class="btn btn-primary w-100" type="submit">Crear cuenta</button>
        </div>
      </div>
    </form>
  </div>
<?php endforeach; ?>
</div>
<p class="text-center small text-secondary mt-3 mb-0">¿Ya tienes cuenta? <a class="fw-bold text-decoration-none" href="<?= url('login') ?>">Iniciar sesión</a></p>
