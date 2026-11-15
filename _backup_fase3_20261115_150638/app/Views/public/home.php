<?php /** Página de inicio: propuesta de valor, mascotas destacadas, pasos y llamada a refugios. */ ?>
<section class="hero"><div class="container-xl"><div class="row align-items-center g-5">
  <div class="col-lg-6">
    <span class="eyebrow mb-3"><i class="bi bi-heart-fill"></i> Plataforma de adopción responsable</span>
    <h1 class="mb-3">Adopta amor,<br>cambia vidas.</h1>
    <p class="lead mb-4" style="max-width:480px">ADOPT ME conecta refugios y familias en un solo lugar: encuentra a tu compañero, sigue tu solicitud paso a paso y olvídate de las hojas de cálculo dispersas.</p>
    <div class="d-flex gap-3 flex-wrap"><a class="btn btn-primary" href="<?= url('catalogo') ?>">Ver mascotas disponibles</a><a class="btn btn-outline-dark" href="<?= url('registro') ?>">Soy un refugio</a></div>
  </div>
  <div class="col-lg-6"><img src="<?= asset('img/mascotas/4.jpg') ?>" alt="Cuatro cachorros esperando un hogar" class="w-100" style="border-radius:var(--radius);object-fit:cover;max-height:420px"></div>
</div></div></section>

<section class="py-5"><div class="container-xl"><div class="row g-4">
  <div class="col-md-6"><div class="card-adopt h-100"><span class="eyebrow mb-3">El problema</span><h3 class="h5">Procesos de adopción dispersos y manuales</h3><p class="text-taupe mb-0">Registros de mascotas, solicitudes y seguimiento en hojas de cálculo y mensajes sueltos: duplicados y demoras.</p></div></div>
  <div class="col-md-6"><div class="card-adopt h-100" style="background:var(--dark)"><span class="eyebrow mb-3">La solución</span><h3 class="h5 text-white">Un solo lugar para todo el proceso</h3><p class="mb-0" style="color:var(--taupe-tint)">Información accesible y un proceso de solicitud estructurado para adoptantes, refugios y administradores.</p></div></div>
</div></div></section>

<section class="py-5"><div class="container-xl">
  <h2 class="mb-2">Mascotas que esperan un hogar</h2><p class="text-taupe mb-4">Una muestra de quienes están disponibles ahora mismo.</p>
  <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4"><?php foreach ($destacadas as $m) include APP_PATH . '/views/partials/pet_card.php'; ?></div>
  <div class="text-center mt-4"><a class="btn btn-dark" href="<?= url('catalogo') ?>">Ver catálogo completo</a></div>
</div></section>

<section class="py-5"><div class="container-xl">
  <h2 class="mb-4">¿Cómo funciona?</h2>
  <div class="row g-4"><?php foreach ([['Explora','Filtra por especie, tamaño y edad.'],['Solicita','Completa el formulario de adopción responsable.'],['Sigue tu proceso','Consulta el estado de tu solicitud.'],['Adopta','Recibe la aprobación y tu certificado.']] as $i => $s): ?>
    <div class="col-sm-6 col-lg-3"><div class="card-adopt h-100"><div class="step-num mb-3"><?= $i + 1 ?></div><h3 class="h6"><?= $s[0] ?></h3><p class="text-taupe small mb-0"><?= $s[1] ?></p></div></div>
  <?php endforeach; ?></div>
</div></section>

<section class="py-4"><div class="container-xl"><div class="cta-band">
  <h2 class="h3 mb-2">¿Tienes un refugio o eres rescatista?</h2><p style="color:var(--taupe-tint)">Publica tus mascotas, gestiona solicitudes y encuentra hogares responsables.</p>
  <a class="btn btn-primary" href="<?= url('registro') ?>">Registrar mi refugio</a>
</div></div></section>
