<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Catalogo</title>
  <link href="<?= asset('vendor/bootstrap-5.3.9-dist/css/bootstrap.min.css') ?>" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link href="<?= asset('css/adoptme.css') ?>" rel="stylesheet">
  <link href="<?= asset('css/components.css') ?>" rel="stylesheet">
  <link href="<?= asset('css/pages.css') ?>" rel="stylesheet">
</head>
<body>
  // Catálogo con filtros (JS en el navegador). Si hay sesión se muestra dentro del panel.
<div class="<?= $user ? '' : 'container-xl pt-4' ?>">
  <div class="d-flex justify-content-between align-items-start gap-3 flex-wrap mb-4"><div><h1 class="h2 mb-1">Catálogo de mascotas</h1><p class="text-secondary mb-0" id="f-count"></p></div></div>
  <div class="card border-0 shadow-sm rounded-4 p-3 mb-3"><div class="row g-2">
    <div class="col-md-4"><input id="f-q" class="form-control" placeholder="Buscar por nombre o raza..." aria-label="Buscar"></div>
    <div class="col-6 col-md-2"><select id="f-especie" class="form-select" aria-label="Especie"><option value="">Especie: todas</option><option>Perro</option><option>Gato</option><option>Conejo</option></select></div>
    <div class="col-6 col-md-3"><select id="f-tamano" class="form-select" aria-label="Tamaño"><option value="">Tamaño: todos</option><option>Pequeño</option><option>Mediano</option><option>Grande</option></select></div>
    <div class="col-12 col-md-3"><select id="f-edad" class="form-select" aria-label="Edad"><option value="">Edad: todas</option><option value="joven">Joven (0-1 año)</option><option value="adulto">Adulto (2-4 años)</option><option value="senior">Senior (5+ años)</option></select></div>
  </div></div>
  <div class="d-flex gap-2 flex-wrap mb-4">
    <button class="btn btn-sm btn-primary" data-filtro-estado="">Todos</button>
    <?php foreach (['disponible', 'en proceso', 'pausado'] as $s): ?><button class="btn btn-sm btn-outline-dark" data-filtro-estado="<?= e($s) ?>"><?= $s ?></button><?php endforeach; ?>
  </div>
  <div id="catalogo-grid" class="row row-cols-1 row-cols-md-2 row-cols-xl-3 g-4"><?php foreach ($mascotas as $m):
    $meses = $m['edad_meses'] ?? null;
    if ($meses === null) {
        preg_match('/(\d+)\s*(mes|año)/', $m['edad'], $coincidencia);
        $cantidad = (int) ($coincidencia[1] ?? 0);
        $unidad = $coincidencia[2] ?? 'año';
        $meses = $unidad === 'mes' ? $cantidad : $cantidad * 12;
    }
  ?>
    <div class="col" data-pet data-nombre="<?= e($m['nombre']) ?>" data-raza="<?= e($m['raza']) ?>" data-especie="<?= e($m['especie']) ?>" data-tamano="<?= e($m['tamano']) ?>" data-estado="<?= e($m['estado']) ?>" data-meses="<?= (int) $meses ?>">
      <article class="pet-card">
        <div class="pet-photo"><?= mascota_img($m) ?>
          <button class="icon-btn pet-fav" data-id="<?= $m['id'] ?>" <?= !$user ? 'data-login="' . url('login') . '"' : '' ?> aria-label="Favorito"><i class="bi bi-heart"></i></button></div>
        <div class="p-3">
          <div class="d-flex justify-content-between align-items-start gap-2">
            <div><a class="stretched-link h5 mb-0 text-dark display-font" href="<?= url('mascota', ['id' => $m['id']]) ?>"><?= e($m['nombre']) ?></a>
              <div class="small text-secondary"><?= e($m['raza']) ?> · <?= e($m['edad']) ?></div></div><?= badge($m['estado']) ?></div>
          <div class="d-flex gap-1 mt-3 flex-wrap">
            <span class="badge rounded-pill bg-body-secondary text-dark-emphasis fw-bold px-2 py-1 pet-tag"><?= e($m['especie']) ?></span>
            <span class="badge rounded-pill bg-body-secondary text-dark-emphasis fw-bold px-2 py-1 pet-tag"><?= e($m['tamano']) ?></span>
            <span class="badge rounded-pill bg-body-secondary text-dark-emphasis fw-bold px-2 py-1 pet-tag"><?= e($m['sexo']) ?></span>
          </div>
        </div>
      </article>
    </div>
  <?php endforeach; ?></div>
  <div id="f-vacio" class="text-center py-5 d-none"><i class="bi bi-search fs-1 text-secondary"></i><h3 class="h5 mt-2">No encontramos mascotas con esos filtros</h3><p class="text-secondary">Prueba quitando algún filtro.</p></div>
</div>

</body>
</html>