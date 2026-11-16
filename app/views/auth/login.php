<?php /** Inicio de sesión real. */ ?>
<h1 class="h3 text-center">Iniciar sesión</h1>
<p class="text-center text-secondary small">Bienvenido de nuevo a ADOPT ME</p>

<?php if (!empty($_SESSION['error'])): ?>
  <div class="alert alert-danger small mb-3"><?= e($_SESSION['error']) ?></div>
  <?php unset($_SESSION['error']); ?>
<?php endif; ?>

<form method="post" action="<?= url('login-post') ?>">
  <div class="mb-3">
    <label class="form-label fw-bold small" for="em">Correo electrónico</label>
    <input id="em" name="email" type="email" class="form-control" required placeholder="tucorreo@ejemplo.com">
  </div>
  <div class="mb-3">
    <label class="form-label fw-bold small" for="pw">Contraseña</label>
    <div class="input-group">
      <input id="pw" name="password" type="password" class="form-control" required minlength="8">
      <button type="button" class="btn btn-outline-dark" data-pw="#pw">Ver</button>
    </div>
  </div>
  <div class="d-flex justify-content-between align-items-center mb-3 small">
    <label class="form-check mb-0"><input type="checkbox" class="form-check-input" checked> Recordarme</label>
    <a class="fw-bold text-decoration-none" href="<?= url('recuperar') ?>">¿Olvidaste tu contraseña?</a>
  </div>
  <button class="btn btn-primary w-100" type="submit">Iniciar sesión</button>
</form>
<p class="text-center small text-secondary mt-3 mb-0">¿No tienes cuenta? <a class="fw-bold text-decoration-none" href="<?= url('registro') ?>">Regístrate</a></p>
