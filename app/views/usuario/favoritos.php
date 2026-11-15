<?php /** Favoritos: el JS muestra solo los ids guardados en el navegador (demo). */ $soloFav = true; ?>
<div class="d-flex justify-content-between align-items-start gap-3 flex-wrap mb-4"><div><h1 class="h2 mb-1">Mis favoritos</h1><p class="text-secondary mb-0">Mascotas guardadas para revisar más tarde</p></div></div>
<div class="row row-cols-1 row-cols-md-2 row-cols-xl-3 g-4"><?php foreach ($mascotas as $m) include APP_PATH . '/views/partials/pet_card.php'; ?></div>
<div id="fav-vacio" class="text-center py-5 d-none"><i class="bi bi-heart fs-1 text-secondary"></i><h3 class="h5 mt-2">Aún no tienes favoritos</h3><p class="text-secondary">Explora el catálogo y guarda las mascotas que más te gusten.</p><a class="btn btn-primary" href="<?= url('catalogo') ?>">Ver catálogo</a></div>
