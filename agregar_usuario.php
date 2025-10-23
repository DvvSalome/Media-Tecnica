<?php
// Datos de conexión
$servername = "localhost";
$username = "root";   // tu usuario de MySQL
$password = "";       // tu contraseña de MySQL
$dbname = "huellitasdb";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Datos del admin inicial
$user = "admin";
$pass = "1234"; // contraseña inicial
$hashed_pass = password_hash($pass, PASSWORD_BCRYPT);

// Insertar admin
$sql = "INSERT INTO admin (username, password) VALUES (?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $user, $hashed_pass);

if ($stmt->execute()) {
    echo "✅ Admin creado correctamente. Usuario: admin | Contraseña: 1234";
} else {
    echo "❌ Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>