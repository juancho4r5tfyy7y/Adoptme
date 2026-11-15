<?php /** Inicio de sesión. Los botones demo entran sin credenciales (solo frontend). */ ?>
<h1 class="h3 text-center">Iniciar sesión</h1><p class="text-center text-taupe small">Bienvenido de nuevo a ADOPT ME</p>
<div class="p-3 mb-3 small" style="background:var(--cream-2);border-radius:var(--radius-sm)"><b>Modo demo — entra como:</b>
  <div class="d-flex gap-2 flex-wrap mt-2"><?php foreach (['usuario' => 'Adoptante', 'refugio' => 'Refugio', 'admin' => 'Administrador'] as $r => $l): ?><a class="btn btn-outline-dark btn-sm" href="<?= url('demo', ['rol' => $r]) ?>"><?= $l ?></a><?php endforeach; ?></div></div>
<form data-demo data-toast="Sesión iniciada" data-redirect="<?= url('demo', ['rol' => 'usuario']) ?>" novalidate>
  <div class="mb-3"><label class="form-label" for="em">Correo electrónico</label><input id="em" type="email" class="form-control" required placeholder="tucorreo@ejemplo.com"><div class="invalid-feedback">Ingresa un correo válido.</div></div>
  <div class="mb-3"><label class="form-label" for="pw">Contraseña</label><div class="input-group"><input id="pw" type="password" class="form-control" required minlength="8"><button type="button" class="btn btn-outline-dark" data-pw="#pw">Ver</button><div class="invalid-feedback">Mínimo 8 caracteres.</div></div></div>
  <div class="d-flex justify-content-between mb-3 small"><label class="form-check"><input type="checkbox" class="form-check-input" checked> Recordarme</label><a class="fw-bold" href="<?= url('recuperar') ?>">¿Olvidaste tu contraseña?</a></div>
  <button class="btn btn-primary w-100">Iniciar sesión</button>
</form>
<p class="text-center small text-taupe mt-3 mb-0">¿No tienes cuenta? <a class="fw-bold" href="<?= url('registro') ?>">Regístrate</a></p>
