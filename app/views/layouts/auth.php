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
<body><div class="auth-shell min-vh-100 d-flex align-items-center justify-content-center p-3"><div class="card border-0 shadow-lg rounded-4 p-4 p-sm-5 w-100 auth-card">
  <a class="small fw-bold text-secondary" href="<?= url('home') ?>">&larr; Volver al inicio</a>
  <div class="d-flex justify-content-center my-3">
    <a href="<?= url('home') ?>" class="logo" aria-label="ADOPT ME">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><circle cx="7" cy="7" r="3"/><circle cx="12" cy="4.5" r="2.6"/><circle cx="17" cy="7" r="3"/><path d="M12 12c-4 0-7 2.7-7 6.2C5 20.8 7 22 9 20.8c1-.6 2-1 3-1s2 .4 3 1c2 1.2 4 0 4-2.6 0-3.5-3-6.2-7-6.2z"/></svg>
      <span>ADOPT<span class="me">ME</span></span>
    </a>
  </div>
  <?= $contenido ?>
</div></div>
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
