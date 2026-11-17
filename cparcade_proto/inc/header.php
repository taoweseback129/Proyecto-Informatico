<?php $u = usuario(); $f = flash(); ?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title><?= e($titulo ?? 'CParcade') ?> · CParcade</title>
  <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<header class="top">
  <a class="logo" href="index.php">CP<b>arcade</b></a>
  <nav>
    <?php if ($u): ?>
      <a href="juego.php">Jugar</a>
      <span><?= e($u['nombre']) ?></span>
      <a href="logout.php">Salir</a>
    <?php else: ?>
      <a href="login.php">Ingresar</a>
      <a class="btn" href="registro.php">Crear cuenta</a>
    <?php endif; ?>
  </nav>
</header>
<main>
<?php if ($f): ?><p class="flash <?= e($f[0]) ?>"><?= e($f[1]) ?></p><?php endif; ?>
