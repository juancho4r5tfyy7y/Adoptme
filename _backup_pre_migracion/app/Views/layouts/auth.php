<!DOCTYPE html><html lang="es"><head><?php include APP_PATH . '/views/partials/head.php'; ?></head>
<body><div class="auth-shell"><div class="auth-card">
  <a class="small fw-bold text-taupe" href="<?= url('home') ?>">&larr; Volver al inicio</a>
  <div class="d-flex justify-content-center my-3"><?php include APP_PATH . '/views/partials/logo.php'; ?></div>
  <?= $contenido ?>
</div></div>
<?php include APP_PATH . '/views/partials/scripts.php'; ?></body></html>
