<?php /** Barra superior. Muestra enlaces públicos o el menú de usuario según la sesión. */
$actual = is_string($_GET['r'] ?? null) ? $_GET['r'] : 'home';
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
    <?php endif; include __DIR__ . '/logo.php'; ?>
    <?php if (!$user): ?>
      <button class="navbar-toggler border-0" data-bs-toggle="collapse" data-bs-target="#nav-pub" aria-label="Menú"><i class="bi bi-list fs-3"></i></button>
      <div class="collapse navbar-collapse" id="nav-pub">
        <ul class="navbar-nav mx-auto gap-1">
          <li><a class="nav-link <?= $actual === 'home' ? 'active' : '' ?>" href="<?= url('home') ?>">Inicio</a></li>
          <li><a class="nav-link <?= $actual === 'catalogo' ? 'active' : '' ?>" href="<?= url('catalogo') ?>">Mascotas</a></li>
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
