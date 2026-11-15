<h1 class="h3 text-center">Recuperar contraseña</h1><p class="text-center text-secondary small">Te enviaremos un enlace válido por 30 minutos</p>
<form data-demo data-toast="Enlace de recuperación enviado a tu correo" data-redirect="<?= url('login') ?>" novalidate>
  <div class="mb-3"><label class="form-label fw-bold small">Correo electrónico</label><input type="email" class="form-control" required placeholder="tucorreo@ejemplo.com"><div class="invalid-feedback">Ingresa un correo válido.</div></div>
  <button class="btn btn-primary w-100">Enviar enlace</button></form>
<p class="text-center small mt-3 mb-0"><a class="fw-bold text-decoration-none" href="<?= url('login') ?>">Volver a iniciar sesión</a></p>
