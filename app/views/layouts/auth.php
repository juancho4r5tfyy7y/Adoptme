<!DOCTYPE html><html lang="es"><head><?php include APP_PATH . '/views/partials/head.php'; ?></head>
<body><div class="auth-shell min-vh-100 d-flex align-items-center justify-content-center p-3"><div class="card border-0 shadow-lg rounded-4 p-4 p-sm-5 w-100 auth-card">
  <a class="small fw-bold text-secondary" href="<?= url('home') ?>">&larr; Volver al inicio</a>
  <div class="d-flex justify-content-center my-3"><?php include APP_PATH . '/views/partials/logo.php'; ?></div>
  <?= $contenido ?>
</div></div>
<?php include APP_PATH . '/views/partials/scripts.php'; ?></body></html>
