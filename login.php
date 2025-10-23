<?php
session_start();

// Si ya está logueado, lo mandamos al panel
if (isset($_SESSION['admin_id'])) {
    header("Location: panel.php");
    exit();
}

// Procesar formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $servername = "localhost";
    $username = "root";   // usuario MySQL
    $password = "";       // contraseña MySQL
    $dbname = "huellitasdb";

    // Conectar
    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }

    $user = $_POST['username'];
    $pass = $_POST['password'];

    // Buscar admin
    $sql = "SELECT id, password FROM admin WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $user);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $hashed_pass);
        $stmt->fetch();

        if (password_verify($pass, $hashed_pass)) {
            // Login exitoso
            $_SESSION['admin_id'] = $id;
            $_SESSION['username'] = $user;
            header("Location: panel.php");
            exit();
        } else {
            $error = "❌ Contraseña incorrecta";
        }
    } else {
        $error = "❌ Usuario no encontrado";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login - Huellitas de Amor</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="card p-4 shadow" style="width: 350px;">
            <h3 class="text-center mb-3">🐾 Huellitas de Amor</h3>
            <h5 class="text-center mb-3">Login Admin</h5>
            <?php if (isset($error)) { ?>
                <div class="alert alert-danger"><?= $error ?></div>
            <?php } ?>
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Usuario</label>
                    <input type="text" name="username" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Contraseña</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Ingresar</button>
            </form>
        </div>
    </div>
</body>
</html>