<?php /** Favoritos: el JS muestra solo los ids guardados en el navegador (demo). */ $soloFav = true; ?>
<div class="d-flex justify-content-between align-items-start gap-3 flex-wrap mb-4"><div><h1 class="h2 mb-1">Mis favoritos</h1><p class="text-secondary mb-0">Mascotas guardadas para revisar más tarde</p></div></div>
<div class="row row-cols-1 row-cols-md-2 row-cols-xl-3 g-4"><?php foreach ($mascotas as $m):
    $meses = $m['edad_meses'] ?? null;
    if ($meses === null) {
        preg_match('/(\d+)\s*(mes|año)/', $m['edad'], $coincidencia);
        $cantidad = (int) ($coincidencia[1] ?? 0);
        $unidad = $coincidencia[2] ?? 'año';
        $meses = $unidad === 'mes' ? $cantidad : $cantidad * 12;
    }
?>
  <div class="col" data-pet <?= !empty($soloFav) ? 'data-fav-item="' . $m['id'] . '"' : '' ?> data-nombre="<?= e($m['nombre']) ?>" data-raza="<?= e($m['raza']) ?>" data-especie="<?= e($m['especie']) ?>" data-tamano="<?= e($m['tamano']) ?>" data-estado="<?= e($m['estado']) ?>" data-meses="<?= (int) $meses ?>">
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
<div id="fav-vacio" class="text-center py-5 d-none"><i class="bi bi-heart fs-1 text-secondary"></i><h3 class="h5 mt-2">Aún no tienes favoritos</h3><p class="text-secondary">Explora el catálogo y guarda las mascotas que más te gusten.</p><a class="btn btn-primary" href="<?= url('catalogo') ?>">Ver catálogo</a></div>
