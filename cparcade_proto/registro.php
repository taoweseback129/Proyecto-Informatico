<?php
require 'config.php';
$titulo = 'Crear cuenta';
if (usuario()) ir('juego.php');

$errores = [];
$nombre = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $email  = strtolower(trim($_POST['email'] ?? ''));
    $pass   = $_POST['pass'] ?? '';
    $pass2  = $_POST['pass2'] ?? '';

    if (strlen($nombre) < 2 || strlen($nombre) > 100) $errores[] = 'El nombre debe tener entre 2 y 100 caracteres.';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL))   $errores[] = 'Ingresar un email válido.';
    if (strlen($pass) < 8)                            $errores[] = 'La contraseña necesita al menos 8 caracteres.';
    if ($pass !== $pass2)                             $errores[] = 'Las contraseñas no coinciden.';

    if (!$errores) {
        $s = $pdo->prepare('SELECT 1 FROM usuarios WHERE email = ?');
        $s->execute([$email]);
        if ($s->fetch()) $errores[] = 'Ese email ya tiene una cuenta.';
    }

    if (!$errores) {
        $s = $pdo->prepare('INSERT INTO usuarios (nombre, email, contrasena) VALUES (?, ?, ?)');
        $s->execute([$nombre, $email, password_hash($pass, PASSWORD_DEFAULT)]);
        session_regenerate_id(true);
        $_SESSION['uid'] = (int)$pdo->lastInsertId();
        flash('Cuenta creada. ¡A jugar!');
        ir('juego.php');
    }
}
require 'inc/header.php';
?>
<form class="panel narrow" method="post">
  <h1>Crear cuenta</h1>
  <?php foreach ($errores as $m): ?><p class="flash err"><?= e($m) ?></p><?php endforeach; ?>
  <?= csrf() ?>
  <label>Nombre<input name="nombre" value="<?= e($nombre) ?>" required maxlength="100"></label>
  <label>Email<input type="email" name="email" value="<?= e($email) ?>" required></label>
  <label>Contraseña<input type="password" name="pass" minlength="8" required></label>
  <label>Repetir contraseña<input type="password" name="pass2" minlength="8" required></label>
  <button>Crear cuenta</button>
  <p>¿Ya tenés cuenta? <a href="login.php">Ingresar</a></p>
</form>
<?php require 'inc/footer.php'; ?>
