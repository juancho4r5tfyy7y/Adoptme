<?php /** Contenedor de toasts, modal de confirmación genérico y scripts (Bootstrap + app.js). */ ?>
<div id="toast-box" class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index:2000"></div>
<div class="modal fade" id="confirm-modal" tabindex="-1" aria-hidden="true"><div class="modal-dialog modal-dialog-centered"><div class="modal-content border-0 shadow-lg rounded-4">
  <div class="modal-body p-4">
    <h3 class="modal-title h5 mb-2"></h3><p class="modal-text text-secondary"></p>
    <div class="mb-3 d-none"><textarea class="form-control" rows="3"></textarea><div class="invalid-feedback">Este campo es obligatorio.</div></div>
    <div class="d-flex justify-content-end gap-2"><button class="btn btn-outline-dark" data-bs-dismiss="modal">Cancelar</button><button class="btn btn-primary btn-ok">Confirmar</button></div>
  </div></div></div></div>
<script src="<?= asset('vendor/bootstrap-5.3.9-dist/js/bootstrap.bundle.min.js') ?>"></script>
<script src="<?= asset('js/app.js') ?>"></script>
