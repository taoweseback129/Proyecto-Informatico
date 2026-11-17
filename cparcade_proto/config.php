<?php
// CParcade prototipo - configuración y funciones comunes
// Valores por defecto de XAMPP: usuario root sin contraseña.
session_start();

$DB = ['host' => 'localhost', 'name' => 'cparcade_proto', 'user' => 'root', 'pass' => ''];

try {
    $pdo = new PDO(
        "mysql:host={$DB['host']};dbname={$DB['name']};charset=utf8mb4",
        $DB['user'], $DB['pass'],
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]
    );
} catch (PDOException $x) {
    die('Sin conexión con la base de datos. Encender MySQL en XAMPP e importar database/cparcade_proto.sql en phpMyAdmin.');
}

// Escapa texto antes de mostrarlo en HTML (evita XSS)
function e($texto) {
    return htmlspecialchars((string)$texto, ENT_QUOTES, 'UTF-8');
}

// Campo oculto con token CSRF para los formularios
function csrf() {
    if (empty($_SESSION['t'])) {
        $_SESSION['t'] = bin2hex(random_bytes(16));
    }
    return '<input type="hidden" name="t" value="' . $_SESSION['t'] . '">';
}

// Todo POST debe traer el token correcto
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ok = !empty($_SESSION['t']) && hash_equals($_SESSION['t'], (string)($_POST['t'] ?? ''));
    if (!$ok) {
        http_response_code(400);
        die('Formulario vencido. Volver atrás y reintentar.');
    }
}

// Mensaje de una sola vez (se muestra y se borra)
function flash($mensaje = null, $tipo = 'ok') {
    if ($mensaje !== null) {
        $_SESSION['flash'] = [$tipo, $mensaje];
        return null;
    }
    $f = $_SESSION['flash'] ?? null;
    unset($_SESSION['flash']);
    return $f;
}

function ir($pagina) {
    header("Location: $pagina");
    exit;
}

// Devuelve el usuario logueado (array) o null
function usuario() {
    global $pdo;
    if (empty($_SESSION['uid'])) {
        return null;
    }
    $s = $pdo->prepare('SELECT id_usuario, nombre, email FROM usuarios WHERE id_usuario = ?');
    $s->execute([$_SESSION['uid']]);
    return $s->fetch() ?: null;
}

// Exige sesión iniciada; si no, manda al login
function requiere_login() {
    $u = usuario();
    if (!$u) {
        flash('Iniciá sesión para jugar.', 'err');
        ir('login.php');
    }
    return $u;
}
