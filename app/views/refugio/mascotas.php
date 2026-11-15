<?php /** Gestión de mascotas del refugio (estado, publicación, edición, eliminación). */ ?>
<div class="d-flex justify-content-between align-items-start gap-3 flex-wrap mb-4"><div><h1 class="h2 mb-1">Gestión de mascotas</h1><p class="text-secondary mb-0">Administra las mascotas de tu refugio</p></div><a class="btn btn-primary" href="<?= url('refugio/mascota-form') ?>"><i class="bi bi-plus-lg me-1"></i>Nueva mascota</a></div>
<div class="card border-0 shadow-sm rounded-4 overflow-hidden"><div class="table-responsive"><table class="table table-hover mb-0 align-middle">
  <thead><tr><th>Mascota</th><th>Especie / Raza</th><th>Estado</th><th>Publicación</th><th>Acciones</th></tr></thead><tbody>
  <?php foreach ($mascotas as $m): ?><tr>
    <td><div class="d-flex align-items-center gap-2"><div class="pet-thumbnail"><?= mascota_img($m) ?></div><b><?= e($m['nombre']) ?></b></div></td>
    <td><?= e($m['especie']) ?> · <?= e($m['raza']) ?></td>
    <td><select class="form-select form-select-sm rounded-pill w-auto" data-cambia-estado aria-label="Estado"><?php foreach (['disponible', 'en proceso', 'adoptado', 'pausado'] as $s): ?><option <?= $m['estado'] === $s ? 'selected' : '' ?>><?= $s ?></option><?php endforeach; ?></select></td>
    <td><?= badge($m['pub']) ?></td>
    <td><div class="d-flex gap-2">
      <?php if ($m['pub'] === 'borrador'): ?><button class="icon-btn" title="Publicar" data-msg="<?= e($m['nombre']) ?> ahora es visible para adoptantes"><i class="bi bi-check-lg"></i></button><?php endif; ?>
      <a class="icon-btn" title="Editar" href="<?= url('refugio/mascota-form', ['id' => $m['id']]) ?>"><i class="bi bi-pencil"></i></a>
      <?php if ($m['estado'] === 'disponible' || $m['pub'] === 'borrador'): ?>
        <button class="icon-btn" title="Eliminar" data-confirm="¿Seguro que deseas eliminar a <?= e($m['nombre']) ?>? No se puede deshacer." data-confirm-title="Eliminar mascota" data-remove="tr" data-toast="Mascota eliminada"><i class="bi bi-trash"></i></button>
      <?php else: ?><button class="icon-btn" title="Solo se eliminan mascotas disponibles o en borrador" data-msg="Solo puedes eliminar mascotas disponibles o en borrador"><i class="bi bi-trash opacity-50"></i></button><?php endif; ?>
    </div></td></tr><?php endforeach; ?></tbody></table></div></div>
