<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>AdoptMe</title>
  <link href="<?= asset('vendor/bootstrap-5.3.9-dist/css/bootstrap.min.css') ?>" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link href="<?= asset('css/adoptme.css') ?>" rel="stylesheet">
  <link href="<?= asset('css/components.css') ?>" rel="stylesheet">
  <link href="<?= asset('css/pages.css') ?>" rel="stylesheet">
</head>
<body>
  // Página de inicio: propuesta de valor, mascotas destacadas, pasos y llamada a refugios.
<section class="py-5 my-2"><div class="container-xl"><div class="row align-items-center g-5">
  <div class="col-lg-6">
    <span class="badge rounded-pill text-bg-warning-subtle text-primary-emphasis px-3 py-2 fw-bold mb-3 d-inline-flex align-items-center gap-2"><i class="bi bi-heart-fill"></i> Plataforma de adopción responsable</span>
    <h1 class="display-5 fw-bolder mb-3">Adopta amor,<br>cambia vidas.</h1>
    <p class="lead text-secondary mb-3 home-lead">ADOPT ME conecta refugios y familias en un solo lugar: encuentra a tu compañero ideal, realiza seguimiento transparente a tu solicitud paso a paso y olvídate de procesos dispersos.</p>
    <div class="d-flex gap-3 flex-wrap"><a class="btn btn-primary" href="<?= url('catalogo') ?>">Ver mascotas disponibles</a><a class="btn btn-outline-dark" href="<?= url('registro') ?>">Soy un refugio</a></div>
  </div>
  <div class="col-lg-6"><img src="<?= asset('img/mascotas/4.jpg') ?>" alt="Cuatro cachorros esperando un hogar" class="w-100 rounded-4 shadow-sm home-photo"></div>
</div></div></section>

<section class="py-5"><div class="container-xl"><div class="row g-4">
  <div class="col-md-6"><div class="card border-0 shadow-sm rounded-4 p-4 h-100"><span class="badge rounded-pill text-bg-warning-subtle text-primary-emphasis px-3 py-2 fw-bold mb-3 align-self-start">El problema</span><h3 class="h5">Procesos de adopción dispersos y manuales</h3><p class="text-secondary mb-0">Registros de mascotas, solicitudes y seguimiento en hojas de cálculo y mensajes sueltos: duplicados y demoras.</p></div></div>
  <div class="col-md-6"><div class="card border-0 shadow-sm rounded-4 p-4 h-100 bg-dark text-white"><span class="badge rounded-pill text-bg-warning-subtle text-primary-emphasis px-3 py-2 fw-bold mb-3 align-self-start">La solución</span><h3 class="h5 text-white">Un solo lugar para todo el proceso</h3><p class="mb-0 text-white-50">Información accesible y un proceso de solicitud estructurado para adoptantes, refugios y administradores.</p></div></div>
</div></div></section>

<section class="py-5"><div class="container-xl">
  <h2 class="mb-2">Mascotas que esperan un hogar</h2><p class="text-secondary mb-4">Una muestra de quienes están disponibles ahora mismo.</p>
  <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4"><?php foreach ($destacadas as $m) include APP_PATH . '/views/partials/pet_card.php'; ?></div>
  <div class="text-center mt-4"><a class="btn btn-dark" href="<?= url('catalogo') ?>">Ver catálogo completo</a></div>
</div></section>

<section class="py-5"><div class="container-xl">
  <h2 class="mb-4">¿Cómo funciona?</h2>
  <div class="row g-4"><?php foreach ([['Explora','Filtra por especie, tamaño y edad.'],['Solicita','Completa el formulario de adopción responsable.'],['Sigue tu proceso','Consulta el estado de tu solicitud.'],['Adopta','Recibe la aprobación y tu certificado.']] as $i => $s): ?>
    <div class="col-sm-6 col-lg-3"><div class="card border-0 shadow-sm rounded-4 p-4 h-100"><div class="rounded-circle bg-dark text-white d-inline-flex align-items-center justify-content-center fw-bold fs-5 mb-3 step-number"><?= $i + 1 ?></div><h3 class="h6"><?= $s[0] ?></h3><p class="text-secondary small mb-0"><?= $s[1] ?></p></div></div>
  <?php endforeach; ?></div>
</div></section>

<section class="py-4"><div class="container-xl"><div class="bg-dark text-white rounded-4 p-5 text-center shadow-sm">
  <h2 class="h3 mb-2 text-white">¿Tienes un refugio o eres rescatista?</h2><p class="text-white-50">Publica tus mascotas, gestiona solicitudes y encuentra hogares responsables.</p>
  <a class="btn btn-primary" href="<?= url('registro') ?>">Registrar mi refugio</a>
</div></div></section>

</body>
</html>
