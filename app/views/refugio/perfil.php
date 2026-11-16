<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Perfil del refugio</title>
  <link href="<?= asset('vendor/bootstrap-5.3.9-dist/css/bootstrap.min.css') ?>" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link href="<?= asset('css/adoptme.css') ?>" rel="stylesheet">
  <link href="<?= asset('css/components.css') ?>" rel="stylesheet">
  <link href="<?= asset('css/pages.css') ?>" rel="stylesheet">
</head>
<body>
<?php /** Perfil institucional del refugio. */ ?>
<div class="d-flex justify-content-between align-items-start gap-3 flex-wrap mb-4"><div><h1 class="h2 mb-1">Perfil del refugio</h1><p class="text-secondary mb-0">Información institucional y de contacto</p></div></div>
<?php if (!empty($_SESSION['error'])): ?>
  <div class="alert alert-danger small mb-3"><?= e($_SESSION['error']) ?></div>
  <?php unset($_SESSION['error']); ?>
<?php endif; ?>
<?php if (!empty($_SESSION['success'])): ?>
  <div class="alert alert-success small mb-3"><?= e($_SESSION['success']) ?></div>
  <?php unset($_SESSION['success']); ?>
<?php endif; ?>
<div class="row g-3">
  <div class="col-lg-6"><form class="card border-0 shadow-sm rounded-4 p-4" method="post" action="<?= url('refugio/perfil') ?>"><div class="small text-uppercase fw-bold text-secondary mb-3">Datos institucionales</div><div class="row g-3">
    <div class="col-12"><label class="form-label fw-bold small">Nombre de la organización</label><input class="form-control" required value="<?= e($r['nombre']) ?>"><div class="invalid-feedback">El nombre es obligatorio.</div></div>
    <div class="col-sm-6"><label class="form-label fw-bold small">Tipo</label><select class="form-select"><?php foreach (['Fundación', 'Albergue', 'Rescatista independiente'] as $t): ?><option <?= $r['tipo'] === $t ? 'selected' : '' ?>><?= $t ?></option><?php endforeach; ?></select></div>
    <div class="col-sm-6"><label class="form-label fw-bold small">Año de fundación</label><input class="form-control" value="<?= e($r['anio']) ?>"></div>
    <div class="col-sm-6"><label class="form-label fw-bold small">Ciudad</label><input class="form-control" required value="<?= e($r['ciudad']) ?>"><div class="invalid-feedback">La ciudad es obligatoria.</div></div>
    <div class="col-sm-6"><label class="form-label fw-bold small">Teléfono</label><input class="form-control" required value="<?= e($r['telefono']) ?>"><div class="invalid-feedback">El teléfono es obligatorio.</div></div>
    <div class="col-12"><button class="btn btn-primary">Guardar cambios</button></div></div></form></div>
  <div class="col-lg-6"><div class="card border-0 shadow-sm rounded-4 p-4">
    <div class="small text-uppercase fw-bold text-secondary mb-3">Administrador del refugio</div>
    <?php if (!empty($administrador)): ?>
      <p class="mb-1"><strong><?= e($administrador['nombre']) ?></strong></p>
      <p class="text-secondary mb-1"><?= e($administrador['correo']) ?></p>
      <p class="small text-secondary mb-0">Esta cuenta inicia sesión con el correo mostrado y la contraseña que se definió al crearla.</p>
    <?php else: ?>
      <p class="small text-secondary">Crea la cuenta de acceso del administrador. La contraseña inicial será la que ingreses aquí.</p>
      <form method="post" action="<?= url('refugio/perfil-admin-post') ?>">
        <div class="mb-3"><label class="form-label fw-bold small">Nombre</label><input name="nombre_contacto" class="form-control" required value="<?= e($r['contacto'] ?? '') ?>"></div>
        <div class="mb-3"><label class="form-label fw-bold small">Correo para iniciar sesión</label><input name="correo_contacto" type="email" class="form-control" required value="<?= e($r['correo'] ?? '') ?>"></div>
        <div class="mb-3"><label class="form-label fw-bold small">Documento</label><input name="documento_admin" class="form-control" required></div>
        <div class="mb-3"><label class="form-label fw-bold small">Contraseña inicial</label><input name="password_admin" type="password" class="form-control" required minlength="8"></div>
        <div class="mb-3"><label class="form-label fw-bold small">Confirmar contraseña</label><input name="confirm_password_admin" type="password" class="form-control" required minlength="8"></div>
        <button class="btn btn-outline-dark">Crear cuenta del administrador</button>
      </form>
    <?php endif; ?>
    <hr class="my-4">
    <div class="small text-uppercase fw-bold text-secondary mb-3">Eliminar cuenta del refugio</div><p class="small text-secondary">No podrás eliminarla si tienes solicitudes pendientes.</p>
    <button class="btn btn-dark" data-msg="No puedes eliminar la cuenta: tienes solicitudes pendientes"><i class="bi bi-trash me-1"></i>Eliminar cuenta</button></div></div>
</div>
</body>
</html>
