<?php
require 'config.php';
$u = requiere_login();
$titulo = 'Tragamonedas';

// El puntaje se calcula en el servidor para que no se pueda hacer trampa desde el navegador.
function girar() {
    $simbolos = ['🍒', '🍋', '🔔', '⭐', '7'];
    $rodillos = [];
    for ($i = 0; $i < 3; $i++) {
        $rodillos[] = $simbolos[random_int(0, count($simbolos) - 1)];
    }
    $distintos = count(array_unique($rodillos));
    if ($distintos === 1)      $puntos = 500;  // los 3 iguales
    elseif ($distintos === 2)  $puntos = 100;  // 2 iguales
    else                       $puntos = 10;   // ninguno igual
    return [$puntos, $rodillos];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    [$puntos, $rodillos] = girar();
    $s = $pdo->prepare('INSERT INTO partidas (id_usuario, puntaje) VALUES (?, ?)');
    $s->execute([$u['id_usuario'], $puntos]);
    $_SESSION['resultado'] = [$puntos, $rodillos];
    ir('juego.php'); // evita que F5 repita la jugada
}

$resultado = $_SESSION['resultado'] ?? null;
unset($_SESSION['resultado']);

// Puntaje total del usuario
$s = $pdo->prepare('SELECT COALESCE(SUM(puntaje), 0) FROM partidas WHERE id_usuario = ?');
$s->execute([$u['id_usuario']]);
$total = (int)$s->fetchColumn();

// Sus últimas 5 jugadas
$s = $pdo->prepare('SELECT fecha, puntaje FROM partidas WHERE id_usuario = ? ORDER BY id_partida DESC LIMIT 5');
$s->execute([$u['id_usuario']]);
$ultimas = $s->fetchAll();

// Ranking top 5
$ranking = $pdo->query(
    'SELECT us.nombre, SUM(p.puntaje) AS total
     FROM partidas p JOIN usuarios us ON us.id_usuario = p.id_usuario
     GROUP BY us.id_usuario, us.nombre
     ORDER BY total DESC LIMIT 5'
)->fetchAll();

require 'inc/header.php';
?>
<h1>Tragamonedas</h1>
<p>3 iguales = 500 puntos · 2 iguales = 100 · ninguno = 10. Tus puntos totales: <b><?= $total ?></b></p>

<?php if ($resultado): ?>
  <div class="result">
    <div class="rodillos"><?= e(implode('  |  ', $resultado[1])) ?></div>
    <b>+<?= (int)$resultado[0] ?> puntos</b>
  </div>
<?php endif; ?>

<form method="post" class="panel narrow">
  <?= csrf() ?>
  <button>Girar</button>
</form>

<section class="cols">
  <div>
    <h2>Tus últimas jugadas</h2>
    <?php if (!$ultimas): ?>
      <p>Todavía no jugaste.</p>
    <?php else: ?>
      <table>
        <?php foreach ($ultimas as $r): ?>
          <tr><td><?= e($r['fecha']) ?></td><td><b><?= (int)$r['puntaje'] ?></b></td></tr>
        <?php endforeach; ?>
      </table>
    <?php endif; ?>
  </div>
  <aside>
    <h2>Top 5</h2>
    <ol class="top5">
      <?php foreach ($ranking as $r): ?>
        <li><?= e($r['nombre']) ?> <b><?= (int)$r['total'] ?></b></li>
      <?php endforeach; ?>
    </ol>
  </aside>
</section>
<?php require 'inc/footer.php'; ?>
