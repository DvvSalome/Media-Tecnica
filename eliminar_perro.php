<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit;
}

if (!isset($_GET['id'])) {
    header("Location: panel.php");
    exit;
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "huellitasdb";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

$id = intval($_GET['id']);

// Primero, eliminar la foto física si existe
$result = $conn->query("SELECT foto FROM perros WHERE id = $id");
if ($row = $result->fetch_assoc()) {
    if ($row['foto'] && file_exists($row['foto'])) {
        unlink($row['foto']);
    }
}

// Luego eliminar registro de la base de datos
$conn->query("DELETE FROM perros WHERE id = $id");

header("Location: panel.php");
exit;