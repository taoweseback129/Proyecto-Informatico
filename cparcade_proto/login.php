<?php
require 'config.php';
$titulo = 'Ingresar';
if (usuario()) ir('juego.php');

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $s = $pdo->prepare('SELECT * FROM usuarios WHERE email = ?');
    $s->execute([strtolower(trim($_POST['email'] ?? ''))]);
    $fila = $s->fetch();

    if (!$fila || !password_verify($_POST['pass'] ?? '', $fila['contrasena'])) {
        $error = 'Email o contraseña incorrectos.';
    } else {
        session_regenerate_id(true);
        $_SESSION['uid'] = (int)$fila['id_usuario'];
        ir('juego.php');
    }
}
require 'inc/header.php';
?>
<form class="panel narrow" method="post">
  <h1>Ingresar</h1>
  <?php if ($error): ?><p class="flash err"><?= e($error) ?></p><?php endif; ?>
  <?= csrf() ?>
  <label>Email<input type="email" name="email" required autofocus></label>
  <label>Contraseña<input type="password" name="pass" required></label>
  <button>Ingresar</button>
  <p>¿Todavía no tenés cuenta? <a href="registro.php">Crear cuenta</a></p>
</form>
<?php require 'inc/footer.php'; ?>
