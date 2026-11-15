<?php /** Layout con barra lateral (offcanvas en móvil) para adoptante, refugio y administrador. */
$layout_panel = true; $actual = $_GET['r'] ?? ''; ?>
<!DOCTYPE html><html lang="es"><head><?php include APP_PATH . '/views/partials/head.php'; ?></head>
<body class="d-flex flex-column min-vh-100">
<?php include APP_PATH . '/views/partials/navbar.php'; ?>
<div class="d-flex flex-grow-1">
  <aside class="sidebar offcanvas-lg offcanvas-start p-3 bg-white border-end" id="sidebar" tabindex="-1">
    <div class="offcanvas-header d-lg-none px-0"><span class="fw-bold">Menú</span><button class="btn-close" data-bs-dismiss="offcanvas" aria-label="Cerrar"></button></div>
    <nav class="offcanvas-body d-block p-0" aria-label="Menú principal">
      <?php foreach (menu_por_rol($user['rol']) as [$ruta, $et, $ic]): ?>
        <a class="side-link <?= $actual === $ruta ? 'active' : '' ?>" href="<?= url($ruta) ?>"><i class="bi bi-<?= $ic ?>"></i><?= e($et) ?></a>
      <?php endforeach; ?>
    </nav>
  </aside>
  <main class="flex-grow-1 p-3 p-sm-4 panel-main"><?= $contenido ?></main>
</div>
<?php include APP_PATH . '/views/partials/scripts.php'; ?>
</body></html>
