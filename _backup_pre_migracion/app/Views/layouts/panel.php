<?php /** Layout con barra lateral (offcanvas en móvil) para adoptante, refugio y administrador. */
$layout_panel = true; $actual = $_GET['r'] ?? ''; ?>
<!DOCTYPE html><html lang="es"><head><?php include APP_PATH . '/views/partials/head.php'; ?></head>
<body>
<?php include APP_PATH . '/views/partials/navbar.php'; ?>
<div class="d-flex">
  <aside class="sidebar offcanvas-lg offcanvas-start p-3" id="sidebar" tabindex="-1">
    <div class="offcanvas-header d-lg-none px-0"><span class="fw-bold">Menú</span><button class="btn-close" data-bs-dismiss="offcanvas"></button></div>
    <nav class="offcanvas-body d-block p-0" aria-label="Menú principal">
      <?php foreach (menu_por_rol($user['rol']) as [$ruta, $et, $ic]): ?>
        <a class="side-link <?= $actual === $ruta ? 'active' : '' ?>" href="<?= url($ruta) ?>"><i class="bi bi-<?= $ic ?>"></i><?= e($et) ?></a>
      <?php endforeach; ?>
    </nav>
  </aside>
  <main class="panel-main"><?= $contenido ?></main>
</div>
<?php include APP_PATH . '/views/partials/scripts.php'; ?>
</body></html>
