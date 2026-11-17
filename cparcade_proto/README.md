# CParcade – Prototipo simple

Solo tiene: **registro**, **login** y **un juego** (tragamonedas por puntos).

## Puesta en marcha (XAMPP)
1. Copiar la carpeta `cparcade_proto` a `C:\xampp\htdocs\`
2. Encender **Apache** y **MySQL** en el panel de XAMPP.
3. Abrir http://localhost/phpmyadmin → pestaña **Importar** → elegir `database/cparcade_proto.sql` → **Importar**.
4. Abrir http://localhost/cparcade_proto/
5. Crear una cuenta y jugar.

Si MySQL tiene contraseña, editarla al inicio de `config.php`.

## Archivos
| Archivo | Qué hace |
|---|---|
| `index.php` | Página de inicio |
| `registro.php` | Crear cuenta (valida datos, guarda con `password_hash`) |
| `login.php` / `logout.php` | Iniciar y cerrar sesión |
| `juego.php` | Tragamonedas: calcula puntos en el servidor, guarda la partida, muestra ranking |
| `config.php` | Conexión PDO, sesión, token CSRF y funciones comunes |
| `inc/header.php`, `inc/footer.php` | Encabezado y pie compartidos |
| `database/cparcade_proto.sql` | 2 tablas: `usuarios` y `partidas` |
