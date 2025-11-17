<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h2>Bienvenido, <?= htmlspecialchars($_SESSION['nombre']) ?>!</h2>
        <p>Esta es una página protegida.</p>
        <a href="logout.php" class="btn-logout">Cerrar Sesión</a>
    </div>
</body>
</html>