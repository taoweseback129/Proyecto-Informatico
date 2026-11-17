<?php
require 'config.php';
$titulo = 'Inicio';
require 'inc/header.php';
?>
<section class="hero">
  <h1>Arcade con alma de casino.<br>Cero dinero real.</h1>
  <p>Girá la tragamonedas, sumá puntos y subí en el ranking.</p>
  <?php if ($u): ?>
    <a class="btn" href="juego.php">Ir a jugar</a>
  <?php else: ?>
    <a class="btn" href="registro.php">Crear cuenta</a>
    <a class="btn ghost" href="login.php">Ingresar</a>
  <?php endif; ?>
</section>
<?php require 'inc/footer.php'; ?>
