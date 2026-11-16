<!DOCTYPE html><html lang="es"><head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title> ADOPT ME</title>
<link href="<?= asset('vendor/bootstrap-5.3.9-dist/css/bootstrap.min.css') ?>" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link href="<?= asset('css/adoptme.css') ?>" rel="stylesheet">
<link href="<?= asset('css/components.css') ?>" rel="stylesheet">
<link href="<?= asset('css/pages.css') ?>" rel="stylesheet">
</head>
<body class="d-flex flex-column min-vh-100">
<?php
$rutaActual = is_string($_GET['r'] ?? null) ? $_GET['r'] : 'home';
$roles = ['usuario', 'refugio', 'admin'];
$rolUsuarios = is_array($user) && in_array($user['rol'] ?? null, $roles, true)
    ? $user['rol']
    : 'usuario';
$nombreUsuario = is_array($user) && is_string($user['nombre'] ?? null)
    ? $user['nombre']
    : 'Usuario';
$nUnread = 0;
$perfil = ['usuario' => 'perfil', 'refugio' => 'refugio/perfil', 'admin' => 'admin/dashboard'][$rolUsuarios];
?>
<nav class="navbar navbar-expand-lg navbar-adopt">
  <div class="container-xl">
    <?php if ($user && ($layout_panel ?? false)): ?>
      <button class="btn btn-outline-dark btn-sm d-lg-none me-2" data-bs-toggle="offcanvas" data-bs-target="#sidebar" aria-label="Abrir menú"><i class="bi bi-list"></i></button>
    <?php endif; ?>
    <a href="<?= url('home') ?>" class="logo" aria-label="ADOPT ME">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><circle cx="7" cy="7" r="3"/><circle cx="12" cy="4.5" r="2.6"/><circle cx="17" cy="7" r="3"/><path d="M12 12c-4 0-7 2.7-7 6.2C5 20.8 7 22 9 20.8c1-.6 2-1 3-1s2 .4 3 1c2 1.2 4 0 4-2.6 0-3.5-3-6.2-7-6.2z"/></svg>
      <span>ADOPT<span class="me">ME</span></span>
    </a>
    <?php if (!$user): ?>
      <button class="navbar-toggler border-0" data-bs-toggle="collapse" data-bs-target="#nav-pub" aria-label="Menú"><i class="bi bi-list fs-3"></i></button>
      <div class="collapse navbar-collapse" id="nav-pub">
        <ul class="navbar-nav mx-auto gap-1">
          <li><a class="nav-link <?= $rutaActual === 'home' ? 'active' : '' ?>" href="<?= url('home') ?>">Inicio</a></li>
          <li><a class="nav-link <?= $rutaActual === 'catalogo' ? 'active' : '' ?>" href="<?= url('catalogo') ?>">Mascotas</a></li>
        </ul>
        <div class="d-flex gap-2"><a class="btn btn-outline-dark btn-sm" href="<?= url('login') ?>">Iniciar sesión</a><a class="btn btn-primary btn-sm" href="<?= url('registro') ?>">Crear cuenta</a></div>
      </div>
    <?php else: ?>
      <div class="ms-auto d-flex align-items-center gap-2">
        <a class="icon-btn position-relative" href="<?= url('notificaciones') ?>" aria-label="Notificaciones"><i class="bi bi-bell"></i>
          <?php if ($nUnread): ?><span class="position-absolute top-0 start-100 translate-middle badge rounded-pill notification-count"><?= $nUnread ?></span><?php endif; ?></a>
        <div class="dropdown">
          <button class="btn p-1 d-flex align-items-center gap-2" data-bs-toggle="dropdown" aria-expanded="false">
            <span class="avatar"><?= e(inicial($nombreUsuario)) ?></span>
            <span class="d-none d-sm-block text-start lh-sm"><span class="d-block fw-bold small"><?= e($nombreUsuario) ?></span><span class="small text-secondary text-capitalize"><?= e($rolUsuarios) ?></span></span>
          </button>
          <ul class="dropdown-menu dropdown-menu-end">
            <li><a class="dropdown-item" href="<?= url($perfil) ?>"><i class="bi bi-person me-2"></i>Mi perfil</a></li>
            <li><a class="dropdown-item" href="<?= url('notificaciones') ?>"><i class="bi bi-bell me-2"></i>Notificaciones</a></li>
            <li><a class="dropdown-item text-danger" href="<?= url('logout') ?>"><i class="bi bi-box-arrow-right me-2"></i>Cerrar sesión</a></li>
          </ul>
        </div>
      </div>
    <?php endif; ?>
  </div>
</nav>
<main class="flex-grow-1"><?= $contenido ?></main>
<footer class="border-top py-4 mt-auto small text-secondary"><div class="container-xl d-flex justify-content-between align-items-center flex-wrap gap-3">
  <a href="<?= url('home') ?>" class="logo" aria-label="ADOPT ME">
    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><circle cx="7" cy="7" r="3"/><circle cx="12" cy="4.5" r="2.6"/><circle cx="17" cy="7" r="3"/><path d="M12 12c-4 0-7 2.7-7 6.2C5 20.8 7 22 9 20.8c1-.6 2-1 3-1s2 .4 3 1c2 1.2 4 0 4-2.6 0-3.5-3-6.2-7-6.2z"/></svg>
    <span>ADOPT<span class="me">ME</span></span>
  </a>
  <span>© 2026 ADOPT ME · Adopta amor, cambia vidas.</span>
</div></footer>
<div id="toast-box" class="toast-container toast-container-adopt position-fixed bottom-0 end-0 p-3"></div>
<div class="modal fade" id="confirm-modal" tabindex="-1" aria-hidden="true"><div class="modal-dialog modal-dialog-centered"><div class="modal-content border-0 shadow-lg rounded-4">
  <div class="modal-body p-4">
    <h3 class="modal-title h5 mb-2"></h3><p class="modal-text text-secondary"></p>
    <div class="mb-3 d-none"><textarea class="form-control" rows="3"></textarea><div class="invalid-feedback">Este campo es obligatorio.</div></div>
    <div class="d-flex justify-content-end gap-2"><button class="btn btn-outline-dark" data-bs-dismiss="modal">Cancelar</button><button class="btn btn-primary btn-ok">Confirmar</button></div>
  </div></div></div></div>
<script src="<?= asset('vendor/bootstrap-5.3.9-dist/js/bootstrap.bundle.min.js') ?>"></script>
<script src="<?= asset('js/app.js') ?>"></script>
</body></html>
