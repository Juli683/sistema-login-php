<?php
session_start();
require 'includes/db.php';

$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = trim($_POST['nombre']);
    $correo = trim($_POST['correo']);
    $pass   = $_POST['password'];

    if (empty($nombre) || empty($correo) || empty($pass)) {
        $mensaje = "Todos los campos son obligatorios.";
    } elseif (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $mensaje = "Correo inválido.";
    } elseif (strlen($pass) < 6) {
        $mensaje = "La contraseña debe tener al menos 6 caracteres.";
    } else {
        $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE correo = ?");
        $stmt->execute([$correo]);
        if ($stmt->rowCount() > 0) {
            $mensaje = "Este correo ya está registrado.";
        } else {
            $hash = password_hash($pass, PASSWORD_BCRYPT);
            $stmt = $pdo->prepare("INSERT INTO usuarios (nombre, correo, contraseña) VALUES (?, ?, ?)");
            if ($stmt->execute([$nombre, $correo, $hash])) {
                $mensaje = "¡Registro exitoso! <a href='login.php'>Inicia sesión</a>";
            } else {
                $mensaje = "Error al registrar.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h2>Registro de Usuario</h2>
        <?php if ($mensaje): ?>
            <p class="msg <?= strpos($mensaje, 'exitoso') ? 'success' : 'error' ?>"><?= $mensaje ?></p>
        <?php endif; ?>
        <form method="POST">
            <input type="text" name="nombre" placeholder="Nombre completo" required>
            <input type="email" name="correo" placeholder="Correo electrónico" required>
            <input type="password" name="password" placeholder="Contraseña (mín. 6)" required minlength="6">
            <button type="submit">Registrarse</button>
        </form>
        <p>¿Ya tienes cuenta? <a href="login.php">Iniciar sesión</a></p>
    </div>
</body>
</html>