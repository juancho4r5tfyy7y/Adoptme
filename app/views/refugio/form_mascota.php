<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= $titulo ?? 'Registrar mascota' ?></title>
  <link href="<?= asset('vendor/bootstrap-5.3.9-dist/css/bootstrap.min.css') ?>" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link href="<?= asset('css/adoptme.css') ?>" rel="stylesheet">
  <link href="<?= asset('css/components.css') ?>" rel="stylesheet">
  <link href="<?= asset('css/pages.css') ?>" rel="stylesheet">
</head>
<body>
<?php
    $v = static fn(string $key, string $default = ''): string => e($m[$key] ?? $default);
    $sel = static fn(string $key, string $value): string => (($m[$key] ?? '') === $value) ? 'selected' : '';
    $chk = static fn(string $key): string => !empty($m[$key]) ? 'checked' : '';
?>
<nav class="small fw-bold text-secondary mb-3"><a href="<?= url('refugio/mascotas') ?>" class="text-decoration-none">Gestión de mascotas</a> / <span class="text-dark"><?= $m ? 'Editar' : 'Nueva mascota' ?></span></nav>
<div class="d-flex justify-content-between align-items-start gap-3 flex-wrap mb-4">
  <div>
    <h1 class="h2 mb-1"><?= $m ? 'Editar mascota' : 'Registrar nueva mascota' ?></h1>
    <p class="text-secondary mb-0">Completa los campos obligatorios antes de guardar</p>
  </div>
</div>

<?php if (!empty($_SESSION['error'])): ?>
  <div class="alert alert-danger small mb-3"><?= e($_SESSION['error']) ?></div>
  <?php unset($_SESSION['error']); ?>
<?php endif; ?>

<form method="post" action="<?= url('refugio/mascota-post') ?>" enctype="multipart/form-data" class="row g-3">
  <?php if ($m): ?><input type="hidden" name="id_mascota" value="<?= (int) $m['id'] ?>"><?php endif; ?>
  <div class="col-lg-6">
    <div class="card border-0 shadow-sm rounded-4 p-4 h-100">
      <div class="small text-uppercase fw-bold text-secondary mb-3">Información básica</div>
      <div class="row g-3">
        <div class="col-12">
          <label class="form-label fw-bold small">Nombre</label>
          <input name="nombre" class="form-control" required value="<?= $v('nombre') ?>">
        </div>
        <div class="col-sm-6">
          <label class="form-label fw-bold small">Especie</label>
          <select name="especie" class="form-select" required>
            <?php foreach (['Perro', 'Gato', 'Conejo'] as $o): ?>
              <option value="<?= e($o) ?>" <?= $sel('especie', $o) ?>><?= e($o) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="col-sm-6">
          <label class="form-label fw-bold small">Raza</label>
          <input name="raza" class="form-control" required value="<?= $v('raza') ?>">
        </div>
        <div class="col-sm-6">
          <label class="form-label fw-bold small">Edad (meses)</label>
          <input name="edad_meses" type="number" min="1" class="form-control" required value="<?= e($m['edad_meses'] ?? 12) ?>">
        </div>
        <div class="col-sm-6">
          <label class="form-label fw-bold small">Sexo</label>
          <select name="sexo" class="form-select" required>
            <option value="">Selecciona</option>
            <option value="macho" <?= $sel('sexo', 'Macho') ?>>Macho</option>
            <option value="hembra" <?= $sel('sexo', 'Hembra') ?>>Hembra</option>
          </select>
        </div>
        <div class="col-12">
          <label class="form-label fw-bold small">Tamaño</label>
          <select name="tamano" class="form-select" required>
            <option value="">Selecciona</option>
            <?php foreach (['pequeño', 'mediano', 'grande'] as $o): ?>
              <option value="<?= e($o) ?>" <?= $sel('tamano', ucfirst($o)) ?>><?= ucfirst(e($o)) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="col-12">
          <label class="form-label fw-bold small">Descripción</label>
          <textarea name="descripcion" class="form-control" rows="3" required><?= $v('desc') ?></textarea>
        </div>
        <div class="col-12">
          <label for="imagen" class="form-label fw-bold small">Fotos de la mascota</label>
          <input id="imagen" name="imagen[]" type="file" class="form-control" accept="image/jpeg,image/png,image/webp" multiple <?= $m ? '' : 'required' ?>>
          <div class="form-text"><?= $m ? 'Puedes agregar fotos JPG, PNG o WEBP (máximo 5 MB cada una).' : 'Selecciona al menos 2 fotos JPG, PNG o WEBP (máximo 5 MB cada una).' ?></div>
          <?php if ($m && !empty($m['fotos'])): ?>
            <div class="d-flex flex-wrap gap-2 mt-3">
              <?php foreach ($m['fotos'] as $indice => $foto): ?>
                <img src="<?= e(asset('img/mascotas/' . $foto)) ?>" alt="Foto <?= $indice + 1 ?> de <?= e($m['nombre']) ?>" class="rounded-3" style="width: 84px; height: 84px; object-fit: cover;">
              <?php endforeach; ?>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
  <div class="col-lg-6">
    <div class="card border-0 shadow-sm rounded-4 p-4 h-100">
      <div class="small text-uppercase fw-bold text-secondary mb-3">Salud</div>
      <label class="form-check mb-2"><input type="checkbox" name="vacunado" class="form-check-input" <?= $chk('vacunado') ?>> Vacunado</label>
      <label class="form-check mb-3"><input type="checkbox" name="esterilizado" class="form-check-input" <?= $chk('esterilizado') ?>> Esterilizado</label>
      <label class="form-label fw-bold small">Estado de salud general</label>
      <input name="salud" class="form-control mb-4" value="<?= $v('salud') ?>" placeholder="Ej. Excelente">
      <div class="small text-uppercase fw-bold text-secondary mb-3">Compatibilidad</div>
      <label class="form-check mb-2"><input type="checkbox" name="ninos" class="form-check-input" <?= $chk('ninos') ?>> Compatible con niños</label>
      <label class="form-check mb-2"><input type="checkbox" name="perros" class="form-check-input" <?= $chk('perros') ?>> Compatible con perros</label>
      <label class="form-check mb-2"><input type="checkbox" name="gatos" class="form-check-input" <?= $chk('gatos') ?>> Compatible con gatos</label>
      <div class="d-grid gap-2 mt-4">
        <button type="submit" name="estado_publicacion" value="borrador" class="btn btn-outline-dark">Guardar como borrador</button>
        <button type="submit" name="estado_publicacion" value="publicado" class="btn btn-primary"><?= $m ? 'Guardar cambios' : 'Publicar mascota' ?></button>
      </div>
    </div>
  </div>
</form>
</body>
</html>
