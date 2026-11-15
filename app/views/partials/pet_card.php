<?php /** Tarjeta de mascota. Requiere $m. Los data-* alimentan los filtros y favoritos del JS. */
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
